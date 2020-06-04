<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {
/**
  
  *@Route("/hello/{prenom}/{age}" , name="hello")
    @Route("/hello", name="hello_base")
  @Route("/hello/{prenom}", name="hello_prenom")
  *@return void
*/
    public function hello() {
      return $this->render(
        'hello.html.twig', 
        [
         

        ]
      );
    }
    /**
     * @Route("/", name="homepage")
     */
    public function home(){
       

       return $this->render(
           'home.html.twig',
          
       );

}

}

?>