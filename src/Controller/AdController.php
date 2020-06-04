<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
    	

    	$ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads ,
        ]);
    }
     /**
 *permet de créer une annonce
    * 
     * @Route("/ads/new", name="ads_create")
     *
     * @return Response
     */
    public function create(){
        $ad = new Ad();


        $form = $this->createForm(AdType::class, $ad);



        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);

    }

 /**
 *permet d'afficher une seule annonce
 	* 
     * @Route("/ads/{slug}", name="ads_show")
     *
     * @return Response
     */
    public function Show( Ad $ad){
    	// je recupère l'annonce qui correspond au slug 
    	// $ad = $repo->findOneBySlug($slug);

    	return $this->render('ad/show.html.twig', [
    		'ad' => $ad

    	]);
    }

}
