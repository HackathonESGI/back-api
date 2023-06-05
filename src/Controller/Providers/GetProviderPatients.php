<?php

namespace App\Controller\Providers;

use App\Entity\User\Provider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GetProviderPatients
{
    public function __invoke(Provider $provider): Response
    {
        return new JsonResponse(data: $provider->getPatients(), status: Response::HTTP_OK);
    }
}