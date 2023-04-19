<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/comment", name="comment_")
*/
class CommentController extends AbstractController
{
    /**
     * @Route("/supprimer/{id}", name="delete", requirements={"id"="\d+"})
     */
    public function delete(Comment $comment, Request $request): Response
    {

        if ($comment->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        //Check if the token is available
        $token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('deleteComment', $token)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();
            //Display success message
            $this->addFlash(
                'success',
                'Le commentaire a été supprimé'
            );

            return $this->redirectToRoute('post_browse');
        }
        // If the token is not available, browse error message
        throw $this->createAccessDeniedException('Veuillez essayer de nouveau');
    }
}
