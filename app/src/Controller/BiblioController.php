<?php
/**
 * Biblio controller.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BiblioController.
 */
class BiblioController extends AbstractController
{
    /**
     * Index action.
     *
     * @return Response HTTP response
     */
    #[Route(name: 'index', methods: 'GET')]
    public function index(): Response
    {
        return $this->render('main/biblio.html.twig');

    }

}
