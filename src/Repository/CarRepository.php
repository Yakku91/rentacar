<?php

namespace App\Repository;

use App\Entity\Car;
use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Car>
 *
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function save(Car $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Car $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getCars(Request $request): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $categories =
            [
                $request->request->get('sedan'),
                $request->request->get('station_wagon'),
                $request->request->get('suv'),
                $request->request->get('van'),
            ];

        if (empty($categories[0]) && empty($categories[1]) && empty($categories[2]) && empty($categories[3])) {
            $categories = ['Limousine', 'Kombi', 'Geländewagen', 'Minibus'];
        }

        $queryBuilder
            ->select('c')
            ->from(Car::class, 'c')
            ->where('c.name LIKE CONCAT(\'%\', :searchInput , \'%\')')
            ->setParameter('searchInput', $request->request->get('searchfield') ?? '')
            ->orderBy('c.name', 'ASC');

        $queryBuilder
            ->andWhere('c.category IN (:categories)')
            ->setParameter('categories', $categories);

        $cars = $queryBuilder->getQuery()->getResult();

        if ($request->request->get('startTime') && $request->request->get('endTime')) {
            $startTime = new \DateTime($request->request->get('startTime')) ?? null;
            $endTime = new \DateTime($request->request->get('endTime')) ?? null;

            if ($endTime > $startTime) {
                $entityManager = $this->getEntityManager();
                $orders = $entityManager->getRepository(Order::class)->findBy(['status' => 'Allowed']);

                foreach ($orders as $order) {
                    if ($startTime <= $order->getEndDate() && $endTime >= $order->getStartDate()) {
                        $key = array_search($order->getCar(), $cars);
                        if ($key !== false) {
                            unset($cars[$key]);
                        }
                    }
                }
            }
        }
        return $cars;
    }
}
