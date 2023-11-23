<?php

namespace App\DarwinApp\PostManagement\Presentation\Controller;

use App\DarwinApp\PostManagement\Domain\Entity\Post;
use App\DarwinApp\PostManagement\Presentation\Form\PostType;
use App\DarwinApp\PostManagement\Domain\Service\PostManagementService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostManagementController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    #[Route(
        path: ['/posts'],
        name: 'darwin_app.post_management.presentation.show_posts',
        methods: [Request::METHOD_GET]
    )]
    public function showPostsAction(): Response
    {
        $posts = $this->entityManager->getRepository(Post::class)->findBy(
            [],
            [
                'createdAt' => 'DESC'
            ]
        );

        return $this->render(
            '@darwin_app.post_management/show_posts.html.twig',
            [
                'posts' => $posts
            ]
        );
    }

    /**
     * @throws Exception
     */
    #[Route(
        path: ['/posts/new'],
        name: 'darwin_app.post_management.presentation.new_post',
        methods: [Request::METHOD_GET, Request::METHOD_POST]
    )]
    public function newPostAction(
        Request               $request,
        PostManagementService $postManagementService
    ): Response
    {
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $postManagementService->handleNewPostCreation($form);

            $this->entityManager->persist($post);
            $this->entityManager->flush();

            return $this->redirect(
                $this->generateUrl('darwin_app.post_management.presentation.show_posts')
            );
        }

        return $this->render(
            '@darwin_app.post_management/new_post.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}
