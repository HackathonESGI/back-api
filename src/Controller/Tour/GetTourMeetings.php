<?php

declare(strict_types=1);

namespace App\Controller\Tour;

use App\Entity\Tour;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class GetTourMeetings
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function __invoke(Tour $tour): Response
    {
        $meetings = $tour->getMeetings()->toArray();

        return new JsonResponse($this->serializer->serialize($meetings, 'json'), Response::HTTP_OK, [], true);
    }
}
