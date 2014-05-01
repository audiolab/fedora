(function ($) {
    Drupal.behaviors.artelekuFormElementAuthority = {
       
		authority: {
			registerSelect: function(){
				 
				$('div.form-type-authorities').each(function(){
					var $sel = $('select.select2', this)
					
					$sel.select2({});
					
					$sel.on('select2-selecting', function(e){
						var tID = e.val;
						$("option[value='" + tID + "']", this).attr('selected', 'selected');
						var id = '#' + this.dataset.hash + '-add';
						$(id).trigger('mousedown');
					});
					
					var seleccionados = [];
					
					$('.hidden-tags input[type="hidden"]').each(function(){
						if (this.dataset.tid != ''){
							seleccionados.push(this.dataset.tid);
						}
					});
						
					$sel.select2('val', seleccionados);	
				
				});
				
				
				
			} //registerSelect
		},
		
		attach: function(context, settings) {
        	this.authority.registerSelect();
			
		}
}
})(jQuery);
	  
