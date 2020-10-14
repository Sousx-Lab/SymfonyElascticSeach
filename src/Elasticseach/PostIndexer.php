<?php
namespace App\Elasticseach;

use App\Entity\Post;
use Elastica\Client;
use Elastica\Document;
use App\Repository\PostRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class PostIndexer 
{   
    private Client $client;
    
    private PostRepository $postRepository;

    private UrlGeneratorInterface $router;

    public function __construct(PostRepository $postRepository, Client $client, UrlGeneratorInterface $router)
    {   
        $this->postRepository = $postRepository;
        $this->client =$client;
        $this->router = $router;
    }

    /**
     * @param Post $post
     * @return Document
     */
    public function buildDocument(Post $post): Document 
    {   
        return new Document(
            $post->getId(),
            [
                'title' => $post->getTitle(),
                'author' => $post->getAuthor(),
                'content' => $post->getContent(),

                /** Not indexed but needed for display */
                'createdAt' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
                'url' => $this->router->generate('post_show', ['slug' => $post->getSlug(), 'id' => $post->getId()], UrlGeneratorInterface::ABSOLUTE_PATH),
            ]
        );
    }

    /**
     * @param string $indexName
     * @return void
     */
    public function indexAllDocuments(string $indexName): void
    {
        $allPosts = $this->postRepository->findAll();
        $index = $this->client->getIndex($indexName);

        $documents = [];
        foreach($allPosts as $post){
            $documents[] = $this->buildDocument($post);
        }

        $index->addDocuments($documents);

        /**
         * Pour réduire le refresh_interval lors d'une indexation massive
         * $index->getSettings()->setRefreshInterval('60s');
         */
        $index->refresh();
    }

}