<?php
namespace Application\Doctrine\Service;

use Application\Doctrine\Repository\BookRepository;
use Application\Entity\Book;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DBALException;

class BookService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Method returns all available books
     *
     * @return array
     */
    public function getAllBooks()
    {
        return $this->getBookRepository()->getAllBooks([]);
    }

    /**
     * Returns books with given name and fitting age of the reviewers
     *
     * @param  string  $bookName
     * @param  string  $compareOperator
     * @param  int     $age
     * @return array|bool
     */
    public function getBooksByNameWithStats(
        string $bookName,
        string $compareOperator,
        int $age
    )
    {
        try {
            return $this->getBookRepository()->getBooksByNameWithStats(
                $bookName,
                $compareOperator,
                $age
            );
        } catch (DBALException $e) {
            return false;
        }
    }

    /**
     * Returns books with name matched by fulltext search and fitting age of the reviewers
     *
     * @param  string  $bookName
     * @param  string  $compareOperator
     * @param  int     $age
     * @return array|bool
     */
    public function getBooksWithFulltextAndStats(
        string $bookName,
        string $compareOperator,
        int $age
    )
    {
        try {
            return $this->getBookRepository()->getBooksWithFulltextAndStats(
                $bookName,
                $compareOperator,
                $age
            );
        } catch (DBALException $e) {
            return false;
        }
    }

    /**
     * Method used to obtain users' repository.
     *
     * @return BookRepository
     */
    private function getBookRepository()
    {
        return $this->entityManager->getRepository(Book::class);
    }
}
