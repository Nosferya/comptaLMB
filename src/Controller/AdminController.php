<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
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
    public function index(Request $request,ObjectManager $manager, UserPasswordEncoderInterface $encoder, UserRepository $repo)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPasswordUser());
            $user->setPasswordUser($hash);
            $now = new \DateTime();
            $user->setDateInscription($now);
            $user->setFirstLogin(1);
            $manager->persist($user);
            $manager->flush();
        }
        return $this->render('admin/index.html.twig', [
            'form' =>$form->CreateView(),
            'ads' => $repo->findAll()
        ]);
    }

    /**
     * 
     * @Route("/admin/user/{id}/edit", name="admin_user_edit")
     * 
     * @param User $user
     * @return Response
     */
    public function edit(User $user, Request $request, ObjectManager $manager,UserPasswordEncoderInterface $encoder){
        $form = $this->createForm(RegistrationType::class, $user);

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPasswordUser());
            $user->setPasswordUser($hash);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'utilisateur <strong>{$user->getNickname()}</strong> a bien été modifié"
            );


           return $this->redirectToRoute('admin');

            
            
        }

        return $this->render('admin/useredit.html.twig', [
            'user' =>$user,
            'form'=> $form->createView()
        ]);
    }


    /**
     * 
     * @Route("/admin/user/{id}/delete", name="admin_user_delete")
     * 
     * @param User $user
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(User $user,ObjectManager $manager){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $manager->remove($user);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'utilisateur {$user->getNickname()} a bien été supprimé !"
        );

        return $this->redirectToRoute('admin');
    }
}
