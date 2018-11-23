<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Grade;
use App\Entity\Saisie;
use App\Entity\Setting;
use App\Form\GradeType;
use App\Form\SaisieType;
use App\Form\RegistrationType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use App\Repository\GradeRepository;
use App\Repository\SaisieRepository;
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
        //function that allows to check if the user need to reset his own password
       $user=$this->getUser();
       $session =$user->getFirstLogin();
       if($session== 1){
           //if $session = 1 redirect to the reset password form
           return $this->redirectToRoute('resetPassword');
       }
       else {
           //if $session = 0 redirect to the main page after login
           return $this->redirectToRoute('saisie');
       }
    }

    /**
     * @Route("/resetpassword", name="resetPassword")
     */
    public function resetpassword(ObjectManager $manager, Request $request, UserPasswordEncoderInterface $encoder)
    {
        //function when administrator reset the password of an user
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user =$this->getUser();
        $id = $user->getId();
        //variable $id contain the user's id of the current session
        $form = $this->CreateForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $ModifyUser= $manager->getRepository(User::class)->find($id);
            //when form is submitted $modifyuser contain the user corresponding to the $id
            $password=$form->get('passwordUser')->getData();
            $hash = $encoder->encodePassword($ModifyUser ,$password);
            $ModifyUser->setPasswordUser($hash);
            $ModifyUser->setFirstLogin(0);
            //when the function is complete set the firstlogin field corresponding to the user to 0 , that way user do not have to resrt his password on his next connection
            

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
    public function saisie(ObjectManager $manager, Request $request, SaisieRepository $repo, UserRepository $userrepo)
    {
        //function that allow a user to enter the number of products he selled and calculate automatically his wage according to other parameters
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user =$this->getUser();
        $id = $user->getId();
        //create a new saisie for the user
        $saisie = new Saisie();
        $form = $this->CreateForm(SaisieType::class, $saisie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
           $entity= $manager->getRepository(User::class)->find($id);
           $entity->getId();
           $saisie->setUser($entity);
           $now= new \DateTime();
           $saisie->setDateSaisie($now);
           $vente =$saisie->getVenteGrossiste();
            $prix= $manager->getRepository(Setting::class)->findOneBy([
                'id'=> 1,
        

            ]);
            //variable $vente and $prix are the other parameters needed to calculate the wage of the user
            $prime=$entity->getPrimeUser();
            $prix=$prix->getReventeUnitaire();

            $idgrade=$entity->getGrade();
            $pourcent= $manager->getRepository(Grade::class)->findOneBy([
                'id'=>$idgrade,
            ]);
            $pourcent = $pourcent->getPourcentPnj();
            
            $calcul = $prix * $vente;
            $calcul=$calcul*($pourcent/100);
            $calcul=$calcul+$prime;
            //variable $calcul calculate the wage of the user according to other parameters
            

            $entity->setPrimeUser($calcul);
           
            $manager->persist($saisie);
            $manager->flush();

            $this->addFlash(
                'success',
                'La saisie a bien été enregistrée !' 

            );

        }
        $saisie=$this->getDoctrine()->getRepository(Saisie::class);
        $liste = $saisie->findBy(
            ['User'=>$id],
            ['id'=>'DESC']

        );
        $listerepo= $repo->findById();

        return $this->render('lmb/saisie.html.twig',[
            'Saisie' =>$form->CreateView(),
            'user'=>$liste,
            'allSaisie'=>$listerepo,
            'userrepo'=>$user->getPrimeUser(),

            
        ]);

    }



        /**
     * 
     * @Route("/user/saisie/{id}/edit", name="user_saisie_edit")
     * 
     * @param Saisie $saisie
     * @return Response
     */
    public function editSaisie(Saisie $saisie, Request $request, ObjectManager $manager){

        //this function allows a user to modify his own number of products selled
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $saisieNow = $saisie->getVenteGrossiste();
        $prix= $manager->getRepository(Setting::class)->findOneBy([
            'id'=> 1,

        ]);
        $user =$this->getUser();
        $id = $user->getId();
        $entity= $manager->getRepository(User::class)->find($id);
        $form = $this->CreateForm(SaisieType::class, $saisie);
        $prime=$entity->getPrimeUser();
            
        $prix=$prix->getReventeUnitaire();

            
        $idgrade=$entity->getGrade();
            
        $pourcent= $manager->getRepository(Grade::class)->findOneBy([
                
            'id'=>$idgrade,
            ]);
            $pourcent = $pourcent->getPourcentPnj();
            
      

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $newSaisie = $saisie->getVenteGrossiste();
            if ($saisieNow < $newSaisie){
                $diff = ($newSaisie-$saisieNow);
                $calcul = $prix * $diff;
                $calcul=$calcul*($pourcent/100);
                $calcul=$calcul+$prime;
                
                $entity->setPrimeUser($calcul);
           
                $manager->persist($saisie);
                $manager->flush();

                return $this->redirectToRoute('saisie');
    
            }

            elseif ($saisieNow > $newSaisie){
                $diff = ($saisieNow-$newSaisie);

                $calcul = $prix * $diff;
                $calcul=$calcul*($pourcent/100);
                $calcul=$prime-$calcul;
                
                $entity->setPrimeUser($calcul);
           
                $manager->persist($saisie);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'La saisie a bien été modifiée !' 

                );


                return $this-redirectToRoute('saisie');
            }

            else {
                return $this-redirectToRoute('saisie');
            }

        }

        return $this->render('lmb/saisieedit.html.twig',[
            'saisie'=>$saisie,
            'form'=>$form->createView()
        ]);
  
    }



    /**
     * @Route("/primes", name="primes")
     */
     public function prime(UserRepository $repo)
     {
         //this function is to show all wages of user to an administrator
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user=$this->getUser()->getRoles()[0];
        if($user === "admin" || $user ==="comptable"){
            
            return $this->render('lmb/prime.html.twig', [
                'primes' => $repo->findAll()
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


    /**
     * @Route("/ForbiddenAccess403", name="error403")
     */
    public function error403()
    {
        return $this->render('lmb/403forbidden.html.twig');
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
        $user=$this->getUser()->getRoles()[0];
        if($user === "admin"){
            
            return $this->render('lmb/grades.html.twig', [
                'formGrade' =>$form->CreateView(),
                'ads' => $repo->findAll()
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
 /**
     * @Route("/settings", name="settings")
     */

    public function settings(SettingRepository $repo)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user=$this->getUser()->getRoles()[0];
        if($user === "admin"){
            
            return $this->render('lmb/settings.html.twig',[
                'setting' => $repo->findAll()
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

      /**
     * 
     * @Route("/admin/grade/{id}/edit", name="admin_grade_edit")
     * 
     * @param Grade $grade
     * @return Response
     */
    public function edit(Grade $grade, Request $request, ObjectManager $manager){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        $role=$this->getUser()->getRoles()[0];
        if($role === "admin"){
            


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
     * @Route("/admin/grade/{id}/delete", name="admin_grade_delete")
     * 
     * @param Grade $grade
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Grade $grade,ObjectManager $manager){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $role=$this->getUser()->getRoles()[0];
        if($role === "admin"){
            
            
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
  

        else {
            $this->addFlash(
                'danger',
                "Vous n'êtes pas autorisé à accéder à cette page "
            );
            return $this->redirectToRoute("error403");

        }
    }

}
