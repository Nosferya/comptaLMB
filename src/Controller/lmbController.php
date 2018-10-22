<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class lmbController extends AbstractController
{
    /**
     * @Route("/", name="login")
     */
    public function login()
    {
        return $this->render('lmb/login.html.twig', [
            'controller_name' => 'LmbController',
        ]);
    }

/**
     * @Route("/saisie", name="saisie")
     */

    public function prime()
    {
        return $this->render('lmb/saisie.html.twig');
    }

    /**
     * @Route("/primes", name="primes")
     */

     public function saisie()
     {
         return $this->render('lmb/prime.html.twig');
     }

     /**
     * @Route("/utilisateurs", name="utilisateurs")
     */

    public function utilisateurs()
    {
        return $this->render('lmb/utilisateurs.html.twig');
    }

    /**
     * @Route("/grades", name="grades")
     */

    public function grades()
    {
        return $this->render('lmb/grades.html.twig');
    }
}
