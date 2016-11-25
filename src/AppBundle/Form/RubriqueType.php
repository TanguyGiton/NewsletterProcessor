<?php
/**
 * Tanguy GITON Copyright (c) 2016.
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RubriqueType extends AbstractType
{
	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add( 'title' )
			->add( 'image' )
			->add( 'icone' )
			->add( 'subtitle' )
			->add( 'posts', CollectionType::class, array(
				'entry_type' => PostType::class,
			) );
	}

	/**
	 * @param OptionsResolver $resolver
	 *
	 * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
	 */
	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( array(
			'data_class' => 'AppBundle\Entity\Rubrique'
		) );
	}
}
