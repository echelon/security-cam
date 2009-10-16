// TODO: Remove dependency on jQuery (--but why?)

/**
 * List of cameras on the current page.
 * GLOBAL.
 */
cameras = new Array();

/**
 * Timeout reload time in seconds. When an image can't be fetched, this is how 
 * long the script will wait before re-requesting the image.
 * GLOBAL.
 */
RELOAD_TIMEOUT = 10;

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
 * Camera Image class. Subclasses the Image() builtin.
 * Supply the original image as the parameter. 
 */
function CamImage(orig) 
{
	/**
	 * The DOM id.
	 */
	this.camId = orig.id;

	/**
	 * Original image.
	 */
	this.orig = orig;

	/**
	 * Last request time.
	 */
	this.lastReqTime = null;

	/**
	 * Last request loaded properly?
	 */
	this.isLastReqLoaded = true; // TODO: Don't need both of these
	this.calledToLoad = false;

	/**
	 * Mutex for member variables (*namely* isLastReqLoaded).
	 * I think this is required since onload() is a callback and setTimeout is
	 * likely threaded in the browser Javascript implementation. 
	 */
	this.mutex = 0;

	/**
	 * Onload callback.
	 * Updates the time display.
	 */
	this.onload = function() 
	{
		var time = new Date();
		$("#"+this.camId).parents("span.cam_block").children(
						"span.cam_info2").text(time.formatted());

		this.isLastReqLoaded = true;
		this.calledToLoad = false;
		this.lastReqTime = 0;
	};

	/**
	 * Get new uri.
	 * Appends a new time hash to the current uri.
	 */
	this.getNewUri = function() 
	{
		var time = new Date().getTime();
		var uri = uriRemoveHash(this.src);
		if(!uri) {
			uri = this.src;
		}
		return uri + '#' + time;
	};

	/**
	 * Updates camera feed.
	 * Only updates if image has been loaded.
	 */
	this.update = function() 
	{
		var now = new Date().getTime()/1000;

		if(!this.isLastReqLoaded && 
		//if(this.calledToLoad) { // &&
			(now - this.lastReqTime < RELOAD_TIMEOUT)) {
				return;
		}
		this.calledToLoad = true;
		this.isLastReqLoaded = false;

		this.lastReqTime = now;
		this.src = this.orig.src;
		this.orig.src = this.getNewUri();

		// Onload callback
		var self = this;
		this.orig.onload = function() { self.onload(); }; // closure
	};
}
CamImage.prototype = new Image();


/**
 * Timer reload
 */
function timedReload() {
	//setTimeout("timedReload()", 1000);
	for(var i = 0; i < cameras.length; i++) {
		cameras[i].update();
	}
}

/**
 * Look for cameras in the HTML and add to cameras global array.
 * Only perform once.
 */
function scanCameras() 
{
	if(cameras.length > 0) {
		return;
	}
	var imgs = document.getElementsByTagName("img");
	for(var i = 0; i < imgs.length; i++) {
		if(imgs[i].className != "cam_img") {
			continue;
		}
		var camImg = new CamImage(imgs[i]);
		cameras.push(camImg);
	}
}

/**
 * Document Ready function.
 */
$(document).ready(function() {
	scanCameras();
	setInterval("timedReload()", 1000);
});

/**
 * Change cookies for "local".
 */
function checkboxLocal()
{
	if(document.getElementById("checkbox_local").checked) {
		$.cookie("local", "1", { expires: 10 });
	}
	else {
		$.cookie("local", "0", { expires: 10 });
	}
}

/**
 * Change cookies for "firewall".
 */
function checkboxFirewall()
{
	if(document.getElementById("checkbox_firewall").checked) {
		$.cookie("firewall", "1", { expires: 10 });
	}
	else {
		$.cookie("firewall", "0", { expires: 10 });
	}
}

