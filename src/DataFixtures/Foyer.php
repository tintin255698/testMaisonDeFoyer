<?php

namespace App\DataFixtures;

use App\Entity\Maison;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class Foyer extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $number = rand(20, 40);
        $slug = new Slugify();
        function getRandomStr($n) {
            $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomStr = '';
            for ($i = 0; $i < $n; $i++) {
                $index = rand(0, strlen($str) - 1);
                $randomStr .= $str[$index];
            }

            return $randomStr;
        }

        $arr = ["EHPAD", "Résidence Services Seniors"];

        for ($i = 0; $i < 6; $i++) {
            $auxerre = new Maison();
            $name = $faker->company;
            $auxerre->setName($name);
            $auxerre->setType(array_rand(array_flip($arr)));
            $auxerre->setAdresse($faker->address);
            $auxerre->setCode('89000');
            $auxerre->setCity('Auxerre');
            $auxerre->setEmail($faker->email);
            $auxerre->setImage($faker->imageUrl(640, 480, true));
            $auxerre->setContent(getRandomStr($number));
            $auxerre->setSlug($slug->slugify($name));
            $manager->persist($auxerre);
        }

            for ($i = 0; $i < 4; $i++) {
                $joigny = new Maison();
                $name = $faker->company;
                $joigny->setName($name);
                $joigny->setType(array_rand(array_flip($arr)));
                $joigny->setAdresse($faker->address);
                $joigny->setCode('89300');
                $joigny->setCity('Joigny');
                $joigny->setEmail($faker->email);
                $joigny->setImage($faker->imageUrl(640, 480, true));
                $joigny->setContent(getRandomStr($number));
                $joigny->setSlug($slug->slugify($name));
                $manager->persist($joigny);

                $manager->flush();
            }
        }
}