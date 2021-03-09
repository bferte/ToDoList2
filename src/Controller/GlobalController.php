<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class GlobalController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(): Response
    {

        if($this->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'))
        {
            return $this->redirectToRoute('login');
        }
        else if($this->isGranted('ROLE_USER'))
        {
            return $this->redirectToRoute('notes');
        }
        

        //return $this->render('global/accueil.html.twig');
    }

    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request,EntityManagerInterface $em,UserPasswordEncoderInterface $encoder)
    {
        $user = new User;
        $form = $this->createForm(InscriptionType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $passwordCrypte = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($passwordCrypte);
            $user->setRoles('ROLE_USER');
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("accueil");
        }
        
        return $this->render('global/inscription.html.twig',[
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $util)
    {
        return $this->render('global/login.html.twig',[
            "lastUserName" => $util->getLastUsername(),
            "error" => $util->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){

        
    }
}
