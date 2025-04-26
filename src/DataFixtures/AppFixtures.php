<?php

namespace App\DataFixtures;

use App\Entity\Document;
use App\Entity\Profil;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Random\RandomException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * L'encodeur de mots de passse
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $hash = $this->encoder->hashPassword($user, 'password');
            $user
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setEmail('email' . $i . '@gmail.com')
                ->setPassword($hash)
            ;

            $profil = new Profil();
            $profil->setCompany($faker->company);

            $user->setProfil($profil);

            $manager->persist($user);
            $manager->persist($profil);

            for ($k = 0; $k < random_int(3, 10); $k++) {
                $document = new Document();
                $document->setFileName($faker->imageUrl($width = 640, $height = 480))
                    ->setUploadedAt(new \DateTimeImmutable())
                    ->setStatus($faker->randomElement(['attente', 'imprime']))
                    ->setProfil($profil);

                $manager->persist($document);
            }
        }

        $manager->flush();
    }
}
