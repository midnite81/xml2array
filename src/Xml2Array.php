<?php

namespace Midnite81\Xml2Array;

use DOMDocument;
use Exception;
use Midnite81\Xml2Array\Exceptions\IncorrectFormatException;

class Xml2Array
{
    /**
     * Factory create method
     *
     * @param $xml
     * @return XmlResponse
     * @throws Exception
     */
    public static function create($xml): XmlResponse
    {
        return (new static())->convert($xml);
    }

    /**
     * Convert XML to Array
     *
     * @param $xml
     * @return XmlResponse
     * @throws Exception
     */
    public function convert($xml): XmlResponse
    {
        if (! is_string($xml) || !str_starts_with(trim($xml), '<')) {
            throw new IncorrectFormatException('XML passed must be a string');
        }

        return new XmlResponse($this->domConvert($xml));
    }

    /**
     * Convert XML
     *
     * @param $xmlString
     * @return array|string
     */
    protected function domConvert($xmlString): array|string
    {
        $doc = new DOMDocument();
        $doc->loadXML($xmlString);
        $root = $doc->documentElement;
        $output = $this->domNodeToArray($root);
        $output['@root'] = $root->tagName;

        return $output;
    }

    /**
     * Internal conversion for node to array
     *
     * @param $node
     * @return array|string
     */
    protected function domNodeToArray($node): array|string
    {
        $output = array();
        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;
            case XML_ELEMENT_NODE:
                for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
                    $child = $node->childNodes->item($i);
                    $v = $this->domNodeToArray($child);
                    if(isset($child->tagName)) {
                        $t = $child->tagName;
                        if(! isset($output[$t])) {
                            $output[$t] = array();
                        }
                        $output[$t][] = $v;
                    }
                    elseif($v || $v === '0') {
                        $output = (string) $v;
                    }
                }
                if($this->hasAttributesButIsNotArray($node, $output)) {
                    $output = ['@content'=>$output]; //Change output into an array.
                }
                if(is_array($output)) {
                    if($node->attributes->length) {
                        $internalAttributes = [];
                        foreach($node->attributes as $attrName => $attrNode) {
                            $internalAttributes[$attrName] = (string) $attrNode->value;
                        }
                        $output['@attributes'] = $internalAttributes;
                    }
                    foreach ($output as $t => $v) {
                        if(is_array($v) && count($v) == 1 && $t != '@attributes') {
                            $output[$t] = $v[0];
                        }
                    }
                }
                break;
        }
        return $output;
    }

    /**
     * @param $node
     * @param array|string $output
     * @return bool
     */
    protected function hasAttributesButIsNotArray($node, array|string $output): bool
    {
        return $node->attributes->length && !is_array($output);
    }
}