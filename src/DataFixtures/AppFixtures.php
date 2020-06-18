<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Booking;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
	private $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder){
		$this->encoder = $encoder;
	}


    public function load(ObjectManager $manager)
    {
		$faker = Factory::create('fr_FR');


		$adminRole= new Role();
		$adminRole->setTitle('ROLE_ADMIN');
		$manager->persist($adminRole);


		$adminUser = new User();
		$adminUser->setFirstName('Yousri')
				  ->setLastName('alaoui')
				  ->setEmail('youssri@gmail.com')
				  ->sethash($this->encoder->encodePassword($adminUser,'password'))
				  ->setSlug("Nom-prenom")
				  ->setPicture('https://assets-fr.imgfoot.com/media/cache/642x382/neymar-5e9abe7a6fa87.jpg')
				  ->setIntroduction($faker->sentence())
				  ->setDescription("<p> c'est une description c'est une description c'est une description c'est une description c'est une description c'est une description c'est une description </p>")
				  ->addUserRole($adminRole);
		$manager->persist($adminUser);




		//gestion des utilisateurs
		$users = [];
		

	
		for($i = 1; $i <= 10 ; $i++	){
			$user = new User();
			$pictureId = $i .'.jpg';
			$picture = 'https://randomuser.me/api/portraits/men/' . $pictureId;

			$hash = $this->encoder->encodePassword($user, 'password');

			$user->setFirstName("Prenom $i")
				->setLastName("Nom $i")
				->setEmail("nom$i-penom$i@gmail.com")
				->setIntroduction("Introduction $i")
				->setDescription("<p> c'est une description c'est une description c'est une description c'est une description c'est une description c'est une description c'est une description $i </p>")
				->setHash($hash)
				->setSlug("Nom-prenom-$i")
				->setPicture($picture);

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
		
		//gestion des reservations
		for($j = 1; $j <= mt_rand(0, 10); $j++){
			$booking= new Booking();

			$createdAt = $faker->dateTimeBetween('-6 months');
			$startDate = $faker->dateTimeBetween('-3 months');

			$duration = mt_rand(3,10);

			$endDate = (clone $startDate)->modify("+$duration jours");	
			$amount = $ad->getPrice() * $duration;

			$booker = $users[mt_rand(0, count($users) -1)];

			$comment = $faker->paragraph();

			$booking->setBooker($booker)
					->setAd($ad)
					->setStartDate($startDate)
					->setEndDate($endDate)
					->setCreatedAt($createdAt)
					->setAmount($amount)
					->setComment($comment);
			
			$manager->persist($booking);
			//gestion des commentaires

			if(mt_rand(0,1)){
				$comment = new Comment();
				$comment->setContent($faker->paragraph())
						->setRating(mt_rand(1,5))
						->setAuthor($booker)
						->setAd($ad);
						
				$manager->persist($comment);
			}


		}


    	$manager->persist($ad);
    }
        $manager->flush();
    }
}
