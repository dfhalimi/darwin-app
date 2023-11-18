<?php

namespace App\DarwinApp\UserManagement\Domain\Controller;

use App\DarwinApp\UserManagement\Domain\Entity\User;
use App\DarwinApp\UserManagement\Domain\Service\RegistrationService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Controller\RegistrationController as FOSUserBundleRegistrationController;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class RegistrationController extends FOSUserBundleRegistrationController
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly FactoryInterface         $formFactory,
        private readonly UserManagerInterface     $userManager,
        TokenStorageInterface                     $tokenStorage,
        private readonly RegistrationService      $registrationService,
        private readonly EntityManagerInterface   $entityManager
    )
    {
        parent::__construct($eventDispatcher, $formFactory, $userManager, $tokenStorage);
    }

    public function registerAction(Request $request): Response
    {
        $user = $this->getUser();

        if ($user instanceof User) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_FORBIDDEN);
            return $this->render(
                'errors/already_logged_in.html.twig',
                [],
                $response
            );
        }

        $user = $this->registrationService->createRudimentaryUser();

        $response = $this->registrationService->dispatchRegistrationInitializedEvent(
            $request,
            $user
        );

        if (!is_null($response)) {
            return $response;
        }

        $form = $this->formFactory->createForm();
        $form->setData($user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                if (($form->getErrors()[0]->getMessageTemplate()) === 'fos_user.username.already_used') {
                    if (!is_null($user)) {
                        /** @var User $foundUser */
                        if (!empty(
                            $foundUser = $this->entityManager->getRepository(User::class)->findOneBy(
                                [
                                    'email' => $user->getEmail()
                                ]
                            ))
                        ) {
                            if (!$foundUser->isEnabled()) {
                                $user = $foundUser;

                                $event = new FormEvent($form, $request);

                                $user->addRole(UserInterface::ROLE_DEFAULT);

                                $user->setEnabled(false);

                                $event->setResponse(new RedirectResponse(''));

                                $this->eventDispatcher->dispatch(
                                    new FilterUserResponseEvent(
                                        $user,
                                        $request,
                                        $event->getResponse()
                                    )
                                );

                                return $event->getResponse();
                            }

                            $response = $this->registrationService->handleRegistrationFailure(
                                $request,
                                $form
                            );

                            if (!is_null($response)) {
                                return $response;
                            }
                        }
                    }
                }
            }

            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $this->eventDispatcher->dispatch(
                    $event,
                    FOSUserEvents::REGISTRATION_SUCCESS
                );

                $this->userManager->updateUser($user);

                if (is_null($response = $event->getResponse())) {
                    $url = $this->generateUrl('fos_user_registration_confirmed');
                    $response = new RedirectResponse($url);
                }

                $this->eventDispatcher->dispatch(
                    new FilterUserResponseEvent(
                        $user,
                        $request,
                        $response
                    ),
                    FOSUserEvents::REGISTRATION_COMPLETED
                );

                return $response;
            }

            $response = $this->registrationService->handleRegistrationFailure(
                $request,
                $form
            );
            if (!is_null($response)) {
                return $response;
            }
        }

        $response = new Response();

        return $this->render(
            '@FOSUser/Registration/register.html.twig',
            [
                'form' => $form->createView()
            ],
            $response
        );
    }
}
