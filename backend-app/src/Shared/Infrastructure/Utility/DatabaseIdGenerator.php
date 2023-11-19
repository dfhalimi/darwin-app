<?php

namespace App\Shared\Infrastructure\Utility;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Symfony\Component\Uid\Uuid;

class DatabaseIdGenerator extends AbstractIdGenerator
{
    public function generateId(EntityManagerInterface $em, $entity): Uuid
    {
        # We need a time-based UUID, because some of our tests implicitly
        # rely on the fact that the UUIDs are sortable by time.
        # However, we do not want the default UUID v6 used by
        # Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator, because
        # it generates UUIDs that start with the same hex values over
        # a long period of time, which results in "hotspots" in the
        # database where basically all new rows start with '1e...',
        # rendering our 1/16 or 1/265 splitting of command runs useless.
        # UUID v1 values on the other hand also start with an encoded
        # timestamp, but uses another encoding mechanisms which means that
        # the first hex values "rotate" much more often, and thus are more
        # evenly distributed over time.

        return Uuid::v1();
    }
}
