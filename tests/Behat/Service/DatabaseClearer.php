<?php

declare(strict_types=1);

namespace App\Tests\Behat\Service;

use Doctrine\ORM\EntityManagerInterface;

readonly class DatabaseClearer
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /** @param class-string $fqcn */
    public function removeAllEntitiesByFqcn(string $fqcn): void
    {
        $connection = $this->entityManager->getConnection();
        $sql = sprintf(
            'TRUNCATE "%s" RESTART IDENTITY CASCADE ',
            $this->entityManager->getClassMetadata($fqcn)->getTableName()
        );
        $stmt = $connection->prepare($sql);
        $stmt->executeStatement();
    }
}
