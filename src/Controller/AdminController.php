<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Historique;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use App\Repository\HistoriqueRepository;
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
        //function that allow to create a new user
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = new User();
        //create a new user in $user
        $form = $this->createForm(RegistrationType::class, $user);
        $password=$user->setPasswordUser("failyv");
        //set the default password to the user
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPasswordUser());
            $user->setPasswordUser($hash);
            //hash the user's password
            $now = new \DateTime();
            $user->setDateInscription($now);
            //set the creation date of the user
            $user->setFirstLogin(1);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'utilisateur <strong>{$user->getNickname()}</strong> a bien été créé"
            );


        }
        $role=$this->getUser()->getRoles()[0];
        if($role === "admin" || $role ==="DRH"){
            //if the user's role is  admin or DRH he can create an user
            
            return $this->render('admin/index.html.twig', [
                'form' =>$form->CreateView(),
                'ads' => $repo->findAll()
            ]);


        }
        else {
            //else he is redirected to an error page
            $this->addFlash(
                'danger',
                "Vous n'êtes pas autorisé à accéder à cette page "
            );
            return $this->redirectToRoute("error403");

        }
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

        $role=$this->getUser()->getRoles()[0];
        if($role === "admin" || $role ==="DRH"){
            

            $form->handleRequest($request);
    
    
            if($form->isSubmitted() && $form->isValid()){
                // $hash = $encoder->encodePassword($user, $user->getPasswordUser());
                // $user->setPasswordUser($hash);
                $manager->persist($user);
                $manager->flush();
    
                $this->addFlash(
                    'success',
                    "L'utilisateur <strong>{$user->getNickname()}</strong> a bien été modifié"
                );
    
    
               return $this->redirectToRoute('admin');
                 
            }

        }
        else {
            $this->addFlash(
                'danger',
                "Vous n'êtes pas autorisé à accéder à cette page "
            );
            return $this->redirectToRoute("error403");

        }


        return $this->render('admin/useredit.html.twig', [
            'user' =>$user,
            'form'=> $form->createView()
        ]);
    }


        /**
     * 
     * @Route("/admin/prime/{id}/pay", name="admin_prime_pay")
     * 
     * @param User $user
     * @return Response
     */
    public function pay(User $user, Request $request, ObjectManager $manager){

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $role=$this->getUser()->getRoles()[0];
        if($role === "admin" || $role ==="comptable"){
            $historique = new Historique;
            $prime =$user->getPrimeUser();
            $id=$user->getId();
            $now=new \DateTime();
            $historique->setPrime($prime);
            $historique->setDatePaiement($now);
            $historique->setUser($user);
            $user->setPrimeUser(0);

            $manager->persist($historique);
            $manager->flush();

            return $this->redirectToRoute('primes');


    }
    else {
        $this->addFlash(
            'danger',
            "Vous n'êtes pas autorisé à accéder à cette page "
        );
        return $this->redirectToRoute("error403");
    }

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
        $role=$this->getUser()->getRoles()[0];
        if($role === "admin"){
            
            $manager->remove($user);
            $manager->flush();
    
            $this->addFlash(
                'success',
                "L'utilisateur {$user->getNickname()} a bien été supprimé !"
            );
    
            return $this->redirectToRoute('admin');

        }
        else {
            $this->addFlash(
                'danger',
                "Vous n'êtes pas autorisé à accéder à cette page "
            );
            return $this->redirectToRoute("error403");

        }
    }


    /**
     * 
     * @Route("/logout", name="logout")
     * 

     */
    public function logout(){

    }


    /**
     * 
     * @Route("/admin/user/{id}/resetpassword", name="admin_user_resetpassword")
     * 
     * @param User $user
     * @param ObjectManager $manager
     * @return Response
     */
    public function resetpassword(User $user,ObjectManager $manager, UserPasswordEncoderInterface $encoder){
        //function that allow a user to reset his own password
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $defaultpassword ="failyv";
        //the default password is failyv
        $user->setPasswordUser($defaultpassword);
        $hash = $encoder->encodePassword($user, $user->getPasswordUser());
        $user->setPasswordUser($hash);
        //use brcrypt to hash the user's password

        $user->setFirstLogin(1);
        //then set his field firstlogin to 1 to allow him to be redirect to the reset password form
        $manager->persist($user);
        $manager->flush();

            $this->addFlash(
                'success',
                "Le mot de passe de l'utilisater {$user->getNickname()} a bien été ré-initialité  !"
            );

            return $this->redirectToRoute('admin');

    }


     /**
     * @Route("/admin/historique", name="historique")
     */
    public function historique(HistoriqueRepository $repo)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user=$this->getUser()->getRoles()[0];
        if($user === "admin"){
            
            return $this->render('admin/historique.html.twig',[
                'historique'=>$repo->findAll()
            ]);

       
        }
        else {
            $this->addFlash(
                'danger',
                "Vous n'êtes pas autorisé à accéder à cette page "
            );
            return $this->redirectToRoute("error403");

        }


    }

    

}
