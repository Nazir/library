<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
// use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Book;
use App\Entity\BookAuthor;

/**
 * @ORM\Entity(repositoryClass=AuthorRepository::class)
 * @ORM\Table(
 *     schema="public",
 *     name="author",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="unq_author", columns={"name"})
 *     },
 *     options={"comment":"Author"}
 * )
 */
class Author
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment":"ID (Identifier)"})
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="public.author_id_seq", initialValue=1, allocationSize=100)
     */
    private int $id;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=false, options={"comment":"Name"})
     * @Assert\NotBlank
     * @Assert\Length(min=2, max=255)
     */
    private string $name;


    /**
     * @var Collection|BookAuthor[]
     * @ORM\OneToMany(targetEntity=BookAuthor::class, mappedBy="author")
     */
    private $bookAuthors;

    /**
     * @var Collection|Book[]
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="authors")
     */
    private $books;

    public function getId(): int
    {
        return $this->id;
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
