<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/administrateur/accueil", name="admin")
     */
    public function index(Request $request,ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPasswordUser());
            $user->setPasswordUser($hash);
            $now = new \DateTime();
            $user->setDateInscription($now);
            $manager->persist($user);
            $manager->flush();
        }
        return $this->render('admin/index.html.twig', [
            'form' =>$form->CreateView()
        ]);
    }
}
