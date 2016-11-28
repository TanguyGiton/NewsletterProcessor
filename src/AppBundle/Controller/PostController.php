<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\Rubrique;
use AppBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class PostController
 * @package AppBundle\Controller
 *
 * @Route(path="/post")
 */
class PostController extends Controller
{
    /**
     * @param Request $request
     * @param Post $post
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     *
     * @Route(path="/edit/{id}", name="post_edit")
     */
    public function editAction(Request $request, Post $post)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new BadRequestHttpException('Only ajax accepted');
        }

        $form = $this->get('form.factory')->createNamedBuilder('edit_post', PostType::class, $post, array(
            'action' => $this->generateUrl('post_edit', array('id' => $post->getId())),
            'fields' => $post->getType()->getFields(),
        ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse(array(
                'status' => 'ok'
            ));
        }

        return $this->render('@App/post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Post $post
     *
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     *
     * @Route(path="/delete/{id}", name="post_delete")
     */
    public function deleteAction(Request $request, Post $post)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new BadRequestHttpException('Only ajax accepted');
        }

        $form = $this->get('form.factory')->createNamedBuilder('delete_post', FormType::class, null, array(
            'action' => $this->generateUrl('post_delete', array('id' => $post->getId())),
        ))
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();

            return new JsonResponse(array(
                'status' => 'ok'
            ));
        }

        return $this->render('@App/post/delete.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \LogicException
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     *
     * @Route(path="/order", name="post_order")
     */
    public function orderAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new BadRequestHttpException('Only ajax accepted');
        }

        if ($request->isMethod('POST') && $request->request->has('posts') && is_array($posts = $request->request->get('posts'))) {

            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('AppBundle:Post');

            foreach ((array)$posts as $i => $post_id) {
                $post = $repository->find($post_id);
                $post->setPosition($i);
            }

            $em->flush();

            return new JsonResponse(array(
                'status' => 'ok',
            ));

        } else {
            throw new BadRequestHttpException('No posts in request');
        }
    }

    /**
     * @param Request $request
     * @param Rubrique $rubrique
     *
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @throws \LogicException
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     *
     * @Route(path="/new/{id}", name="post_new")
     */
    public function addAction(Request $request, Rubrique $rubrique)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new BadRequestHttpException('Only ajax accepted');
        }

        $post = new Post();
        $post->setType($rubrique->getType())
            ->setRubrique($rubrique);

        $form = $this->get('form.factory')->createNamedBuilder('new_post', PostType::class, $post, array(
            'action' => $this->generateUrl('post_new', array(
                'id' => $rubrique->getId(),
            )),
            'fields' => $post->getType()->getFields(),
        ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return new JsonResponse(array(
                'status' => 'ok'
            ));
        }

        return $this->render('@App/post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }
}
