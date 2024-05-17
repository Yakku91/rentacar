<?php

namespace App\Controller;

use App\Entity\Car;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CarController extends AbstractController
{
    #[Route('/{_locale}/', name: 'list_cars')]
    public function getCarList(
        Request $request,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator
    ): Response {
        $cars = $entityManager->getRepository(Car::class)->getCars($request);
        $startTime = null;
        $endTime = null;
        $title = 'Vehicles';
        if ($request->request->get('startTime') && $request->request->get('endTime')) {
            $startDateTime = new \DateTime($request->request->get('startTime'));
            $endDateTime = new \DateTime($request->request->get('endTime'));

            if ($endDateTime > $startDateTime) {
                $startTime = $startDateTime->format('d.m.Y');
                $endTime = $endDateTime->format('d.m.Y');
                $title = 'Car.list';
            } else {
                $this->addFlash('warning', $translator->trans('Flash.wrongDates'));
            }
        }

        return $this->render(
            '/carlistview/carlist.html.twig',
            [
                'cars' => $cars,
                'title' => $title,
                'startDate' => $startTime,
                'endDate' => $endTime
            ]
        );
    }
}
