<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordUpdateType;
use App\Entity\PasswordUpdate;
use  App\Form\AccountType;
use App\Form\RegistrationType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * permet d'afficher et de gerer le formulaire
     * @Route("/login", name="account_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig',[
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }
    /**
     * permet de se déconnecter
     * 
     * @Route("/logout", name="account_logout")
     * 
     * @return void
     */
    public function logout() {
        // rien
    }
    /**
     * permet d'afficher le formulaire d'inscription
     * 
     * @Route("/register", name="account_register")
     * 
     * @return Response
     */


    public function register(Request $request,  UserPasswordEncoderInterface $encoder){
        $user = new User();        
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
       
        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user , $user->getHash());
            $user->setHash($hash);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compte a bien été créé ! Vous pouvez vous connecter !"
            );
            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
        ]);



    }
    /**
     * Permet de modifer un profile
     * 
     * @Route("/account/profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function profile(Request $request) {
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class , $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les données du profil ont été enregistrée avec succès !"
            );
            
        }
        return $this->render('account/profile.html.twig',[
            'form' => $form->createView()
        ]);


    }

    /**
     * permet d'afficher le profil de l'utilisateur connecté
     * 
     * @Route("/account", name="account_index")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function myAccount(){
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     *Permet d'afficher les reservations d'un user
     * 
     *@Route("/account/bookings", name="account_bookings")
     * 
     *@return Response
     */
    public function bookings(){
        return $this->render('account/bookings.html.twig');
    }









    /**
     * Permet de modifier le mot de passe
     * 
     * @Route("/account/password-update", name="account_password")
     * 
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder){
        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
         
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            // 1 verifier que le old password est le meme que le password du user
           if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash() )){

           }else{
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);
                $user->setHash($hash);

                $manager->persist($hash);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié !"
                );
                return $this->redirectToRoute('homepage');
            }
        
        }

        return $this->render('account/password.html.twig',[
            'form' => $form->createView()
        ]);

    }
}