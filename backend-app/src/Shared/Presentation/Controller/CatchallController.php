<?php

namespace App\Shared\Presentation\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class CatchallController extends AbstractController
{
    public function indexAction(
        RouterInterface $router
    ): Response
    {
        return new RedirectResponse(
            $router->generate(
                'darwin_app.post_management.presentation.show_posts'
            )
        );
    }
}
