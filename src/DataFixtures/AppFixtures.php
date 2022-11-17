<?php

namespace App\DataFixtures;


use App\Entity\Cars;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $car1= new Cars();  
        $car1->setName('Opel Kadett');
        $car1->setYear(1991);
        $car1->setColor('Dark Blue');
        $car1->setCounter(1);
        $manager->persist($car1);


        $car2= new Cars(); 
        $car2->setName('Nissan Almera');
        $car2->setYear(1995);
        $car2->setColor('Light Blue');
        $car2->setCounter(2);
        $manager->persist($car2);

        $car3= new Cars();
        $car3->setName('Honda Civic');
        $car3->setYear(1997);
        $car3->setColor('Orange');
        $car3->setCounter(3);
        $manager->persist($car3);

        
        $manager->flush();
    }
}
