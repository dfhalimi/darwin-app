<?php

namespace App\DarwinApp\PostManagement\Presentation\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PostManagementController extends AbstractController
{
    public function showPostsAction(): Response
    {
        // TODO get posts for user
        $posts = [];

        return $this->render(
            '@darwin_app.post_management/show_posts.html.twig',
            [
                'posts' => $posts
            ]
        );
    }
}
