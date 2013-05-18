<?php

namespace Qwer\LottoFrontendBundle\Form\Country;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CountryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('title')
            ->add('image', 'file', array("required" => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Qwer\LottoFrontendBundle\Entity\Country'
        ));
    }

    public function getName()
    {
        return 'qwer_lottofrontendbundle_country_countrytype';
    }
}
