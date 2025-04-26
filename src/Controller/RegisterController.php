<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class RegisterController extends AbstractController
{
    /**
     * @throws \JsonException
     */
    #[Route('/api/register', name: 'app_register', methods: ['POST', 'GET'])]
    public function index(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getcontent(), true, 512, JSON_THROW_ON_ERROR);

        //dd($data);

        if (!isset($data['email'], $data['password'], $data['firstName'], $data['lastName'])) {
            return new JsonResponse(['error' => 'Missing required fields'], 400);
        }

        $email = $data['email'];
        $password = $data['password'];
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];


        $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existingUser) {
            return new JsonResponse(['error' => 'Email already exists'], 400);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $hashedPassword = $hasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $profil = new Profil();
        $profil->setCompany('');
        $profil->setUser($user);
        $user->setProfil($profil);

        $entityManager->persist($user);
        $entityManager->persist($profil);
        $entityManager->flush();

        return new JsonResponse(['success' => 'User created'], 201);

    }
}
