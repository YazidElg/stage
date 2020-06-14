<?php

namespace App\Controller;
use App\Entity\Ad;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AdRepository;
use App\Controller\ObjectMananger;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function create(Request $request){
        $ad = new Ad();

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);


        
        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();

            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $ad->setAuthor($this->getUser());

            $manager->persist($ad);
            $manager->flush();


            $this->addFlash(
                'success', 
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée  "
            );
           

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);

    }
    /**
    *permet d afficher le formulaire d edition
    *@Route("/ads/{slug}/edit", name="ads_edit")
    *@Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Impossible de modifier une annonce qui ne vous appartient pas !")
    * @return Response
    */
    public function edit(Ad $ad, Request $request){

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();

            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();


            $this->addFlash(
                'success', 
                "Les modifications de l'annonce <strong>{$ad->getTitle()}</strong> ont bien été enregistrées  "
            );
           

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
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
     /**
    *permet de supprmer une annonce
 	* 
    * @Route("/ads/{slug}/delete", name="ads_delete")
    *@Security("is_granted('ROLE_USER') and user == ad.getAuthor()", message="Vous n'avez pas le droit")
    *@param Ad $ad
    *@param ObjectManager $manager
    * @return Response
    */
    public function delete(Ad $ad,ObjectManager $manager){
        $manager->remove($ad);
        $manager->flush();
        $this->addFlash(
            'success',
            "L'annonce <strong>{$ad->getTitle()}</strong> a bien ete supprimee !"

        );

        return $this->redirectToRoute("ads_index");
    }

}
