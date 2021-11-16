<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordType;
use App\Form\ProfileType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/{id}", name="profile", methods={"GET"})
     */
    public function showProfile(User $user): Response
    {
        if ($user->getUsername() == $this->getUser()->getUsername()) {
            return $this->render('profile/showProfile.html.twig', [
                'user' => $user,
            ]);
        } else return $this->showProfile($this->getUser()); //костыль?
    }

    /**
     * @Route("/{id}/edit", name="profile_edit", methods={"GET","POST"})
     */
    public function editProfile(Request $request, User $user): Response
    {

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);
        if ($user->getUsername() === $this->getUser()->getUsername()) {
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->render('profile/showProfile.html.twig', [
                    'user' => $this->getUser(),
                ]);
            }

            return $this->renderForm('profile/editProfile.html.twig', [
                'user' => $user,
                'form' => $form,
            ]);
        } else  return $this->redirect('/news');
    }

    /**
     * @Route("/{id}/edit/password", name="password_edit", methods={"GET","POST"})
     */
    public function editPassword(Request $request, User $user, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $form = $this->createForm(PasswordType::class);
        $form->handleRequest($request);
        if ($user->getUsername() == $this->getUser()->getUsername()) {
            if ($form->isSubmitted() && $form->isValid()) {
                $user->setPassword(
                    $userPasswordHasherInterface->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $this->getDoctrine()->getManager()->flush();

                return $this->render('profile/showProfile.html.twig', [
                    'user' => $this->getUser(),
                ]);
            }

            return $this->renderForm('profile/editPassword.html.twig', [
                'form' => $form
            ]);
        } else  return $this->redirect('/news');
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }

}