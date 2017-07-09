<?php
namespace tests\unit;

use Api\Infrastructure\Serializer\JsonSerializer;
use PHPUnit\Framework\TestCase;

class JsonSerializerTest extends TestCase
{
    private $serializer;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->serializer = new JsonSerializer();
    }

    /**
     * @test
     */
    public function checkSerialize()
    {
        $this->assertEquals(json_encode($this->stub()), $this->serializer->serialize($this->stub()));
    }

    /**
     * @test
     */
    public function checkUnserialize()
    {
        $this->assertEquals(json_decode($this->jsonStub(), true), $this->serializer->unserialize($this->jsonStub()));
    }

    private function stub()
    {
        return ['some_key' => 'some_value'];
    }

    private function jsonStub()
    {
        return '{"some_key":"some_value"}';
    }
}