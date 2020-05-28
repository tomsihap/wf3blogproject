<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController {

    /**
     * @Route("/", name="app_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository) {

        $articles = $articleRepository->findLastArticles(3);

        return $this->render('app/index.html.twig', [
            'articles' => $articles
        ]);
    }
}