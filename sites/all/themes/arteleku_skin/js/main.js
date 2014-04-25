
// Using the closure to map jQuery to $.
(function ($) {
// Store our function as a property of Drupal.behaviors.
Drupal.behaviors.arteleku_skin = {
  attach: function (context, settings) {
  	var no_flow = $('.no-footer-flow');
  	if (no_flow.length == 0){
  		registerResponsiveJS();
  	}
  	mobileMenu();

	}
};

var mobileMenu = function () {
  	enquire.register("screen and (max-width: 569px)", {

		  match : function() {
		  	$('#block-system-main-menu .menu').hide();
		  },

		  unmatch : function() {
				$('#block-system-main-menu .menu').show();
		  },

		  setup : function() {
		  	$('#menu-mobile a').click(function(e){
		  		e.preventDefault();
		  		$('#block-system-main-menu .menu').toggle();
		  	});
		  },
		}).listen().fire();
}

var registerResponsiveJS = function (){
  	enquire.register("screen and (min-width: 1200px)", {
		  match : function() {
		  	posicionadoFooter();
		  },
		  unmatch : function() {
		  	$('div#content').css('height', 'auto')
		  },
		}).listen().fire();

		enquire.register("screen and (min-width: 992px) and (max-width: 1199px)", {
		  match : function() {
		  	posicionadoFooter();
		  },
		  unmatch : function() {
		  	$('div#content').css('height', 'auto')
		  },
		}).listen().fire();

  	enquire.register("screen and (min-width: 770px) and (max-width: 991px)", {
		  match : function() {
		  	posicionadoFooter();
		  },
		  unmatch : function() {
		  	$('div#content').css('height', 'auto')
		  },
		}).listen().fire();
}


var posicionadoFooter = function(){
		var container = $('div#content');
		var containerTop = container.offset().top;
		var containerHeight = container.height();
		var bloques = $('.floating-panel', container)
		var maxHeight = 0;
		bloques.each(function (index, Element){
			var bloqueTop = $(Element).offset().top - containerTop;
			var bloqueHeight = $(Element).outerHeight();
			var altura = bloqueHeight + bloqueTop;
			if (altura > maxHeight){
				maxHeight = altura;
			}
		});
		if (maxHeight){
			container.css('height', maxHeight);
		}
};

}(jQuery));

