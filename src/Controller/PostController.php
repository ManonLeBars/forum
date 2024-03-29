<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\PostType;
use App\Form\CommentType;
use App\Repository\PostRepository;
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
    public function browse(PostRepository $postRepository): Response
    {
        $post = new Post;
        
        $post = $postRepository->findBy(['is_active' => true], ['created_at' => 'DESC']);

        return $this->render('post/browse.html.twig', [
            'posts' => $post,
        ]);
    }

    /**
     * @Route("/{id}", name="read" , requirements={"id"="\d+"})
     */
    public function read(Post $post, Request $request): Response
    {
        $comment = new Comment();

        //Add new comment to this post
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        // Control of the form that is submit and flush
        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setPost($post);
            $comment->setUser($this->getUser());
        
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Commentaire ajouté'
            );            

            return $this->redirectToRoute('post_read', ['id' => $post->getId()]);
        }

        return $this->render('post/read.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            

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

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Nouveau post créé'
            );

            return $this->redirectToRoute('post_read', ['id' => $post->getId()]);

        }
        return $this->render('post/add.html.twig', [
        'form' => $form->createView(),
        ]);
    }

        /**
     * @Route("/post/supprimer/{id}", name="delete", requirements={"id"="\d+"})
     */
    public function delete(Post $post, Request $request): Response
    {

        if ($post->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        //Check if the token is available
        $token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('deletePost', $token)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();
            //Display success message
            $this->addFlash(
                'success',
                'Le post a été supprimé'
            );

            return $this->redirectToRoute('post_browse');
        }
        // If the token is not available, browse error message
        throw $this->createAccessDeniedException('Veuillez essayer de nouveau');
    }
}
