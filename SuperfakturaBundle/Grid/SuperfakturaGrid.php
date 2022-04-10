<?php

namespace Application\SuperfakturaBundle\Grid;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Zita\Bundle\DataGridBundle\Builder\GridBuilder;
use Zita\Bundle\DataGridBundle\Model\GridManager;
use Zita\Bundle\DataGridBundle\Model\AjaxGrid;
use Zita\Bundle\DataGridBundle\Model\DataGrid;

/**
 * Grid vypisu uctu superfaktury
 *
 * @package Application\SuperfakturaBundle\Grid
 * @author  Michal Krauter
 */
class SuperfakturaGrid extends DataGrid
{
    public function buildGrid(GridBuilder $builder)
    {
        $self = $this;

        $builder->addIdentifierColumn("id", "ID", "range", function ($item) {
            return $item->getId();
        }, null, array("visible" => false));

        $builder->addColumn("internalName", "application_superfaktury.superfaktura.internal_name", "text", function ($item) {
            return $item->getInternalName();
        });

        $builder->addColumn("email", "application_superfaktury.superfaktura.email", "text", function ($item) {
            return $item->getEmail();
        });

        $builder->addColumn("default", "application_superfaktury.superfaktura.default", "boolean", function($item) {
            return $item->isDefault();
        }, function($data, $value) use ($self) {
            return $self->format("boolean", $value);
        });

        $builder->addColumn("admin", "", null, null, function ($data, $value) use ($self) {
            return $self->render("ApplicationSuperfakturaBundle:SuperfakturaAdmin:_column_admin.html.twig", array("item" => $data));
        }, array("non_exportable" => true));
    }

    public function getName()
    {
        return "application_superfaktura_superfaktura_list";
    }
}
