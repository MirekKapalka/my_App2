<?php

namespace App\Controller;

use App\Entity\Cars;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

class CarsController extends AbstractController
{


    #[Route('/cars/create', name: 'create_car')]
    public function createProduct(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $product = new Cars();
        $product->setName('Opel Kadett');
        $product->setYear(1991);
        $product->setColor('Dark Blue');

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $this->render('cars/create.html.twig', [
            'controller_name' => 'CarsController',
            'name' => $product->getName(),
            'year' => $product->getYear(),
            'color' => $product->getColor()
        ]);
    }


    #[Route('/cars', name: 'app_cars')]
    public function index(): Response
    {
        return $this->render('cars/index.html.twig', [
            'controller_name' => 'CarsController',
        ]);
    }
}
