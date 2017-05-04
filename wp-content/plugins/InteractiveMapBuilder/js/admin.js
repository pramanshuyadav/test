
var map_options   = null;

var options;
var default_options;

(function ($) {
	"use strict";
	
		$.fn.fitText = function() {
			this.each(function() {
				var _this = jQuery(this);
				
				if(typeof _this.data("fitText") === "undefined") {
					_this.data("fitText", _this.text());
				} else {
					_this.text(_this.data("fitText"));
				}
				
				var i     = 0;
				if(_this.text()=="")
					return;
				var divh = _this.parent().height();
				while (_this.outerHeight()>divh && 100>i++) {
					_this.text(function(index, text) {
						return text.replace(/\W*\s(\S)*$/, '...');
					});
				}
			});
			return this;
		};
		
		// Adding a tap event
		$(function () {
		
			$.event.special.tap = {
			// Abort tap if touch moves further than 10 pixels in any direction
			distanceThreshold: 10,
			// Abort tap if touch lasts longer than half a second
			timeThreshold: 500,
			setup: function() {
				var self = this,
				$self = $(self);

				// Bind touch start
				$self.on('touchstart', function(startEvent) {
					// Save the target element of the start event
					var target = startEvent.target,
					touchStart = startEvent.originalEvent.touches[0],
					startX = touchStart.pageX,
					startY = touchStart.pageY,
					threshold = $.event.special.tap.distanceThreshold,
					timeout;

					function removeTapHandler() {
						clearTimeout(timeout);
						$self.off('touchmove', moveHandler).off('touchend', tapHandler);
					};

					function tapHandler(endEvent) {
						removeTapHandler();

						// When the touch end event fires, check if the target of the
						// touch end is the same as the target of the start, and if
						// so, fire a click.
						if (target == endEvent.target) {
							$.event.simulate('tap', self, endEvent);
						}
					};

					// Remove tap and move handlers if the touch moves too far
					function moveHandler(moveEvent) {
						var touchMove = moveEvent.originalEvent.touches[0],
						moveX = touchMove.pageX,
						moveY = touchMove.pageY;

						if (Math.abs(moveX - startX) > threshold ||
							Math.abs(moveY - startY) > threshold) {
							removeTapHandler();
						}
					};

					// Remove the tap and move handlers if the timeout expires
					timeout = setTimeout(removeTapHandler, $.event.special.tap.timeThreshold);

					// When a touch starts, bind a touch end and touch move handler
					$self.on('touchmove', moveHandler).on('touchend', tapHandler);
				});
			}
		};
	
		// Debounce function
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

		// Adding a notouch class for non touch devices that enables some hover effects
		if(!("ontouchstart" in document.documentElement)) {
			jQuery(".wrap").addClass("notouch");
		}
		var close_all_more_indicators_except = function(element) {
			jQuery(".more_indicator").not(element).removeClass("touch");
		}
		jQuery(".wrap").on("touchstart", function(e) {
			//console.log("33");
			close_all_more_indicators_except(jQuery(e.target).parents(".more_indicator").add(e.target));
		});
		/*jQuery(".wrap").on("mousedown", function(e) {
			alert("asd");
		});*/
		jQuery(".more_indicator.option_select .value").click(function(e) {
			/*var width = $(this).attr("value");
			_this.set_width(width);
			$("#resize_box").css("width", width+"%");*/
			var parent = jQuery(e.target).parents(".more_indicator");
			parent.css("width", parent.width());
			parent.find("ul li").removeClass("selected");
			
			parent.find(".show_selected_here").text(jQuery(this).text());
			jQuery(this).addClass("selected");
			
		});
		jQuery(".more_indicator.option_multiselect .value").click(function(e) {
			/*var width = $(this).attr("value");
			_this.set_width(width);
			$("#resize_box").css("width", width+"%");*/
			//var parent = jQuery(e.target).parents(".more_indicator");
			//parent.css("width", parent.width());
			//parent.find("ul li").removeClass("selected");
			
			//parent.find(".show_selected_here").text(jQuery(this).text());
			jQuery(this).toggleClass("selected");
			
		});
		var connect_all_more_indicators = function() {
			jQuery(".more_indicator").off("touchstart").on("touchstart", function(e) {
				var target = jQuery(e.target);
				var me = jQuery(this);
				if(target.parents(".inner_body").length==0) {
					me.toggleClass("touch");
					e.preventDefault();
				}
			});
		};
		connect_all_more_indicators();
		
		if(jQuery("#new_maps_page").length!=0) {
			/*------------------------------------------------------------------------------
			 Map Builder Page
			 
			 0 - Find elements
			 1 - Functions
			 2 - Initializing
			 3 - Bind methods
			------------------------------------------------------------------------------*/
			
			/*------------------------------------------------------------------------------
			 0 - Find elements
			------------------------------------------------------------------------------*/
			
			var table        = jQuery("#data_table");
			var form         = jQuery(".data_table_form");	// Data Table form
			var arrow        = jQuery(".data_table_form_arrow");	// Form arrow
			var point        = table.find("tbody");
			var copy_row     = table.find(".clone_row");
			var click_action = jQuery("#template");
			
			var click_action_description   = jQuery("#template_description");
			var example_click_action_value = jQuery("#example_click_action_value");
			
			var preview     = new Preview_Handler(iframe_url, iframe_name, ".loader");
			var regions     = new Regions(default_options["region"]);
			var resolutions = new ResolutionMap(default_options["resolution"], regions.find_resolutions(regions.get_value()));
			var modes       = new DisplayMode(current_mode);
			var mapper      = new OptionMapper();
			
			var templates = page_config["click_actions"];
			
			jQuery.data( document.body, "loading_no", 0 );
			jQuery.data( document.body, "preview", preview);
			jQuery.data( document.body, "form",    form);
			jQuery.data( document.body, "table",   table);
			
			var elements = [
				["#mapstyle_bgcolor", "background_color", "color", null],
				["#mapstyle_datalesscolor", "dataless_regions_color", "color", null],
				["#mapstyle_height", "height", "number", "auto"],
				["#mapstyle_width", "width", "number", "auto"],
				["#mapstyle_aspectratio", "keep_aspect_ratio", "bool", null],
				["#mapstyle_interactivity", "enable_region_interactivity", "bool", null],
				["#mapstyle_bordersize", "border_width", "number", null],
				["#mapstyle_bordercolor", "border_color", "color", null],
				["#mapstyle_markersize", "marker_size", "number", null],
				["#mapstyle_markeropacity", "marker_opacity", "percent", null],
				["#show_tooltips", "tooltip_enable", "bool", null],
				["#tooltip_background_color", "tooltip_color", "color", null],
				["#tooltip_font_name", "tooltip_font_family", "string", "<global-font-name>"],
				["#tooltip_font_size", "tooltip_font_size", "number", "<global-font-size>"],
				["#tooltip_trigger", "tooltip_trigger", "string", "focus"],
				["#use_html", "tooltip_use_html", "bool", "false"],];
				
			var iframe = preview.get_iframe();
			
			
			/*------------------------------------------------------------------------------
			 1 - Functions
			------------------------------------------------------------------------------*/
			
			var table_to_map = function() {
				show_map_loader();
				var table     = construct_data_table_obj();
				var colors    = get_table_colors(table);
				if(current_mode=="textmode") {
					colors = [ "#000000" ];
				}
				var max_value = colors.length;
				var _gtable   = TableMapper(table, current_mode, colors);
				var actions   = get_action_values(table);
				console.log(table, colors, max_value, gtable, current_mode, actions);
				default_options["colorAxis"]["colors"]   = colors;
				if(max_value==0) {
					default_options["colorAxis"]["maxValue"] = 1;
				} else {
					default_options["colorAxis"]["maxValue"] = max_value;
				}
				gtable                                   = _gtable;
				click_actions                            = actions;
						
				invoke_redraw();
				
				/*jQuery.ajax({
					dataType: "json",
					url: imb_object.ajax_url,
					proccessData: true,
					type: "POST",
					data: {
						action: "translate_table",
						table:  construct_data_table_obj(),
						mode:   current_mode
					},
					success: function(data) {
						default_options["colorAxis"]["colors"]   = data.colors;
						default_options["colorAxis"]["maxValue"] = data.maxvalue;
						gtable                                   = data.table;
						click_actions                            = data.actions;
						invoke_redraw();
					},
					error: function(error) {
					
					},
					timeout: 15000
				});*/
			}
			
			/*var get_gtable = function(table, mode, colors) {
				TableMapper(table
			}*/
			var get_action_values = function(table) {
				var a = [];
				jQuery.each(table, function(key, row) {
					a.push(row["click_action_value"]);
				});
				return a;
			}
			
			var get_table_colors = function(table) {
				var colors = [];
				jQuery.each(table, function(key, row) {
					var color = row["color"];
					if(colors.indexOf(color)===-1) {
						colors.push(color);
					}
				});
				return colors;
			}
			
			// 1.1 - Map Functions
			var show_map_loader = function() {
				var _now = jQuery.data( document.body, "loading_no");
				_now++;
				jQuery.data( document.body, "loading_no", _now );
				
				preview.show_loader();
				var _preview = preview;
				setTimeout(function() {
					if(_now==jQuery.data( document.body, "loading_no")) {
						_preview.hide_loader();
					}
				},8000);
			}
			var invoke_redraw = function() {
				var _iframe = iframe[0].contentWindow;
				if(typeof _iframe.drawMarkersMap == "function") {
					show_map_loader();
					_iframe.drawMarkersMap();
				}
			}
			
			// 1.2 - Table Functions
			var open_dialog = function(location) {
				var link = jQuery(".add_to_table").eq(0);
				// Set Region/Marker-Text
				form.find("[name=location]").val(location);
				link.trigger("click")
			}
			var set_data_table_row = function(row, rm_column, lat, lng, color, tooltip_title, tooltip_text, clickaction_value) {		
				// Set Region/Marker-Text
				row.find(".rm_column").text(rm_column);
				
				// Set latitude and longitude
				row.find(".latitude").text(lat);
				row.find(".longitude").text(lng);
				
				// Set Color
				row.find(".color_circle").css("backgroundColor", color);
				
				// Text label
				row.find(".text_label").text(tooltip_title);
				
				// Set Tooltip
				row.find(".tooltip_title_cell").text(tooltip_title);
				row.find(".tooltip_text_cell").text(tooltip_text);
				
				// Set Click Value
				row.find(".clickaction_column").text(clickaction_value);
				
				return row;
			}
			var create_new_data_table_row = function(rm_column, lat, lng, color, tooltip_title, tooltip_text, clickaction_value) {
				return set_data_table_row(copy_row.clone().removeClass("clone_row"), rm_column, lat, lng, color, tooltip_title, tooltip_text, clickaction_value);
			}
			var set_form_data = function(rm_column, lat, lng, color, tooltip_title, tooltip_text, clickaction_value) {
				
				// Set Region/Marker-Text
				form.find("[name=location]").val(rm_column);
				
				// Set latitude and longitude
				form.find("[name=latitude]").val(lat);
				form.find("[name=longitude]").val(lng);
				
				// Set Color
				form.find("[name=form_color]").val(color).css("backgroundColor", color).css("color", newColor(hexToRgb(color)));
				
				// Set Tooltip
				form.find("[name=tooltip_title]").val(tooltip_title);
				form.find("[name=tooltip_text]").val(tooltip_text);
				
				// Set Click Value
				form.find("[name=click_action]").val(clickaction_value);
			}
			var position_form = function(form, row) {
				var top = form.offset().top - form.offsetParent().offset().top;
				// Is the box in the user view?
				{
					var SCREEN_PADDING_TOP    = 10 + jQuery("#wpadminbar").height();
					var SCREEN_PADDING_BOTTOM = 10;
					var BOX_BORDER            = 5;
					var BOX_PADDING_TOP       = 22 + BOX_BORDER;
					var BOX_PADDING_BOTTOM    = 54 + BOX_BORDER;
					
					var form_top      = form.position().top;
					var box_y_top     = form.offset().top - jQuery(document).scrollTop();
					var box_y_bottom  = box_y_top + BOX_BORDER + form.height() + BOX_BORDER;
					var row_y1        = row.offset().top - jQuery(document).scrollTop();
					var row_y2        = row_y1 + row.height();
					
					// 1 - Bottom correction
					var s = jQuery(window).height() - SCREEN_PADDING_BOTTOM - box_y_bottom;
					var c = (s < 0) ? s : 0;
					
					// 2 - Top correction
					s  = (box_y_top + c) - SCREEN_PADDING_TOP;
					c -= (s < 0) ? s : 0;
					
					// 3 - Row lower correction
					s  = (box_y_bottom + c)  - BOX_PADDING_BOTTOM - row_y2;
					c -= (s < 0) ? s : 0;
					
					// 4 - Row upper correction
					s  = row_y1 - (box_y_top + c + BOX_PADDING_TOP);
					c += (s < 0) ? s : 0;

					top = form_top + c;
					form.css("top",top);
				}
				return top;
			}
			var get_form_data = function() {
				var data = {};
				
				// Set Region/Marker-Text
				data["location"] = form.find("[name=location]").val();
				
				// Set latitude and longitude
				data["latitude"] = form.find("[name=latitude]").val();
				data["longitude"] = form.find("[name=longitude]").val();
				
				// Set Color
				data["color"] = form.find("[name=form_color]").val();
				
				// Set Tooltip
				data["tooltip_title"] = form.find("[name=tooltip_title]").val();
				data["tooltip_text"] = form.find("[name=tooltip_text]").val();
				
				// Set Click Value
				data["click_action_value"] = form.find("[name=click_action]").val();
				
				return data;
			}
			var is_in_delete_state = function(page) {
				if(page.hasClass("table_edit") || page.hasClass("table_add")) {
					return false;
				}
				return true;
			}
			var check_empty_table = function() {
				if(table.find("tbody tr").length==0) {
					table.find("tfoot").show();
				}
			}
			var update_table_count = function() {
				jQuery("#table_count").text(table.find("tbody tr").length);
			}
			var delete_data_table_entry = function(row, page, check_page_state) {
				if(check_page_state) {
					if(!is_in_delete_state(page)) {
						return;
					}
				}
				row.remove();
				check_empty_table();
				update_table_count();
				table_to_map();
			}
			var construct_data_table_obj = function() {
				var obj = [];
				table.find("tbody tr").each(function() {
					obj.push(jQuery(this).data("value"));
				});
				return obj.reverse();
			}
			
			// 2.2 - Helper function. Function that is binded to the click event of each row
			var row_function = function(e) {
				// Check for delete cell
				if(jQuery(e.target).closest("td").hasClass("delete_cell")) {
					// Delete this entry
					delete_data_table_entry(jQuery(this), jQuery("#new_maps_page"), true);
					return false;
				}
				
				// Set data to form
				var value = jQuery(this).data("value");
				set_form_data(value.location, value.latitude, value.longitude, value.color, value.tooltip_title, value.tooltip_text, value.click_action_value);
				
				// Get optimal box position
				var box_top = position_form(form, jQuery(this));

				// Set arrow position
				var arrow_top = jQuery(this).offset().top - jQuery(this).offsetParent().offset().top - box_top;
				
				// Set the new values
				arrow.css("top", arrow_top);
				//form.css("top",  box_top);
				
				var old_row = table.data("selected");
				if(old_row!==null) {
					old_row.removeClass("selected_row");
				}
				jQuery(this).addClass("selected_row");
				
				// Saving the selected row
				table.data("selected", jQuery(this));
				
				// Show form
				if(typeof setTimeout=="function") {
					setTimeout(function() {
						jQuery("#new_maps_page").removeClass("table_add").addClass("table_edit"); 
					},10);
				} else {
					jQuery("#new_maps_page").removeClass("table_add").addClass("table_edit"); 
				}
				
				//console.log(jQuery(e.target).closest("td"));
				
				e.preventDefault();
				return false;
			}
			var get_click_action_id = function() {
				return click_action.find(":selected").val();
			}
			var get_click_action_description = function() {
				var id = get_click_action_id();
				if(id==-2) {
					return jQuery("#custom_template_description").html();
				} else {
					return jQuery("#data_of_click_action_"+id).find(".description").html();
				}
			}
			var get_click_action_example = function() {
				var id = get_click_action_id();
				return jQuery("#data_of_click_action_"+id).find(".example").html();
			}
			var get_click_action_html = function() {
				var id = get_click_action_id();
				return jQuery("#data_of_click_action_"+id).find(".html").html();
			}
			var get_click_action_css = function() {
				var id = get_click_action_id();
				return jQuery("#data_of_click_action_"+id).find(".css").html();
			}
			var get_click_action_js = function() {
				var id = get_click_action_id();
				return jQuery("#data_of_click_action_"+id).find(".js").html();
			}
			var create_preview = function(width, height, callback) {
				var _iframe       = iframe[0].contentWindow;
				var chart         = new _iframe.google.visualization.GeoChart(document.getElementById("invisible_container"));
				var options       = jQuery.extend(true, {}, _iframe.map_generator["maps"][1]["options"]);
				options["height"] = height;
				options["width"]  = width;
				chart.draw(_iframe.gtables[1], options);
				_iframe.google.visualization.events.addListener(chart, 'ready', function() {
					callback(chart.getImageURI());
				});
			}
			
			// Calculates a new color
			var newColor = function(color) {
				var match   = /rgb\((\d+).*?(\d+).*?(\d+)\)/.exec(color);
				var r       = parseInt(match[1]);
				var g       = parseInt(match[2]);
				var b       = parseInt(match[3]);
				var temprgb = {r: r, g: g, b: b};
				var temphsv = RGB2HSV(temprgb);
				var avg     = (r+g+b)/3;
				var radius  = Math.sqrt(Math.pow(temphsv.value,2) + Math.pow(temphsv.saturation*(temphsv.value/100),2));
				if(radius >  30 + temphsv.saturation)
					return "rgb(" + 10 + "," + 10 + "," + 10 + ")";
				else
					return "rgb(" + 230 + "," + 230 + "," + 230 + ")";
			}
			var RGB2HSV = function(rgb) {
				var hsv            = new Object();
				var max            = max3(rgb.r,rgb.g,rgb.b);
				var dif            = max-min3(rgb.r,rgb.g,rgb.b);
				hsv.saturation     = (max==0.0)?0:(100*dif/max);
				if(hsv.saturation==0) hsv.hue = 0;
				else if(rgb.r==max)   hsv.hue = 60.0*(rgb.g-rgb.b)/dif;
				else if(rgb.g==max)   hsv.hue = 120.0+60.0*(rgb.b-rgb.r)/dif;
				else if(rgb.b==max)   hsv.hue = 240.0+60.0*(rgb.r-rgb.g)/dif;
				if(hsv.hue<0.0) hsv.hue+=360.0;
				hsv.value      = Math.round(max*100/255);
				hsv.hue        = Math.round(hsv.hue);
				hsv.saturation = Math.round(hsv.saturation);
				return hsv;
			}
			var min3 = function(a,b,c) { 
				return (a<b)?((a<c)?a:c):((b<c)?b:c); 
			}
			var max3 = function(a,b,c) { 
				return (a>b)?((a>c)?a:c):((b>c)?b:c); 
			}
			var hexToRgb = function(h) {
				var r = parseInt((cutHex(h)).substring(0,2),16), g = parseInt((cutHex(h)).substring(2,4),16), b = parseInt((cutHex(h)).substring(4,6),16)
				return "rgb(" + r + ',' + g + ',' + b + ")";
			}
			var cutHex = function(h) {
				return (h.charAt(0)=="#") ? h.substring(1,7):h
			}
			
			/*------------------------------------------------------------------------------
			 2 - Initializing
			------------------------------------------------------------------------------*/
			var html = document.getElementById("html_textarea");
			var js   = document.getElementById("js_textarea");
			var css  = document.getElementById("css_textarea");
			
			CodeMirror.defineMode("mustache", function(config, parserConfig) {
				var mustacheOverlay = {
					token: function(stream, state) {
						var ch;
						if (stream.match("{{")) {
							while ((ch = stream.next()) != null)
							if (ch == "}" && stream.next() == "}") break;
							stream.eat("}");
							return "mustache";
						}
						while (stream.next() != null && !stream.match("{{", false)) {}
						return null;
					}
				};
				return CodeMirror.overlayMode(CodeMirror.getMode(config, parserConfig.backdrop || "htmlmixed"), mustacheOverlay);
			});
			var html_editor = CodeMirror.fromTextArea(html, {
				lineNumbers: true,
				mode:  "mustache",
				autoCloseTags: true,
				matchTags: {bothTags: true},
				styleActiveLine: true,
				extraKeys: {
					"F11": function(cm) {
						cm.setOption("fullScreen", !cm.getOption("fullScreen"));
					},
					"Esc": function(cm) {
						if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
					}
				}
			});
			var js_editor = CodeMirror.fromTextArea(js, {
				lineNumbers: true,
				mode:  "javascript",
				styleActiveLine: true,
				extraKeys: {
					"F11": function(cm) {
						cm.setOption("fullScreen", !cm.getOption("fullScreen"));
					},
					"Esc": function(cm) {
						if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
					}
				}
			});
			var css_editor = CodeMirror.fromTextArea(css, {
				lineNumbers: true,
				mode:  "css",
				styleActiveLine: true,
				extraKeys: {
					"F11": function(cm) {
						cm.setOption("fullScreen", !cm.getOption("fullScreen"));
					},
					"Esc": function(cm) {
						if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
					}
				}
			});
			
			table.data("selected", null);		
			
			// 1.3 - Fill table
			//var dd_table = page_config["table"];
			var decoded_table = page_config["table"];
			var encoded_table = null;
			if(decoded_table!=="") {
				try {
					encoded_table = JSON.parse(decoded_table);
					
					if(encoded_table !== null && typeof encoded_table == "object") {
						var count = 0;
						$.each(encoded_table, function( index, value ) {
							var row = create_new_data_table_row(value.location, value.latitude, value.longitude, value.color, value.tooltip_title, value.tooltip_text, value.click_action_value);
							row.data("value", value);
							row.bind("click tap", row_function);
							point.prepend(row);
							table.find("tfoot").hide();
							count++;
						});
						jQuery("#table_count").text(count);
					} else {
						data_table = [];
					}
				} catch(e) {
					alert("There was an error when loading the map elements. Try to reload the map with F5. If the error still occurs, please contact us via info@meisterpixel.com.");
				}
			} else {
				data_table = [];
			}
			/*if(data_table !== null && typeof data_table == "object") {
				$.each(data_table, function( index, value ) {*/
			

			
			$.each(elements, function(i, element) {
				var _invoke_redraw = invoke_redraw;
				var _mapper     = mapper;
				var selector    = element[0];
				var option_name = element[1];
				var type        = element[2];
				var zero_value  = element[3];
				
				var me = jQuery(selector);
				var t  = me.attr("type");
				if(t == "checkbox") {
					me.prop("checked", _mapper.get(default_options, option_name)=="true");
				} else {
					var v = _mapper.get(default_options, option_name);
					me.val(v);
					if(type=="color") {
						me.val(v.toLowerCase());
						me.css("color", newColor(hexToRgb(v))).css("background", v);
					}
				}
			});
			
			// Tooltip box
			// Test if some properties should be hidden
			if(!jQuery("#show_tooltips").is(":checked")) {
				jQuery("#tooltip_trigger_group").addClass("disabled");
				jQuery("#tooltip_trigger").prop("disabled", true).val("");
				jQuery("#use_html").prop("disabled", true);
			}
			if(jQuery("#use_html").is(":checked")) {
				jQuery("#tooltip_text_styling_group").addClass("disabled");
				jQuery("#tooltip_background_color").prop("disabled", true);
				jQuery("#tooltip_font_name").prop("disabled", true);
				jQuery("#tooltip_font_size").prop("disabled", true);
			} else {
				jQuery("#tooltip_text_styling_group").removeClass("disabled");
				jQuery("#tooltip_background_color").prop("disabled", false);
				jQuery("#tooltip_font_name").prop("disabled", false);
				jQuery("#tooltip_font_size").prop("disabled", false);
			}
			
			
			/*------------------------------------------------------------------------------
			 3 - Bind methods
			------------------------------------------------------------------------------*/
			
			// 2.1 - Link "add marker/region"
			jQuery(".add_to_table").bind("click", function(e) { 
				var box_top = position_form(form, table.find("thead tr"));
				
				var old_row = table.data("selected");
				if(old_row!==null) {
					old_row.removeClass("selected_row");
				}
				
				// Saving null for the selected row
				table.data("selected", null);
				
				// Show form. Workaround so that no transition will be shown.
				if(typeof setTimeout=="function") {
					setTimeout(function() {
						jQuery("#new_maps_page").removeClass("table_edit").addClass("table_add"); 
					},10);
				} else {
					jQuery("#new_maps_page").removeClass("table_edit").addClass("table_add"); 
				}
				return false; 
			});

			// 2.2 - DELETE
			jQuery("#data_table_form_delete").bind("click tap", function(e) {
				var row = table.data("selected");
				if(row!==null) {
					delete_data_table_entry(row, jQuery("#new_maps_page"), false);
					
					jQuery("#new_maps_page").removeClass("table_edit table_add")
				}
				
				e.preventDefault();
				return false;
			});
			
			// 2.3 - CHANGE
			jQuery("#data_table_form_change").bind("click tap", function(e) {
				var row = table.data("selected");

				if(row!==null) {
					var data = get_form_data();
					
					set_data_table_row(row, data.location, data.latitude, data.longitude, data.color, data.tooltip_title, data.tooltip_text, data.click_action_value);
					row.data("value",data);
					//console.log(row.data("value"));
					row.removeClass("selected_row");
					
					// Close form
					jQuery("#new_maps_page").removeClass("table_edit table_add");
					
					table_to_map();
				}
				
				
				e.preventDefault();
				return false;
			});
			
			// 2.4 - CANCEL
			jQuery("#data_table_form_cancel").bind("click tap", function(e) { 
				var old_row = table.data("selected");
				if(old_row!==null) {
					old_row.removeClass("selected_row");
				}
				jQuery("#new_maps_page").removeClass("table_edit table_add");

				e.preventDefault();				
				return false;
			});
			// 2.5 - ADD
			jQuery("#data_table_form_add").bind("click tap", function(e) {
				var value = get_form_data();
				var row = create_new_data_table_row(value.location, value.latitude, value.longitude, value.color, value.tooltip_title, value.tooltip_text, value.click_action_value);
				row.data("value", value);
				row.bind("click tap", row_function);
				point.prepend(row);
				table.find("tfoot").hide();
				
				// Close form
				jQuery("#new_maps_page").removeClass("table_edit table_add");
					
				table_to_map();
				update_table_count();
				e.preventDefault();
			});
			
			// 2.6 - INPUT for map name
			//  -on focusin: hide label
			//  -on focusout: show label, if there is no text
			//  -hide label: if there is text, when it's loaded
			jQuery("#title").focusin(function() { jQuery("#title-prompt-text").hide(); });
			jQuery("#title").focusout(function() {
				if(jQuery("#title").val()=="")
					jQuery("#title-prompt-text").show();
			});
			if(jQuery("#title").val()!=="")
				jQuery("#title-prompt-text").hide();
				

			// 2.7 - BUTTON for saving map
			jQuery(".map_transaction").bind("click tap", function(e) {
				var _this = this;
				jQuery(this).siblings(".spinner").css("visibility", "visible");
				set_message("");
				
				var callback = function(img) {
					var __this = _this;
					jQuery.ajax({
						dataType: "json",
						url:      imb_object.ajax_url,
						type:     "POST",
						data: {
							action:       "imb_save_map",
							id:           map_id,
							title:        jQuery("#title").val(),
							click_action: get_click_action_id(),
							mode:         current_mode,
							table:        JSON.stringify(construct_data_table_obj()),
							image:        img,
							options:      mapper.get_all(default_options),
							html:         html_editor.getValue(),
							css:          css_editor.getValue(),
							js:           js_editor.getValue(),
						},
						success: function(result) { 
							jQuery(__this).siblings(".spinner").css("visibility", "hidden");
							if(result !== null && typeof result == "object") {
								if(typeof result["message"] !== "undefined") {
									set_message(result["message"]);
								} else if(typeof result["error"] !== "undefined") {
									set_error_message(result["error"]);
								}
							}
							console.log(result); 
						}
					});
				}
				create_preview(300,150,callback);
				jQuery(this).blur();
				e.preventDefault();
			});
			
			// 2.8 - Coordinate search
			jQuery("#find_coordinates_link").click(function(e) {
				jQuery("#data_form_location").css("borderColor", "");
				jQuery(".data_table_form .rm_box").addClass("visible");
				jQuery("#data_form_location").focus().select();
				e.preventDefault();
			});
			jQuery("#data_table_lat_long_close").click(function(e) {
				jQuery(".data_table_form .rm_box").removeClass("visible");
				e.preventDefault();
			});
			jQuery("#data_table_lat_long_search").click(function(e) {
				e.preventDefault();
				var address=jQuery("#data_form_location").val();//address which you want Longitude and Latitude
				jQuery("#lat_long_spinner").show();
				jQuery.ajax({
					type: "GET",
					dataType: "json",
					url: "http://maps.googleapis.com/maps/api/geocode/json",
					data: {'address': address,'sensor':false},
					success: function(data){
						if(data.results.length){
							jQuery('#data_form_latitude').val(data.results[0].geometry.location.lat);
							jQuery('#data_form_longitude').val(data.results[0].geometry.location.lng);
							jQuery("#lat_long_spinner").hide();
							jQuery(".data_table_form .rm_box").removeClass("visible");
						}else{
							//jQuery('data_form_#latitude').val('invalid address');
							//jQuery('#data_form_longitude').val('invalid address');
							jQuery("#data_form_location").css("borderColor", "#D54E21");
							jQuery("#lat_long_spinner").hide();
					   }
					}
				});
			});
			jQuery("#data_form_location").keypress(function(e) {
				if(e.which == 13) {
					jQuery("#data_table_lat_long_search").trigger("click");
				}
				jQuery("#data_form_location").css("borderColor", "");
			});
			
			jQuery("#map_template_title").click(function() {
				var e = jQuery(this);
				if(e.hasClass("collapsed")) {
					e.removeClass("collapsed");
					jQuery("#map_template_body").slideDown();
				} else {
					e.addClass("collapsed");
					jQuery("#map_template_body").slideUp();
				}
			});
			if(jQuery("#map_template_title").hasClass("collapsed")) {
				jQuery("#map_template_body").hide();
			}
			regions.addListener("change", function() {  
				var region = regions.get_value();
				var res    = regions.find_resolutions(region);
				
				resolutions.set_available_resolutions(res);

				default_options["region"]     = region;
				default_options["resolution"] = resolutions.get_value();
				
				invoke_redraw();
			});
			
			modes.addListener("change", function() {
				var value = modes.get_value();
				var classes = "marker_text_mode marker_coord_mode region_mode text_mode";
				
				if(value=="regions") {
					jQuery("#new_maps_page").removeClass(classes).addClass("region_mode");
					current_mode = "regions";
					default_options["displayMode"]="regions";
				} else if(value=="text") {
					jQuery("#new_maps_page").removeClass(classes).addClass("marker_text_mode");
					current_mode = "marker_by_name";
					default_options["displayMode"]="markers";
				} else if(value=="coordinates") {
					jQuery("#new_maps_page").removeClass(classes).addClass("marker_coord_mode");
					current_mode = "marker_by_coordinates";
					default_options["displayMode"]="markers";
				} else if(value=="textmode") {
					jQuery("#new_maps_page").removeClass(classes).addClass("text_mode");
					current_mode = "textmode";
					default_options["displayMode"]="text";
				}
				
				table_to_map();
			});
			
			resolutions.addListener("change", function() {
				var resolution                = resolutions.get_value();
				default_options["resolution"] = resolution;

				invoke_redraw();
			});
			
			$.each(elements, function(i, element) {
				var _invoke_redraw = invoke_redraw;
				var _mapper     = mapper;
				var selector    = element[0];
				var option_name = element[1];
				var type        = element[2];
				var zero_value  = element[3];
				
				jQuery(selector).change(function() {
					var me = jQuery(this);
					var v  = me.val();
					
					// Convert checkboxes
					var t  = me.attr("type");
					if(t == "checkbox") {
						if(me.is(":checked")) {
							v = "true";
						} else {
							v = "false";
						}
					}
					
					// Empty value?
					if(v=="") {
						if(zero_value===null) {
							me.val(_mapper.get(default_options, option_name));
						} else {
							me.val(zero_value);
							var o = _mapper.get(default_options, option_name);
							if(o!=zero_value) {
								_mapper.set(default_options, option_name, zero_value);
								_invoke_redraw();
							}
						}
						return;
					}
					
					// Valid input
					if(type=="number") {
						if(!/^\d+$/.test(v) && v!=zero_value) {
							me.css("borderColor", "#D54E21");
							return;
						}
						me.css("borderColor", "");
					} else if(type=="color") {
						v = v.toLowerCase();
						if(!/^#[a-f0-9]{6}$/i.test(v)) {
							me.css("borderColor", "#D54E21");
							return;
						}
						me.css("borderColor", "");
					} else if(type=="percent") {
						if(!/^\d+$/.test(v)) {
							me.css("borderColor", "#D54E21");
							return;
						}
						if(parseInt(v) < 0 || 100 < parseInt(v)) {
							me.css("borderColor", "#D54E21");
							return;
						}
						me.css("borderColor", "");
					}
					
					// Set value
					var o = _mapper.get(default_options, option_name);
					if(o!=v) {
						console.log(o,v);
						_mapper.set(default_options, option_name, v);
						_invoke_redraw();
					}
				});
			});
			
			// Tooltip hide/show
			jQuery("#show_tooltips").bind("click tap", function() {
				if(jQuery(this).is(":checked")) {
					jQuery("#tooltip_trigger").prop("disabled", false).val("focus");
					jQuery("#use_html").prop("disabled", false);
					jQuery("#tooltip_trigger_group").removeClass("disabled");
					jQuery("#tooltip_background_color").prop("disabled", false);
					jQuery("#tooltip_font_name").prop("disabled", false);
					jQuery("#tooltip_font_size").prop("disabled", false);
					
				} else {
					jQuery("#tooltip_trigger_group").addClass("disabled");
					jQuery("#tooltip_trigger").prop("disabled", true).val("");
					jQuery("#use_html").prop("disabled", true);
					jQuery("#tooltip_background_color").prop("disabled", true);
					jQuery("#tooltip_font_name").prop("disabled", true);
					jQuery("#tooltip_font_size").prop("disabled", true);
				}
			});
			jQuery("#use_html").bind("click tap", function() {
				if(jQuery(this).is(":checked")) {
					jQuery("#tooltip_text_styling_group").addClass("disabled");
					jQuery("#tooltip_background_color").prop("disabled", true);
					jQuery("#tooltip_font_name").prop("disabled", true);
					jQuery("#tooltip_font_size").prop("disabled", true);
					jQuery("#new_maps_page").addClass("html_tooltips");
				} else {
					jQuery("#tooltip_text_styling_group").removeClass("disabled");
					jQuery("#tooltip_background_color").prop("disabled", false);
					jQuery("#tooltip_font_name").prop("disabled", false);
					jQuery("#tooltip_font_size").prop("disabled", false);
					jQuery("#new_maps_page").removeClass("html_tooltips");
				}
			});
			
			var previous_template = null;
			click_action.on("focus", function() {
				previous_template = this.value;
			});
			click_action.change(function(e) {
				var title_element = jQuery("#map_template_title .title_cap");
				var id            = get_click_action_id();
				title_element.text(jQuery(this).find(":selected").text());
				// Switching from -2 to something else?
				if(previous_template==-2 && id != -2) {
				
					var confirm_text = jQuery("#switching_from_custom_template").text();
					
					jQuery(this).val(-2);
					
					if(confirm(confirm_text)) {
						jQuery(this).val(id);
					} else {
						e.preventDefault();
						return false;
					}
				}
				
				
				
				var description = get_click_action_description();
				
				if(description!="") {
					click_action_description.html(description);
					click_action_description.removeClass("empty_description");
				} else {
					click_action_description.addClass("empty_description");
				}
				jQuery("#li_description").removeClass("hide_me");
				jQuery("#example_box").removeClass("hide_me");
				
				// Set description
				if(id!=-2) {
				
					
					preview.load(id);
					iframe = preview.get_iframe();
					
					
					
					//templates
					
					jQuery(html).val(templates[get_click_action_id()]["html"]);
					jQuery(css).val(templates[get_click_action_id()]["css"]);
					jQuery(js).val(templates[get_click_action_id()]["js"]);
					html_editor.setValue(jQuery(html).val());
					html_editor.refresh();
					js_editor.setValue(jQuery(js).val());
					js_editor.refresh();
					css_editor.setValue(jQuery(css).val());
					css_editor.refresh();
					
				} else {
					//description = jQuery("#custom_template_description");
					jQuery("#li_description").addClass("hide_me");
					jQuery("#example_box").addClass("hide_me");
				}
				
				// Set exampple value
				example_click_action_value.html(get_click_action_example());
				
				previous_template = this.value;
			});
			jQuery("#update_template").on("click tap", function() {
				click_action.val("-2").trigger("change");
				
				preview.show_loader();
				$("html, body").animate({ scrollTop: 0 }, 600);
			});
			
			jQuery("#map_click_control .value").click(function(e) {
				var v = jQuery(this).attr("what");
				
				if(v=="open_dialog") {
					page_config["map_click_opens_dialog"] = !page_config["map_click_opens_dialog"];
				} else if(v=="click_action") {
					page_config["map_click_runs_action"] = !page_config["map_click_runs_action"];
				}
			});
			
			// COLOR PICKERS
			

			var colorpickers = jQuery('.aaacolorpicker');
			colorpickers.each(function() {
				var element = jQuery(this);
				var parent  = element.parents(".colorpicker_parent");
				
				var _hexToRgb = hexToRgb;
				var _newColor = newColor;
				element.removeClass("picker_is_open");
				element.iris({
					change: function( event, ui ) {
						var input = jQuery(this);
						var color = ui.color.toString();

						input.val( color ).css( 'background', color );
						input.css("color", _newColor(_hexToRgb(color)));
						//input.trigger( 'change' );
					},
					palettes: ['#125', '#459', '#78b', '#ab0', '#de3', '#f0f'],
					mode: 'hsv',
					controls: {
						horiz: 'h', // horizontal defaults to saturation
						vert: 'v',  // vertical defaults to lightness
						strip: 's'  // right strip defaults to hue
					},
				});
				element.on("mousedown", function (e) {
					
					if(element.hasClass("picker_is_open")) {
						jQuery(this).iris('hide');
						element.removeClass("picker_is_open");
					} else {
						jQuery(this).iris('show');
						element.addClass("picker_is_open");
					}
					element.trigger('change');
					
				});
				
			});
			jQuery(".wrap").on("mousedown", function(e) {
				var me           = jQuery(e.target).parents(".colorpicker_parent").find(".aaacolorpicker");
				var open_pickers = colorpickers.filter(".picker_is_open");
				open_pickers.not(me).removeClass("picker_is_open").trigger("change").iris('hide');
			});
			
			if(page_config["map_is_import"]) {
				jQuery("#form").submit();
			}

		} else if(jQuery("#click_actions_page").length!=0) {
			/*------------------------------------------------------------------------------
			 Click Action Page
			 
			 0 - Find elements
			 1 - Functions
			 2 - Initializing
			 3 - Bind methods
			------------------------------------------------------------------------------*/
			
			
			/*------------------------------------------------------------------------------
			 0 - Find elements
			------------------------------------------------------------------------------*/

			
			// URL of this page
			var url = imb_object.click_action_page_url;
			
			// Left menu
			var menu    = new Menu_Handler(".elements", "#name_input_container", url);
			
			// Preview
			var preview = new Preview_Handler(imb_object.iframe_url, "#action_preview", ".loader");
			
			// Import Box
			var i_box   = new Import_Box("#import_box", "#import_action");
			
			// This page
			var page    = new Click_Action_Page(page_config["base_mode"], page_config["base_action"], page_config["click_actions"], preview, null, i_box, url);
			
			var html = document.getElementById("html_textarea");
			var js   = document.getElementById("js_textarea");
			var css  = document.getElementById("css_textarea");
			
			var menu_elements      = jQuery(".elements");
			var menu_highlight_css = "highlighted arrow_right";
			var menu_base_css      = "linked";
			
			/*------------------------------------------------------------------------------
			 1 - Functions
			------------------------------------------------------------------------------*/
			var editor2textarea = function() {
				//console.log(html_editor.getValue());
				jQuery(html).val(html_editor.getValue());
				jQuery(js).val(js_editor.getValue());
				jQuery(css).val(css_editor.getValue());
			}
			
			var get_form_data = function() {
				var data = page.form.get_data();
				if(page.current_mode=="edit" || page.current_mode=="copy") {
					data["name"] = jQuery("#loading_content .highlighted input").val();
				} else {
					data["name"] = jQuery("#name_input_container input").val();
				}
				return data;
			}
			
			var set_menu = function(new_menu, id) {
				var loading_content = jQuery("#loading_content");
				loading_content.find(">").remove();
				loading_content.html(new_menu);
				menu.bind_events();
				menu.highlight(id);
				var num = loading_content.find(".element").length;
				jQuery("#action_count").text(num);
			}
			
			var closest_menu_entry = function() {
				var prev = jQuery("#loading_content .highlighted").prevAll(".linked:first");
				if(prev.length==1)
					return prev.find("[actionid]").attr("actionid");
					
				var next = jQuery("#loading_content .highlighted").nextAll(".linked:first");
				if(next.length==1)
					return next.find("[actionid]").attr("actionid");
					
				return null;
			}
			
			var map_transaction = function() {
				var to_select = closest_menu_entry();
				
				page.hide_message();
				if(page.current_mode!=="copy") {
					editor2textarea();
				}
				page.show_button_spinner();
				var _page     = page;
				var _set_menu = set_menu;
				jQuery.ajax({
					url: 		ajaxurl,
					data:		{
									'action':'imb_save_clickaction',
									'mode'  : page.current_mode,
									'form'  : get_form_data(),
									'import_data' : jQuery("#import_text").val(),
								},
					dataType: 	'json',
					type: 		"POST",
					success: 	function(data,textStatus,jqXHR) {
									_page.hide_button_spinner();
									if(typeof data == "undefined" || typeof data.success == "undefined" || typeof data.message == "undefined") {
										_page.show_message("", ".error_incomplete", "error");
										return;
									}
									if(data.success) {
										_set_menu(data.menu, data.id);
										_page.show_message(data.message, ".success", "updated");
										_page.actions = data.click_actions;
										//_page.menu.update_data(data);
										_page.current_action = -3;//data.id;
										_page.current_mode   = "edit";
										
										if(data.id==-2) {
											if(to_select && typeof _page.actions[to_select] !== "undefined") {
												_page.change_page(to_select, "show", true);
											} else {
												// Check if menu is empty
												_page.change_page(-1, "show", true);
											}
										} else {
											_page.change_page(data.id, "show", true);
										}

										
									} else {
										_page.show_message(data.message, ".error_custom", "error");

									}

								},
					error:		function(jqXHR,textStatus,errorThrown ) {
									_page.hide_button_spinner();
									_page.change_page(_page.current_action, "show", true);
									switch (textStatus) {
										case "timeout":
											_page.show_message("", ".error_timeout", "error");
											break;
										case "error":
											_page.show_message("", ".error_error", "error");
											break;
										case "abort":
											_page.show_message("", ".error_abort", "error");
											break;
										case "parsererror":
											_page.show_message("", ".error_parsererror", "error");
											break;
										default:
											_page.show_message("", ".success", "error");
											break;
									}
								},
					timeout:    15000
				});
			}
			
			/*------------------------------------------------------------------------------
			 2 - Initializing
			------------------------------------------------------------------------------*/
			
			CodeMirror.defineMode("mustache", function(config, parserConfig) {
				var mustacheOverlay = {
					token: function(stream, state) {
						var ch;
						if (stream.match("{{")) {
							while ((ch = stream.next()) != null)
							if (ch == "}" && stream.next() == "}") break;
							stream.eat("}");
							return "mustache";
						}
						while (stream.next() != null && !stream.match("{{", false)) {}
						return null;
					}
				};
				return CodeMirror.overlayMode(CodeMirror.getMode(config, parserConfig.backdrop || "htmlmixed"), mustacheOverlay);
			});
			var html_editor = CodeMirror.fromTextArea(html, {
				lineNumbers: true,
				mode:  "mustache",
				autoCloseTags: true,
				matchTags: {bothTags: true},
				styleActiveLine: true,
				extraKeys: {
					"F11": function(cm) {
						cm.setOption("fullScreen", !cm.getOption("fullScreen"));
					},
					"Esc": function(cm) {
						if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
					}
				}
			});
			var js_editor = CodeMirror.fromTextArea(js, {
				lineNumbers: true,
				mode:  "javascript",
				styleActiveLine: true,
				extraKeys: {
					"F11": function(cm) {
						cm.setOption("fullScreen", !cm.getOption("fullScreen"));
					},
					"Esc": function(cm) {
						if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
					}
				}
			});
			var css_editor = CodeMirror.fromTextArea(css, {
				lineNumbers: true,
				mode:  "css",
				styleActiveLine: true,
				extraKeys: {
					"F11": function(cm) {
						cm.setOption("fullScreen", !cm.getOption("fullScreen"));
					},
					"Esc": function(cm) {
						if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
					}
				}
			});
			
			
			/*------------------------------------------------------------------------------
			 3 - Bind methods
			------------------------------------------------------------------------------*/
			menu.addListener("click", function() {
				page.change_page(menu.clicked_id, "show", true);
			});
			menu.addListener("dblclick", function() {
				page.change_page(menu.clicked_id, "edit", true);
			});
			
			page.addListener("action_change", function() {
				if(page.current_mode=="show") {
					menu.highlight(page.current_action);
				} else if(page.current_mode=="new") {
					menu.dehighlight();
				} else if(page.current_mode=="edit") {
				}
			});
			
			jQuery(".left_container .actions .input_element input").focusin(function() { jQuery(".left_container .actions .input_element label").hide(); });
			jQuery(".left_container .actions .input_element input").focusout(function() {
				if(jQuery(".left_container .actions .input_element input").val()=="")
					jQuery(".left_container .actions .input_element label").show();
			});
			if(jQuery(".left_container .actions .input_element input").val()!=="")
				jQuery(".left_container .actions .input_element label").hide();
				
			// Preview update
			$(".form_submit").click(function() {
				preview.show_loader();
				//page.hide_message();
				page.form.$form.attr("action", preview.url + page.current_action);
				$("html, body").animate({ scrollTop: 0 }, 600);
			});
			
			// [ + Add Click Action ] ... this is the button on the top
			$("#add_action, #create_first").click(function(e) {
				page.change_page(-1, "new", true);
				page.fadeout_message();
				e.preventDefault();
			});
			
			// [ + Add ] ... this is the button on the right that will save the new click action.
			$("#add_button").click(function(e) {
				map_transaction();
				e.preventDefault();
			});
			
			// Export
			$("#export_link").click(function(e) {
				page.change_page(page.current_action, "export", true);
				page.fadeout_message();
				e.preventDefault();
			});
			
			$('html').keyup(function(e){
				if(e.keyCode == 46 && page.current_mode=="show") {
					jQuery("#delete_action_name").html(page.actions[page.current_action]["name_esc"]);
					page.change_page(page.current_action, "delete", true);
					e.preventDefault();
				}
			});

			// Save
			$("#edit_button").click(function(e) {
				map_transaction();
				e.preventDefault();
			});
			
			// Delete
			jQuery("#delete_link").click(function(e) {
				jQuery("#delete_action_name").html(page.actions[page.current_action]["name_esc"]);
				page.change_page(page.current_action, "delete", true);
				e.preventDefault();
			});
			
			// Delete (Box)
			jQuery("#delete_confirm_button").click(function(e) {
				map_transaction();
				page.change_page(page.current_action, "show", false);
				e.preventDefault();
			});
			
			jQuery("label.layover").siblings("input, textarea")
			.focusout(function() {
				if(jQuery(this).val()=="")
					jQuery(this).siblings("label").show();
			})
			.focusin(function() { 
				jQuery(this).siblings("label").hide(); 
			})
			.each(function() {
				if(jQuery(this).val()=="")
					jQuery(this).siblings("label").show();
				else
					jQuery(this).siblings("label").hide();
			});

			// COPY
			jQuery("#copy_link").click(function(e) {
				page.current_mode = "copy";
				page.set_form_elements(page.current_action, "copy");
				//console.log(page.current_action);
				map_transaction();
				e.preventDefault();
			});
			
			// IMPORT
			jQuery("#import_confirm_button").click(function(e) {
				page.current_mode = "import";
				map_transaction();
				page.change_page(page.current_action, "show", true);
				e.preventDefault();
			});
			
			// EDIT
			jQuery("#edit_link").click(function(e) {
				e.preventDefault();
				page.change_page(page.current_action, "edit", true);
			});
			
			jQuery(".messagebg, .messagebox .close").click(function() {
				page.change_page(page.current_action, "show", true);
			});
			
			window.addEventListener("popstate", function(e) {    
				var state = e.state;
				if(state) {
					if(typeof page.actions[state.action] !== "undefined" || state.action==page.current_action)
					page.change_page(state.action, state.mode, false);
				} else {
					page.change_page(page_config["base_action"], page_config["base_mode"], false);
				}
				console.log(state);
			});
			
			var textarea1 = jQuery("[name=data_table_value_1]");
			var textarea2 = jQuery("[name=data_table_value_2]");
			var textarea3 = jQuery("[name=data_table_value_3]");
			var desc      = jQuery("#description");
			
			{ // Configure the autosize textareas
				textarea1.css("height", "20px");
				textarea2.css("height", "20px");
				textarea3.css("height", "20px");
				desc.css({"height": "20px", "padding": "1em"});
				textarea1.autosize();
				textarea2.autosize();
				textarea3.autosize();
				textarea1.css("height", "20px");
				textarea2.css("height", "20px");
				textarea3.css("height", "20px");
				desc.autosize();
			}
			
			jQuery(".show_table").click( function() {
				var v1 = jQuery(this).attr("toggle");
				var v2 = jQuery(this).text();
				jQuery(this).attr("toggle", v2);
				jQuery(this).text(v1);
				
				jQuery(".table_container").slideToggle();
				
				if(textarea1.is(":visible")) {
					textarea1.trigger("autosize.resize");
					textarea2.trigger("autosize.resize");
					textarea3.trigger("autosize.resize");
				}
			});
			
			page.addListener("change", function() {
				if(page.current_mode=="edit" || page.current_mode=="new") {
					if(textarea1.is(":visible")) {
						textarea1.trigger("autosize.resize");
						textarea2.trigger("autosize.resize");
						textarea3.trigger("autosize.resize");
					}
					if(!jQuery(".table_container").is(":visible")) {
						jQuery(".show_table").trigger("click");
					}
					html_editor.setValue(jQuery(html).val());
					html_editor.refresh();
					js_editor.setValue(jQuery(js).val());
					js_editor.refresh();
					css_editor.setValue(jQuery(css).val());
					css_editor.refresh();
					
					desc.trigger("autosize.resize");
				}
				receive_map_event();
			});
				
		} else if(jQuery("#manage_maps_page").length!=0) {
			/*------------------------------------------------------------------------------
			 Manage Maps Page
			 
			 0 - Find elements
			 1 - Functions
			 2 - Initializing
			 3 - Bind methods
			------------------------------------------------------------------------------*/
			
			/*------------------------------------------------------------------------------
			 0 - Find elements
			------------------------------------------------------------------------------*/
			var page       = jQuery("#manage_maps_page");
			var delete_box = jQuery("#delete_box");
			var import_box = jQuery("#import_box");
			var export_box = jQuery("#export_box");
			
			page.data("wait_for_load", false);
			
			/*------------------------------------------------------------------------------
			 1 - Functions
			------------------------------------------------------------------------------*/
			var check_all_descriptions = function() {
				jQuery(".description_container small").fitText();
			};
			var change_page = function(state) {
				var modes = "delete_mode import_mode export_mode";
				if(state=="delete") {
					page.removeClass(modes).addClass("delete_mode");
				} else if(state=="import") { 
					page.removeClass(modes).addClass("import_mode");
				} else if(state=="export") { 
					page.removeClass(modes).addClass("export_mode");
				} else {
					page.removeClass(modes);
				}
			};
			var delete_link = function(e) {
				var tr   = jQuery(this).parents("tr");
				var id   = tr.attr("mapid");
				var name = tr.attr("mapname");
				
				// Set name
				delete_box.find("#delete_action_name").text(name);
				
				// Set link
				delete_box.find("#delete_confirm_button").attr("href", page_config["delete_url"] + id);
				delete_box.data("selected_id", id);
				close_all_more_indicators_except();
				change_page("delete");
				
				e.stopImmediatePropagation();
				e.preventDefault();
			};
			var export_link = function(e) {
				var tr   = jQuery(this).parents("tr");
				var id   = tr.attr("mapid");
				var name = tr.attr("mapname");
				
				jQuery("#export_text").val(tr.find(".export_data textarea").val());
				export_box.find(".download_map").attr("href", page_config["download_url"] + id);
				
				change_page("export");
				close_all_more_indicators_except();
				
				e.stopImmediatePropagation();
				e.preventDefault();
			};
			
			// Table spinner
			var show_table_spinner = function() {
				jQuery("#table_spinner").css({opacity: 0.9, display: "block"});
			};
			
			var hide_table_spinner = function() {
				jQuery("#table_spinner").css({opacity: 0.0, display: "block"});
			};
			
			// Table fade out
			var fade_out_table = function() {
				jQuery(".manage_maps_table").css({opacity: 0.5});
			};
			
			var fade_in_table = function() {
				jQuery(".manage_maps_table").css({opacity: 1});
			};
			
			var load_table = function(href, add_to_history, data) {
				var _b   = bind_methods_for_loading_content;
				var _page = page;
				
				if(!add_to_history && data) {
					 jQuery("#loading_area").html(data);
					 _b();
					 return;
				}
				jQuery("#loading_area").load(href + " #loading_area >", function() {
					if(add_to_history && page_config["enable_history"]) {
						page_config["first_pop"] = false;
						history.pushState({url: href, data: jQuery("#loading_area").html()}, "", href);
					}
				
					jQuery(".manage_maps_table").css({opacity: 1});
					
					if(page.data("wait_for_load")) {
						var m = jQuery("#message");
						m.css("visibility", "visible");
						m.slideDown();
						page.data("wait_for_load", false);
					}
					
					var id = page.data("newid");
					jQuery("[mapid="+id+"]").find(".newflag").show();
					_b();
				});
			};
			
			// Pagination
			var pagination_click = function(e) {
				e.preventDefault();
				fade_out_table();
				show_table_spinner();
				load_table(jQuery(this).attr("href"), true);
				jQuery(this).blur();
			};
			
			var show_message = function(type, message) {
				var m = jQuery("#message");
				m.removeClass("updated error");
				m.find("p").hide();
				switch (type) {
					case "custom_success":
						m.addClass("updated");
						m.find(".custom_success").text(message).show();
						break;
					case "success_delete":
						m.addClass("updated");
						m.find(".success_delete").show();
						break;
					case "success_copy":
						m.addClass("updated");
						m.find(".success_copy").show();
						break;
					case "error_delete":
						m.addClass("error");
						m.find(".error_delete").show();
						break;
					case "error_copy":
						m.addClass("error");
						m.find(".error_copy").show();
						break;
					case "custom_error":
						m.addClass("error");
						m.find(".custom_error").text(message).show();
						break;
					case "timeout":
						m.addClass("error");
						m.find(".error_timeout").show();
						break;
					case "error":
						m.addClass("error");
						m.find(".error_error").show();
						break;
					case "abort":
						m.addClass("error");
						m.find(".error_abort").show();
						break;
					case "parsererror":
						m.addClass("error");
						m.find(".error_parsererror").show();
						break;
					case "error_incomplete":
						m.addClass("error");
						m.find(".error_incomplete").show();
						break;
					default:
						break;
				}
				if(!page.data("wait_for_load")) {
					m.css("visibility", "visible");
					m.slideDown();
				}
			};
			
			var copy_map = function(e) {
				var id = jQuery(this).parents("tr").attr("mapid");
				fade_out_table();
				show_table_spinner();
				change_page("");
				
				jQuery("#message").css("visibility", "hidden");
				
				// Local functions
				var _hide_spinner = hide_table_spinner;
				var _show_table   = fade_in_table;
				var _page         = page;
				
				// Send request
				jQuery.ajax({
					url: 		ajaxurl,
					data:		{
									'action':'imb_copy_map',
									'id'    : id,
								},
					dataType: 	'json',
					success: 	function(data,textStatus,jqXHR ) {
									
									if(typeof data.success == "undefined" || typeof data.message == "undefined" || typeof data.newid == "undefined") {
										show_message("error_incomplete");
										_hide_spinner();
										_show_table();
										return;
									}
									if(data.success) {
										_page.data("wait_for_load", true);
										_page.data("newid", data.newid);
										if(data.message=="") {
											show_message("success_copy");
										} else {
											show_message("custom_success", data.message);
										}
										load_table(jQuery(".manage_maps_table").attr("baseurl"), true);
									} else {
										if(data.message=="") {
											show_message("error_copy");
										} else {
											show_message("custom_error", data.message);
										}
										
										_hide_spinner();
										_show_table();
									}
									console.log("success", data);
								},
					error:		function(jqXHR,textStatus,errorThrown ) {
									_hide_spinner();
									_show_table();
									switch (textStatus) {
										case "timeout":
											show_message("timeout");
											break;
										case "error":
											show_message("error");
											break;
										case "abort":
											show_message("abort");
											break;
										case "parsererror":
											show_message("parsererror");
											break;
										default:
											show_message("error");
											break;
									}
								},
					timeout:    15000
				});
				
				e.preventDefault();
			};
			
			var confirmed_delete = function(e) {
				var id = delete_box.data("selected_id");
				fade_out_table();
				show_table_spinner();
				change_page("");
				
				jQuery("#message").css("visibility", "hidden");
				
				// Local functions
				var _hide_spinner = hide_table_spinner;
				var _show_table   = fade_in_table;
				var _page         = page;
				
				// Send request
				jQuery.ajax({
					url: 		ajaxurl,
					data:		{
									'action':'imb_delete_map',
									'id'    : id,
								},
					dataType: 	'json',
					success: 	function(data,textStatus,jqXHR ) {
									
									if(typeof data.success == "undefined" || typeof data.message == "undefined") {
										show_message("error_incomplete");
										_hide_spinner();
										_show_table();
										return;
									}
									if(data.success) {
										_page.data("wait_for_load", true);
										if(data.message=="") {
											show_message("success_delete");
										} else {
											show_message("custom_success", data.message);
										}
										load_table(jQuery(".manage_maps_table").attr("thisurl"), true);
									} else {
										if(data.message=="") {
											show_message("error_delete");
										} else {
											show_message("custom_error", data.message);
										}
										
										_hide_spinner();
										_show_table();
									}
									console.log("success", data);
								},
					error:		function(jqXHR,textStatus,errorThrown ) {
									_hide_spinner();
									_show_table();
									switch (textStatus) {
										case "timeout":
											show_message("timeout");
											break;
										case "error":
											show_message("error");
											break;
										case "abort":
											show_message("abort");
											break;
										case "parsererror":
											show_message("parsererror");
											break;
										default:
											show_message("error");
											break;
									}
								},
					timeout:    15000
				});
				
				e.preventDefault();
			};
			var bind_preview_sizes = function(e) {
				if(jQuery(this).hasClass("preview_normal")) {
					jQuery("#loading_area").removeClass().addClass("midsize_preview");
				} else if(jQuery(this).hasClass("preview_small")) {
					jQuery("#loading_area").removeClass();
				} else if(jQuery(this).hasClass("preview_big")) {
					jQuery("#loading_area").removeClass().addClass("bigsize_preview");
				}
				check_all_descriptions();
				e.preventDefault();
			}
			
			var bind_edit_click = function(e) {
				console.log(jQuery(this));
				var tr = jQuery(this).parents("tr");
				tr.find("td:first-child,td:nth-child(2)").unbind("click tap");
				var href = tr.find(".map_link").attr("href");
				//show_table_spinner();
				tr.addClass("selected");
				window.location = href;
				e.preventDefault();
			};
			
			var bind_methods_for_loading_content = function() {
				jQuery(".maps_pagination00 a").bind("click tap", pagination_click);
				
				// In each row
				jQuery(".delete_link").bind("click tap", delete_link);
				jQuery(".export_link").bind("click tap", export_link);
				jQuery(".copy_link").bind("click tap", copy_map);
				//jQuery(".manage_maps_table tbody tr").find("td:first-child,td:nth-child(2)").bind("click", bind_edit_click);

				// Table Header
				jQuery(".manage_maps_table th a").bind("click tap", pagination_click);
				jQuery(".preview_sizes").bind("click tap", bind_preview_sizes);
				
				connect_all_more_indicators();
				check_all_descriptions();
			};
			
			
			/*------------------------------------------------------------------------------
			 2 - Initializing
			------------------------------------------------------------------------------*/
			$.ajaxSetup ({
				// Disable caching of AJAX responses
				cache: true
			});
			
			jQuery(".keep_width").each(function() { 
				console.log(this);
				//var _this = jQuery(this);
				jQuery(this).css("width", jQuery(this).width());
			});
			
			/*------------------------------------------------------------------------------
			 3 - Bind methods
			------------------------------------------------------------------------------*/
			page.find(".close, .messagebg").bind("tap click", function(e) {
				change_page("");
				
				e.preventDefault();
			});
			
			jQuery("#import_link").bind("click tap", function(e) {
				change_page("import");
				e.preventDefault();
			});
			
			jQuery("#delete_confirm_button").bind("click", confirmed_delete);
			
			import_box.find(".tab_list li").bind("click tap", function(e) {
				var me = jQuery(this);
				e.preventDefault();
				
				if(me.hasClass("selected_tab"))
					return;
				
				var class_to_add = me.attr("setclass");
				import_box.find(".tab_inner").removeClass().addClass("tab_inner").addClass(class_to_add);
				import_box.find(".selected_tab").removeClass("selected_tab");
				me.addClass("selected_tab");
			});
			import_box.find("textarea").focusin(function() { 
				jQuery(this).siblings("label").hide(); 
			}).focusout(function() {
				if(jQuery(this).val()=="")
					jQuery(this).siblings("label").show();
			});
			if(import_box.find("textarea").val()!=="") {
				import_box.find("textarea").siblings("label").hide();
			}
			export_box.find(".tab_list li").click(function(e) {
				var me = jQuery(this);
				
				e.preventDefault();
				
				if(me.hasClass("selected_tab"))
					return;
				
				var class_to_add = me.attr("setclass");
				export_box.find(".tab_inner").removeClass().addClass("tab_inner").addClass(class_to_add);
				export_box.find(".selected_tab").removeClass("selected_tab");
				me.addClass("selected_tab");
			});
			
			jQuery( window ).resize(debounce(check_all_descriptions,200,false));
			bind_methods_for_loading_content();
			
			if(page_config["enable_history"]) {
				window.addEventListener("popstate", function(e) {    
					if(page_config["first_pop"]) {
						page_config["first_pop"] = false;
						return;
					}
					var state = e.state;
					if(state) {
						fade_out_table();
						show_table_spinner();
						load_table(state.url, false, state.data);
					} else {
						console.log(e);
						fade_out_table();
						show_table_spinner();
						load_table(page_config["base_url"], false);
					}
				});
			};
		}

	});
}(jQuery));


//Copyright (c) 2010 Nicholas C. Zakas. All rights reserved.
//MIT License

function EventTarget(){
    this._listeners = {};
}

EventTarget.prototype = {

    constructor: EventTarget,

    addListener: function(type, listener){
        if (typeof this._listeners[type] == "undefined"){
            this._listeners[type] = [];
        }

        this._listeners[type].push(listener);
    },

    fire: function(event){
        if (typeof event == "string"){
            event = { type: event };
        }
        if (!event.target){
            event.target = this;
        }

        if (!event.type){  //falsy
            throw new Error("Event object missing 'type' property.");
        }

        if (this._listeners[event.type] instanceof Array){
            var listeners = this._listeners[event.type];
            for (var i=0, len=listeners.length; i < len; i++){
                listeners[i].call(this, event);
            }
        }
    },

    removeListener: function(type, listener){
        if (this._listeners[type] instanceof Array){
            var listeners = this._listeners[type];
            for (var i=0, len=listeners.length; i < len; i++){
                if (listeners[i] === listener){
                    listeners.splice(i, 1);
                    break;
                }
            }
        }
    }
};
