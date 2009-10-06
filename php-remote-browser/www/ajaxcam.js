function reload2() {
	var time = new Date().getTime();
	var	img = document.getElementById("ajaximg0");
	img.src = img.src + '#' + time;
}

/* Reload images on Ajax page when called. */
function reload2b() {
	var time = new Date().getTime();
	var tags = document.getElementsByTagName("img");
	for(var i = 0; i < tags.length; i++) {
		if(tags[i].className != "ajaximg") {
			continue;
		}
		tags[i].src = tags[i].src + '#' + time;
	}
}

/* Document Ready Function */
$(document).ready(function() {
	setInterval("reload2b()", 1500);
});


