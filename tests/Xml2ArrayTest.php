<?php

namespace Midnite81\Xml2Array\Tests;

use Illuminate\Support\Collection;
use Midnite81\Xml2Array\Xml2Array;
use Midnite81\Xml2Array\XmlResponse;
use Mockery;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;

class Xml2ArrayTest extends TestCase
{
    protected $xmlResponse;

    public function setUp()
    {
        $this->xmlResponse = $this->setupXml();
    }

    /**
     * @test
     */
    public function it_returns_a_response()
    {
        $this->assertInstanceOf(XmlResponse::class, $this->xmlResponse);
    }

    /**
     * @test
     * @expectedException \Midnite81\Xml2Array\Exceptions\IncorrectFormatException
     */
    public function it_throws_an_exception_when_not_valid_xml()
    {
        $xmlResponse = Xml2Array::create('invalid xml');
    }

    /**
     * @test
     */
    public function it_returns_an_array()
    {

        $xmlResponseArray = $this->xmlResponse->toArray();

        $this->assertInternalType('array', $xmlResponseArray);
        $this->assertArrayHasKey('food', $xmlResponseArray);
        $this->assertArrayHasKey('name', $xmlResponseArray['food']);
        $this->assertArrayHasKey('price', $xmlResponseArray['food']);
        $this->assertArrayHasKey('description', $xmlResponseArray['food']);
        $this->assertArrayHasKey('calories', $xmlResponseArray['food']);
    }

    /**
     * @test
     */
    public function it_returns_valid_json()
    {

        $xmlResponseJson = $this->xmlResponse->toJson();
        $decodedJson = json_decode($xmlResponseJson);

        $this->assertJson($xmlResponseJson);
        $this->objectHasAttribute('food', $decodedJson);
        $this->objectHasAttribute('food', $decodedJson);
        $this->objectHasAttribute('name', $decodedJson->food);
        $this->objectHasAttribute('price', $decodedJson->food);
        $this->objectHasAttribute('description', $decodedJson->food);
        $this->objectHasAttribute('calories', $decodedJson->food);
    }

    /**
     * @test
     */
    public function it_returns_to_a_laravel_collection()
    {
        $xmlResponseCollection = $this->xmlResponse->toCollection();

        $this->assertInstanceOf(Collection::class, $xmlResponseCollection);
        $this->assertInternalType('array', $xmlResponseCollection->get('food'));
        $this->assertEquals('Belgian Waffles', $xmlResponseCollection->get('food')['name']);
    }
    
    /**
     * @test
     */
    public function it_returns_a_serialised_object()
    {
        $xmlResponseSerialized = $this->xmlResponse->serialize();

        $this->assertInternalType('string', $xmlResponseSerialized);
        $this->assertContains('Belgian Waffles', $xmlResponseSerialized);
    }

    /**
     * @test
     */
    public function it_returns_an_aliased_serialised_object()
    {
        $xmlResponseSerialized = $this->xmlResponse->serialise();

        $this->assertInternalType('string', $xmlResponseSerialized);
        $this->assertContains('Belgian Waffles', $xmlResponseSerialized);
    }

    /**
     * @test
     */
    public function it_returns_to_string_correctly()
    {

        $decodedJson = json_decode($this->xmlResponse . '');

        $this->assertJson($this->xmlResponse . '');
        $this->objectHasAttribute('food', $decodedJson);
        $this->objectHasAttribute('food', $decodedJson);
        $this->objectHasAttribute('name', $decodedJson->food);
        $this->objectHasAttribute('price', $decodedJson->food);
        $this->objectHasAttribute('description', $decodedJson->food);
        $this->objectHasAttribute('calories', $decodedJson->food);
    }

    /**
     * @test
     */
    public function it_returns_traversable()
    {
        $this->assertInstanceOf(\Traversable::class, $this->xmlResponse->getIterator());
        $this->assertInstanceOf(\ArrayIterator::class, $this->xmlResponse->getIterator());
    }

    /**
     * @test
     */
    public function it_offset_exists_correctly()
    {
        $this->assertTrue($this->xmlResponse->offsetExists('food'));
    }

    /**
     * @test
     */
    public function it_gets_offset_correctly()
    {
        $this->assertEquals('Belgian Waffles', $this->xmlResponse->offsetGet('food')['name']);
    }


    protected function sampleXml()
    {
        return <<<xml
                <breakfast_menu>
                    <food>
                        <name>Belgian Waffles</name>
                        <price>$5.95</price>
                        <description>
                        Two of our famous Belgian Waffles with plenty of real maple syrup
                        </description>
                        <calories>650</calories>
                    </food>
                </breakfast_menu>
xml;

    }

    /**
     * @return Xml2Array
     */
    protected function setupXml()
    {
        return Xml2Array::create($this->sampleXml());
    }
}