<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\BookLang;
use App\Entity\BookAuthor;
use App\Entity\Author;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @ORM\Table(
 *     schema="public",
 *     name="book",
 *     options={"comment":"Book"}
 * )
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment":"ID (Identifier)"})
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="public.book_id_seq", initialValue=1, allocationSize=100)
     */
    private int $id;

    /**
     * @var Collection|BookLang[]
     * @ORM\OneToMany(targetEntity=BookLang::class, mappedBy="book")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $bookLangs;

    /**
     * @var Collection|BookAuthor[]
     * @ORM\OneToMany(targetEntity=BookAuthor::class, mappedBy="book")
     */
    private $bookAuthors;

    /**
     * @var Collection|Author[]
     * @ORM\OneToMany(targetEntity=Author::class, mappedBy="book")
     */
    // * Many books have many authors.
    // * @ManyToMany(targetEntity=Author::class)
    // * @JoinTable(name="author",
    // *      joinColumns={@JoinColumn(name="id", referencedColumnName="id")},
    // *      inverseJoinColumns={@JoinColumn(name="id_book", referencedColumnName="id", unique=true)}
    // *      )
   private $authors;

    public function __construct()
    {
        $this->bookLangs = new ArrayCollection();
        $this->bookAuthors = new ArrayCollection();
        $this->authors = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection|BookLang[]
     */
    public function getBookLang(): Collection
    {
        return $this->bookLangs;
    }

    public function addBookLang(BookLang $bookLang): self
    {
        if (!$this->bookLangs->contains($bookLang)) {
            $this->bookLangs[] = $bookLang;
        }

        return $this;
    }

    public function removeBookLang(BookLang $bookLang): self
    {
        if ($this->bookLangs->removeElement($bookLang)) {
        }

        return $this;
    }

    /**
     * @return Collection|BookAuthor[]
     */
    public function getBookAuthors(): Collection
    {
        return $this->bookAuthors;
    }

    public function addBookAuthor(BookAuthor $bookAuthor): self
    {
        if (!$this->bookAuthors->contains($bookAuthor)) {
            $this->bookAuthors[] = $bookAuthor;
        }

        return $this;
    }

    public function removeBookAuthor(BookAuthor $bookAuthor): self
    {
        if ($this->bookAuthors->removeElement($bookAuthor)) {
        }

        return $this;
    }

    /**
     * @return Collection|Author[]
     */
    public function getAuthors(): Collection
    {
        $authors = new ArrayCollection();
        foreach ($this->bookAuthors as $entry) {
            $authors[] = $entry->getAuthor();
        }
        $this->authors = $authors;
        return $this->authors;
    }
}
