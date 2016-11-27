<?php
/**
 * Tanguy GITON Copyright (c) 2016.
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $fields = $options['fields'];

        foreach ((array)$fields as $field) {
            $builder->add($field);
        }
    }

	/**
	 * @param OptionsResolver $resolver
	 *
	 * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
	 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Post',
            'fields' => array('title'),
        ));
    }
}
