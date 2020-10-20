<?php

namespace App\Services\ElasticSearch;

use Elastica\Query;

use Elastica\Client;
use Elastica\Search;

use Elastica\ResultSet;
use Elastica\Query\BoolQuery;
use Elastica\Query\MultiMatch;

class SearchQuery {

    private Client $client;

    public function __construct(Client $client) {

        $this->client = $client;
    }
    /**
     * Search Query
     * @param string $query
     * @param integer $limit
     * @return ResultSet|null
     */
    public function search(string $query, string $index, array $fields = [], int $limit = 10): ?ResultSet
    {
        
        $search = new Search($this->client);
        $search->addIndex($index);

        $match = new MultiMatch();
        $match->setQuery($query)
              ->setFields($fields);

        $bool = new BoolQuery();
        $bool->addMust($match);

        $elasticQuery = new Query();
        $elasticQuery->setQuery($bool)
                     ->setMinScore(1.0);
        /*$elasticQuery->setSize($limit);*/
    
        try {
             $foundedItems = $this->client->getIndex($index)->search($elasticQuery);
        } catch (\Throwable $e) {
            if($e){
                return null;
            }
        }
        return $foundedItems;
    }
}