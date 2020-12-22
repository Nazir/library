<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Lang;
use App\Entity\BookLang;
use App\Entity\BookAuthor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Book[]    findByText(string $text, string $lang = null, int $offset = null, int $limit = null)
 * @method Book|null findOneByIdAndLang(int $id, string $lang = 'ru'): ?Book
 */
class BookRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * Search book by text
     * @param string $text
     * @param string $lang Book language
     * @param int $offset Offset
     * @param int $limit Limit
     * @return Book[] Returns an array of Book objects
     */
    public function findByText(string $text, string $lang = null, int $offset = null, int $limit = null)
    {
        // Varian 0
        // // Get language
        // $Lang = $this->getEntityManager()->getRepository(Lang::class)
        //     ->findOneBy(['name' => $lang]);
        // $data = $this->getEntityManager()
        //     ->getRepository(BookLang::class)
        //     ->findBy(['name' => $text, 'lang' => $Lang], ['name' => 'ASC'], $limit, $offset);

        // Varian 1
        $text = strtoupper($text);
        $text = '%' . $text . '%';

        //          1.1
        // // $qb = $this->getEntityManager()->createQueryBuilder();
        // $qb = $this->createQueryBuilder('b');
        // $data = $qb
        //     ->addSelect('bl')
        //     // ->addSelect('aa')
        //     // ->addSelect('ba.author#get(\'name\')')
        //     // ->addSelect('b.bookAuthors')
        //     // ->select(['b.id AS Id', 'bl.name AS Name'])
        //     // ->addSelect('ba.author.id')
        //     // ->addSelect('a')
        //     // ->addSelect('a.name')
        //     // ->from(Book::class, 'b')
        //     ->innerJoin('b.bookLangs', 'bl')
        //     ->innerJoin('bl.lang', 'l')
        //     // ->innerJoin(BookLang::class, 'bl', \Doctrine\ORM\Query\Expr\Join::WITH, 'bl.idBook = b.id')
        //     // ->innerJoin(Lang::class, 'l', \Doctrine\ORM\Query\Expr\Join::WITH, 'l.id = bl.idLang')
        //     // ->leftJoin('b.bookAuthors', 'ba')
        //     // ->leftJoin('b.authors', 'aa')
        //     // ->leftJoin('b.bookAuthors', 'ba', \Doctrine\ORM\Query\Expr\Join::WITH, 'bl.book.id = b.id')
        //     // ->leftJoin('ba.author', 'a')
        //     // ->leftJoin(Author::class, 'a', \Doctrine\ORM\Query\Expr\Join::WITH, 'a = ba.author')
        //     // ->where($qb->expr()->eq('IDENTITY(b)', $item['Id']))
        //     ->where($qb->expr()->like('bl.name', $qb->expr()->literal($text)))
        //     // ->groupBy('b, bl')
        //     ->orderBy('bl.name', 'ASC');
        // if (isset($lang))
        //     $data = $qb->andWhere($qb->expr()->like('l.name', $qb->expr()->literal($lang)));
        // if (isset($limit))
        //     $data = $qb->setMaxResults($limit);
        // if (isset($offset))
        //     $data = $qb->setFirstResult($offset);
        // // dd($qb->getQuery()->getSQL());
        // // $data = $qb->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        // $data = $qb->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_OBJECT);
        // // dd($data[0]->getBookAuthors()[1]->getAuthor()->getName());
        // // dd($data[0]->getAuthors());
        // // dd($data);

        // $selects = is_array($select) ? $select : func_get_args();

        //          1.2
        $qb = $this->createQueryBuilder('b');
        $data = $qb
            ->select(['b.id AS Id', 'bl.name AS Name'])
            ->innerJoin('b.bookLangs', 'bl')
            ->innerJoin('bl.lang', 'l')
            ->where($qb->expr()->like('bl.name', $qb->expr()->literal($text)))
            ->orderBy('bl.name', 'ASC');
        if (isset($lang))
            $data = $qb->andWhere($qb->expr()->like('l.name', $qb->expr()->literal($lang)));
        if (isset($limit))
            $data = $qb->setMaxResults($limit);
        if (isset($offset))
            $data = $qb->setFirstResult($offset);
        // dd($qb->getQuery()->getSQL());
        $data = $qb->getQuery()->execute();
        $authors = [];
        $i = 0;
        foreach ($data as $ket=>$item) {
            $qb = $this->getEntityManager()->createQueryBuilder();
            $authors = $qb
                ->select(['a.id AS Id', 'a.name AS Name'])
                ->from(BookAuthor::class, 'ba')
                ->leftJoin('ba.author', 'a')
                ->leftJoin('ba.book', 'b')
                ->where($qb->expr()->eq('b.id', $item['Id']))
                ->orderBy('a.name', 'ASC')
                ->setMaxResults($limit)
                ->getQuery()
                ->execute();
            $data[$i]['Author'] = $authors;
            $i++;
        }
        // dd($qb->getDQL());
        // dd($data);

        // Varian 2 - самый быстрый и самый логичный, но не "для всех" (если поддержка исключительно людьми не знающих SQL на базовом уровне). Code monkey?
        // $connection = $this->getEntityManager()->getConnection();
        // $query = '
        //     SELECT
        //       b.id AS "Id"
        //     , bl.name AS "Name"
        //     , array_agg(a) AS "Author"
        //     FROM book AS b
        //         INNER JOIN book_lang AS bl ON bl.id_book = b.id
        //         INNER JOIN lang AS l ON l.id = bl.id_lang
        //         LEFT JOIN book_author AS ba ON ba.id_book = b.id
        //         LEFT JOIN author AS a ON a.id = ba.id_author
        //     WHERE
        //         bl.name LIKE :text
        //     GROUP BY
        //       b.id
        //     , bl.name
        //     ORDER BY
        //         bl.name
        //     LIMIT :limit
        //     OFFSET :offset
        //     ';
        // $statement = $connection->prepare($query);
        // $statement->bindValue('text', $text);
        // $statement->bindValue('limit', $limit, \Doctrine\DBAL\ParameterType::INTEGER);
        // $statement->bindValue('offset', $offset, \Doctrine\DBAL\ParameterType::INTEGER);
        // $statement->execute();
        // $data = $statement->fetchAllAssociative();

        return $data;
    }

    /**
     * Search book by ID & language
     * @param int $id Book ID
     * @param string $lang Book language
     * @return Book|null
     */
    public function findOneById(int $id, string $lang): ?Book
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.bookLangs', 'bl')
            ->innerJoin('bl.lang', 'l')
            ->andWhere('b.id = :id')
            ->setParameter('id', $id)
            ->andWhere('l.name LIKE :lang')
            ->setParameter('lang', $lang)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
