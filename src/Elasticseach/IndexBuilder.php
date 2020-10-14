<?php 
namespace App\Elasticseach;

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

    public function create(string $indexName, string $settingsFile, string $path = null): Index
    {
        if(null === $indexName || \strlen($indexName) === 0){
            throw new \InvalidArgumentException("Index name must be a valid string");
        }

        if(null === $path){
            $path = __DIR__ ."/../../config/elasticSearch/" ;
        }
        
        if(!is_file($path . $settingsFile) || !file_exists($path . $settingsFile)){
            throw new FileNotFoundException('File config not found');
        } 
        $settings = $this->parseSettingsFile($path, $settingsFile);
        
        $index = $this->client->getIndex($indexName);
        $index->create($settings, true);
        return $index;
    }

    private function parseSettingsFile(string $path, string $settingsFile): array
    {
        return Yaml::parse(file_get_contents($path . $settingsFile));
    }
}