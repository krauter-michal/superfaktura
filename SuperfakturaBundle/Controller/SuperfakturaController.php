<?php

namespace Application\SuperfakturaBundle\Controller;

use Zita\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Application\SuperfakturaBundle\Form\SuperfakturaType;
use Application\SuperfakturaBundle\Api\SuperFakturaAPI;

class SuperfakturaController extends BaseController
{
    public function listAction(Request $request)
    {
        $manager = $this->getManager();
        $gridManager = $this->get("zita_datagrid.grid_manager");
        $grid = $gridManager->getGrid("application_superfaktura_superfaktura_list");
        $superfaktury = $manager->getRepository()->findBy([], ['id' => 'DESC']);

        $grid->setDataFromObjects($superfaktury);

        return $this->render('ApplicationSuperfakturaBundle:SuperfakturaAdmin:list.html.twig', [
            "grid" => $grid,
        ]);
    }

    public function createAction(Request $request)
    {
        $manager = $this->getManager();
        $factory = $this->get('application_superfaktura.superfaktura_factory');
        $superfaktura = $factory->createSuperfaktura();

        $form = $this->createForm(SuperfakturaType::class, $superfaktura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($superfaktura->isDefault()) {
                $manager->changeDefaultSuperfaktura($superfaktura);
            }

            $manager->saveSuperfaktura($superfaktura);

            $this->setInfo("application_superfaktura.superfaktura.created");

            if ($request->get("stay")) {
                return $this->redirect($this->generateUrl("application_superfaktura.update_superfaktura",
                    ["id" => $superfaktura->getId()]));
            }

            return $this->redirect($this->generateUrl('application_superfaktura.superfaktura_list'));
        }

        return $this->render('ApplicationSuperfakturaBundle:SuperfakturaAdmin:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function updateAction(Request $request, $id)
    {
        $manager = $this->getManager();
        $superfaktura = $manager->getRepository()->find($id);

        if (!$superfaktura) {
            throw $this->createNotFoundException(sprintf("Can't find record with ID '%s'.", $id));
        }

        $form = $this->createForm(SuperfakturaType::class, $superfaktura, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->saveSuperfaktura($superfaktura);
            $manager->flush();

            $this->setInfo("application_superfaktura.superfaktura.updated");

            if ($request->get("stay")) {
                return $this->redirect($this->generateUrl("application_superfaktura.update_superfaktura", ["id" => $superfaktura->getId()]));
            }

            return $this->redirect($this->generateUrl('application_superfaktura.superfaktura_list'));
        }

        return $this->render('ApplicationSuperfakturaBundle:SuperfakturaAdmin:update.html.twig', array(
            'form'   => $form->createView(),
            'superfaktura' => $superfaktura,
        ));
    }

    public function deleteAction($id)
    {
        $manager = $this->getManager();
        $superfaktura = $manager->getRepository()->find($id);

        if (!$superfaktura) {
            throw $this->createNotFoundException(sprintf("Can't find record with ID '%s'.", $id));
        }

        $manager->removeSuperfaktura($superfaktura);

        $this->setInfo("application_superfaktura.superfaktura.deleted");

        return $this->redirect($this->generateUrl("application_superfaktura.superfaktura_list"));
    }

    public function exportAction(Request $request, $orderId)
    {
        try {
            $this->doExport($orderId);
        } catch (\Exception $e) {
            $this->setError($e->getMessage() ?: "application_superfaktura.order.exported.failed");
        }

        return $this->redirect($this->generateUrl("order.admin.update", ["id" => $orderId]));
    }

    public function exportOrderListAction(Request $request, $orderId)
    {
        try {
            $this->doExport($orderId);
        } catch (\Exception $e) {
            $this->setError($e->getMessage() ?: "application_superfaktura.order.exported.failed");
        }

        return $this->redirect($this->generateUrl("order.admin.list"));
    }

    protected function doExport($orderId)
    {
        $manager = $this->getDoctrine()->getManager();
        $superfakturaRepository = $manager->getRepository('ApplicationSuperfakturaBundle:Superfaktura');

        $orderManager = $this->get('easyshop.order_manager');
        $order = $orderManager->find($orderId);
        if (!$order) {
            throw $this->createNotFoundException(sprintf("Can't find order with ID '%s'.", $orderId));
        }

        $superfaktura = $superfakturaRepository->findOneBy(array('default' => 1));

        $result = new SuperFakturaAPI($order, $superfaktura);
        $response = $result->getResponse();

        if ($response->error === 0){
            //complete information about created invoice
            $this->setInfo("application_superfaktura.order.exported.success");

            $now = new \DateTime();
            $order->setSuperfakturaExportDate($now);

            $orderManager->flush();
        }
        else {
            if (isset($response->error_message) && isset($response->error_message->invoice_no_formatted[0])) {
                throw new \Exception($response->error_message->invoice_no_formatted[0]);
            } else {
                throw new \Exception('API error #' . $response->error);
            }
        }

    }

    protected function createErrorMessage($object)
    {
        $message = '';
        foreach (get_object_vars($object) as $var)
        {
            if (is_array($var)) {
                foreach($var as $mess) {
                    $message .= "{$mess}\n";
                }
            }
        }
        return $message;
    }

    public function getManager()
    {
        return $this->get("application_superfaktura.superfaktura_manager");
    }
}
