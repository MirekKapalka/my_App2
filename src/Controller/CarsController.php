<?php

namespace App\Controller;


use App\Form\DeleteCarType;
use App\Entity\Cars;
use App\Form\NewCarType;
use App\Form\RedirectType;
use App\Repository\CarsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\DomCrawler\Form;
use Symfony\Component\Form\Form as FormForm;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class CarsController extends AbstractController
{
    #[Route('/cars', name: 'cars_create')]
    public function createNewCar(ManagerRegistry $doctrine, Request $request, Request $request2, CarsRepository $carsRepository):Response{
         
        $cars= new Cars();
        $newCarForm = $this->createForm(NewCarType::class, $cars);
        $newCarForm->handleRequest($request);
        $cars= $doctrine->getRepository(Cars::class)->findAll();


        ///COUNTERCHECK
        $i =1;
        foreach($cars as $car){
                $getcount = $car->getCounter();   
                if($getcount!= $i){
                    $car->setCounter($i);
                    $carsRepository->save($car,true);
                }
                $i++;
        }

        $lenght = count($cars)+1;

        $newButton= $this->createForm(RedirectType::class);       

        $newButton->handleRequest($request2);

        if($newCarForm->isSubmitted()&&$newCarForm->isValid()){
            $newData= $newCarForm->getData();
            
            $newData->setCounter($lenght);

            $carsRepository->save($newData,true);
        }    

     if($newButton->isSubmitted()&&$newButton->isValid()){
            $newButtonData=$newButton->getData(); 
            return $this->redirectToRoute('cars_show_one',[
                'counter' => $newButtonData["Id"]
            ]);
       }

        if (!$cars) {
            throw $this->createNotFoundException(
                'No cars found'
            );
        }
       
        return $this->renderForm('cars/index.html.twig',
    [
        'cars_array' => $cars,
        'form' => $newCarForm,
        'button' => $newButton
    ]);
    }


    #[Route('/cars/{counter}', name: 'cars_show_one')]
    public function show(ManagerRegistry $doctrine, $counter,CarsRepository $carsRepository,Request $request3): Response
    {
        $car = $doctrine->getRepository(Cars::class)->findOneby([
            'counter' => $counter            
        ]);

        $newDelete= $this->createForm(DeleteCarType::class); 
        $newDelete->handleRequest($request3);

    
        
       // dd($name);
        if($newDelete->isSubmitted()&&$newDelete->isValid()){
                 $name=$car->getName();
                $carsRepository->remove($car,true);

                return $this->redirectToRoute('cars_remove',[
                    'name'=>$name
               ]           
            );
        }else

       // if (!$car) {
    //        throw $this->createNotFoundException(
    //            'No product found for id '.$counter
     //       );
      //  }


        return $this->renderForm('cars/fetch.html.twig', [
            'car' => $car,
            'delete_form' =>$newDelete


        ]);

    }

    #[Route('/cars/removed/{name}', name: 'cars_remove')]
    public function index($name): Response
    {    
  
        return $this->render('cars/remove.html.twig',[
            'name'=>$name
        ]);
    }

}