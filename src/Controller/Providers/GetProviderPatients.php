<?php

namespace App\Controller\Providers;

use App\Entity\User\Provider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class GetProviderPatients
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function __invoke(Provider $provider): Response
    {
        $patients = $provider->getPatients()->toArray();
        return new JsonResponse($this->serializer->serialize($patients, 'json'), Response::HTTP_OK, [], true);
    }
}
