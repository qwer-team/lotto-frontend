<?php

namespace Qwer\LottoFrontendBundle\Form\Country;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Qwer\LottoBundle\Form\ResourceType;

class CountryType extends ResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $langs = $this->languageService->getLanguages();
        foreach ($langs as $lang) {
            $translation = $lang . "Translation.";
            $builder
            ->add($translation . 'title')
            ;
        }
        $builder
            ->add('code')
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
