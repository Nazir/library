<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Lang;
use App\Entity\BookAuthor;
use App\Entity\BookLang;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $bookName = 'Book ';
        $authorName = 'Author ';
        $lang = new Lang();
        $lang->setName('en');
        $manager->persist($lang);
        $manager->flush();

        $count = 10000;
        for ($i = 1; $i <= $count; $i++) {
            $book = new Book();
            $manager->persist($book);

            if ($i === 5000) {
                $lang = new Lang();
                $lang->setName('ru');
                $manager->persist($lang);
                $bookName = 'Книга ';
                $authorName = 'Автор ';
            }

            $bookLang = new BookLang($book, $lang);
            $bookLang->setName($bookName . $i); // mt_rand(10, 100)
            $manager->persist($bookLang);

            $author = new Author();
            $author->setName($authorName . $i);
            $manager->persist($author);

            $bookAuthor = new BookAuthor($book, $author);
            $manager->persist($bookAuthor);
            if (!($i % 100))
                $manager->flush();
        }
    }
}
