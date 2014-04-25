<?php

/**
 * @file
 * islandora-solr-facet-pages-wrapper.tpl.php
 * Islandora solr wrapper template file to include the letterer pager, numerical
 * pager and facet results
 *
 * Variables available:
 * - $variables: all array elements of $variables can be used as a variable. e.g. $base_url equals $variables['base_url']
 * - $base_url: The base url of the current website. eg: http://example.com .
 * - $user: The user object.
 *
 * - $letterer: Rendered alphabetical pager
 * - $pager: Rendered numerical pager
 * - $results: Rendered list of facet values
 *
 */
?>
<div class="cuatro-columnas-adaptativo">
	<div class="islandora-solr-facet-pages-wrapper">
	 <div id="primera-adaptativo"><div id="solr-ajax-display"> <?php print $results; ?></div> </div>
	 <div id="subcolumna"><div id="solr-ajax-facets"> <?php print $facets; ?></div> </div>
	</div>
</div>

