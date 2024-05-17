<?php

namespace App\Controller;

use App\Entity\Car;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DetailsController extends AbstractController
{
    #[Route('/{_locale}/showDetails/{car}', name: 'show_details')]
    public function showDetails(EntityManagerInterface $entityManager, Request $request, Car $car): Response
    {
        return $this->render('/details/details.html.twig', ['car' => $car, 'title' => $car->getName()]);
    }
}
