//Markdown Parsing
function mdParse() {
	var myTxt = document.getElementById('MDText').value;
	document.getElementById('HTMLText').innerHTML = marked(myTxt);
	document.getElementById('Preview').innerHTML = marked(myTxt);
}	
//Strikethrough and gray out line if the item is checked
function mdFormat() {
	$("li:has(input:checked)").css("text-decoration", "line-through").css("font-style", "italic").css("color", "gray");
	$("li:has(input[type='checkbox'])").css("list-style-type", "none").css("margin-left", "-25px");
}

//call functions
function mdStart() {
	mdParse();
	mdFormat();
}
