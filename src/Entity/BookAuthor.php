<?php

namespace App\Entity;

use App\Repository\BookAuthorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Book;
use App\Entity\Author;

/**
 * @ORM\Entity(repositoryClass=BookAuthorRepository::class)
 * @ORM\Table(
 *     schema="public",
 *     name="book_author",
 *     options={"comment":"Book - Author"}
 * )
 */
class BookAuthor
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="bookAuthors")
     * @ORM\JoinColumn(name="id_book", referencedColumnName="id")
     * @Assert\NotBlank
     */
    private Book $book;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="bookAuthors")
     * @ORM\JoinColumn(name="id_author", referencedColumnName="id")
     * @Assert\NotBlank
     */
    private Author $author;

    // /**
    //  * @ORM\Column(name="id_author", type="integer", nullable=false, options={"comment":"ID Author"})
    //  */
    // private int $authorId;

    // private string $authorName;

    public function __construct(Book $book, Author $author)
    {
        $this->book = $book;
        $this->author = $author;
    }

    public function getBook(): Book
    {
        return $this->book;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    // public function getAuthorName(): string
    // {
    //     $this->authorName = $this->author->getName();
    //     return $this->authorName;
    // }
}
