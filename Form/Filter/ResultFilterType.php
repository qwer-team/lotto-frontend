<?php

namespace Qwer\LottoFrontendBundle\Form\Filter;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
 

class ResultFilterType extends AbstractType
{
  
    public function buildForm(FormBuilderInterface $builder, array $options )
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
        $types = $options['attr']["types"];
        
        $builder
            ->add('start', 'datetime', $startParams)
            ->add('end', 'datetime', $endParams)
            ->add('lottoType','choice',array(
                'choices' =>  $this->getTypes($types) ,
                'required' => false, 'attr' => array( 'class' => 'loto-filter')) );
    }

    function getTypes($types){
        $list=array();
        foreach ($types as $type) {
            $list[$type->getId()]=$type->getCountry()->getTitle()." \ ".$type->getTitle();
            
        }
        return $list;//array('4' => 'lotto1', '7' => 'lotto3' );
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