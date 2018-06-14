<?php
namespace Application\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Application\Entity\Book;

class BookRepository extends EntityRepository
{
    /**
     * Method used to obtain available users from the database
     *
     * @param array $criteria - additional criteria
     * @param int $hydrate - result hydration mode
     * @return mixed - available users
     */
    public function getBooks($criteria, $hydrate = Query::HYDRATE_OBJECT)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('b')
            ->from(Book::class, 'b');

        if (isset($criteria['order_by']) && $criteria['order_by']) {
            switch ($criteria['order_by']) {
                case 'date':
                    $qb->orderBy('b.' . $criteria['order_by']);
                    break;
            }
        } else {
            $qb->orderBy('b.name', 'ASC');
        }

        if (isset($criteria['limit']) && $criteria['limit']) {
            $qb->setMaxResults($criteria['limit']);
        }

        return $qb->getQuery()->getResult($hydrate);
    }

    /**
     * @param string $bookName
     * @param string $compareOperator
     * @param int $age
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getBooksByNameWithStats(
        string $bookName,
        string $compareOperator,
        int $age
    ): array
    {
        $connection = $this->getEntityManager()->getConnection();

        $sql = 'SELECT b.id,
  b.name,
  100.0 AS score,
  b.book_date,
  sum(CASE r.sex WHEN \'m\' THEN r.age ELSE 0 END) / sum(CASE r.sex WHEN \'m\' THEN 1 ELSE 0 END) AS males_avg_age,
  sum(CASE r.sex WHEN \'f\' THEN r.age ELSE 0 END) / sum(CASE r.sex WHEN \'f\' THEN 1 ELSE 0 END) AS females_avg_age
FROM books b
JOIN reviews r ON r.book_id = b.id
WHERE b.name LIKE :bookName
  AND r.age ' . $compareOperator . ' :age
GROUP BY b.id
;';

        $stmt = $connection->prepare($sql);
        $stmt->execute([
            'bookName' => $bookName,
            'age' => $age,
        ]);

        $result = $stmt->fetchAll();

        return $result;
    }

    /**
     * @param string $bookName
     * @param string $compareOperator
     * @param int $age
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getBooksWithFulltextAndStats(
        string $bookName,
        string $compareOperator,
        int $age
    ): array
    {
        $connection = $this->getEntityManager()->getConnection();

        $sql = 'SELECT b.id, b.name,
  MATCH (b.name) AGAINST (:bookName IN NATURAL LANGUAGE MODE) AS score,
  b.book_date,
  sum(CASE r.sex WHEN \'m\' THEN r.age ELSE 0 END) / sum(CASE r.sex WHEN \'m\' THEN 1 ELSE 0 END) AS males_avg_age,
  sum(CASE r.sex WHEN \'f\' THEN r.age ELSE 0 END) / sum(CASE r.sex WHEN \'f\' THEN 1 ELSE 0 END) AS females_avg_age
FROM books b
JOIN reviews r ON r.book_id = b.id
WHERE MATCH (b.name) AGAINST (:bookName IN NATURAL LANGUAGE MODE)
  AND r.age ' . $compareOperator . ' :age
GROUP BY b.id
;';

        $stmt = $connection->prepare($sql);
        $stmt->execute([
            'bookName' => $bookName,
            'age' => $age,

        ]);

        $result = $stmt->fetchAll();

        return $result;
    }
}