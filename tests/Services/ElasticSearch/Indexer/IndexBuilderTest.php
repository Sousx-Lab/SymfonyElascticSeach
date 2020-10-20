<?php
namespace App\Tests\Services\ElasticSearch;

use Elastica\Client;
use PHPUnit\Framework\TestCase;
use App\Services\ElasticSearch\Indexer\IndexBuilder;;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class IndexBuilderTest extends TestCase
{

    public function getIndexBuilder(): IndexBuilder
    {
        $client = new Client();
        $builder = new IndexBuilder($client);
        return $builder;
    }
      
    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(object &$object, string $methodName, array $parameters = array())
    {
      $reflection = new \ReflectionClass(get_class($object));
      $method = $reflection->getMethod($methodName);
      $method->setAccessible(true);
    
      return $method->invokeArgs($object, $parameters);
    }

    public function testBadIndexNameException(): void
    {   
      $this->expectException(\InvalidArgumentException::class);
      $indexBuilder = $this->getIndexBuilder();
      $indexBuilder->create('', '');
    }
    public function testWithoutSettingsFileException(): void
    {
      $this->expectException(FileNotFoundException::class);
      $indexBuilder = $this->getIndexBuilder();
      $indexBuilder->create('blog', "");
    }

    public function testSettingsFileBadExtentionException(): void
    {
      $this->expectException(\Exception::class);
      $indexBuilder = $this->getIndexBuilder();
      $indexBuilder->create('blog', $_SERVER["INDEX_POSTS_TEST_BAD_EXTENTION"], __DIR__ . "/");
    }

    public function testParsedSettingsFileMustReturnAnArray(): void
    {
      $indexBuilder = $this->getIndexBuilder();
      $parser = $this->invokeMethod($indexBuilder, "parseSettingsFile", [__DIR__ . "/", $_SERVER["INDEX_POSTS_TEST"]]);
      $this->assertIsArray($parser);
    }
}