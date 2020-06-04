<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
    	for($i = 1; $i <= 30; $i++){
    	$ad = new Ad();

    	$ad->setTitle("Titre de l'annonce n $i")
        ->setSlug("titre-de-l-annonce$i")
    	->setCoverImage("http://placehold.it/1000x300")
    	->setIntroduction("Bonjour c'est une introduction")
    	->setPrice(mt_rand(40,3000))
    	->setContent("<p>Je suis un contenu riche!</p>")
    	->setVille("Rabat");


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
