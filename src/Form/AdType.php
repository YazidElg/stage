<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends AbstractType
{


    /**
    *Permet d'avoir la configuration de base d'un champ 
    *@param string $label
    *@param string $placeholder
    *@param array $options
    *@return array

    */

    private function getConfiguration($label, $placeholder, $options = []) {
        return array_merge([
                'label' => $label,
                'attr' => [
                    'placeholder' => $placeholder
                        ]

], $options);


    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre", "Tapez un titre pour votre annonce"))
       ->add('slug', TextType::class, $this->getConfiguration("Adresse web", "Tapez l'adresse web (Automatique)", [
            'required' => false

       ]))
            ->add('introduction', TextType::class, $this->getConfiguration("introduction", "Donnez une introduction pour votre annonce"))
            ->add('content', TextareaType::class, $this->getConfiguration("Description detaillée", "Tapez une description a votre annonce"))
            ->add('ville', ChoiceType::class, [
    'choices' => [
        'Les villes' => [
            'Rabat' => 'Rabat',
            'Kenitra' => 'Kenitra',
            'Tanger' => 'Tanger',
            'Marrakech' => 'Marrakech',
            'Casablanca' => 'Casablanca',
            'Tetouane' => 'Tetouane',
            'Salé' => 'Salé',
        ]]])
            ->add('coverImage' ,UrlType::class, $this->getConfiguration("Url de l'image principale", "Donnez l'adresse d'une image"))
            ->add('price', MoneyType::class, $this->getConfiguration("Prix par jour", "Indiquez le prix que vous voulez pour un jour")
        )
            ->add('images', CollectionType::class, 
                [
                    'entry_type' => ImageType::class, 
                    'allow_add' => true,
                    'allow_delete' => true
                ]

        )
           
     ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
