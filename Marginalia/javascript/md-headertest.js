$(document).ready(function(){
	//test Note title length
	$('#Notebook').keyup(function(){
		var el = $(this);
		var value = el.val().length;
		if (value > 49) {
			alert ("Your Notebook name must be less than 50 characters");
			el.addClass("text-danger")
		} else if (value < 49) {
			el.removeClass("text-danger")
		} 
	});

	//test Note title length
	$('#Title').keyup(function(){
		var el = $(this);
		var value = el.val().length;
		if (value > 49) {
			alert ("Your Note Title must be less than 50 characters");
			el.addClass("text-danger")
		} else if (value < 49) {
			el.removeClass("text-danger")
		} 
	});
	
});