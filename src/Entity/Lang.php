<?php

namespace App\Entity;

use App\Repository\LangRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Language
 * 
 * @ORM\Entity(repositoryClass=LangRepository::class)
 * @ORM\Table(
 *     schema="public",
 *     name="lang",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="unq_lang", columns={"name"})
 *     },
 *     options={"comment":"Language"}
 * )
 */
class Lang
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment":"ID (Identifier)"})
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="public.language_id_seq", initialValue=1)
     */
    private int $id;

    /**
     * @ORM\Column(name="name", type="string", length=2, nullable=false, options={"comment":"Name"})
     * @Assert\NotBlank
     * @Assert\Length(min=2, max=2)
     */
    private string $name;

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
