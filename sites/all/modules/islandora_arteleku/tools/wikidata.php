<?php

class wikiData {

	var $language="eu";
	var $type='item';
	var $limit='50';	

	function searchEntities($query,$continue=0) {
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_URL,'https://en.wikidata.org/w/api.php');

		// SEARH
		$data = array(
			'action' => 'wbsearchentities',
			'search' => $query,
			'language' => $this->language,
			'format' => 'json',
			'limit' => $this->limit,
			'type' => $this->type,
			'continue' => $continue
		);

		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		$json = curl_exec($ch);
		
		$results = json_decode($json,true);

		if (isset($results['search-continue'])) {
			$offset = $results['search-continue'];
			$r0 = new arrayObject($results['search']);
			$r = array_merge((array) $r0, (array) $this->searchEntities($query,$continue=$offset));
		} else {
			$r = new arrayObject($results['search']);	
		}
		
		return new arrayObject($r);
	}

	function getEntities($ids) {
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_URL,'https://en.wikidata.org/w/api.php');

		// get id
		// ?action=wbgetentities&ids=Q42
		$data = array(
			'action' => 'wbgetentities',
			'format' => 'json',
			'ids' => $ids,
			'props' => 'aliases|info'
		);

		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		$json = curl_exec($ch);
		
		$results = json_decode($json,true);

		return new arrayObject($results['entities']);
	}
}


if (isset($argv)) :
	if (isset($argv[1])) $query = $argv[1]; else $query="audiolab"; 
	$wd = new wikiData();
	print_entities($wd,$query);
endif;


function print_entities($wd,$query) {
	$it = $wd->searchEntities($query)->getIterator();
	foreach ($it as $item) :
		print $item['id']." | ".$item['label']."\n";
		//var_dump($wd->getEntities($item['id']));
		$ite = $wd->getEntities($item['id'])->getIterator();
		foreach ($ite as $entity) :
			print $entity['id']."|".$entity['modified']."|\n";
			//check de los alias es,en,eu
			if(isset($entity['aliases']['es'])) : 
				print "CASTELLANO\n";
				$aliases = new arrayObject($entity['aliases']['es']);
				foreach ($aliases as $alias):
					print $alias['value']."\n";
				endforeach;
			endif;
			if(isset($entity['aliases']['en'])) : 
				print "ENGLISH\n";
				$aliases = new arrayObject($entity['aliases']['en']);
				foreach ($aliases as $alias):
					print $alias['value']."\n";
				endforeach;
			endif;
			if(isset($entity['aliases']['eu'])) : 
				print "EUSKERA\n";
				$aliases = new arrayObject($entity['aliases']['eu']);
				foreach ($aliases as $alias):
					print $alias['value']."\n";
				endforeach;
			endif;
		endforeach;
		print "\n";	
	endforeach;
}


?>
