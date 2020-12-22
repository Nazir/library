<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201213182003 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE public.author_id_seq INCREMENT BY 100 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE public.book_id_seq INCREMENT BY 100 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE public.language_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE public.author (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX unq_author ON public.author (name)');
        $this->addSql('COMMENT ON TABLE public.author IS \'Author\'');
        $this->addSql('COMMENT ON COLUMN public.author.id IS \'ID (Identifier)\'');
        $this->addSql('COMMENT ON COLUMN public.author.name IS \'Name\'');
        $this->addSql('CREATE TABLE public.book (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON TABLE public.book IS \'Book\'');
        $this->addSql('COMMENT ON COLUMN public.book.id IS \'ID (Identifier)\'');
        $this->addSql('CREATE TABLE public.book_author (id_book INT NOT NULL, id_author INT NOT NULL, PRIMARY KEY(id_book, id_author))');
        $this->addSql('CREATE INDEX IDX_180E91AF40C5BF33 ON public.book_author (id_book)');
        $this->addSql('CREATE INDEX IDX_180E91AF9B986D25 ON public.book_author (id_author)');
        $this->addSql('COMMENT ON TABLE public.book_author IS \'Book - Author\'');
        $this->addSql('COMMENT ON COLUMN public.book_author.id_book IS \'ID (Identifier)\'');
        $this->addSql('COMMENT ON COLUMN public.book_author.id_author IS \'ID (Identifier)\'');
        $this->addSql('CREATE TABLE public.book_lang (id_book INT NOT NULL, id_lang INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id_book, id_lang))');
        $this->addSql('CREATE INDEX IDX_1C38FA4D40C5BF33 ON public.book_lang (id_book)');
        $this->addSql('CREATE INDEX IDX_1C38FA4DBA299860 ON public.book_lang (id_lang)');
        $this->addSql('CREATE UNIQUE INDEX unq_book_lang ON public.book_lang (id_book, id_lang, name)');
        $this->addSql('COMMENT ON TABLE public.book_lang IS \'Book - Language\'');
        $this->addSql('COMMENT ON COLUMN public.book_lang.id_book IS \'ID Book\'');
        $this->addSql('COMMENT ON COLUMN public.book_lang.id_lang IS \'ID Language\'');
        $this->addSql('COMMENT ON COLUMN public.book_lang.name IS \'Name\'');
        $this->addSql('CREATE TABLE public.lang (id INT NOT NULL, name VARCHAR(2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX unq_lang ON public.lang (name)');
        $this->addSql('COMMENT ON TABLE public.lang IS \'Language\'');
        $this->addSql('COMMENT ON COLUMN public.lang.id IS \'ID (Identifier)\'');
        $this->addSql('COMMENT ON COLUMN public.lang.name IS \'Name\'');
        $this->addSql('ALTER TABLE public.book_author ADD CONSTRAINT FK_180E91AF40C5BF33 FOREIGN KEY (id_book) REFERENCES public.book (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.book_author ADD CONSTRAINT FK_180E91AF9B986D25 FOREIGN KEY (id_author) REFERENCES public.author (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.book_lang ADD CONSTRAINT FK_1C38FA4D40C5BF33 FOREIGN KEY (id_book) REFERENCES public.book (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.book_lang ADD CONSTRAINT FK_1C38FA4DBA299860 FOREIGN KEY (id_lang) REFERENCES public.lang (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE public.book_author DROP CONSTRAINT FK_180E91AF9B986D25');
        $this->addSql('ALTER TABLE public.book_author DROP CONSTRAINT FK_180E91AF40C5BF33');
        $this->addSql('ALTER TABLE public.book_lang DROP CONSTRAINT FK_1C38FA4D40C5BF33');
        $this->addSql('ALTER TABLE public.book_lang DROP CONSTRAINT FK_1C38FA4DBA299860');
        $this->addSql('DROP SEQUENCE public.author_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE public.book_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE public.language_id_seq CASCADE');
        $this->addSql('DROP TABLE public.author');
        $this->addSql('DROP TABLE public.book');
        $this->addSql('DROP TABLE public.book_author');
        $this->addSql('DROP TABLE public.book_lang');
        $this->addSql('DROP TABLE public.lang');
    }
}
