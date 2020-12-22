<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Author;
use App\Model\ApiMessage;

class AuthorService implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Create author from array
     * @param array $data Array with data
     * @return ApiMessage
     */
    public function create(array $data): ApiMessage
    {
        if (empty($data))
            return (new ApiMessage(null, ApiMessage::HTTP_BAD_REQUEST))->setMessage(null, 'The array is empty!', false);

        $name = $data['Name'];
        if (!filter_var($name, FILTER_SANITIZE_STRING))
            $name = null;

        if (empty($name))
            return (new ApiMessage(null, ApiMessage::HTTP_BAD_REQUEST))->setMessage(null, 'The [Name] field must not be empty!', false);

        $author = $this->entityManager->getRepository(Author::class)->findOneBy(['name' => $name]);
        if (!is_null($author))
            return (new ApiMessage(null, ApiMessage::HTTP_INTERNAL_SERVER_ERROR))->setMessage(null, 'Dublicate', false);

        $book = new Author();
        $book->setName($name);

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return (new ApiMessage())->setMessage(null, 'Created');
    }
}
