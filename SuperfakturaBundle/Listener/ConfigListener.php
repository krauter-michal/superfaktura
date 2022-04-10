<?php

namespace Application\SuperfakturaBundle\Listener;

use Webget\AdminBundle\ConfigEvent;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Zita\Bundle\WysiwygBundle\Form\Type\HtmlTextareaType;

class ConfigListener
{

    /**
     * ma probehnout jen jednou
     * @var boolean
     */
    protected $initialized = false;
    protected $translator;
    protected $router;

    public function __construct($translator, $router)
    {
        $this->translator = $translator;
        $this->router = $router;
    }

    public function onConfigFormCreate(ConfigEvent $event)
    {
        if (!$this->initialized) {
            $manager = $event->getManager();
            $manager->addLink("application_superfaktura", $this->router->generate("application_superfaktura.superfaktura_list"),"application_superfaktura.list.header", null, null, "modules");
            $this->initialized = true;
        }

        return $event;
    }
}
