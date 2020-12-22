<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Lang;
use App\Entity\BookLang;
use App\Entity\BookAuthor;
use App\Model\ApiMessage;

class BookService implements ContainerAwareInterface
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
     * Create book from array
     * @param array $data Array with data
     * @return ApiMessage
     */
    public function create(array $data): ApiMessage
    {
        if (empty($data))
            return (new ApiMessage())->setStatusCode(ApiMessage::HTTP_BAD_REQUEST)->setMessage($data, 'Request is empty!', false);

        $name = null;
        if (key_exists('Name', $data))
            $name = $data['Name'];
        if (!filter_var($name, FILTER_SANITIZE_STRING))
            $name = null;
        if (empty($name))
            return (new ApiMessage())->setStatusCode(ApiMessage::HTTP_BAD_REQUEST)->setMessage($data, 'The [Name] field must not be empty!', false);

        $lang = null;
        if (key_exists('Lang', $data))
            $lang = $data['Lang'];
        if (!filter_var($lang, FILTER_SANITIZE_STRING))
            $lang = null;
        if (empty($lang))
            return (new ApiMessage())->setStatusCode(ApiMessage::HTTP_BAD_REQUEST)->setMessage($data, 'The [Lang] field must not be empty!', false);
        $lang = $this->entityManager->getRepository(Lang::class)->findOneBy(['name' => $lang]);

        $bookLang = $this->entityManager->getRepository(BookLang::class)->findOneBy(['name' => $name, 'lang' => $lang]);
        if (!is_null($bookLang))
            return (new ApiMessage())->setStatusCode(ApiMessage::HTTP_INTERNAL_SERVER_ERROR)->setMessage($data, 'Dublicate', false);

        $authors = $data['Author'];

        $author = $this->entityManager->getRepository(Author::class)->findOneBy(['name' => $name]);
        if (!is_null($author))
            return (new ApiMessage())->setStatusCode(ApiMessage::HTTP_INTERNAL_SERVER_ERROR)->setMessage($data, 'Dublicate', false);

        $book = new Book();
        $this->entityManager->persist($book);

        $bookLang = new BookLang($book, $lang);
        $bookLang->setName($name);
        $this->entityManager->persist($bookLang);

        foreach ($authors as $key => $value) {
            $name = $value['Name'];
            if (!empty($name)) {
                if (!filter_var($name, FILTER_SANITIZE_STRING))
                    return (new ApiMessage())->setStatusCode(ApiMessage::HTTP_BAD_REQUEST)->setMessage($data, 'The [Author.Name] field must not be empty!', false);

                $author = $this->entityManager->getRepository(Author::class)->findOneBy(['name' => $name]);
                if (is_null($author)) {
                    $author = new Author;
                    $author->setName($name);
                    $this->entityManager->persist($author);
                }
                $bookAuthor = new BookAuthor($book, $author);
                $this->entityManager->persist($bookAuthor);
                // $book->addBookAuthor($author);
            }
        }

        $this->entityManager->flush();

        return (new ApiMessage())->setMessage(null, 'Created', false);
    }

    /**
     * Search book
     * @param string $text Text to search
     * @param string $lang Book language
     * @param int $offset Offset
     * @param int $limit Limit
     * @return ApiMessage
     */
    public function search(string $text, string $lang = 'ru', int $offset = 0, $limit = null): ApiMessage
    {
        if (empty($text))
            return (new ApiMessage())->setStatusCode(ApiMessage::HTTP_BAD_REQUEST)->setMessage('Request is empty!');

        if (!isset($limit))
            $limit = ApiMessage::DATA_LIMIT;

        $data = $this->entityManager->getRepository(Book::class)->findByText($text, $lang, $offset, $limit);
        // Code monkey
        // dd($data);
        // dd(json_encode($data));
        // dd((new ApiMessage())->setMessage($data));

        if (!$data)
            return (new ApiMessage())->setStatusCode(ApiMessage::HTTP_NOT_FOUND)->setMessage(null, 'No book found for text "' . $text . '" & lang "' . $lang . '"', false);

        return (new ApiMessage())->setMessage($data);
    }

    /**
     * Search book by ID
     * @param int $id Book ID (Identifier)
     * @param string $lang Book language
     * @return ApiMessage
     */
    public function searchId(int $id, string $lang = 'ru'): ApiMessage
    {
        $data = $this->entityManager->getRepository(Book::class)->findOneById($id, $lang);

        if (!$data)
            return (new ApiMessage())->setStatusCode(ApiMessage::HTTP_NOT_FOUND)->setMessage(null, 'No book found for ID "' . $id . '" & lang "' . $lang . '"', false);

        return (new ApiMessage())->setMessage($data);
    }
}
