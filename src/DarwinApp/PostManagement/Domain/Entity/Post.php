<?php

namespace App\DarwinApp\PostManagement\Domain\Entity;

use App\Shared\Infrastructure\Utility\DateTimeUtility;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table(name="posts")
 */
class Post
{
    /**
     * @throws Exception
     */
    public function __construct(
        string $title
    )
    {
        $this->title = $title;
        $this->imageFile = null;
        $this->imageFileName = null;
        $this->rating = null;
        $this->createdAt = DateTimeUtility::createDateTimeUtc();
        $this->updatedAt = null;
        $this->requiredLikes = 100;
        $this->currentLikes = 0;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="App\Shared\Infrastructure\Utility\DatabaseIdGenerator")
     * @ORM\Column(name="id", type="guid")
     */
    private string $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $title;

    /**
     * @Vich\UploadableField(mapping="post_image", fileNameProperty="imageFileName")
     * @Assert\File(maxSize="10M")
     */
    private ?File $imageFile;

    /**
     * @ORM\Column(name="image_file_name", type="string", length=255, nullable=true)
     */
    private ?string $imageFileName;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $rating;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $updatedAt;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private int $requiredLikes;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private int $currentLikes;

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @throws Exception
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if ($imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = DateTimeUtility::createDateTimeUtc();
        }
    }

    public function getImageFileName(): ?string
    {
        return $this->imageFileName;
    }

    public function setImageFileName(?string $imageFileName): void
    {
        $this->imageFileName = $imageFileName;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): void
    {
        $this->rating = $rating;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getRequiredLikes(): int
    {
        return $this->requiredLikes;
    }

    public function setRequiredLikes(int $requiredLikes): void
    {
        $this->requiredLikes = $requiredLikes;
    }

    public function getCurrentLikes(): int
    {
        return $this->currentLikes;
    }

    public function setCurrentLikes(int $currentLikes): void
    {
        $this->currentLikes = $currentLikes;
    }
}
