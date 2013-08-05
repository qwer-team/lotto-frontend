<?php

namespace Qwer\LottoFrontendBundle\Form\Filter;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ResultFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $startParams = array(
                'widget' => 'single_text',
                'format' => 'YYYY-MM-dd',
                //'date_format' => 'dd.MM.YYYY / hh:mm',
                'attr' => array(
                    'class' => 'start_dater'
                )
        );
        $endParams = array(
             'widget' => 'single_text',
                'format' => 'YYYY-MM-dd',
                //'date_format' => 'dd.MM.YYYY / hh:mm',
                'attr' => array(
                    'class' => 'finish_dater'
                )
        );
        $builder
            ->add('start', 'datetime', $startParams)
            ->add('end', 'datetime', $endParams)
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Qwer\LottoFrontendBundle\Entity\Filter\ResultFilter'
        ));
    }

    public function getName()
    {
        return 'result_filter_type';
    }
}