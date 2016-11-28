<?php
/**
 * Tanguy GITON Copyright (c) 2016.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Newsletter;
use AppBundle\Entity\Rubrique;
use AppBundle\Form\NewsletterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class NewsletterController
 * @package AppBundle\Controller
 */
class NewsletterController extends Controller
{
    /**
     * @param Newsletter $newsletter
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/view/{id}.html", name="newsletter_show")
     */
    public function viewAction(Newsletter $newsletter)
    {
        return $this->render('@App/newsletter/newsletter.html.twig', array(
            'newsletter' => $newsletter,
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     *
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $newsletters = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Newsletter')
            ->findAll();

        return $this->render('@App/index.html.twig', [
            'newsletters' => $newsletters,
        ]);
    }

    /**
     * @param $newsletter
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/edit/{id}", name="newsletter_edit")
     */
    public function editAction(Newsletter $newsletter)
    {
        return $this->render('@App/newsletter/edit.html.twig', [
            'newsletter' => $newsletter,
        ]);
    }

    /**
     * @param Request $request
     * @param Newsletter $newsletter
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     * @throws \LogicException
     *
     * @Route(path="header-edit/{id}", name="newsletter_header-edit")
     */
    public function editHeaderAction(Request $request, Newsletter $newsletter)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new BadRequestHttpException('Only ajax accepted');
        }

        $form = $this->get('form.factory')->createNamedBuilder('edit_newsletter-header', NewsletterType::class, $newsletter, array(
            'action' => $this->generateUrl('newsletter_header-edit', array('id' => $newsletter->getId())),
        ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse(array(
                'status' => 'ok'
            ));
        }

        return $this->render('@App/newsletter/edit-header.html.twig', [
            'newsletter' => $newsletter,
            'form' => $form->createView(),
        ]);
    }

    /**
     *
     * @param Newsletter $newsletter
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     *
     * @Route(path="/clone/{id}", name="newsletter_clone")
     */
    public function cloneAction(Newsletter $newsletter)
    {
        $em = $this->getDoctrine()->getManager();

        $new = clone $newsletter;
        $em->persist($new);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }

    /**
     *
     * @throws \LogicException
     *
     * @Route(path="/new", name="newsletter_new")
     *
     */
    public function newAction()
    {

        $em = $this->getDoctrine()->getManager();

        /**
         * Types
         */
        $texte_type = $em->getRepository('AppBundle:Type')->findOneBy(array(
            'slug' => 'texte',
        ));
        $agenda_type = $em->getRepository('AppBundle:Type')->findOneBy(array(
            'slug' => 'agenda-item',
        ));

        /**
         * Default Newsletter
         */
        $newsletter = new Newsletter();
        $newsletter
            ->setTitle('L\'e-bdo du Groupe ESIEA')
            ->setWeek('Semaine X')
            ->setLogo('https://www.esiea.fr/e-bdo/Logo-ESIEA-Groupe.png')
            ->setFooter('<b>Service Communication &amp; Admissions du Groupe ESIEA</b><br>
                            <a href="tel:+33143902116" style="display: inline-block;text-decoration: none;font-family: Helvetica, Arial, sans-serif;color: #999999;font-size: small;"><span style="color: #999999;text-decoration: none;">01 43 90 21 16</span></a> &nbsp;&nbsp; <a href="mailto:communication@esiea.fr" style="display: inline-block;text-decoration: none;font-family: Helvetica, Arial, sans-serif;color: #999999;font-size: small;">communication@esiea.fr</a><br>
                            <a href="https://www.facebook.com/esiea" target="_blank" style="display: inline-block;text-decoration: none;font-family: Helvetica, Arial, sans-serif;color: #999999;font-size: small;">Facebook</a> &nbsp;&nbsp; <a href="https://twitter.com/groupeesiea?lang=fr" target="_blank" style="display: inline-block;text-decoration: none;font-family: Helvetica, Arial, sans-serif;color: #999999;font-size: small;">Twitter</a> &nbsp;&nbsp; <a target="_blank" href="http://www.scoop.it/t/esiea" style="display: inline-block;text-decoration: none;font-family: Helvetica, Arial, sans-serif;color: #999999;font-size: small;"><span style="color: #999999;text-decoration: none;">Soop.It</span></a> &nbsp;&nbsp; <a href="http://www.esiea.fr" target="_blank" style="display: inline-block;text-decoration: none;font-family: Helvetica, Arial, sans-serif;color: #999999;font-size: small;"><span style="color: #999999;text-decoration: none;">www.esiea.fr</span></a> &nbsp;&nbsp; <a href="http://www.intechinfo.fr" target="_blank" style="display: inline-block;text-decoration: none;font-family: Helvetica, Arial, sans-serif;color: #999999;font-size: small;"><span style="color: #999999;text-decoration: none;">www.intechinfo.fr</span></a>');

        /**
         * Rubrique - Quoi de neuf ?
         */

        $rubrique = new Rubrique();
        $rubrique
            ->setNewsletter($newsletter)
            ->setTitle('Quoi de neuf ?')
            ->setImage('https://www.esiea.fr/wp-content/uploads/2016/09/actu-2.png')
            ->setIcone('http://esiea.fr/e-bdo/icone-actu.png')
            ->setPosition(1)
            ->setType($texte_type);

        $newsletter->addRubrique($rubrique);

        /**
         * Rubrique - Les écoles dans la presse
         */

        $rubrique = new Rubrique();
        $rubrique
            ->setNewsletter($newsletter)
            ->setTitle('Les écoles dans la presse')
            ->setImage('https://www.esiea.fr/wp-content/uploads/2016/09/presse-2.png')
            ->setIcone('http://esiea.fr/e-bdo/icone-presse.png')
            ->setPosition(2)
            ->setType($texte_type);

        $newsletter->addRubrique($rubrique);

        /**
         * Rubrique - Vie des campus
         */

        $rubrique = new Rubrique();
        $rubrique
            ->setNewsletter($newsletter)
            ->setTitle('Vie des campus')
            ->setImage('https://www.esiea.fr/wp-content/uploads/2016/09/campus-2.png')
            ->setIcone('http://esiea.fr/e-bdo/icone-campus.png')
            ->setPosition(3)
            ->setType($texte_type);

        $newsletter->addRubrique($rubrique);

        /**
         * Rubrique - Challenges & projets
         */

        $rubrique = new Rubrique();
        $rubrique
            ->setNewsletter($newsletter)
            ->setTitle('Challenges & projets')
            ->setImage('https://www.esiea.fr/wp-content/uploads/2016/09/projets-2.png')
            ->setIcone('http://esiea.fr/e-bdo/icone-projet.png')
            ->setSubtitle('Quel que soit le challenge auquel vous vous présentez, vous pouvez recevoir du soutien et des conseils de votre école : n’oubliez pas de prévenir votre directeur de campus de votre participation.')
            ->setPosition(4)
            ->setType($texte_type);

        $newsletter->addRubrique($rubrique);

        /**
         * Rubrique - A vos agendas
         */

        $rubrique = new Rubrique();
        $rubrique
            ->setNewsletter($newsletter)
            ->setTitle('A vos agendas')
            ->setImage('https://www.esiea.fr/wp-content/uploads/2016/09/agenda-2.png')
            ->setIcone('http://esiea.fr/e-bdo/icone-calendrier.png')
            ->setPosition(5)
            ->setType($agenda_type);

        $newsletter->addRubrique($rubrique);

        $em->persist($newsletter);
        $em->flush();

        return $this->redirectToRoute('newsletter_edit', array('id' => $newsletter->getId()));

    }

    /**
     * @param Request $request
     * @param Newsletter $newsletter
     *
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \LogicException
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     *
     * @Route(path="/delete/{id}", name="newsletter_delete")
     */
    public function deleteAction(Request $request, Newsletter $newsletter)
    {
        if (!$request->isXmlHttpRequest()) {
            //throw new BadRequestHttpException('Only ajax accepted');
        }

        $form = $this->get('form.factory')->createNamedBuilder('delete_newsletter', FormType::class, null, array(
            'action' => $this->generateUrl('newsletter_delete', array('id' => $newsletter->getId())),
        ))
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($newsletter);
            $em->flush();

            return new JsonResponse(array(
                'status' => 'ok',
            ));
        }

        return $this->render('@App/newsletter/delete.html.twig', [
            'newsletter' => $newsletter,
            'form' => $form->createView(),
        ]);
    }
}
