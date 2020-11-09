<?php
namespace App\Controller;

use App\Repository\PostRepository;
use App\Services\Cache\PostsCache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController 
{

    /**
     * @param PostRepository $postRepository
     * @param Request $request
     * @Route("/", name="home")
     * @return Response
     */
    public function index(PostsCache $postsCache, Request $request): Response
    {
        return $this->render('home/index.html.twig',[
            'posts' => $postsCache->getPosts() ? : [],
            'local' => $request->getPreferredLanguage()
        ]);
    }
    
}