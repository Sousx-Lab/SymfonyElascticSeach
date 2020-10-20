<?php 
namespace App\Services\ElasticSearch\Indexer;

use Elastica\Client;
use Elastica\Index;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Yaml\Yaml;


class IndexBuilder
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Create a ElasticSeach Index
     * @param string $indexName
     * @param string $settingsFile
     * @param string $path
     * @return Index
     */
    public function create(string $indexName, string $settingsFile, string $path = null): Index
    {
        if(null === $indexName || \strlen($indexName) === 0){
            throw new \InvalidArgumentException("Index name must be a valid string");
        }

        if(null === $path){
            $path =  dirname(__DIR__ , 4) . '/config/elasticSearch/';
        }
        if(!is_file($path . $settingsFile) || !file_exists($path . $settingsFile )){
            throw new FileNotFoundException('Settings file not found');
        }
        
        $extention = pathinfo($path . $settingsFile);
        if('yaml' !== $extention['extension']){

            throw new \Exception($settingsFile . " Not a Yaml settings file");
        }

        $settings = $this->parseSettingsFile($path, $settingsFile);
        
        $index = $this->client->getIndex($indexName);
        $index->create($settings, true);
        return $index;
    }

    /**
     * Parse YAML settings file
     * @param string $path
     * @param string $settingsFile
     * @return array
     */
    private function parseSettingsFile(string $path, string $settingsFile): array
    {
        return Yaml::parse(file_get_contents($path . $settingsFile));
    }
}