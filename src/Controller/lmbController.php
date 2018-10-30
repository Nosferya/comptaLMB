<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Grade;
use App\Entity\Saisie;
use App\Form\GradeType;
use App\Form\SaisieType;
use App\Form\RegistrationType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use App\Repository\GradeRepository;
use App\Repository\SettingRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @Route("/redirect", name="redirect")
     */
    public function redirectUser()
    {
       $user=$this->getUser();
       $session =$user->getFirstLogin();
       if($session== 1){
           return $this->redirectToRoute('resetPassword');
       }
       else {
           return $this->redirectToRoute('saisie');
       }
    }

    /**
     * @Route("/resetpassword", name="resetPassword")
     */
    public function resetpassword(ObjectManager $manager, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user =$this->getUser();
        $id = $user->getId();
        $form = $this->CreateForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $ModifyUser= $manager->getRepository(User::class)->find($id);
            $password=$form->get('passwordUser')->getData();
            $hash = $encoder->encodePassword($ModifyUser ,$password);
            $ModifyUser->setPasswordUser($hash);
            $ModifyUser->setFirstLogin(0);
            

            $manager->persist($ModifyUser);
            $manager->flush();
            return $this->redirectToRoute('redirect');

        }


        return $this->render('lmb/resetpassword.html.twig',[
            'resetForm' => $form->CreateView()
        ]);
    }

    /**
     * @Route("/saisie", name="saisie")
     */
    public function saisie(ObjectManager $manager, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user =$this->getUser();
        $id = $user->getId();
        $saisie = new Saisie();
        $form = $this->CreateForm(SaisieType::class, $saisie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
           $entity= $manager->getRepository(User::class)->find($id);
           $entity->getId();
           $saisie->setUser($entity);
            
           
            $manager->persist($saisie);
            $manager->flush();
        }

        return $this->render('lmb/saisie.html.twig',[
            'Saisie' =>$form->CreateView()
        ]);

    }

    /**
     * @Route("/primes", name="primes")
     */
     public function prime()
     {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
         return $this->render('lmb/prime.html.twig');
     }

     /**
     * @Route("/utilisateurs", name="utilisateurs")
     */

    public function utilisateurs()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('lmb/utilisateurs.html.twig');
    }

    /**
     * @Route("/grades", name="grades")
     */

    public function grades(Request $request,ObjectManager $manager, GradeRepository $repo)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $grade = new Grade();
        $form = $this->createForm(GradeType::class, $grade);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($grade);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le grade <strong>{$grade->getNomGrade()}</strong> a bien été ajouté"
            );

        }
        return $this->render('lmb/grades.html.twig', [
            'formGrade' =>$form->CreateView(),
            'ads' => $repo->findAll()
        ]);

    }
 /**
     * @Route("/settings", name="settings")
     */

    public function settings(SettingRepository $repo)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('lmb/settings.html.twig',[
            'setting' => $repo->findAll()
        ]);
    }

      /**
     * 
     * @Route("/admin/grade/{id}/edit", name="admin_grade_edit")
     * 
     * @param Grade $grade
     * @return Response
     */
    public function edit(Grade $grade, Request $request, ObjectManager $manager){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(GradeType::class, $grade);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($grade);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "Le grade <strong>{$grade->getNomGrade()}</strong> a bien été modifié"
            );

           return $this->redirectToRoute('grades');

            
        }

        return $this->render('admin/gradeedit.html.twig', [
            'grade' =>$grade,
            'form'=> $form->createView()
        ]);
    }

    /**
     * 
     * @Route("/admin/grade/{id}/delete", name="admin_grade_delete")
     * 
     * @param Grade $grade
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Grade $grade,ObjectManager $manager){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if(count($grade->getUser())> 0){
           $this->addFlash(
               'warning',
               "Vous ne pouvez pas supprimer le grade <strong> {$grade->getNomGrade() }</strong> car il est associé à un utilisateur."
           ) ;
        }
        else {

            $manager->remove($grade);
            $manager->flush();
    
            $this->addFlash(
                'success',
                "Le grade <strong>{$grade->getNomGrade()}</strong> a bien été supprimé !"
            );

        }

        return $this->redirectToRoute('grades');
    }

}
