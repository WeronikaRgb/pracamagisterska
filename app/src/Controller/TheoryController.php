<?php
/**
 * Info controller.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TheoryController.
 */
class TheoryController extends AbstractController
{
    /**
     * Index action.
     *
     * @return Response HTTP response
     */
    #[Route('/theory')]
    public function index(): Response
    {
        return $this->render('definition.html.twig');

    }

}
