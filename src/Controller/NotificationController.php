<?php

namespace App\Controller;

use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class NotificationController extends AbstractController
{
    #[Route('/api/notifications', name: 'api_notification', methods: ['GET'])]
    public function listNotification(EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non authentitfie'], 401);
        }

        $notifications = $entityManager->getRepository(Notification::class)->findBy(['user' => $user]);
        $data = array_map(static fn($n) => [
            'id'=> $n->getId(),
            'message' => $n->getMessage(),
            'type' => $n->getType(),
            'read' => $n->getStatus(),
        ], $notifications);

        return new JsonResponse($data, 200);
    }
}
