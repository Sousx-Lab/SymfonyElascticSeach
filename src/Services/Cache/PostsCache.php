<?php

namespace App\Services\Cache;

use App\Repository\PostRepository;
use DateInterval;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;

class PostsCache {
    
    private CacheInterface $cache;

    private PostRepository $repository;
    
    public function __construct(PostRepository $repository, CacheInterface $cache) {
        $this->cache = $cache;
        $this->repository = $repository;
    }

    /**
     * Get cached Posts from cache
     * @return array|null
     */
    public function getPosts(): ?array
    {
        return $items = $this->cache->get("posts", function(ItemInterface $item){
            $item->expiresAfter(new DateInterval('PT23H'));
            return $this->repository->findAll();
        });
    }

    /**
     * delete Posts from cache
     * @return boolean
     */
    public function deletePosts(): bool
    {
       return $this->cache->delete("posts");
    }
}