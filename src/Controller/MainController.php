<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_browse")
     */
    public function browse(): Response
    {
        return $this->render('main/browse.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
