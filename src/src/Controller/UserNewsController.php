<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/news")
 */
class UserNewsController extends AbstractController
{
    /**
     * @Route("/", name="news_index")
     */
    public function index(NewsRepository $newsRepository): Response
    {
        return $this->render('news/index.html.twig', [
            'news' => $newsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="news_show", methods={"GET"})
     */
    public function show(News $news): Response
    {
        return $this->render('news/show.html.twig', [
            'news' => $news,
        ]);
    }

    /**
     * @Route("/tags/{tag}", name="news_show_by_tag", methods={"GET"})
     */
    public function showByTags(NewsRepository $newsRepository, string $tag): Response
    {
        $news = $newsRepository->findByTags($tag);
        return $this->render('news/by_tag.html.twig', [
            'news' => $news,
            'tag' => $tag
        ]);
    }
    /**
     * @Route("/search", name="news_search", methods={"POST"})
     */
    //news search
    public function search(Request $request, NewsRepository $repository): Response
    {
        $searchStr = $request->get('search');
        $news = $repository->search($searchStr);
        return $this->renderForm('news/index.html.twig', [
            'news' => $news
        ]);
    }
}
