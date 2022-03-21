<?php

namespace App\DataFixtures;

use App\Entity\BookItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class BookItemFixture extends Fixture
{
    private $faker;

    public function __construct() {

        $this->faker = Factory::create();
    }
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 50; $i++) {
            $manager->persist($this->getBookItem());
        }
        $manager->flush();
    }

    private function getBookItem() {

        /*return new BookItem(
            $this->faker->name(),
            $this->faker->name(),
            $this->faker->name(),
            $this->faker->numberBetween(10, 20),
            $this->faker->DateTime(),
            $this->faker->sentence(10)
        ); */
    }
}
