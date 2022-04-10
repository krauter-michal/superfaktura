<?php

namespace Application\SuperfakturaBundle\Api;

class SuperFakturaAPI
{
    protected $coefficient = 1;
    protected $response;

    public function __construct($order, $superfaktura)
    {
        $api = $this->createApi($superfaktura);
        if ($api instanceof SFAPIclient)
        {
            // setup customer data
            $this->setClient($api, $order);

            //setup invoice data
            $this->setOrder($api, $order);

            // setup products data
            $this->setProducts($api, $order);

            // setup payment data
            $this->setPayment($api, $order);

            // setup shipping data
            $this->setShipping($api, $order);

            //save invoice
            $response = $api->save();

            $this->setResponse($response);
        }
    }

    protected function setClient(SFAPIclient $api, $order)
    {
        $customer = $order->getCustomer();
        $billingAddress = $customer->getBillingAddress();

        //setup client data
        $api->setClient(array(
            'name'    => $billingAddress->getFirstname().' '.$billingAddress->getSurname(),
            'ico'     => $customer->getCompanyId(),
            'ic_dph'  => $customer->getTaxId(),
            'email'   => $billingAddress->getEmail(),
            'address' => $billingAddress->getStreet(),
            'city'    => $billingAddress->getCity(),
            'zip'     => $billingAddress->getPostcode(),
            'phone'   => $billingAddress->getPhone(),
        ));
    }

    protected function setOrder(SFAPIclient $api, $order)
    {
        $api->setInvoice(array(
            //all items are optional, if not used, they will be filled automatically
            //'name'                 => 'My invoice',
            'variable'               => $order->getNumber(),					//variable symbol / reference
            //'constant'             => '0308',					//constant symbol
            //'specific'             => '2012', 					//specific symbol
            'invoice_no_formatted'   => $order->getNumber(), //pokud nen uvedeno, SF ho za vs dopln podle aktulnho slovn
            'created'                => $order->getCreated()->format('Y-m-d'), //datum vystaven
            'delivery'               => $order->getCreated()->format('Y-m-d'), //datum dodn
            'due'                    => $order->getCreated()->format('Y-m-d'), //datum splatnosti
            'type'					 => 'order',
            'invoice_currency'		 => 'CZK',
        ));

    }

    protected function setProducts(SFAPIclient $api, $order)
    {
        foreach ($order->getItems() as $item) {

            //add invoice item, this can be called multiple times
            //if you are not a VAT registered, use tax = 0
            $api->addItem(array(
                'name'        => $item->getName(),
                'description' => $item->getCode(),
                'quantity'    => (int) $item->getAmount(),
                'unit'        => 'ks',
                'unit_price'  => round($item->getUnitNominalPrice(false), 2),
                'tax'         => $item->getVat()->getPercent()
            ));
        }
    }

    protected function setPayment(SFAPIclient $api, $order)
    {
        $payment = $order->getPayment();

        $api->addItem(array(
            'name'        => $payment->getName(),
            'quantity'    => 1,
            'unit_price'  => round($payment->getPriceExcludingVat(), 2),
            'tax'         => $payment->getVat()->getPercent(),
        ));
    }

    protected function setShipping(SFAPIclient $api, $order)
    {
        $shipping = $order->getShipping();

        $api->addItem(array(
            'name'        => $shipping->getName(),
            'quantity'    => 1,
            'unit_price'  => round($shipping->getPriceExcludingVat(), 2),
            'tax'         => $shipping->getVat()->getPercent(),
        ));
    }

    protected function createApi($superfaktura)
    {
        if ($superfaktura->getExchangeRate()) {
            $this->setCoefficient($superfaktura->getExchangeRate());
        }

        if ($superfaktura->getApiEndpoint() == "cz") {
            $api = new SFAPIclientCZ($superfaktura->getEmail(), $superfaktura->getApiKey());
        }
        else if ($superfaktura->getApiEndpoint() == "sk") {
            $api = new SFAPIclientSK($superfaktura->getEmail(), $superfaktura->getApiKey());
        }
        else {
            $api = new SFAPIclientSB($superfaktura->getEmail(), $superfaktura->getApiKey());
        }
        return $api;
    }

    protected function createException($object)
    {
        $message = '';
        foreach (get_object_vars($object) as $var)
        {
            $message .= $this->fromUtf("{$var}\n");
        }

        return $message;
    }

    protected function toUtf($string)
    {
        return iconv('CP1250', 'UTF-8//TRANSLIT', $string);
    }

    protected function fromUtf($string)
    {
        return iconv('UTF-8', 'CP1250//TRANSLIT', $string);
    }

    public function getCoefficient()
    {
        return $this->coefficient;
    }

    public function setCoefficient($coefficient)
    {
        $this->coefficient = $coefficient;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($response)
    {
        $this->response= $response;
    }

}
