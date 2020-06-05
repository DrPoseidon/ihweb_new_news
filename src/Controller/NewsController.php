<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\News;
use App\Form\CommentsType;
use App\Form\NewsType;
use App\Repository\CommentsRepository;
use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @Route("/news")
 */
class NewsController extends AbstractController
{
    /**
     * @Route("/", name="news_index", methods={"GET"})
     */
    public function index(NewsRepository $newsRepository): Response
    {
        return $this->render('news/index.html.twig', [
            'news' => $newsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="news_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $news = new News();
        $news->setDate(new \DateTime());
        $news->setNewsUser($this->getUser());
        $news->setNumberOfViews(0);
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($news);
            $entityManager->flush();

            return $this->redirectToRoute('news_index');
        }

        return $this->render('news/new.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="news_show", methods={"GET","POST"})
     */
    public function show(News $news, Request $request, CommentsRepository $commentsRepository): Response
    {
        $comment = new Comments();
        $comment->setCommentsUser($this->getUser());
        $comment->setCommentsNews($news);
        $commentForm = $this->createForm(CommentsType::class,$comment);
        $commentForm->handleRequest($request);

        if($commentForm->isSubmitted() && $commentForm->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success','Комментарий добавлен!');
            return $this->redirect($request->getUri());
        }




        $news->setNumberOfViews($news->getNumberOfViews()+1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($news);
        $entityManager->flush();

        return $this->render('news/show.html.twig', [
            'news' => $news,
            'comments' => $commentsRepository->findBy(array('Comments_news' => $news->getId()), array('id' => 'DESC')),
            'formC' => $commentForm->createView()
        ]);
    }
}
