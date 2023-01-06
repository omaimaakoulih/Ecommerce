<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;

class MyController extends AbstractController
{
    /**
     * @Route("/my", name="app_my")
     */
    public function index(): Response
    {
        return $this->render('my/index.html.twig', [
            'controller_name' => 'MyController',
        ]);
    }
    /**
     * @Route("/my/home/{user}", name="home_page")
     */

    public function home($user):Response
    {
        return $this->render('my/Home.html.twig',['user' => $user]);
    }

    /**
     * @Route("/my/login", name="login_page")
     */
     public function login(Request $request):Response
     {
        if($request->request->count()>0){
            
                $repo = $this->getDoctrine()->getRepository(User::class);
                $user =$repo->findOneBy(['email' => $request->request->get('email')]);

                if($user != null){
                    if($user->getPassword() === $request->request->get('password')){
                        return $this->redirectToRoute('home_page',['user' => $user->getUserName()]);
                    }
                    else{
                        return $this->render('my/login.html.twig',['failed'=>true]);
                    }
                }
                else{
                    return $this->render('my/login.html.twig',['failed'=>true]);
                }
                
            
        }

         return $this->render('my/login.html.twig',['failed'=>false]);
     }

     /**
     * @Route("/my/signup", name="signup_page")
     */
     public function signup(Request $request)
     {
       
        
        if($request->request->count()>0){
            if($request->request->get('password') === $request->request->get('confpassword')){
                $manager = $this->getDoctrine()->getManager();

                $user = new User();
                $user->setEmail($request->request->get('email'))
                    ->setUserName($request->request->get('userName'))
                    ->setPassword($request->request->get('password'));
                
                $manager->persist($user);
                $manager->flush();

                return $this->redirectToRoute('home_page',['user' => $user->getUserName()]);
            }
            else{
                return $this->render('my/signup.html.twig',['failed'=>true]);
            }
        }
         return $this->render('my/signup.html.twig',['failed'=>false]);
     }
}
