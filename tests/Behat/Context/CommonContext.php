<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use App\Entity\User\User;
use App\Tests\Behat\Service\SharedStorage;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class CommonContext implements Context
{
    private string $uri;

    private Response $response;

    private array $server = [];

    private ?string $content = null;

    /** @var array<string> */
    private array $query = [];

    private string $userToken;

    public const LAST_RESPONSE_KEY = 'last_response';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly KernelInterface $kernel,
        private readonly SharedStorage $sharedStorage,
        private readonly JWTTokenManagerInterface $JWTTokenManager
    ) {
    }

    /** @Given /^the base uri is "([^"]*)"$/ */
    public function theBaseUriIs(string $uri): void
    {
        $this->uri = $uri;
    }

    /** @Given /^I want to (get|modify|delete) the (existing|last) "([^"]*)" resource$/ */
    public function iWantToGetTheExistingResource(string $arg1, string $arg, string $resource): void
    {
        $id = $this->sharedStorage->get(sprintf('%s_id', $resource));
        TestCase::assertIsInt($id);
        $this->uri = sprintf('%s/%s', $this->uri, $id);
    }

    /** @Given /^I want to (get|modify|delete) the non-existent "([^"]*)" resource having an uid of "([^"]*)"$/ */
    public function iWantToGetTheNonExistentResource(string $arg1, string $resource, string $id): void
    {
        $this->uri = sprintf(
            '%s/%s',
            $this->uri,
            $id
        );
    }

    /**
     * @Given /^I am authenticated$/
     */
    public function iAmAuthenticated(): void
    {
        $authUserEmail = 'admin@admin.com';
        $authUser = $this->entityManager->getRepository(User::class)->findOneBy(
            ['email' => $authUserEmail]
        );

        if (null === $authUser) {
            $authUser = new User();
            $authUser->setEmail('admin@admin.com')
                ->setFirstname('admin')
                ->setLastname('admin')
                ->setMobilePhone('1234567890')
                ->setRoles([
                    'ROLE_ADMIN',
                    'ROLE_USER',
                ])
                ->setPassword('$2y$13$Mmc3CGubwgJqmZwlvZKS8OBBAJtivglUnk1e/2lR/5QQn9uQGQD0W');

            $this->entityManager->persist($authUser);
            $this->entityManager->flush();
        }

        TestCase::assertInstanceOf(User::class, $authUser);

        $this->userToken = $this->JWTTokenManager->create($authUser);

        $this->server += [
            'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->userToken),
        ];
    }

    /** @Given /^the request has the payload:$/ */
    public function theRequestHasThePayload(PyStringNode $string): void
    {
        $this->server += ['HTTP_CONTENT-TYPE' => 'application/ld+json'];
        $this->content = $string->getRaw();
    }

    /** @When /^I send a "([^"]*)" request$/ */
    public function iSendARequest(string $method): void
    {
        $this->server += [
            'REQUEST_URI' => $this->uri,
            'QUERY_STRING' => implode('&', $this->query),
            'REQUEST_METHOD' => $method,
        ];

        $this->response = $this->kernel->handle(
            new Request(
                server: $this->server,
                content: $this->content,
            )
        );

        $this->sharedStorage->set(CommonContext::LAST_RESPONSE_KEY, $this->response);
    }

    /** @Then /^the response status code should be (\d+)$/ */
    public function theResponseStatusCodeShouldBe(int $statusCode): void
    {
        TestCase::assertSame($statusCode, $this->response->getStatusCode());
    }

    /** @Given /^the content should contain (\d+) elements$/ */
    public function theContentShouldContainElements(int $amount): void
    {
        TestCase::assertIsString($this->response->getContent());
        $content = json_decode($this->response->getContent(), true);
        TestCase::assertIsArray($content);
        TestCase::assertArrayHasKey('hydra:member', $content);
        $hydraMember = $content['hydra:member'];
        TestCase::assertIsArray($hydraMember);
        TestCase::assertCount($amount, $hydraMember);
    }

    /** @Then /^the collection should have elements of type "([^"]*)"$/ */
    public function theCollectionShouldHaveElementsOfType(string $type): void
    {
        $response = $this->getLastResponse();
        TestCase::assertIsString($response->getContent());
        $collection = json_decode($response->getContent(), true);
        TestCase::assertIsArray($collection);
        TestCase::assertSame('hydra:Collection', $collection['@type']);
        $items = $collection['hydra:member'];
        TestCase::assertIsArray($items);
        foreach ($items as $item) {
            TestCase::assertSame($type, $item['@type']);
        }
    }

    /** @Then /^the returned element should have the "([^"]*)" equals to "([^"]*)"$/ */
    public function theReturnedElementShouldHaveThe(string $field, string $value): void
    {
        $response = $this->getLastResponse();
        TestCase::assertIsString($response->getContent());
        $content = json_decode($response->getContent(), true);
        TestCase::assertIsArray($content);
        TestCase::assertSame($value, $content[$field]);
    }

    private function getLastResponse(): Response
    {
        $response = $this->sharedStorage->get(CommonContext::LAST_RESPONSE_KEY);
        TestCase::assertInstanceOf(Response::class, $response);

        return $response;
    }
}
