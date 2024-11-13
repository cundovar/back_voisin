<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    private JWTEncoderInterface $jwtEncoder;

    public function __construct(JWTEncoderInterface $jwtEncoder)
    {
        $this->jwtEncoder = $jwtEncoder;
    }

    #[Route('/api/test-token', name: 'api_test_token', methods: ['POST'])]
    public function testToken(Request $request): JsonResponse
    {
        $token = $request->get('token');

        if (!$token) {
            return new JsonResponse(['error' => 'Token not provided'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $payload = $this->jwtEncoder->decode($token);
            return new JsonResponse(['status' => 'success', 'payload' => $payload]);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], JsonResponse::HTTP_UNAUTHORIZED);
        }
    }
}
