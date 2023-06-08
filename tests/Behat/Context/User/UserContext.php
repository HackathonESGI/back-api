<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context\User;

use App\Entity\User\Patient;
use App\Entity\User\Provider;
use App\Entity\User\User;
use App\Tests\Behat\Service\DatabaseClearer;
use App\Tests\Behat\Service\SharedStorage;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

final class UserContext implements Context
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DatabaseClearer $databaseClearer,
        private readonly SharedStorage $sharedStorage,
    ) {
    }

    /** @AfterScenario */
    public function clearDatabase(): void
    {
        $this->databaseClearer->removeAllEntitiesByFqcn(User::class);
    }

    /** @Given /^there is an existing (user|provider|patient) with the email "([^"]*)"$/ */
    public function thereIsAnExistingUserWithTheEmail(string $type, string $email): void
    {
        $object = $this->createUser($type, $email);
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }

    /** @Given /^the following (user|provider|patient) exist:$/ */
    public function theFollowingPatientExist(string $type, TableNode $table): void
    {
        $user = null;
        foreach ($table as $row) {
            assert(is_array($row));
            $user = $this->createUser($type, $row['email'], $row['firstname'], $row['lastname']);
            $this->entityManager->persist($user);
        }
        $this->entityManager->flush();

        TestCase::assertInstanceOf(User::class, $user);
        $this->sharedStorage->set($type.'_id', $user->getId());
    }

    /** @Given /^the (user|patient|provider) with the email "([^"]*)" should exist$/ */
    public function thePatientWithTheEmailShouldExist(string $type, string $email): void
    {
        $class = match ($type) {
            'patient' => new Patient(),
            'provider' => new Provider(),
            default => new User(),
        };
        $user = $this->entityManager->getRepository($class::class)->findOneBy(
            ['email' => $email]
        );

        TestCase::assertNotNull($user);
    }

    private function createUser(
        string $type,
        string $email,
        string $firstname = 'Test',
        string $lastname = 'Test'
    ): Provider|User|Patient {
        switch ($type) {
            case 'provider':
                $object = new Provider();
                break;
            case 'patient':
                $object = new Patient();
                $object->setLat(51)
                    ->setLong(69);
                break;
            default:
                $object = new User();
        }

        TestCase::assertInstanceOf(User::class, $object);

        $object->setFirstname($firstname)
            ->setLastname($lastname)
            ->setMobilePhone('1234567890')
            ->setPassword('$2y$13$pbkh//MzAw7xmsSVxyShvulLW9dNTwoxN1qo1fDcNQqhWSTATmoEm')
            ->setEmail($email);

        return $object;
    }
}
