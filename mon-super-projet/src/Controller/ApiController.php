<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request; 
use App\Form\ArticleType;

class ApiController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
    $this->entityManager = $entityManager;
    }

    #[Route('/api', name: 'app_api')]
    public function index(): Response
    {
        $articleRepository = $this->entityManager->getRepository(Article::class);
        $articles = $articleRepository->findAll();

        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
            'articles' => $articles,
        ]);
    }

    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('api/home.html.twig');
    }

    #[Route('/api/success', name: 'api/success')]
    public function succes(): Response
    {
        return $this->render('api/success.html.twig');
    }

    #[Route('/api/new', name: 'create')]
    #[Route('/api/{id}/edit', name: 'edit')]
    public function form(?Article $article = null, Request $request): Response
    {
        $id = $request->attributes->get('id');
        if ($id !== null) {
            $articleRepository = $this->entityManager->getRepository(Article::class);
            $article = $articleRepository->find($id);
        }

        if (!$article) {
            $article = new Article();
        }

        $form = $this->createForm(ArticleType::class, $article);


            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $article->setCreatedAt(new \DateTimeImmutable());
                $article = $form->getData();
                $entityManager = $this->entityManager;
                $entityManager->persist($article);
                $entityManager->flush();
    
            return $this->redirectToRoute('api/success');
            }

        return $this->render('api/create.html.twig', [
            'formArticle' => $form->createView()
        ]);
    }
}
