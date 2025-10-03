<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $om): void
    {
        // Demo API client (auth user)
        $client = (new Client())
            ->setEmail('partner@bilemo.test')
            ->setName('Acme Partner')
            ->setRoles(['ROLE_CLIENT']);
        $client->setPassword($this->hasher->hashPassword($client, 'ChangeMe123!'));
        $om->persist($client);

        // Some catalog products
        $now = new \DateTimeImmutable();
        foreach ([
            ['Galaxy S25', 'Samsung', '999.00'],
            ['iPhone 17 Pro', 'Apple', '1299.00'],
            ['Pixel 10', 'Google', '899.00'],
        ] as [$name, $brand, $price]) {
            $p = (new Product())
                ->setName($name)
                ->setBrand($brand)
                ->setPrice($price)
                ->setDescription(null)
                ->setSpecs(null)
                ->setCreatedAt($now)
                ->setUpdatedAt(new \DateTime());
            $om->persist($p);
        }

        $om->flush();
    }
}
