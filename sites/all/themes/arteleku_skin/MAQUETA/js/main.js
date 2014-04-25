

var reScale = function(){
	var biggestHeight = 0;
	console.log("Hemos entrado")
	
	$('#content>div').each(function (index, Element) {
		
		if((!$(this).hasClass('clear')) && ($(this).css('position')=='absolute')){
			var p = $(this).outerHeight() + parseInt($(this).css('top'), 10);	
			console.log($(this).outerHeight());	
			if(p > biggestHeight){
				biggestHeight = p;
				console.log(this);
			}
		
		}
	});

	var altura = parseInt($('#content').css('height'), 10);
	if (altura != biggestHeight){
		$('#content').css('height', biggestHeight);	
	}




}


$(document).ready(function(){
	reScale();
	$(window).resize(function(){reScale();});
});
