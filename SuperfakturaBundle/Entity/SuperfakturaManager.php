<?php
namespace Application\SuperfakturaBundle\Entity;

use Zita\Bundle\FrameworkBundle\Model\AbstractModelManager;

/**
 * Správce uctu Superfaktury
 *
 * @author Michal Krauter
 */
class SuperfakturaManager extends AbstractModelManager
{
    public function createSuperfaktura($name = "Superfaktura")
    {
        return $this->create(array($name));
    }

    public function saveSuperfaktura(Superfaktura $superfaktura, $flush = true)
    {
        $em = $this->getObjectManager();
        $em->persist($superfaktura);

        if ($superfaktura->isDefault()) {
            foreach ($this->getRepository()->findBy(['default' => 1]) as $r) {
                if ($r->getId() != $superfaktura->getId()) {
                    $r->setDefault(false);
                }
            }
        }

        if ($flush) {
            $em->flush();
        }
    }

    public function removeSuperfaktura(Superfaktura $superfaktura, $flush = true)
    {
        $em = $this->getObjectManager();
        $em->remove($superfaktura);

        if ($flush) {
            $em->flush();
        }
    }

    public function findDefaultSuperfaktura()
    {
        return $this->getRepository()->findOneBy(array('default' => 1));
    }

    public function findDefaultSuperfakturs()
    {
        return $this->getRepository()->findBy(array('default' => 1));
    }

    public function changeDefaultSuperfaktura($superfaktura)
    {
        $superfaktura->setDefault(true);

        $defaultSuperfakturs = $this->findDefaultSuperfakturs();

        foreach($defaultSuperfakturs as $defaultSuperfaktura) {
            if ($defaultSuperfaktura !== $superfaktura) {
                $defaultSuperfaktura->setDefault(false);

                $this->saveSuperfaktura($defaultSuperfaktura, false);
            }
        }

        $this->saveSuperfaktura($superfaktura, false);
    }

    public function findAll($name = '')
    {
        return $this->getRepository($name)->findBy([], ['internalName' => 'ASC']);
    }
}