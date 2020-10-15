<?php

namespace App\Controller\PostController;

use Throwable;
use Elastica\Query;
use Elastica\Client;
use Elastica\Search;
use Elastica\Query\BoolQuery;
use Elastica\Query\MultiMatch;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchPostsController extends AbstractController
{

    /**
     * @param Request $request
     * @param Client $client
     * @Route("/search", name="search", methods={"GET"})
     * @return Response
     */
    public function search(Request $request, Client $client): Response
    {
        /*if(!$request->isXmlHttpRequest()){
            return $this->render('post/search.html.twig');
        }*/
        
        $query = $request->query->get('q', '');
        if($query === ''){
            return $this->render('search/search.html.twig',[
                'query' => $query
            ]);
        };
        $limit = $request->query->get('l', 10);

        $search = new Search($client);
        $search->addIndex('blog');

        $match = new MultiMatch();
        $match->setQuery($query)
              ->setFields(["title", "content"]);

        $bool = new BoolQuery();
        $bool->addMust($match);

        $elasticQuery = new Query();
        $elasticQuery->setQuery($bool)
                     ->setMinScore(1.0);
        /*$elasticQuery->setSize($limit);*/
        try {
            $foundedPosts = $client->getIndex('blog')->search($elasticQuery);
        } catch (Throwable $e) {
            if($e){
                $response = new Response();
                return $this->render('search/search.html.twig', [
                    'elastica_connexion_error' => true,
                ],$response->setStatusCode(422));
                // throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY, "Oops la recherche n'a pas pu aboutir");
            }
        }
        
        $totalHits = $foundedPosts->getTotalHits();
        $results = [];
        foreach($foundedPosts as $post){
            $results[] = $post->getSource();
        }
        
        return $this->render('search/search.html.twig',[
            'results' => $results,
            'totalHits' => $totalHits,
            'query' => $query,
        ]);
    }
}