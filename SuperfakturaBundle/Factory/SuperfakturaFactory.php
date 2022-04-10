<?php

namespace Application\SuperfakturaBundle\Factory;

use Application\SuperfakturaBundle\Entity\Superfaktura;

class SuperfakturaFactory
{
    public function createSuperfaktura()
    {
        return new Superfaktura();
    }
}
