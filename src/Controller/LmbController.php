<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LmbController extends AbstractController
{
    /**
     * @Route("/lmb", name="lmb")
     */
    public function index()
    {
        return $this->render('lmb/index.html.twig', [
            'controller_name' => 'LmbController',
        ]);
    }
}
