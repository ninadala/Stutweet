<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/new', name: 'user_new')]
    public function new(UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $doctrine, Request $request): Response
    {
        $user = new User($userPasswordHasher);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $repository = $doctrine->getRepository(User::class);
            // $users = $repository->findAll();
            // foreach ($users as $u) {
            //     if ($u->getUsername() === $user->getUsername()) {
            //         return new \LogicException("Ce nom d'utilisateur est déjà pris");
            //     }
            // }
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("home");
        }
        
        return $this->render('user/form.html.twig', [
            'user_form' => $form->createView(),
        ]);
    }
}
