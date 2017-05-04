var debounce = function (func, threshold, execAsap) {
	var timeout;
	return function debounced () {
		var obj = this, args = arguments;
		function delayed () {
			if (!execAsap)
				func.apply(obj, args);
			timeout = null; 
		};
 
		if (timeout)
			clearTimeout(timeout);
		else if (execAsap)
			func.apply(obj, args);
 
		timeout = setTimeout(delayed, threshold || 100); 
	};
};
jQuery( document ).ready(function() {
	
	jQuery( document ).click(function() {
		set_iframe_height();
	});
	var iframe = get_iframe();
	jQuery( iframe ).load(function() {
		set_iframe_height();
	});
});
function calculate_iframe_height() {
	var height1 = jQuery("html").height();
	var height2 = jQuery("body > div").height();
	//alert(height1 + " " + height2);
	if(height1>height2)
		return height1;
	else
		return height2;
}
function get_iframe() {
	return jQuery("iframe", top.document);
}
var last_h = 0;
function set_iframe_height() {
	var h      = calculate_iframe_height();
	if(h>last_h) {
		var iframe = get_iframe();
		iframe.animate({ height: h});
		last_h = h;
	}
}
window.onerror = function(e) {
	console.log(e);
}