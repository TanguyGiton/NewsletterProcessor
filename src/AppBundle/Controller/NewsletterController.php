<?php
/**
 * Tanguy GITON Copyright (c) 2016.
 */

namespace AppBundle\Controller;

use AppBundle\Form\NewsletterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class NewsletterController
 * @package AppBundle\Controller
 */
class NewsletterController extends Controller
{
	/**
	 * @param $id
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws \LogicException
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 *
	 * @Route("/view/{id}.html", name="newsletter_show")
	 */
	public function viewAction( $id ) {
		$newsletter = $this->getDoctrine()
		                   ->getRepository( 'AppBundle:Newsletter' )
		                   ->find( $id );

		if ( $newsletter === null ) {
			throw new NotFoundHttpException( 'Newsletter not found' );
    }

		return $this->render( '@App/newsletter/newsletter.html.twig', array(
			'newsletter' => $newsletter,
		) );
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws \LogicException
	 *
	 * @Route("/", name="homepage")
	 */
	public function indexAction() {
		$newsletters = $this->getDoctrine()
		                    ->getManager()
		                    ->getRepository( 'AppBundle:Newsletter' )
		                    ->findAll();

		return $this->render( '@App/index.html.twig', [
			'newsletters' => $newsletters,
		] );
	}

	/**
	 * @param $id
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 * @Route("/edit/{id}", name="newsletter_edit")
	 */
	public function editAction( $id ) {

		$em = $this->getDoctrine()->getManager();

		$newsletter = $em->getRepository( 'AppBundle:Newsletter' )->find( $id );

		$form = $this->createForm( NewsletterType::class, $newsletter );

		if ( ! $newsletter ) {
			throw new NotFoundHttpException( 'Newsletter not found' );
		}

		return $this->render( '@App/edit.html.twig', [
			'newsletter' => $newsletter,
			'form'       => $form->createView(),
		] );
	}
}
