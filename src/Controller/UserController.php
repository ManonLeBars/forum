<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profil", name="user_")
*/
class UserController extends AbstractController
{

    /**
     * @Route("/other/{id}", name="browse",  requirements={"id"="\d+"})
     */
    public function browse(User $user): Response
    {
        return $this->render('user/browse.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/mine/{id}", name="read",  requirements={"id"="\d+"})
     */
    public function read(User $user): Response
    {
        return $this->render('user/read.html.twig', [
            'user' => $user,
        ]);
    }

    /**
    * @Route("/modifier/{id}", name="edit", requirements={"id"="\d+"}, methods={"GET","POST"})
    */
    public function edit(User $user, Request $request): Response
    {
        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->flush();
            // Display success message
            $this->addFlash(
                'success',
                'Ton profil a bien été modifié'
            );

            return $this->redirectToRoute('user_read', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
