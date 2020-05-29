<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
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
     * @Route("/create", name="article_create", methods={"GET", "POST"})
     */
    public function create(Request $request) {

        $article = new Article; // L'objet qui contiendra les données du formulaire généré
        $form = $this->createForm(ArticleType::class, $article); // Le formulaire généré
        $form->handleRequest($request); // Le formulaire gèrera les données de requête si jamais il en reçoit

        if($form->isSubmitted() && $form->isValid()) {

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/create.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/{article}/edit", name="article_update", methods={"GET", "POST"})
     */
    public function update(Request $request, Article $article) {

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/create.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/{article}/delete", name="article_delete", methods={"POST"})
     */
    public function delete(Article $article)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($article);
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