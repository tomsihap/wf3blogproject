<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * On préfixe toutes les routes du controller par "/articles"
 * @Route("/articles")
 */
class ArticleController extends AbstractController {

    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function index() {
        $articles = $this->articleRepository->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/search", name="article_search", methods={"GET"})
     */
    public function search(Request $request) {

        $search = $request->query->get('q');

        $articles = $this->articleRepository->findByContent($search);

        return $this->render('article/index.html.twig', [
            'articles' => $articles
        ]);
    }



    /**
     * @Route("/create", name="article_create", methods={"GET"})
     */
    public function create() {
        return $this->render('article/create.html.twig');
    }

    /**
     * @Route("/", name="article_new", methods={"POST"})
     */
    public function new(Request $request) {

        $article = new Article;
        $article->setTitle($request->request->get('title'));
        $article->setContent($request->request->get('content'));

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($article);
        $manager->flush();

        return $this->redirectToRoute("article_index");

    }
    
    /**
     * @Route("/{article}", name="article_show", methods={"GET"})
     */
    public function show(Article $article) {
        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
    }
}