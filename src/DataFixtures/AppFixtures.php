<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\User;
use App\Entity\Image;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
		//gestion des utilisateurs
		$users = [];
		for($i = 1; $i <= 10 ; $i++	){
			$user = new User();

			$user->setFirstName("Prenom $i")
				->setLastName("Nom $i")
				->setEmail("nom$i-penom$i@gmail.com")
				->setIntroduction("Introduction $i")
				->setDescription("<p> c'est une description c'est une description c'est une description c'est une description c'est une description c'est une description c'est une description $i </p>")
				->setHash('password')
				->setSlug("Nom-prenom-$i");

			$manager->persist($user);
			$users[] = $user;
				
		}

		//Gestion des annonces
    	for($i = 1; $i <= 30; $i++){
    	$ad = new Ad();

			$user = $users[mt_rand(0, count($users) - 1)];

    	$ad->setTitle("Titre de l'annonce n $i")
        ->setSlug("Titre-de-l-annonce$i")
    	->setCoverImage("http://placehold.it/1000x300")
    	->setIntroduction("Bonjour c'est une introduction")
    	->setPrice(mt_rand(40,3000))
    	->setContent("<p>Je suis un contenu riche! </p>")
		->setVille("Rabat")
		->setAuthor($user);


    	for($j = 1; $j <= mt_rand(2, 5); $j++){
    		$image = new Image();

    		$image->setUrl("http://placehold.it/1000x300")
    		->setCaption("Image de la voiture")
    		->setAd($ad);

    		$manager->persist($image);
    	}


    	$manager->persist($ad);
    }
        $manager->flush();
    }
}
