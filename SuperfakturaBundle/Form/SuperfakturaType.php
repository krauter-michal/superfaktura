<?php

namespace Application\SuperfakturaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Formular pro nastaveni uctu superfaktury
 *
 * @package Application\SuperfakturaBundle\Form
 * @author  Michal Krauter
 */
class SuperfakturaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("default", CheckboxType::class, array(
            "label"    => "application_superfaktury.superfaktura.default",
            "required" => false,
        ));

        $builder->add("internalName", TextType::class, array(
            "label"    => "application_superfaktury.superfaktura.internal_name",
            "required" => true,
        ));

        $builder->add("email", TextType::class, array(
            "label"    => "application_superfaktury.superfaktura.email",
            "required" => true,
        ));

        $builder->add("apiKey", TextType::class, array(
            "label"    => "application_superfaktury.superfaktura.api_key",
            "required" => true,
        ));

        $builder->add("logoId", TextType::class, array(
            "label"    => "application_superfaktury.superfaktura.logo_id",
            "required" => false,
        ));

        $builder->add("exchangeRate", TextType::class, array(
            "label"    => "application_superfaktury.superfaktura.exchange_rate",
            "required" => false,
        ));

        $builder->add("numericalSeries", TextType::class, array(
            "label"    => "application_superfaktury.superfaktura.numerical_series",
            "required" => false,
        ));

        $builder->add("eshopName", TextType::class, array(
            "label"    => "application_superfaktury.superfaktura.eshop_name",
            "required" => false,
        ));

        $builder->add("prefix", TextType::class, array(
            "label"    => "application_superfaktury.superfaktura.prefix",
            "required" => false,
        ));

        $builder->add("invoiceName", TextType::class, array(
            "label"    => "application_superfaktury.superfaktura.invoice.name",
            "required" => false,
        ));
        $builder->add("invoicePhone", TextType::class, array(
            "label"    => "application_superfaktury.superfaktura.invoice.phone",
            "required" => false,
        ));
        $builder->add("invoiceEmail", TextType::class, array(
            "label"    => "application_superfaktury.superfaktura.invoice.email",
            "required" => false,
        ));
        $builder->add("invoiceWeb", TextType::class, array(
            "label"    => "application_superfaktury.superfaktura.invoice.web",
            "required" => false,
        ));

        $choices = array("cz" => "SuperFaktura.cz", "sk" => "SuperFaktura.sk", "sand" => "Testovací");

        $builder->add("apiEndpoint", Type\ChoiceType::class, array(
            "label" => "application_superfaktury.superfaktura.api_endpoint",
            "choices" => array_flip($choices),
            "choices_as_values" => true,
            "attr" => array("class" => "ch-plugin")
        ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            // 'translation_domain'      => "public",
            'disable_ajax_validation' => true,
        ));
    }

    public function getBlockPrefix()
    {
        return 'application_superfaktura';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
