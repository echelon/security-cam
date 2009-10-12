// TODO: This isn't ajax.

/**
 * Remove URI hash
 * Removes any #foo... hash from the uri.
 */
function uriRemoveHash(uri)
{
	var regex = /([^#]*)#(.*)/gi; 
	var r = uri.match(regex);
	return RegExp.$1;
}

/**
 * Return formatted date (HH:MM:SS)
 * TODO: Add PHP-like format string.
 */
Date.prototype.formatted = function()
{
	var h = this.getHours();
	var m = this.getMinutes();
	var s = this.getSeconds();
	if(m < 10) {
		m = "0" + m;
	}
	if(s < 10) {
		s = "0" + s;
	}
	return h + ":" + m + ":" + s;
}


/**
 * Camera Image class
 * Subclasses the Image() builtin. 
 */
function CamImage() 
{
	/**
	 * The DOM ID. Must be set by the user.
	 */
	this.id = 0;

	/**
	 * The image source.
	 */
	//this.src = src;

	/**
	 * Onload callback.
	 * Updates the time display.  TODO: TEMP RENAME onload2
	 */
	this.onload = function() {
		var time = new Date();
		$("#"+this.id).parents("span.cam_block").children(
						"span.cam_info2").text(time.formatted());
	};

	/**
	 * Get new uri.
	 * Appends a time hash.
	 */
	/*this.getNewUri = function() {
		var time = new Date().getTime();
		var uri = uriRemoveHash(this.src);
		if(!uri) {
			uri = this.src;
		}
		return uri + '#' + time;
	};*/

	/**
	 * Updates camera feed.
	 */
	/*this.update = function() {
		this.src = this.getNewUri();
		this.onload("");
	};*/
}
CamImage.prototype = new Image();




/* Reload images on Ajax page when called. */
function reload2b() {
	var time = new Date().getTime();
	var imgs = document.getElementsByTagName("img");
	for(var i = 0; i < imgs.length; i++) {
		if(imgs[i].className != "cam_img") {
			continue;
		}

		var camImg = new CamImage();
		var uri = uriRemoveHash(imgs[i].src);

		if(!uri) {
			uri = imgs[i].src;
		}
		camImg.src = uri + '#' + time;
		//$("div#debug").html("Test "+camImg.src);

		camImg.id = imgs[i].id;
		//camImg.onload(""); // TODO: temp comment
		//imgs[i] = camImg; // TODO: temp comment
		imgs[i].src = camImg.src;
		imgs[i].onload = camImg.onload
		//img.update();
	}
}


/* Document Ready Function */
$(document).ready(function() {
	setInterval("reload2b()", 1500);
});


