<?php

namespace ReportBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
class LawFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'filter_text',array(
                    'condition_pattern' => FilterOperands::STRING_BOTH))
            ->add('description', 'filter_text',array(
                    'condition_pattern' => FilterOperands::STRING_BOTH))
            ->add('applicationAuthority', 'filter_text',array(
                    'condition_pattern' => FilterOperands::STRING_BOTH))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'ModelBundle\Entity\Law',
            'csrf_protection'   => false,
            'validation_groups' => array('filter'),
            'method'            => 'GET',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'law_filter';
    }
}
