// Objects to access chart data. 
var charts        = {};
//var tables        = {};
var gtables       = {};
var options       = {};
//var actions       = {};
var map_functions = {};
var actions_store = {};
var element_store = {};

// Load Google Geochart API
google.load('visualization', '1', {'packages': ['geochart']});
google.setOnLoadCallback(drawMarkersMap);

// OnLoad Callback Function
function drawMarkersMap() {
	var _map_click = map_click;
	jQuery(".interactive_map_finder").each(function() {
		var id     = jQuery(this).attr("imap");
		var css_id = jQuery(this).attr("id");
		var __map_click = _map_click;
		
		var table   = map_table_preloader();
		var opt     = map_options_preloader();
		if(table !== null) {
			map_generator["maps"][id]["gtable"] = table;
		}
		if(opt !== null) {
			map_generator["maps"][id]["options"] = opt;
		}
		
		gtables[id] = new google.visualization.DataTable( map_generator["maps"][id]["gtable"] );
		charts[id]  = new google.visualization.GeoChart(document.getElementById(css_id));
		charts[id].draw(gtables[id], map_generator["maps"][id]["options"]);
		var _chart = charts[id];
		
		// Callbacks for preview
		google.visualization.events.addListener(charts[id], 'select', function() {
			var selection = _chart.getSelection();
			if(selection.length == 1) {
				interactive_map_select_event(selection[0].row);
			}
		});
		google.visualization.events.addListener(charts[id], 'regionClick', function(e) { 
			interactive_map_regionClick_event(e.region); 
		});
		google.visualization.events.addListener(charts[id], 'ready', function() {
			interactive_map_ready_event();
		});
		google.visualization.events.addListener(charts[id], 'error', function(id, msg) {
			//alert("Error");
		});
		
		// Callback for click action
		google.visualization.events.addListener(charts[id], 'select', function() {
			if(check_to_run_click_action()) {
				
				//map_functions[id]();
				__map_click(id);
			}
		});
		
		actions_store[id] = null;
		element_store[id] = new Object();
		
		// Add class if map is using html tooltips
		if(map_generator["maps"][id]["options"]["tooltip"]["isHtml"] == "true") {
			jQuery(this).addClass("with_html_tooltips");
		}
	});
	  
};


var line_start = null;

var map_click = function(id) {
	var $=null;
	if(typeof jQuery !== "undefined") $=jQuery;
	
	var selection = charts[id].getSelection();
	if (selection.length == 1) {
		var i            = selection[0].row;
		var click_values = map_generator["maps"][id]["actions"];
		var click_value  = click_values[i];
		var cssid        = map_generator["maps"][id]["cssid"];
		var map_storage  = actions_store[id];
		if(typeof element_store[id][i] == "undefined") {
			element_store[id][i] = null;
		}
		var storage     = element_store[id][i];
		
		if(line_start===null && typeof (new Error()).lineNumber  === "number") {
			line_start=(new Error()).lineNumber + 3;
		}
		try {
			eval(map_generator["maps"][id]["javascript"]);
			console.log("here");
		} catch(err) {
			var line_end = null;
			var line     = null;
			if(typeof err.lineNumber  === "number") {
				line_end = err.lineNumber;
				line     = line_end - line_start+1;
			}
			interactive_map_code_error(err, line, {});
		}
		actions_store[id]    = map_storage;
		element_store[id][i] = storage;
		
		//charts[id].setSelection(null);
	}
}


// Debounce Function for the redraw of the map.
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

// Resize map if the window is resized
jQuery( window ).resize(debounce(function() {
	jQuery.each(charts, function(id, key) {
		charts[id].draw(gtables[id], map_generator["maps"][id]["options"]);
	});
	//drawMarkersMap();
}, 250, false));

function test_top_function_exists(name) {
	try {
		if(typeof top == "object" && typeof top[name] == "function") {
			return true;
		}
	} catch(err) {
		return false;
	}
	return false;
}

function interactive_map_select_event(i) {
	//if(typeof top == "object" && typeof top.receive_map_event == "function") {
	if(test_top_function_exists("receive_map_event")) {
		top.receive_map_event(i);
	}
}

function interactive_map_regionClick_event(i) {
	//if(typeof top == "object" && typeof top.open_dialog == "function") {
	if(test_top_function_exists("open_dialog")) {
		top.open_dialog(i);
	}
}

function interactive_map_ready_event() {
	//if(typeof top == "object" && typeof top.receive_map_ready_event == "function") {
	if(test_top_function_exists("receive_map_ready_event")) {
		top.receive_map_ready_event();
	}
	if(typeof set_iframe_height == "function") {
		set_iframe_height();
	}
}

function interactive_map_code_error(error, line, state) {
	//if(typeof top == "object" && typeof top.get_code_error == "function") {
	if(test_top_function_exists("get_code_error")) {
		top.get_code_error(error, line, state);
	}
}

function map_table_preloader() {
	var table = null;
	//if(typeof top == "object" && typeof top.get_map_table == "function") {
	if(test_top_function_exists("get_map_table")) {
		table = top.get_map_table();
	}
	return table;
}

function map_options_preloader() {
	var table = null;
	//if(typeof top == "object" && typeof top.get_map_options == "function") {
	if(test_top_function_exists("get_map_options")) {
		table = top.get_map_options();
	}
	return table;
}

function check_to_run_click_action() {
	//if(typeof top == "object" && typeof top.has_to_run_click_action == "function") {
	if(test_top_function_exists("has_to_run_click_action")) {
		return top.has_to_run_click_action();
	}
	return true;
}

