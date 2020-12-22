<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\BookService;
// use Symfony\Component\Validator\Constraints as Assert;
// use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class BookController extends AbstractController
{
    /**
     * @var BookService
     */
    private $BookService;

    public function __construct(BookService $BookService)
    {
        $this->BookService = $BookService;
    }

    /**
     * @Route("/book/create", name="book_create", methods={"POST"}, defaults={"_format": "json"})
     */
    // * @Assert\Json(
    //     *     message = "This value should be valid JSON."
    //     * )
    public function create(Request $request): Response
    {
        // if ($request->isMethod('POST') && ($request->getContentType() === 'json')) {
        if ($request->isMethod('POST')) {
            return $this->BookService->create($request->toArray()); //$request->request->all()
        }

        // throw new MethodNotAllowedHttpException(['POST']);
    }

    /**
     * Book search
     *
     * @Route("/book/search", name="book_search", methods={"POST"})
     */
    public function search(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $text = null;
            if ($request->request->has('text')) 
                $text = $request->request->get('text');
            return $this->BookService->search($text);
        }
    }

    /**
     * Book info
     *
     * @Route(
     *     "/{_locale<%app.supported_locales%>}/book/{id}",
     *     name="book_info",
     *     methods={"GET"},
     *     locale="ru",
     *     requirements={
     *         "_locale": "en|ru",
     *     }
     * )
     */
    public function info(Request $request, int $id): Response
    {
        if ($request->isMethod('GET')) {
            return $this->BookService->searchId($id, $request->getLocale());
        }
    }
}
