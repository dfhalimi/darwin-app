<?php

namespace App\DarwinApp\UserManagement\Domain\Service;

use App\DarwinApp\UserManagement\Domain\Entity\User;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly class RegistrationService
{
    public function __construct(
        private UserManagerInterface     $userManager,
        private EventDispatcherInterface $eventDispatcher
    )
    {
    }

    public function createRudimentaryUser(): User
    {
        /** @var User $user */
        $user = $this->userManager->createUser();
        $user->setEnabled(false);
        $user->setPlainPassword(sha1(mt_rand(0, mt_getrandmax()) . mt_rand(0, mt_getrandmax())));
        return $user;
    }

    public function dispatchRegistrationInitializedEvent(
        Request $request,
        User    $user
    ): ?Response
    {
        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch($event, FOSUserEvents::REGISTRATION_INITIALIZE);
        if (!is_null($event->getResponse())) {
            return $event->getResponse();
        }
        return null;
    }

    public function handleRegistrationFailure(
        Request       $request,
        FormInterface $form
    ): ?Response
    {
        $event = new FormEvent($form, $request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->eventDispatcher->dispatch($event, FOSUserEvents::REGISTRATION_FAILURE);
            if (!is_null($response = $event->getResponse())) {
                return $response;
            }
        }

        return null;
    }
}
