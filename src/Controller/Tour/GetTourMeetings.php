<?php

declare(strict_types=1);

namespace App\Controller\Tour;

use App\Entity\Tour;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GetTourMeetings
{
    public function __construct()
    {
    }

    public function __invoke(Tour $tour): Response
    {
        return new JsonResponse($tour->getMeetings()->toArray(), Response::HTTP_OK);
    }
}