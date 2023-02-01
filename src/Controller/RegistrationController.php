<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\JWTService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SendMailService $mail, JWTService $jwt): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                ),
            $user->setProfilImage('/images/author/new-user.png'),
            $user->setIsVerified(false)
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // On va générer le JWT : On crée le header, payload puis on génère le token

            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];

            $payload = [
                'user_id' =>$user->getId()
            ];

            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            // On envoie un mail
            $mail->send(
                'no-reply@monsite.net',
                $user->getUserMail(),
                'Activation de votre compte sur le site Snowtricks',
                'registername',
                compact('user', 'token')
            );

            $this->addFlash('success', 'Votre inscription est en cours, merci de la confirmer via le mail qui vous a été envoyé.');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser($token, JWTService $jwt, UserRepository $userRepo, EntityManagerInterface $manager): Response
    {
        // On vérifie si le token est valide, n'a pas expiré, et n'a pas été modifié

        if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret')))
        {
            $payload = $jwt->getPayload($token);
            $user = $userRepo->find($payload['user_id']);

            // On vérifie si le user existe et n'a pas encore activé son compte
            if($user && !$user->isIsVerified())
            {
                $user->setIsVerified(true);

                $manager->flush($user);

                $this->addFlash('success', 'Utilisateur activé.');

                return $this->redirectToRoute('app_home');

            }
        }

        $this->addFlash('danger', 'Le token est invalide ou a expiré');

        return $this->redirectToRoute('app_home');
    }

    #[Route('/resendmail', name: 'resend_mail')]
    public function resendVerif(JWTService $jwt, SendMailService $mail, UserRepository $userRepo): Response
    {
        $user = $this->getUser();

        if(!$user){
            $this->addFlash('warning', 'Vous devez être connecté pour accéder à cette page');

            return $this->redirectToRoute('app_home');
        }

        if($user->isIsVerified()){
            $this->addFlash('warning', 'Cet utilisateur est déjà activé');

            return $this->redirectToRoute('app_home');
        }

        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        $payload = [
            'user_id' =>$user->getId()
        ];

        $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

        // On envoie un mail
        $mail->send(
            'no-reply@monsite.net',
            $user->getUserMail(),
            'Activation de votre compte sur le site Snowtricks',
            'registername',
            compact('user', 'token')
        );

        $this->addFlash('success', 'Email de vérification envoyé');

        return $this->redirectToRoute('app_home');

    }

}
