/**
 * @file
 * Javascript file for islandora solr search facets
 */

(function ($) {

  // Adds facet toggle functionality
  Drupal.behaviors.islandoraSolrAjax = {
    attach: function(context, settings) {
	$('.solr-facet-link', context).each(function(index, element){
			$(this).click(function (e){
				$.ajax({
					url:this.href,
					success: function(response){
						console.log(response);
						$('#solr-ajax-display', context).html(response.data);
					}
				});	
				e.preventDefault();	
			});
		});
	

	}//attach
    }

})(jQuery);
