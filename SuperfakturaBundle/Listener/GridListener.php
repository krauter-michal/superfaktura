<?php

namespace Application\SuperfakturaBundle\Listener;

class GridListener
{
    const ORDER_LIST = 'easyshop_order_order_list';

    protected $orderManager;
    protected $twig;

    public function __construct($orderManager, $twig)
    {
        $this->orderManager = $orderManager;
        $this->twig = $twig;
    }

    public function onGridCreate($event)
    {;
        $grid = $event->getGrid();
        $builder = $event->getBuilder();

        if ($grid->getName() == self::ORDER_LIST) {

            $builder->addColumn("superfaktura_action", "application_superfaktura.order_grid.superfaktura_action.label", null, null, function($data, $value) use ($grid) {
                $order = $this->orderManager->find($data["id"]);

                return $grid->render("ApplicationSuperfakturaBundle:SuperfakturaAdmin:order_grid_action.html.twig", [
                    "item" => $data,
                    "order" => $order
                ]);
            }, ["non_exportable" => true]);

        }

        return $event;
    }

}
