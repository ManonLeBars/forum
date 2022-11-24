<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post", name="post_")
*/
class PostController extends AbstractController
{
    /**
     * @Route("", name="browse")
     */
    public function browse(): Response
    {
        return $this->render('post/browse.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    /**
     * @Route("/1", name="read")
     */
    public function read(): Response
    {
        return $this->render('post/read.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    /**
     * @Route("/ajouter", name="add")
     */
    public function add(Request $request): Response
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $post->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

        }
        return $this->render('post/add.html.twig', [
        'form' => $form->createView(),
        ]);
    }
}
