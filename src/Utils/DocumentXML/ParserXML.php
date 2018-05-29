<?php
namespace DocumentXML;

use DOMDocument;

/**
 * Convert XML to array and SOAP XML to array
 *
 * Author: Sapan Kumar Mohanty
 * @link https://github.com/sapankumarmohanty/lamp
 *
 * @param  string  $contents
 * @param  integer $get_attributes
 * @param  string  $priority
 *
 * @return array
 */

class ParserXML {

    public static function xml2array(string $contents, int $get_attributes = 1, string $priority = 'tag') : array
    {
        if (!$contents) return array();
        if (!function_exists('xml_parser_create')) {
            // print "'xml_parser_create()' function not found!";
            return array();
        }
        // Get the XML parser of PHP - PHP must have this module for the parser to work
        $parser = xml_parser_create('');
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); // http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($contents) , $xml_values);
        xml_parser_free($parser);
        if (!$xml_values) return [];

        // Initializations
        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();
        $current = & $xml_array; //Refference
        // Go through the tags.
        $repeated_tag_index = array(); //Multiple tags with same name will be turned into an array
        foreach($xml_values as $data) {
            unset($attributes, $value); //Remove existing values, or there will be trouble
            // This command will extract these variables into the foreach scope
            // tag(string), type(string), level(int), attributes(array).
            extract($data); //We could use the array by itself, but this cooler.
            $result = array();
            $attributes_data = array();
            if (isset($value)) {
                if ($priority == 'tag') $result = $value;
                else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
            }
            // Set the attributes too.
            if (isset($attributes) and $get_attributes) {
                foreach($attributes as $attr => $val) {
                                    if ( $attr == 'ResStatus' ) {
                                        $current[$attr][] = $val;
                                    }
                    if ($priority == 'tag') $attributes_data[$attr] = $val;
                    else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
                }
            }
            // See tag status and do the needed.
                        //echo"<br/> Type:".$type;
            if ($type == "open") { //The starting of the tag '<tag>'
                $parent[$level - 1] = & $current;
                if (!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                    $current[$tag] = $result;
                    if ($attributes_data) $current[$tag . '_attr'] = $attributes_data;
                                        //print_r($current[$tag . '_attr']);
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    $current = & $current[$tag];
                }
                else { //There was another element with the same tag name
                    if (isset($current[$tag][0])) { //If there is a 0th element it is already an array
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                        $repeated_tag_index[$tag . '_' . $level]++;
                    }
                    else { //This section will make the value an array if multiple tags with the same name appear together
                        $current[$tag] = array(
                            $current[$tag],
                            $result
                        ); //This will combine the existing item and the new item together to make an array
                        $repeated_tag_index[$tag . '_' . $level] = 2;
                        if (isset($current[$tag . '_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset($current[$tag . '_attr']);
                        }
                    }
                    $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                    $current = & $current[$tag][$last_item_index];
                }
            }
            elseif ($type == "complete") { //Tags that ends in 1 line '<tag />'
                // See if the key is already taken.
                if (!isset($current[$tag])) { //New Key
                    $current[$tag] = $result;
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $attributes_data) $current[$tag . '_attr'] = $attributes_data;
                }
                else { //If taken, put all things inside a list(array)
                    if (isset($current[$tag][0]) and is_array($current[$tag])) { //If it is already an array...
                        // ...push the new element into that array.
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                        if ($priority == 'tag' and $get_attributes and $attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                        $repeated_tag_index[$tag . '_' . $level]++;
                    }
                    else { //If it is not an array...
                        $current[$tag] = array(
                            $current[$tag],
                            $result
                        ); //...Make it an array using using the existing value and the new value
                        $repeated_tag_index[$tag . '_' . $level] = 1;
                        if ($priority == 'tag' and $get_attributes) {
                            if (isset($current[$tag . '_attr'])) { //The attribute of the last(0th) tag must be moved as well
                                $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                                unset($current[$tag . '_attr']);
                            }
                            if ($attributes_data) {
                                $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                            }
                        }
                        $repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
                    }
                }
            }
            elseif ($type == 'close') { //End of tag '</tag>'
                $current = & $parent[$level - 1];
            }
        }
        return self::cleanEmptyArray($xml_array);
    }


    private static function cleanEmptyArray(array &$data)
    {
        foreach ($data as $key => &$value) {

            if (empty($value) && is_array($value)) {
                $value = '';
            } else {
                if (is_array($value)) $value = self::cleanEmptyArray($value);
            }
        }
        return $data;
    }

    /**
     * The main function for converting to an XML document.
     * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
     * Based on: http://snipplr.com/view/3491/convert-php-array-to-xml-or-simple-xml-object-if-you-wish/
     *
     * @param array $data
     * @param string $rootNodeName - what you want the root node to be - defaultsto data.
     * @param SimpleXMLElement $xml - should only be used recursively
     * @return string XML
     */
    public static function toXml($data, $rootNodeName = 'data', &$xml=null)
    {
        // turn off compatibility mode as simple xml throws a wobbly if you don't.
        if ( ini_get('zend.ze1_compatibility_mode') == 1 ) ini_set ( 'zend.ze1_compatibility_mode', 0 );
        if ( is_null( $xml ) ) {
/*
             $xml = simplexml_load_string(stripslashes("<?xml version='1.0' encoding='utf-8'?><root xmlns:example='http://example.namespace.com' version='1.0'></root>"));
*/
             $xml = simplexml_load_string(stripslashes("<Document-Invoice></Document-Invoice>"));

        }

        // loop through the data passed in.
        foreach( $data as $key => $value ) {

            // no numeric keys in our xml please!
            $numeric = false;
            if ( is_numeric( $key ) ) {
                $numeric = 1;
                $key = $rootNodeName;
            }

            // delete any char not allowed in XML element names
            $key = preg_replace('/[^a-z0-9\-\_\.\:]/i', '', $key);

            //check to see if there should be an attribute added (expecting to see _id_)
            $attrs = false;

            //if there are attributes in the array (denoted by attr_**) then add as XML attributes
            if ( is_array( $value ) ) {
                foreach($value as $i => $v ) {
                    $attr_start = false;
                    $attr_start = stripos($i, 'attr_');
                    if ($attr_start === 0) {
                        $attrs[substr($i, 5)] = $v; unset($value[$i]);
                    }
                }
            }


            // if there is another array found recursively call this function
            if ( is_array( $value ) ) {

                if ( ParserXML::is_assoc( $value ) || $numeric ) {

                    // older SimpleXMLElement Libraries do not have the addChild Method
                    if (method_exists('SimpleXMLElement','addChild'))
                    {
                        // $node = $xml->addChild( $key, null, 'http://www.lcc.arts.ac.uk/' );
                        $node = $xml->addChild( $key, null);
                        if ($attrs) {
                            foreach($attrs as $key => $attribute) {
                                $node->addAttribute($key, $attribute);
                            }
                        }
                    }

                }else{
                    $node =$xml;
                }

                // recrusive call.
                if ( $numeric ) $key = 'anon';
                ParserXML::toXml( $value, $key, $node );
            } else {

                    // older SimplXMLElement Libraries do not have the addChild Method
                    if (method_exists('SimpleXMLElement','addChild'))
                    {
                        // $childnode = $xml->addChild( $key, $value, 'http://www.lcc.arts.ac.uk/' );
                        $childnode = $xml->addChild( $key, $value );
                        if ($attrs) {
                            foreach($attrs as $key => $attribute) {
                                $childnode->addAttribute($key, $attribute);
                            }
                        }
                    }
            }
        }

        // pass back as unformatted XML
        //return $xml->asXML('data.xml');

        // if you want the XML to be formatted, use the below instead to return the XML
        $doc = new DOMDocument('1.0');
        $doc->preserveWhiteSpace = false;
        @$doc->loadXML( ParserXML::fixCDATA($xml->asXML()) );
        $doc->formatOutput = true;
        return $doc->saveXML();
        // return $doc->save('data.xml');
    }

    public static function fixCDATA($string) {
        //fix CDATA tags
        $find[]     = '&lt;![CDATA[';
        $replace[] = '<![CDATA[';
        $find[]     = ']]&gt;';
        $replace[] = ']]>';

        $string = str_ireplace($find, $replace, $string);
        return $string;
    }

    // determine if a variable is an associative array
    public static function is_assoc( $array ) {
        return (is_array($array) && 0 !== count(array_diff_key($array, array_keys(array_keys($array)))));
    }


}
