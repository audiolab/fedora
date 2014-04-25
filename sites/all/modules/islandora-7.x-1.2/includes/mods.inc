<?php

/**
 * @file
 * Implements a simple class for working with MODS data.
 *
 * Inspiration and design shamelessly stolen from the pyfedora
 * project at http://pypi.python.org/pypi/pyfedora/0.1.0
 */

/**
 * MODS Class
 */
class Mods {

  public $mods = array(
    'titleInfo' => array(),
    'name' => array(),
    'genre' => array(),
    'originInfo' => array(),
    'language' => array(),
    'physicalDescription' => array(),
    'abstract' => array(),
    'subject' => array(),
    'relatedItem' => array(),
    'location' => array(),
    'typeOfResource' => array(),
    'tableOfContents' => array(),
    'targetAudience' => array(),
    'note' => array(),
    'classification' => array(),
    'identifier' => array(),
    'accessCondition' => array(),
    'part' => array(),
    'recordInfo' => array(),
  );

   public $mods_collection = array();

  public $owner;

  /**
   * Constructor.
   *
   * @param string $mods_xml
   */
  public function __construct($mods_xml = NULL) {
    if (!empty($mods_xml)) {
      $this->mods = self::importFromXMLString($mods_xml);
    }
  }

  /**
   * Add an element.
   *
   * @param string $element_name
   *   The name of the element to add.
   * @param string $value
   *   The value of the element to add.
   */
  public function addElement($element_name, $value) {
    if (is_string($value) && is_array($this->dc[$element_name])) {
      $this->dc[$element_name][] = $value;
    }
  }

  /**
   * Replace the given DC element with the given values.
   *
   * @param string $element_name
   *   The name of the elements to set.
   * @param mixed $values
   *   The values of the set the elements too.
   */
  public function setElement($element_name, $values) {
    if (is_array($values)) {
      $this->dc[$element_name] = $values;
    }
    elseif (is_string($values)) {
      $this->dc[$element_name] = array($values);
    }
  }

  /**
   * Serialize this object as an XML string.
   *
   * @return string
   *   The serialized XML.
   */
  public function asXML() {
    $dc_xml = new DomDocument();
    $oai_dc = $dc_xml->createElementNS('http://www.openarchives.org/OAI/2.0/oai_dc/', 'oai_dc:dc');
    $oai_dc->setAttribute('xmlns:dc', 'http://purl.org/dc/elements/1.1/');
    foreach ($this->dc as $dc_element => $values) {
      if (is_array($values) && !empty($values)) {
        foreach ($values as $value) {
          $new_item = $dc_xml->createElement($dc_element, $value);
          $oai_dc->appendChild($new_item);
        }
      }
      else {
        $new_item = $dc_xml->createElement($dc_element);
        $oai_dc->appendChild($new_item);
      }
    }
    $dc_xml->appendChild($oai_dc);
    return $dc_xml->saveXML();
  }

  /**
   * Serializes this object as an array.
   *
   * @return array
   *   The serialized object.
   */
  public function asArray() {
    $mods_array = array();
    foreach ($this as $element) {
      if (!empty($element)) {
        foreach ($element as $field => $values) {
          // Split value if the result value is an array.
          if (is_array($values)) {
            $value = '';
            $i = 0;
            foreach ($values as $piece) {
              if (!empty($piece)) {
                if ($i++) {
                  $value .= ", ";
                }
                $value .= $piece;
              }
            }
          }
          else {
            $value = $values;
          }
	  //dsm($field);
          //$mods_label = explode(':', $field);
	  //$mods_label = array($field);
          //$element_label = drupal_ucfirst($mods_label[1]);
          $element_label = drupal_ucfirst($field);
          $mods_array[$field]['label'] = $element_label;
          $mods_array[$field]['value'] = $value;
          $mods_array[$field]['class'] = drupal_strtolower(preg_replace('/[^A-Za-z0-9]/', '-', $field));
        }
      }
    }
    return $mods_array;
  }


  /**
   * Creates a new instance of the class by parsing mods_xml.
   *
   * @param string $dc_xml
   *
   */

  public static function importFromXMLStringOLD($mods_xml) {
    $mods_doc = new DomDocument();
    if (!empty($mods_xml) && $mods_doc->loadXML($mods_xml)) { 
      $oai_mods = $mods_doc->getElementsByTagNameNS('http://www.loc.gov/mods/v3', '*');
      $new_mods = new Mods();
      foreach ($oai_mods as $child) {
        if ( isset($new_mods->mods[$child->nodeName]) ) {
          array_push($new_mods->mods[$child->nodeName], $child->nodeValue);
        }
      }
      return $new_mods;
    }
    return NULL;
  }


  public static function importFromXMLString2($mods_xml) {
	$new_mods = new Mods();
	$mods_array = json_decode(json_encode((array)simplexml_load_string($mods_xml)),1);
	$new_mods->mods = $mods_array;
	return $new_mods;
  }

  public static function importFromXMLString3($mods_xml) {
	$new_mods = new Mods();
	$mods_array = array();
	$mods_dom = new DomDocument();
	$oai_mods = $mods_dom->getElementByTagNameNS('http://www.loc.gov/mods/v3','mods');
	foreach ($oai_mods as $e) {
		dpm($e);
	}
	return $new_mods;
  }

    public static function importFromXMLString($mods_xml) {
        $mods_doc = new DomDocument();
        if (!empty($mods_xml) && $mods_doc->loadXML($mods_xml)) {
            $oai_mods = $mods_doc->getElementsByTagNameNS('http://www.loc.gov/mods/v3', '*');
            $new_mods = new Mods();
            foreach ($oai_mods as $child) {
                if (isset($new_mods->mods[$child->nodeName])) {
                    $l = "und";
                    if ($child->hasAttribute('xml:lang')){
                        $l = $child->getAttribute('xml:lang');
                    }
                    $_v = Mods::recursiveParser($child);
                    if (is_array($_v)){
                        $new_mods->mods[$child->nodeName][$l][]= $_v;
                    }else{
                        $new_mods->mods[$child->nodeName][$l]['value'] = $_v;
                    }

                    if ($child->hasAttributes()){
                        foreach ($child->attributes as $attr){
                            if($attr->name != 'lang'){
                                $new_mods->mods[$child->nodeName][$l][count($new_mods->mods[$child->nodeName][$l]) - 1]["@" . $attr->name] = $attr->value;
                            }
                        }

                    }

                }
            }
            return $new_mods;
        }
        return NULL;
    }

    public static function recursiveParser($node_el){
        $childs = $node_el->childNodes;
        $ret = array();
        if($childs->length  > 1){
            foreach ($childs as $child){
                if($child->nodeType != XML_TEXT_NODE){
                    $l = "und";
                    if ($child->hasAttribute('xml:lang')){
                        $l = $child->getAttribute('xml:lang');
                    }
                    $_v = Mods::recursiveParser($child);
                    if (is_array($_v)){
                        $ret[$child->nodeName][$l] = $_v;
                    }else{
                        $ret[$child->nodeName]['value'] = $_v;
                    }
                    if ($child->hasAttributes()){
                        foreach ($child->attributes as $attr){
                            if($attr->name != 'lang'){
                                $ret[$child->nodeName]["@" . $attr->name] = $attr->value;
                            }
                        }

                    }
                }
            }
        }else{
            $ret = $node_el->nodeValue;
        }

        return $ret;

    }


}
