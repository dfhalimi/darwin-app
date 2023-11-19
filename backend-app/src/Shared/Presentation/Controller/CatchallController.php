<?php

namespace App\Shared\Presentation\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CatchallController extends AbstractController
{
    public function indexAction(): Response
    {
        // TODO adjust later
        return $this->redirect(
            $this->generateUrl(
                'darwin_app.post_management.presentation.show_posts'
            )
        );
    }
}
