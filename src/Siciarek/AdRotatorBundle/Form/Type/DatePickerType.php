<?php

namespace Siciarek\AdRotatorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DatePickerType extends AbstractType
{

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaults = array(
            'widget' => 'single_text',
            'trim' => true,
            'format' => 'yyyy-MM-dd',
            'attr'   => array(
                'autocomplete' => 'off',
                'class'        => 'datepicker',
            ),
        );

        $resolver->setDefaults($defaults);
    }


    public function getParent()
    {
        return 'date';
    }

    public function getName()
    {
        return 'datepicker';
    }
}
