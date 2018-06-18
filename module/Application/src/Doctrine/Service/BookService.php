<?php
namespace Application\Doctrine\Service;

use Application\Doctrine\Repository\BookRepository;
use Application\Entity\Book;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DBALException;
use Zend\Log\Logger;

class BookService
{
    /**
     * @var BookRepository
     */
    private $bookRepository;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(BookRepository $bookRepository, Logger $logger)
    {
        $this->bookRepository = $bookRepository;
        $this->logger = $logger;
    }

    /**
     * Method returns all available books
     *
     * @return array
     */
    public function getAllBooks()
    {
        return $this->bookRepository->getAllBooks([]);
    }

    /**
     * Returns books with given name and fitting age of the reviewers
     *
     * @param  string  $bookName
     * @param  string  $compareOperator
     * @param  int     $age
     * @return array
     */
    public function getBooksByNameWithStats(
        string $bookName,
        string $compareOperator,
        int $age
    ): array
    {
        try {
            return $this->bookRepository->getBooksByNameWithStats(
                $bookName,
                $compareOperator,
                $age
            );
        } catch (DBALException $e) {
            $this->logger->err($e->getMessage());

            return [];
        }
    }

    /**
     * Returns books with name matched by fulltext search and fitting age of the reviewers
     *
     * @param  string  $bookName
     * @param  string  $compareOperator
     * @param  int     $age
     * @return array
     */
    public function getBooksWithFulltextAndStats(
        string $bookName,
        string $compareOperator,
        int $age
    ): array
    {
        try {
            return $this->bookRepository->getBooksWithFulltextAndStats(
                $bookName,
                $compareOperator,
                $age
            );
        } catch (DBALException $e) {
            $this->logger->err($e->getMessage());

            return [];
        }
    }
}
