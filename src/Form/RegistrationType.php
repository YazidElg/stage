<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class RegistrationType extends ApplicationType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("Prénom", "Votre prénom ..."))
            ->add('lastName', TextType::class, $this->getConfiguration("Nom", "Votre nom de famille ..."))
            ->add('email', EmailType::class, $this->getConfiguration("Email", "Votre email ..."))
            ->add('picture', UrlType::class, $this->getConfiguration("Photo de profil", "URL de votre photo ..."))
            ->add('hash', PasswordType::class, $this->getConfiguration("Mot de passe", "Choisissez un bon mot de passe ..."))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration("Confirmation de mot de passe", "Confirmez votre mot de passe ..."))
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "Présentez vous ..."))
            ->add('description', TextareaType::class, $this->getConfiguration("Description détaillée", "Présentez vous en détails"))
            ->add('slug', TextType::class, $this->getConfiguration("Adresse web", "Adresse web"))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
