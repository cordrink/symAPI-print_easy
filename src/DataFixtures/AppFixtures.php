<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Document;
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
            $user->setEmail('email' . $i . '@gmail.com')
                ->setPassword($hash);

            $manager->persist($user);

            $customer = new Customer();
            $customer->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setCompany($faker->company)
                ->setUser($user);

            $manager->persist($customer);


            for ($k = 0; $k < random_int(3, 10); $k++) {
                $document = new Document();
                $document->setFileName($faker->imageUrl($width = 640, $height = 480))
                    ->setUploadedAt(new \DateTimeImmutable())
                    ->setStatus($faker->randomElement(['attente', 'imprime']))
                    ->setUser($customer);

                $manager->persist($document);
            }

        }

        $manager->flush();
    }
}
