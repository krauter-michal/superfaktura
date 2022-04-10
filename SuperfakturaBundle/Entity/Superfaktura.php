<?php

namespace Application\SuperfakturaBundle\Entity;


class Superfaktura
{
    protected $id;
    protected $default = false;
    protected $internalName;
    protected $email;
    protected $apiKey;
    protected $logoId;
    protected $exchangeRate;
    protected $numericalSeries;
    protected $eshopName;
    protected $prefix;
    protected $invoiceName;
    protected $invoicePhone;
    protected $invoiceEmail;
    protected $invoiceWeb;
    protected $apiEndpoint;

    public function __toString()
    {
        if (!empty($this->getInternalName())) {
            return $this->getInternalName();
        }

        return '(' . $this->getId() . ')';
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function isDefault()
    {
        return $this->default;
    }

    public function setDefault($default)
    {
        $this->default = (bool)$default;
    }

    public function getInternalName()
    {
        return $this->internalName;
    }

    public function setInternalName($internalName)
    {
        $this->internalName = $internalName;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getLogoId()
    {
        return $this->logoId;
    }

    public function setLogoId($logoId)
    {
        $this->logoId = $logoId;
    }

    public function getExchangeRate()
    {
        return $this->exchangeRate;
    }

    public function setExchangeRate($exchangeRate)
    {
        $this->exchangeRate = $exchangeRate;
    }

    public function getNumericalSeries()
    {
        return $this->numericalSeries;
    }

    public function setNumericalSeries($numericalSeries)
    {
        $this->numericalSeries = $numericalSeries;
    }

    public function getEshopName()
    {
        return $this->eshopName;
    }

    public function setEshopName($eshopName)
    {
        $this->eshopName = $eshopName;
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    public function getInvoiceName()
    {
        return $this->invoiceName;
    }

    public function setInvoiceName($invoiceName)
    {
        $this->invoiceName = $invoiceName;
    }

    public function getInvoicePhone()
    {
        return $this->invoicePhone;
    }

    public function setInvoicePhone($invoicePhone)
    {
        $this->invoicePhone = $invoicePhone;
    }

    public function getInvoiceEmail()
    {
        return $this->invoiceEmail;
    }

    public function setInvoiceEmail($invoiceEmail)
    {
        $this->invoiceEmail = $invoiceEmail;
    }

    public function getInvoiceWeb()
    {
        return $this->invoiceWeb;
    }

    public function setInvoiceWeb($invoiceWeb)
    {
        $this->invoiceWeb = $invoiceWeb;
    }

    public function getApiEndpoint()
    {
        return $this->apiEndpoint;
    }

    public function setApiEndpoint($apiEndpoint)
    {
        $this->apiEndpoint = $apiEndpoint;
    }


}
