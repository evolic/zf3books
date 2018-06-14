<?php
namespace Application\Entity;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Doctrine\Repository\ReviewRepository;

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

    public function __construct()
    {
    }
}