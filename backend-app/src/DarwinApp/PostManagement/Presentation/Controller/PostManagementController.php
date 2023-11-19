<?php

namespace App\DarwinApp\PostManagement\Presentation\Controller;

use App\DarwinApp\PostManagement\Domain\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PostManagementController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function showPostsAction(): Response
    {
        $posts = $this->entityManager->getRepository(Post::class)->findAll();

        return $this->render(
            '@darwin_app.post_management/show_posts.html.twig',
            [
                'posts' => $posts
            ]
        );
    }

    public function newPostAction(): Response
    {

    }
}
