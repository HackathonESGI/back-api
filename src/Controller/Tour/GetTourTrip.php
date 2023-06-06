<?php

namespace App\Controller\Tour;

use App\Entity\Tour;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GetTourTrip
{
    public function __invoke(Tour $tour): Response
    {
        $trip = $this->getBetterTrip($tour);
        return new JsonResponse($trip, Response::HTTP_OK);
    }

    private function getBetterTrip(Tour $tour): array
    {
        // TODO: Implement Gabriel's code here
        return [];
    }
}
