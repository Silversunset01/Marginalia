$(document).ready(function(){
//Show/Hide preview
    $("#togglePreview").click(function(){
        $("#Preview").toggle();
		var test = $("#MDInput").hasClass("float-left");
		if (test) {
			$("#MDInput").removeClass("w-50");
			$("#MDInput").addClass("w-100");
			$("#MDInput").removeClass("float-left");		
			$("#Preview").removeClass("w-50");
			$("#Preview").removeClass("pl-2");
			$("#Preview").removeClass("float-right");		
		} else {
			$("#MDInput").removeClass("w-100");
			$("#MDInput").addClass("w-50");
			$("#MDInput").addClass("float-left");
			$("#Preview").addClass("w-50");
			$("#Preview").addClass("pl-2");
			$("#Preview").addClass("float-right");			
		}
    });
});