<?php

namespace App\DarwinApp\UserManagement\Presentation\Controller;

use FOS\UserBundle\Controller\SecurityController as FOSUserBundleSecurityController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends FOSUserBundleSecurityController
{
    public function __construct(
        AuthenticationUtils        $authenticationUtils,
        ?CsrfTokenManagerInterface $csrfTokenManager,
    )
    {
        parent::__construct($authenticationUtils, $csrfTokenManager);
    }

    #[Route(
        path: ['/login'],
        name: 'fos_user_security_login',
        methods: [Request::METHOD_GET, Request::METHOD_POST]
    )]
    public function loginAction(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute(
                'darwin_app.post_management.presentation.show_posts'
            );
        }
        return parent::loginAction();
    }

    #[Route(
        path: ['/login_check'],
        name: 'fos_user_security_check',
        methods: [Request::METHOD_POST]
    )]
    public function checkAction(): Response
    {
        return parent::checkAction();
    }

    #[Route(
        path: ['/logout'],
        name: 'fos_user_security_logout',
        methods: [Request::METHOD_GET, Request::METHOD_POST]
    )]
    public function logoutAction(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute(
                'darwin_app.post_management.presentation.show_posts'
            );
        }
        return parent::logoutAction();
    }
}
