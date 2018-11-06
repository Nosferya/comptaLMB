<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Grade;
use App\Entity\Niveau;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenomUser')
            ->add('nomUser')
            ->add('telUser', TextType::class)
            // ->add('passwordUser', PasswordType::class)
            ->add('nickname')    
            ->add('grade', EntityType::class, array(

                'class' => Grade::class,
            

                'choice_label' => 'nomGrade',
            

            ))

            ->add('niveau', EntityType::class, array(

                'class' =>  Niveau::class,
            

                'choice_label' => 'nomNiveau',
            

            ));
       
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
