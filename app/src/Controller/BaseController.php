<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{
    protected function createdResponse(
        array $data,
        int $status = Response::HTTP_OK,
    ): JsonResponse {
        return $this->json($data, $status);
    }
}
