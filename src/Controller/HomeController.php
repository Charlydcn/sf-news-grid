<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    #[Route('/index')]
    #[Route('/')]
    public function index(ArticleRepository $articleRepo): Response
    {
        $articles = $articleRepo->findBy([], ['creationDate' => 'DESC'], 7);

        return $this->render('home.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route('/article', name: 'article')]
    public function article(): Response
    {
        return $this->render('article.html.twig');
    }

    #[Route('/admin', name: 'admin')]
    public function admin(): Response
    {
        return $this->render('admin.html.twig');
    }
}
