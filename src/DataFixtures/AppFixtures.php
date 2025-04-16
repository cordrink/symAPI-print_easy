<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Document;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Random\RandomException;

class AppFixtures extends Fixture
{
    /**
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 30; $i++) {
            $customer = new Customer();
            $customer->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setCompany($faker->company)
            ;

            $manager->persist($customer);

            for ($j = 0; $j < random_int(3, 10); $j++) {
                $document = new Document();
                $document->setFileName($faker->imageUrl($width = 640, $height = 480))
                    ->setUploadedAt(new \DateTimeImmutable())
                    ->setStatus($faker->randomElement(['attente', 'imprime']))
                    ->setUser($customer)
                    ;

                $manager->persist($document);
            }
        }

        $manager->flush();
    }
}
