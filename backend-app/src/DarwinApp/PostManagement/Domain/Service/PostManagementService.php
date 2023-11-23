<?php

namespace App\DarwinApp\PostManagement\Domain\Service;

use App\DarwinApp\PostManagement\Domain\Entity\Post;
use Symfony\Component\Form\FormInterface;

class PostManagementService
{
    public function handleNewPostCreation(
        FormInterface $form
    ): Post
    {
        /** @var Post $post */
        $post = $form->getData();

        return $post;
    }
}
