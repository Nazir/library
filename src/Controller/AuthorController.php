<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AuthorService;

/**
 * @Route("/author", name="author_")
 */
class AuthorController extends AbstractController
{
    /**
     * @var AuthorService
     */
    private $AuthorService;

    public function __construct(AuthorService $AuthorService)
    {
        $this->AuthorService = $AuthorService;
    }

    /**
     * @Route("/create", name="create", methods={"POST"}, defaults={"_format": "json"})
     */
    public function create(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            return $this->AuthorService->create($request->toArray());
        }
    }
}
