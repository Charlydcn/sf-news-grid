<?php

namespace App\Controller;

use App\Entity\Article;
use App\Service\ImageOptimizer;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/article')]
class ArticleController extends AbstractController
{
    private $imageOptimizer;

    public function __construct(ImageOptimizer $imageOptimizer)
    {
        $this->imageOptimizer = $imageOptimizer;
    }

    #[Route('/admin/articles', name: 'articles', methods: ['GET'])]
    public function index(ArticleRepository $articleRepo): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepo->findBy([], ['creationDate' => 'DESC']),
        ]);
    }

    #[Route('/admin/new', name: 'new_article', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        SluggerInterface $slugger,
        EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if (!$form->isValid()) {
                $errors = $form->getErrors(true);
                foreach ($errors as $error) {
                    $errorMsg = $error->getMessage();
                    $this->addFlash('error', $errorMsg);
                    return $this->redirectToRoute('new_article');
                }
            }

            $article->setCreationDate(new \DateTime());
            $imgFile = $form->get('img')->getData();

            if($imgFile) {
                $originalFilename = pathinfo($imgFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imgFile->guessExtension();

                $temporaryPath = $this->getParameter('articles_directory') . '/' . $newFilename;

                try {
                    
                    $imgFile->move(
                        $this->getParameter('articles_directory'),
                        $newFilename
                    );

                    $this->imageOptimizer->resize($temporaryPath, 500, 300);
                } catch (FileException $e) {
                    dd('Erreur upload image');
                }

                $article->setImg($newFilename);
            }

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('articles', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show_article', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/admin/{id}/edit', name: 'edit_article', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        ValidatorInterface $validator,
        SluggerInterface $slugger,
        Article $article,
        EntityManagerInterface $entityManager): Response
    {
        $originalImg = $article->getImg();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $errors = $form->getErrors(true);
                foreach ($errors as $error) {
                    $errorMsg = $error->getMessage();
                    $this->addFlash('error', $errorMsg);
                    return $this->redirectToRoute('edit_article', ['id' => $article->getId()]);
                }
            }

            $imgFile = $form->get('img')->getData();
            if ($imgFile) {
                // traitement de l'image dans une fonction dédiée à ça
                $this->handleImgChange($imgFile, $originalImg, $article, $slugger, $validator);
            } else {
                $article->setImg($originalImg);
            }

            $entityManager->flush();

            return $this->redirectToRoute('articles', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/admin/{id}', name: 'delete_article', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            if($article->getImg()) {
                unlink($this->getParameter('articles_directory') . "/" . $article->getImg());
            }
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('articles', [], Response::HTTP_SEE_OTHER);
    }

    private function handleImgChange(
        $imgFile,
        $originalImg,
        Article $article,
        SluggerInterface $slugger,
        ValidatorInterface $validator,
    ): void
    {
        if($originalImg) {
            $filePath = $this->getParameter('articles_directory') . "/" . $originalImg;

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
    
        $violations = $validator->validate($imgFile, new Assert\File([
            'maxSize' => '5120k',
            'mimeTypes' => ['image/jpg', 'image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'],
        ]));
    
        if (count($violations) === 0) {
            $originalFilename = pathinfo($imgFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imgFile->guessExtension();
    
            $temporaryPath = $this->getParameter('articles_directory') . '/' . $newFilename;
    
            try {
                $imgFile->move($this->getParameter('articles_directory'), $newFilename);
                // Redimensionner l'image 
                $this->imageOptimizer->resize($temporaryPath, 500, 300);
                $article->setImg($newFilename);
            } catch (FileException $e) {
                // En cas d'erreur, revenir à l'image originale si possible
                $article->setImg($originalImg);
            }
        } else {
            foreach ($violations as $violation) {
                // Gérer les violations ici
            }
            $article->setImg($originalImg);
        }
    }
    
}
