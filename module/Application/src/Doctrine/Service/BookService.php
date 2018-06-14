<?php
namespace Application\Doctrine\Service;

use Application\Doctrine\Repository\BookRepository;
use Application\Entity\Book;
use Doctrine\ORM\EntityManager;

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
     * Method used to obtain users' repository.
     *
     * @return BookRepository
     */
    private function getBookRepository()
    {
        return $this->entityManager->getRepository(Book::class);
    }

    /**
     * @return array
     */
    public function getBooks()
    {
        return $this->getBookRepository()->getBooks([]);
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
        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
            return false;
        }
    }
}