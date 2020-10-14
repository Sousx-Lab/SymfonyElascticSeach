<?php
namespace App\Controller\PostController;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController 
{
    
    private PostRepository $repository;

    public function __construct(PostRepository $repository) 
    {
        $this->repository = $repository;    
    }

    /**
     * @Route("/posts", name="posts_index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('post/index.html.twig',[
            'posts' => $this->repository->findAll()
        ]);
    }

    /**
     * @param Post $post
     * @param string $slug
     * @Route("/post/{slug}-{id}", name="post_show", requirements={"slug": "[a-z0-9\-]*"})
     * @return Response
     */
    public function show(Post $post, string $slug): Response
    {
        if($post->getSlug() !== $slug){
            return $this->redirectToRoute('post_show',[
                'id' => $post->getId(),
                'slug' => $post->getSlug()
            ], 301);
        }
        return $this->render('post/show.html.twig',[
            'post' => $post
        ]);
       
    }
    
    
}