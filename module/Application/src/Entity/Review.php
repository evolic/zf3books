<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A Unit entity class
 *
 * @ORM\Entity(repositoryClass="Application\Doctrine\Repository\ReviewRepository")
 * @ORM\Table(name="reviews")
 */
class Review
{
    const SEX_MALE   = 'm';
    const SEX_FEMALE = 'f';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="integer");
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $sex;

    /**
     * @ORM\Column(type="integer");
     */
    private $book_id;

    /**
     * Many Reviews have One Book.
     *
     * @ORM\ManyToOne(targetEntity="Book", inversedBy="reviews")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id")
     */
    private $book;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @return string
     */
    public function getSex(): string
    {
        return $this->sex;
    }

    /**
     * @return int
     */
    public function getBookId(): int
    {
        return $this->book_id;
    }

    /**
     * @return Book
     */
    public function getBook(): Book
    {
        return $this->book;
    }
}
