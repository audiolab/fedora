<?php

// Acceso a los datos de la Library of Congress 
// http://id.loc.gov/vocabulary/relators.json

class lOC {

	var $language="eu";
	var $type='item';
	var $limit='50';	
	var $url='http://id.loc.gov/vocabulary/relators.json';

	function getRelators() {
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_URL,$this->url);

		$json = curl_exec($ch);
		
		$results = json_decode($json,true);
		
		return new arrayObject($results);	
		//return new arrayObject($results["<http://id.loc.gov/vocabulary/relators/abr>"]);
	}
}


if (isset($argv)) :
	if (isset($argv[1])) $query = $argv[1]; else $query="audiolab"; 
	$l = new lOC();
	//print_entities($l,$query);
	print_relators($l->getRelators());
endif;


function print_relators($r) {
	$it = $r->getIterator();
	foreach ($it as $rs):
		//print_r(array_keys($rs));
		//print_r($rs["<http://www.loc.gov/mads/rdf/v1#authoritativeLabel>"][0]);
		if (isset($rs["<http://www.loc.gov/mads/rdf/v1#authoritativeLabel>"][0])):
			print $rs["<http://www.loc.gov/mads/rdf/v1#authoritativeLabel>"][0]["value"]."\n";
		endif;
	endforeach;
}


?>
