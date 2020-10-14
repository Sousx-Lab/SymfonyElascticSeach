<?php
namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController 
{
    private PostRepository $repository;

    public function __construct(PostRepository $repository) 
    {
        $this->repository = $repository;
    }

    /**
     * @param PostRepository $postRepository
     * @Route("/", name="home")
     * @return Response
     */
    public function index(Request $request): Response
    {
        return $this->render('home/index.html.twig',[
            'posts' => $this->repository->findLatest() ? : null,
            'local' => $request->getPreferredLanguage()
        ]);
    }
    
}