<?php

namespace App\Entity;

use App\Repository\BookLangRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Book;
use App\Entity\Lang;

/**
 * @ORM\Entity(repositoryClass=BookLangRepository::class)
 * @ORM\Table(
 *     schema="public",
 *     name="book_lang",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="unq_book_lang", columns={"id_book", "id_lang", "name"})
 *     },
 *     options={"comment":"Book - Language"}
 * )
 */
class BookLang
{
    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=false, options={"comment":"Name"})
     * @Assert\NotBlank
     * @Assert\Length(min=1, max=255)
     */
    private string $name;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="bookLangs")
     * @ORM\JoinColumn(name="id_book", referencedColumnName="id")
     * @Assert\NotBlank
     */
    private Book $book;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Lang::class, inversedBy="bookLangs")
     * @ORM\JoinColumn(name="id_lang", referencedColumnName="id")
     * @Assert\NotBlank
     */
    private Lang $lang;

    public function __construct(Book $book, Lang $lang)
    {
        $this->book = $book;
        $this->lang = $lang;
    }

    public function getIdBook(): int
    {
        return $this->book->getId();
    }

    public function getBook(): Book
    {
        return $this->book;
    }

    public function getIdLang(): int
    {
        return $this->lang->getId();
    }

    public function getLang(): Lang
    {
        return $this->lang;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
