<?php

namespace App\DarwinApp\UserManagement\Domain\Entity;

use App\Shared\Infrastructure\Utility\DateTimeUtility;
use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\Table;
use Exception;
use FOS\UserBundle\Model\User as FOSUser;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity]
#[Table(name: "users")]
#[Index(columns: ["created_at"], name: "created_at_idx")]
#[Index(columns: ["email"], name: "email_idx")]
#[Index(columns: ["username"], name: "username_idx")]
class User extends FOSUser
{
    /**
     * @throws Exception
     */
    public function __construct(
        ?string $id = null
    )
    {
        parent::__construct();
        if (!is_null($id)) {
            $this->id = $id;
        }
        $this->createdAt = DateTimeUtility::createDateTimeUtc();
    }

    #[Id]
    #[Column(
        name: "id",
        type: "guid"
    )]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: "App\Shared\Infrastructure\Utility\DatabaseIdGenerator")]
    protected $id;

    #[Assert\Email(
        mode: "strict",
        groups: ["Registration"]
    )]
    protected $email;

    #[Column(
        name: "created_at",
        type: "datetime",
        nullable: false
    )]
    protected DateTime $createdAt;

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
