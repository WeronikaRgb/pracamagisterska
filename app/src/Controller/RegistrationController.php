<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Enum\UserRole;
use App\Repository\UserRepository;
use App\Form\Type\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use DateTime;
use Symfony\Component\Validator\Constraints\IsTrue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

/**
 * RegistrationController.
 */
class RegistrationController extends AbstractController
{
    /**
     * @param Request                     $request
     * @param array $roles User roles
     * @param UserPasswordHasherInterface $userPasswordHarsher
     * @param UserAuthenticatorInterface  $userAuthenticator
     * @param LoginFormAuthenticator      $authenticator
     * @param EntityManagerInterface      $entityManager
     *
     * @return Response
     */
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHarsher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        $user->setRoles([UserRole::ROLE_USER->value]);

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $isOver13 = $form->get('isOver13')->getData();
            if (!$isOver13) {
                $this->addFlash('error', 'Musisz mieć ukończone 13 lat.');
                return $this->redirectToRoute('app_register');
            }

            $user->setPassword(
                $userPasswordHarsher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            // save the user to the database
            $entityManager->persist($user);
            $entityManager->flush();


            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}

