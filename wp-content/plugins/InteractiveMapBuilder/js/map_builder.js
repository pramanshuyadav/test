
OptionMapper = (function() {
	var $=jQuery;
	
	function OptionMapper() {
	
	}
	
	OptionMapper.prototype.set = function(options, name, value) {

		switch (name) {
			case "background_color":
				options["backgroundColor"]["fill"] = value;
				break;
			case "border_color":
				options["backgroundColor"]["stroke"] = value;
				break;
			case "border_width":
				options["backgroundColor"]["strokeWidth"] = value;
				break;
			case "dataless_regions_color":
				options["datalessRegionColor"] = value;
				break;
			case "display_mode":
				options["displayMode"] = value;
				break;
			case "enable_region_interactivity":
				options["enableRegionInteractivity"] = value;
				break;
			case "height":
				options["height"] = value;
				break;
			case "keep_aspect_ratio":
				options["keepAspectRatio"] = value;
				break;
			case "region":
				options["region"] = value;
				break;
			case "marker_opacity":
				options["markerOpacity"] = parseFloat(value)/100;
				break;
			case "marker_size":
				options["sizeAxis"]["minSize"] = value;
				options["sizeAxis"]["maxSize"] = value;
				break;
			case "resolution":
				options["resolution"] = value;
				break;
			case "tooltip_enable":
				if(value=="true")
					options["tooltip"]["trigger"] = "focus";
				else
					options["tooltip"]["trigger"] = "none";
				break;
			case "tooltip_color":
				options["tooltip"]["textStyle"]["color"] = value;
				break;
			case "tooltip_font_family":
				options["tooltip"]["textStyle"]["fontName"] = value;
				break;
			case "tooltip_font_size":
				options["tooltip"]["textStyle"]["fontSize"] = value;
				break;
			case "tooltip_trigger":
				options["tooltip"]["trigger"] = value;
				break;
			case "width":
				options["width"] = value;
				break;
			case "tooltip_use_html":
				options["tooltip"]["isHtml"] = value;
				break;
			default:
				break;
		}
	}
	
	OptionMapper.prototype.get = function(options, name) {

		switch (name) {
			case "background_color":
				return options["backgroundColor"]["fill"];
				break;
			case "border_color":
				return options["backgroundColor"]["stroke"];
				break;
			case "border_width":
				return options["backgroundColor"]["strokeWidth"];
				break;
			case "dataless_regions_color":
				return options["datalessRegionColor"];
				break;
			case "display_mode":
				return options["displayMode"];
				break;
			case "enable_region_interactivity":
				return options["enableRegionInteractivity"];
				break;
			case "height":
				return options["height"];
				break;
			case "keep_aspect_ratio":
				return options["keepAspectRatio"];
				break;
			case "region":
				return options["region"];
				break;
			case "marker_opacity":
				return (parseFloat(options["markerOpacity"])*100) + "";
				break;
			case "marker_size":
				//return options["sizeAxis"]["minSize"];
				return options["sizeAxis"]["maxSize"];
				break;
			case "resolution":
				return options["resolution"];
				break;
			case "tooltip_enable":
				if(options["tooltip"]["trigger"]=="none")
					return "false";
				else
					return "true";
				break;
			case "tooltip_color":
				return options["tooltip"]["textStyle"]["color"];
				break;
			case "tooltip_font_family":
				return options["tooltip"]["textStyle"]["fontName"];
				break;
			case "tooltip_font_size":
				return options["tooltip"]["textStyle"]["fontSize"];
				break;
			case "tooltip_trigger":
				return options["tooltip"]["trigger"];
				break;	
			case "width":
				return options["width"];
				break;
			case "tooltip_use_html":
				return options["tooltip"]["isHtml"];
				break;
			default:
				return null;
				break;
		}
	}
	
	OptionMapper.prototype.get_all = function(options) {
		var output = {};

		output["background_color"]            = options["backgroundColor"]["fill"];
		output["border_color"]                = options["backgroundColor"]["stroke"];
		output["border_width"]                = options["backgroundColor"]["strokeWidth"];
		output["dataless_regions_color"]      = options["datalessRegionColor"];
		output["display_mode"]                = options["displayMode"];
		output["enable_region_interactivity"] = options["enableRegionInteractivity"];
		output["height"]                      = options["height"];
		output["keep_aspect_ratio"]           = options["keepAspectRatio"];
		output["region"]                      = options["region"];
		output["marker_opacity"]              = (parseFloat(options["markerOpacity"])) + "";
		output["marker_size"]                 = options["sizeAxis"]["maxSize"];
		output["resolution"]                  = options["resolution"];
		if(options["tooltip"]["trigger"]=="focus" || options["tooltip"]["trigger"]=="selection") {
			output["tooltip_enable"] = "true";
		} else {
			output["tooltip_enable"] = "false";
		}
		output["tooltip_color"]               = options["tooltip"]["textStyle"]["color"];
		output["tooltip_font_family"]         = options["tooltip"]["textStyle"]["fontName"];
		output["tooltip_font_size"]           = options["tooltip"]["textStyle"]["fontSize"];
		
		output["width"]                       = options["width"];
		output["tooltip_trigger"]             = options["tooltip"]["trigger"];
		output["tooltip_html"]                = options["tooltip"]["isHtml"];
		
		return output;
	}
	
	return OptionMapper;
})();

Regions = (function() {
	var $=jQuery;
	
	function Regions(code) {
		// Base container
		this.box_id = "#optionbox_region";
		this.box    = jQuery(this.box_id);
		
		// root
		this.root_li              = jQuery(this.box_id + " #world_area");
		this.value_element        = jQuery(this.box_id + " .box_value");
		
		// Select fields
		this.continent_element    = jQuery(this.box_id + " #continent_select");
		this.subcontinent_element = jQuery(this.box_id + " #subcontinent_select");
		this.country_element      = jQuery(this.box_id + " #country_select");
		this.province_element     = jQuery(this.box_id + " #province_select");
		
		// Connect Selects
		{
			this.continent_element.data("c", {prev: null, next: this.subcontinent_element});
			this.subcontinent_element.data("c", {prev: this.continent_element, next: this.country_element});
			this.country_element.data("c", {prev: this.subcontinent_element, next: this.province_element});
			this.province_element.data("c", {prev: this.country_element, next: null});
		}
		
		// Data Tree
		this.data     = regions;
		this.root     = "world";
		this.selected = code;
		
		// Labels
		this.continent_element.data("label", this.translate_string("select_continent", "- For more detail: Select continent -"));
		this.subcontinent_element.data("label", this.translate_string("select_subcontinent", "- For more detail: Select subcontinent -"));
		this.country_element.data("label", this.translate_string("select_country", "- For more detail: Select country -"));
		this.province_element.data("label", this.translate_string("select_state", "- For more detail: Select state -"));
		
		// Sorting function
		this.sorting = function(a,b){
			if(a.v > b.v){ return 1}
			if(a.v < b.v){ return -1}
			return 0;
		}
		this.selection_class = "selected_entry";
		
		this.init();
		this.bind_events();
	}
	
	Regions.prototype = new EventTarget();
	
	Regions.prototype.init = function() {
		var _this = this;
		var chain = [];
		var element = this.find_data(this.selected);
		if(!element) {
			this.selected = this.root;
			element       = this.find_data(this.root);
			this.root_li.addClass(_this.selection_class);
		}
			
		while(element) {
			chain.push(element);
			element = element[0];
			element = this.find_data(element);
			if(element===null) break;
		}
		chain = chain.reverse();
		
		var current = this.continent_element;
		var last    = null;
		for(i=0; i<chain.length; i++) {
			if(this.has_children(chain[i][1]))
				this.fill_select(current.data("label"), chain[i][1], current);

			if(last!==null) {
				last.val(chain[i][1]);
				if(i+1==chain.length)
					last.parents("li").addClass(this.selection_class);
			}
			
			last    = current;
			if(current!==null)
				current = current.data("c")["next"];
		}
		
	}
	
	Regions.prototype.set_value = function(value) {
		if(this.selected == value) return;
		
		var element = this.find_data(value);
			
		if(element===null) return;
		
		var name = element[2];
		//this.value_element.animate({"opacity": 0.4}, 100, "swing", function() { jQuery(this).attr("title", name).text(name).animate({"opacity": 1}, 100); })
		this.value_element.attr("title", name).text(name);
		this.selected = value;
		this.fire("change");
	}
	
	Regions.prototype.get_value = function() {
		return this.selected;
	}
	
	Regions.prototype.has_children = function(code) {
		var _code = code;
		var _has_children = false;
		jQuery.each(this.data, function(index, value) {
			if(value[0] == code) {
				_has_children = true;
			}
		});
		return _has_children;
	}
	
	Regions.prototype.find_data = function(code) {
		var _code = code;
		var _found = null;
		jQuery.each(this.data, function(index, value) {
			if(value[1] == code) {
				_found = value;
			}
		});
		return _found;
	}
	
	Regions.prototype.find_resolutions = function(code) {
		var data = this.find_data(code);
		if(data!==null) {
			return data[3];
		}
		return 0;
	}
	
	Regions.prototype.hide_all_after = function(element) {
		var element = element.data("c")["next"];
		while(element) {
			element.parents("li").addClass("hidden_field");
			element = element.data("c")["next"];
		}
	}
	
	Regions.prototype.bind_events = function() {
		var _this = this;
		
		var change = function() {
			if(this.value!=-1) {
				// Deselect and select me
				_this.box.find("." + _this.selection_class).removeClass(_this.selection_class);
				jQuery(this).parents("li").addClass(_this.selection_class);
				
				_this.set_value(this.value);
				
				var next = jQuery(this).data("c")["next"];
				if(next!=null && _this.has_children(this.value)) {
					_this.fill_select(next.data("label"), this.value, next);
					//next.parents("li").show();
					_this.hide_all_after(next);
				} else {
					_this.hide_all_after(jQuery(this));
				}
				
			} else {
				// Deselect me and select parent
				_this.box.find("." + _this.selection_class).removeClass(_this.selection_class);
				var parent = jQuery(this).data("c")["prev"];
				if(parent==null) {
					_this.root_li.addClass(_this.selection_class);
					_this.set_value("world");
				} else {
					parent.parents("li").addClass(_this.selection_class);
					_this.set_value(parent.val());
				}
				_this.hide_all_after(jQuery(this));
			}
		};
		
		this.root_li.bind("click tap", function(e) {
			_this.box.find("." + _this.selection_class).removeClass(_this.selection_class);
			_this.root_li.addClass(_this.selection_class);
			
			_this.set_value("world");
			_this.continent_element.val("-1");
			_this.hide_all_after(_this.continent_element);
			
			e.preventDefault();
		});
		
		_this.box.find(".box_header").bind("click tap", function(e) {
			var element = jQuery(this);
			element.siblings(".box_body").slideToggle(function() {
				element.parent().toggleClass("mapbox-active");
			});
			e.preventDefault();
		});
		
		this.continent_element.change(change);
		this.subcontinent_element.change(change);
		this.country_element.change(change);
		this.province_element.change(change);
	}
	
	Regions.prototype.translate_string = function(text_id, default_text) {
		if(typeof imb_object == "object" && imb_object!==null) {
			if(typeof imb_object[text_id] == "string") {
				return imb_object[text_id];
			}
		}
		
		return default_text;
	}
	
	Regions.prototype.fill_select = function(caption, parent, select) {
		var temp = [];
		var _this   = this;
		var _parent = parent;
		
		// Insert label
		select.empty().append(jQuery('<option></option>').attr("value", -1).text(caption));
		
		jQuery.each(this.data, function(index, value) {
			if(value[0] == _parent) {
				temp.push({v:value[2], k: value[1]});
			}
		});
		temp.sort(this.sorting);
		
		jQuery.each( temp, function( key, obj ) {
			select.append(jQuery('<option></option>').attr("value", obj.k).text(obj.v));
		});
		
		select.parents("li").removeClass("hidden_field");
	}
	
	return Regions;
})();

/**
 *  Class for the logic of the Region box
 *
 *  HTML:
 
	<div id="#optionbox_region">
		<div class="box_value">...</div>
		<ul>
			<li>
				<div id="world_area">...</div>
			</li>
			<li>
				<select id="continent_select"></select>
			</li>
			<li>
				<select id="subcontinent_select"></select>
			</li>
			<li>
				<select id="country_select"></select>
			</li>
			<li>
				<select id="province_select"></select>
			</li>
		</ul>
	</div>
  
 *  JS:
  
	var box = new RegionBox(); 
  
 *
 *  @class RegionBox
 */
function RegionBox() {
	this.prototype = new EventTarget();
	this.box_id               = "#optionbox_region";
	this.box_element          = null;
	this.box_value_element    = null;
	this.box_select_elements  = null;
	this.continent_element    = null;
	this.subcontinent_element = null;
	this.country_element      = null;
	this.province_element     = null;
	this.last_value           = null;
	this.codes_all            = null;
	this.box_li_entries       = null;


	this.get_box_element = function() {
		if(this.box_element === null)
			this.box_element = jQuery(this.box_id);
		return this.box_element;
	}
	this.get_box_value_element = function() {
		if(this.box_value_element === null)
			this.box_value_element = this.get_box_element().find(".box_value");
		return this.box_value_element;
	}
	this.get_box_select_elements = function() {
		if(this.box_select_elements===null)
			this.box_select_elements = this.get_box_element().find("select");
		return this.box_select_elements;
	}
	this.get_continent = function() {
		if(this.continent_element === null)
			this.continent_element = this.get_box_select_elements().filter("#continent_select");
		return this.continent_element;
	}
	this.get_subcontinent = function() {
		if(this.subcontinent_element === null)
			this.subcontinent_element = this.get_box_select_elements().filter("#subcontinent_select");
		return this.subcontinent_element;
	}
	this.get_country = function() {
		if(this.country_element === null)
			this.country_element = this.get_box_select_elements().filter("#country_select");
		return this.country_element;
	}
	this.get_province = function() {
		if(this.province_element === null)
			this.province_element = this.get_box_select_elements().filter("#province_select");
		return this.province_element;
	}
	this.get_box_li_entries = function() {
		if(this.box_li_entries === null)
			this.box_li_entries = this.get_box_element().find("ul li");
		return this.box_li_entries;
	}
	
	this.init = function() {
		this.fill_continents();
		this.bind_methods();
		this.hide_subcontinent();
		this.hide_country();
		this.hide_province();
	}

	this.change = function(func) {
		this.addListener("change", func);
	}
		
	/**
	 *  Binds all events. Especially the changes of the select fields.
	 *  
	 *  @method bind_methods
	 */
	this.bind_methods = function() {
		var box = this.get_box_element();
		var obj = this;
		this.get_continent().change(function () {
			var value = this.value;
			if(value!=-1) {
				obj.fill_subcontinents(value);
				obj.show_subcontinent();
				obj.set_pointer_to("continent");
				obj.on_change_internal("continent", value);
			} else {
				obj.hide_subcontinent();
				obj.set_pointer_to("world");
				obj.on_change_internal("world", "world");
			}
			obj.hide_country();
			obj.hide_province();
		});
		this.get_subcontinent().change(function () {
			var value = this.value;
			if(value!=-1) {
				obj.fill_countries(value);
				obj.show_country();
				obj.set_pointer_to("subcontinent");
				obj.on_change_internal("subcontinent", value);
			} else {
				obj.hide_country();
				obj.set_pointer_to("continent");
				obj.on_change_internal("continent", obj.get_continent().val());
			}
			obj.hide_province();
		});
		this.get_country().change(function () {
			var value = this.value;
			if(value!=-1) {
				obj.set_pointer_to("country");
				if(value=="US") {
					obj.fill_provinces(value);
					obj.show_province();
				} else {
					obj.hide_province();
				}
				obj.on_change_internal("country", value);
			} else {
				obj.set_pointer_to("subcontinent");
				obj.hide_province();
				obj.on_change_internal("subcontinent", obj.get_subcontinent().val());
			}
		});
		this.get_province().change(function () {
			var value = this.value;
			if(value==-1) {
				obj.set_pointer_to("country");
				obj.on_change_internal("country", obj.get_country().val());
			} else {
				obj.set_pointer_to("province");
				obj.on_change_internal("province", value);
			}
		});
		box.find(".box_header").click(function() {
			var element = jQuery(this);
			element.siblings(".box_body").slideToggle(function() {
				element.parent().toggleClass("mapbox-active");
			});
		});
		box.find("#world_area").click(function() {
			obj.set_pointer_to("world");
			obj.get_continent().val(-1);
			obj.hide_subcontinent();
			obj.hide_country();
			obj.hide_province();
			obj.on_change_internal("world", "world");
		});
	}

	/**
	 *  Fills the select field with the continents.
	 *  
	 *  @method fill_continents
	 */
	this.fill_continents = function() {
		var label = this.translate_string("select_continent", "- For more detail: Select continent -");
		this.fill_select(label, this.get_codes_continents(), this.get_continent());
	}
	
	/**
	 *  Fills the select field with the subcontinents.
	 *  
	 *  @method fill_subcontinents
	 *  @param {String} value The REGION-CODE of a continent.
	 */
	this.fill_subcontinents = function(value) {
		var label = this.translate_string("select_subcontinent", "- For more detail: Select subcontinent -");
		this.fill_select(label, this.get_codes_subcontinents(value), this.get_subcontinent());
	}
	
	/**
	 *  Fills the select field with the countries.
	 *  
	 *  @method fill_countries
	 *  @param {String} value The REGION-CODE of a subcontinent.
	 */
	this.fill_countries = function(value) {
		var label = this.translate_string("select_country", "- For more detail: Select country -");
		this.fill_select(label, this.get_codes_countries(value), this.get_country());
	}
	
	/**
	 *  Fills the select field with the provinces.
	 *  
	 *  @method fill_provinces
	 *  @param {String} value The REGION-CODE of a country.
	 */
	this.fill_provinces = function(value) {
		var label = this.translate_string("select_state", "- For more detail: Select state -");
		this.fill_select("- For more detail: Select state -", this.get_codes_provinces(value), this.get_province());
	}
	
	/**
	 *  Helper function to fill the select fields. The data is sorted before the select is filled.
	 *  
	 *  @methid fill_select
	 *  @param {String} caption The caption for the preselected first element with the id -1.
	 *  @param {Object} element_array All elements that should be inserted. Before they will be sorted by name.
	 *  @param {Object} select The select field where the data should be inserted.
	 */
	this.fill_select = function(caption, element_array, select) {
		var sorting = true;
		select.empty().append(jQuery('<option></option>').attr("value", -1).text(caption));
		if(sorting) {
			var temp = [];
			var _this = this;
			jQuery.each(element_array, function(key, value) {
				var name = _this.translate_code(key);
				temp.push({v:name, k: key});
			});
			temp.sort(function(a,b){
				if(a.v > b.v){ return 1}
				if(a.v < b.v){ return -1}
				return 0;
			});
			jQuery.each( temp, function( key, obj ) {
				select.append(jQuery('<option></option>').attr("value", obj.k).text(obj.v));
			});
		} else {
			var _this = this;
			jQuery.each( element_array, function( key, value ) {
				var name = _this.translate_code(key);
				select.append(jQuery('<option></option>').attr("value", key).text(name));
			});
		}
	}
	
	this.translate_code = function(code) {
		var name = this.get_codes_all(code);
		
		if(typeof imb_object == "object" && imb_object!==null) {
			if(typeof imb_object[code] == "string") {
				return imb_object[code];
			}
		}
		
		return name;
	}
	
	this.translate_string = function(text_id, default_text) {
		if(typeof imb_object == "object" && imb_object!==null) {
			if(typeof imb_object[text_id] == "string") {
				return imb_object[text_id];
			}
		}
		
		return default_text;
	}
	
	/**
	 *  Selects the given position.
	 *  
	 *  @method set_pointer_to
	 *  @param {String} position Possible values: world, continent, subcontinent, country, province
	 */
	this.set_pointer_to = function(position) {
		this.get_box_li_entries().removeClass("selected_entry");
		
		if(position == "world")
			this.get_box_element().find("#world_area").addClass("selected_entry");//this.get_box_pointer().animate({top: "13px"});
		else if(position == "continent")
			this.get_continent().parents("li").addClass("selected_entry");
		else if(position == "subcontinent")
			this.get_subcontinent().parents("li").addClass("selected_entry");
		else if(position == "country")
			this.get_country().parents("li").addClass("selected_entry");
		else if(position == "province")
			this.get_province().parents("li").addClass("selected_entry");
	}

	this.hide_subcontinent = function() {
		this.get_subcontinent().empty();
		this.get_subcontinent().parents("li").hide();
		this.hide_country();
		this.hide_province();
	}
	this.show_subcontinent = function() {
		this.get_subcontinent().parents("li").show();
		this.hide_country();
		this.hide_province();
	}

	this.hide_country = function() {
		this.get_country().empty();
		this.get_country().parents("li").hide();
		this.hide_province();
	}
	this.show_country = function() {
		this.get_country().parents("li").show();
		this.hide_province();
	}
	this.hide_province = function() {
		this.get_province().empty();
		this.get_province().parents("li").hide();
	}
	this.show_province = function() {
		this.get_province().parents("li").show();
	}

	this.on_change_internal = function(new_level, code) {
		var name = this.translate_code(code);
		this.get_box_value_element().attr("title", name).text(name);
		if(this.last_value!==code) {
			this.last_value=code;
			this.on_change(new_level, code);
		}
	}
	this.on_change = function(new_level, code) {
		//alert("Level: " + new_level + ", Region: " + code);
	}

	/**
	 *  Sets the REGION-CODE.
	 *  
	 *  @param {String} code The code that has to be set. If not found, it will be set to world.
	 *  @method set_value
	 */
	this.set_value = function(code) {
		this.hide_subcontinent();
		this.hide_country();
		this.hide_province();
		var level = this.find_recursive_levels(code);
		var pointer_pos = "world";
		var selected_code = "world";

		if(typeof(level["continent"])!=="undefined") {
			this.fill_subcontinents(level["continent"]);
			this.show_subcontinent();
			pointer_pos = "continent";
			selected_code = level["continent"];
			this.get_continent().val(level["continent"]);

			if(typeof(level["subcontinent"])!=="undefined") {
				this.fill_countries(level["subcontinent"]);
				this.show_country();
				pointer_pos = "subcontinent";
				selected_code = level["subcontinent"];
				this.get_subcontinent().val(level["subcontinent"]);

				if(typeof(level["country"])!=="undefined") {
					this.get_country().val(level["country"]);
					pointer_pos = "country";
					if(level["country"]=="US") {
						this.fill_provinces(level["country"]);
						this.show_province();
						pointer_pos = "province";
						selected_code = level["province"];
						if(typeof(level["province"])!=="undefined") {
							this.get_province().val(level["province"]);
						}
					}
				}
			}
		} else {
			this.get_continent().val("-1");
		}
		this.on_change_internal(pointer_pos, selected_code);
		this.set_pointer_to(pointer_pos);
	}

	/**
	 *  Finds the ancestors of the given code.
	 *  
	 *  @method find_recursive_levels
	 *  @param {String} code The REGION-CODE to search for
	 *  @return {Object} The found code and its ancestors. The return object has the beginning with the level 
	 *  of code all the ancestor's levels as keys. Possible keys are: province, country, subcontinent, 
	 *  continent. The values are the REGION-CODES.
	 */
	this.find_recursive_levels = function(code) {
		/**
		 *  
		 *  hay is the current code to search for in the REGION-CODES.
		 *  Note: REGION-CODE is UNIQUE for a region. So there are no two regions with the same code.
		 *  
		 *  Logic:
		 *   1 - Try to find the hay in the provinces
		 *   2 - Try to find the hay in the countries
		 *   3 - Try to find the hay in the continents or subcontinents
		 *  
		 */
		 
		var hay           = code;
		var found_levels  = {};
		
		/**
		 *  1 - Try to find the hay in the provinces
		 *  
		 *      If province found: 
		 *         - set found_levels["province"] = hay
		 *         - set hay                      = country of hay
		 */
		var all_provinces = this.get_codes_provinces("all");
		var found_country = null;
		jQuery.each(all_provinces, function(country_code, provinces) {
			if(found_country===null) {
				var found = false;
				var _hay  = hay;
				
				jQuery.each(provinces, function(province_code, province_name) {
					if(!found && _hay==province_code) {
						found = true;
					}
				});
				
				if(found) {
					found_country = country_code;
					found_levels["province"] = code;
					hay                      = found_country;
				}
			}
		});


		/**
		 *  2 - Try to find the hay in the countries
		 *  
		 *      If country found: 
		 *         - set found_levels["country"]      = hay
		 *         - set found_levels["subcontinent"] = subcontinent of hay
		 *         - set hay                          = subcontinent of hay
		 */
		var all_countries      = this.get_codes_countries("all");
		var found_subcontinent = null;
		jQuery.each(all_countries, function(subcontinent_code, countries) {
			if(found_subcontinent===null) {
				var found = false;
				var _hay  = hay;
				
				jQuery.each(countries, function(country_code, country_code_name) {
					if(!found && _hay==country_code) {
						found = true;
					}
				});
				if(found) {
					found_subcontinent           = subcontinent_code;
					found_levels["country"]      = hay;
					found_levels["subcontinent"] = found_subcontinent;
					hay                          = found_subcontinent;
				}
			}
		});


		/**
		 *  3 - Try to find the hay in the continents or subcontinents
		 *  
		 *      If continent found: 
		 *         - set found_levels["continent"] = hay
		 *      If subcontinent found: 
		 *         - set found_levels["subcontinent"] = hay
		 *         - set found_levels["continent"]    = continent of hay
		 */
		var all_subcontinents = this.get_codes_subcontinents("all");
		var found_continent   =  null;
		jQuery.each(all_subcontinents, function(continent_code, subcontinents) {
			if(found_continent===null) {
				var found = false;
				var _hay  = hay;
				
				if(continent_code == hay) {
					found_continent           = hay;
					found_levels["continent"] = hay;
				}
				
				jQuery.each(subcontinents, function(subcontinent_code, subcontinent_name) {
					if(!found && _hay==subcontinent_code) {
						found = true;
					}
				});
				
				if(found) {
					found_continent = continent_code;
					found_levels["subcontinent"] = hay;
					found_levels["continent"]    = found_continent;
				}
			}
		});

		return found_levels;
	}

	/**
	 *  Returns all countries as an object. 
	 *  The key is the REGION-CODE or name for the map api. The value is the name that will be displayed.
	 *
		var continents = box.get_codes_continents();
		alert(continents["002"]); // Will alert "Africa"
		
	 *  @method get_codes_continents
	 *  @return {Object} Returns the continents.
	 */
	this.get_codes_continents = function() {
		return {"002": "Africa", "019": "Americas",  "142": "Asia", "150": "Europe", "009": "Oceania"};
	}

	/**
	 *  Returns the subcontinents for the given REGION-CODE.
	 *  
	 *  @method get_codes_subcontinents
	 *  @param {String} continent The REGION-CODE of the continent OR "all" for all subcontinents.
	 *  @return {Object} Returns the subcontinents for the given REGION-CODE. 
	 */
	this.get_codes_subcontinents = function(continent) {
		var africa   = {"015": "Northern Africa", "011": "Western Africa", "017": "Middle Africa", "014": "Eastern Africa", "018": "Southern Africa"};
		var europe   = {"154": "Northern Europe", "155": "Western Europe", "151": "Eastern Europe", "039": "Southern Europe"};
		var americas = {"021": "Northern America", "029": "Caribbean", "013": "Central America", "005": "South America"};
		var asia     = {"143": "Central Asia", "030": "Eastern Asia", "034": "Southern Asia", "035": "South-Eastern Asia", "145": "Western Asia"};
		var oceania  = {"053": "Australia and New Zealand", "054": "Melanesia", "057": "Micronesia", "061": "Polynesia"};

		if(continent=='002')
			return africa;
		else if(continent=='150')
			return europe;
		else if(continent=='019')
			return americas;
		else if(continent=='142')
			return asia;
		else if(continent=='009')
			return oceania;
		else if(continent=="all")
			return {'002': africa, '150': europe, '019': americas, '142': asia, '009': oceania};
		else
			return {};
	}

	this.get_codes_countries = function(subcontinent) {
		var north_africa    = {"DZ": "Algeria", "EG": "Egypt", "EH": "Western Sahara", "LY": "Libya", "MA": "Morocco", "SD": "Sudan", "TN": "Tunisia"};
		var western_africa  = {"BF": "Burkina Faso", "BJ": "Benin", "CI": "Côte d'Ivoire", "CV": "Cape Verde", "GH": "Ghana", "GM": "Gambia", "GN": "Guinea", "GW": "Guinea-Bissau", "LR": "Liberia", "ML": "Mali", "MR": "Mauritania", "NE": "Niger", "NG": "Nigeria", "SH": "Saint Helena, Ascension and Tristan da Cunha", "SL": "Sierra Leone", "SN": "Senegal", "TG": "Togo"};
		var middle_africa   = {"AO": "Angola", "CD": "Congo, the Democratic Republic of the", "ZR": "Zaire", "CF": "Central African Republic", "CG": "Congo", "CM": "Cameroon", "GA": "Gabon", "GQ": "Equatorial Guinea", "ST": "Sao Tome and Principe", "TD": "Chad"};
		var eastern_africa  = {"BI": "Burundi", "DJ": "Djibouti", "ER": "Eritrea", "ET": "Ethiopia", "KE": "Kenya", "KM": "Comoros", "MG": "Madagascar", "MU": "Mauritius", "MW": "Maldives", "MZ": "Mozambique", "RE": "Réunion", "RW": "Rwanda", "SC": "Seychelles", "SO": "Somalia", "TZ": "Tanzania", "UG": "Uganda", "YT": "Mayotte", "ZM": "Zambia", "ZW": "Zimbabwe"};
		var southern_africa = {"BW": "Botswana", "LS": "Lesotho", "NA": "Namibia", "SZ": "Swaziland", "ZA": "South Africa"};

		var northern_europe = {"GG": "Guernsey", "JE": "Jersey", "AX": "Åland", "DK": "Denmark", "EE": "Estonia", "FI": "Finland", "FO": "Faroe Islands", "GB": "United Kingdom", "IE": "Ireland", "IM": "Isle of Man", "IS": "Iceland", "LT": "Lithuania", "LV": "Latvia", "NO": "Norway", "SE": "Sweden", "SJ": "Svalbard and Jan Mayen"};
		var western_europe  = {"AT": "Austria", "BE": "Belgium", "CH": "Switzerland", "DE": "Germany", "DD": "German Democratic Republic", "FR": "France", "FX": "France, Metropolitan", "LI": "Liechtenstein", "LU": "Luxembourg", "MC": "Monaco", "NL": "Netherlands"};
		var eastern_europe  = {"BG": "Bulgaria", "BY": "Belarus", "CZ": "Czech Republic", "HU": "Hungary", "MD": "Moldova", "PL": "Poland", "RO": "Romania", "RU": "Russia", "SU": "USSR", "SK": "Slovakia", "UA": "Ukraine"};
		var southern_europe = {"AD": "Andorra", "AL": "Albania", "BA": "Bosnia and Herzegovina", "ES": "Spain", "GI": "Gibraltar", "GR": "Greece", "HR": "Croatia", "IT": "Italy", "ME": "Montenegro", "MK": "Macedonia", "MT": "Malta", "CS": "Serbia and Montenegro", "RS": "Serbia", "PT": "Portugal", "SI": "Slovenia", "SM": "San Marino", "VA": "Holy See (Vatican City State)", "YU": "Yugoslavia"};

		var north_america   = {"BM": "Bermuda", "CA": "Canada", "GL": "Greenland", "PM": "Saint Pierre and Miquelon", "US": "United States"};
		var caribbean       = {"AG": "Antigua and Barbuda", "AI": "Anguilla", "AN": "Netherlands Antilles", "AW": "Aruba", "BB": "Barbados", "BL": "Saint Barthélemy", "BS": "Bahamas", "CU": "Cuba", "DM": "Dominica", "DO": "Dominican Republic", "GD": "Grenada", "GP": "Guadeloupe", "HT": "Haiti", "JM": "Jamaica", "KN": "Saint Kitts and Nevis", "KY": "Cayman Islands", "LC": "Saint Lucia", "MF": "Saint Martin (French part)", "MQ": "Martinique", "MS": "Montserrat", "PR": "Puerto Rico", "TC": "Turks and Caicos Islands", "TT": "Trinidad and Tobago", "VC": "Saint Vincent and the Grenadines", "VG": "Virgin Islands, British", "VI": "Virgin Islands, U.S."};
		var central_america = {"BZ": "Belize", "CR": "Costa Rica", "GT": "Guatemala", "HN": "Honduras", "MX": "Mexico", "NI": "Nicaragua", "PA": "Panama", "SV": "El Salvador"};
		var south_america   = {"AR": "Argentina", "BO": "Bolivia", "BR": "Brazil", "CL": "Chile", "CO": "Colombia", "EC": "Ecuador", "FK": "Falkland Islands (Malvinas)", "GF": "French Guiana", "GY": "Guyana", "PE": "Peru", "PY": "Paraguay", "SR": "Suriname", "UY": "Uruguay", "VE": "Venezuela"};

		var central_asia      = {"TM": "Turkmenistan", "TJ": "Tajikistan", "KG": "Kyrgyzstan", "KZ": "Kazakhstan", "UZ": "Uzbekistan"};
		var eastern_asia      = {"CN": "China", "HK": "Hong Kong", "JP": "Japan", "KP": "North Korea", "KR": "South Korea", "MN": "Mongolia", "MO": "Macao", "TW": "Taiwan"};
		var southern_asia     = {"AF": "Afghanistan", "BD": "Bangladesh", "BT": "Bhutan", "IN": "India", "IR": "Iran", "LK": "Sri Lanka", "MV": "Maldives", "NP": "Nepal", "PK": "Pakistan"};
		var southeastern_asia = {"BN": "Brunei", "ID": "Indonesia", "KH": "Cambodia", "LA": "Laos", "MM": "Myanmar", "BU": "Burma", "MY": "Malaysia", "PH": "Philippines", "SG": "Singapore", "TH": "Thailand", "TL": "Timor-Leste", "TP": "East Timor", "VN": "Vietnam"};
		var western_asia      = {"AE": "United Arab Emirates", "AM": "Armenia", "AZ": "Azerbaijan", "BH": "Bahrain", "CY": "Cyprus", "GE": "Georgia", "IL": "Israel", "IQ": "Iraq", "JO": "Jordan", "KW": "Kuwait", "LB": "Lebanon", "OM": "Oman", "PS": "Palestine, State of", "QA": "Qatar", "SA": "Saudi Arabia", "NT": "Neutral Zone", "SY": "Syria", "TR": "Turkey", "YE": "Yemen", "YD": "South Yemen"};

		var australia_and_newzealand = {"AU": "Australia", "NF": "Norfolk Island", "NZ": "New Zealand"};
		var melanesia                = {"FJ": "Fiji", "NC": "New Caledonia", "PG": "Papua New Guinea", "SB": "Solomon Islands", "VU": "Vanuatu"};
		var micronesia               = {"FM": "Micronesia, Federated States of", "GU": "Guam", "KI": "Kiribati", "MH": "Marshall Islands", "MP": "Northern Mariana Islands", "NR": "Nauru", "PW": "Palau"};
		var polynesia                = {"AS": "American Samoa", "CK": "Cook Islands", "NU": "Niue", "PF": "French Polynesia", "PN": "Pitcairn", "TK": "Tokelau", "TO": "Tonga", "TV": "Tuvalu", "WF": "Wallis and Futuna", "WS": "Samoa"};

		if(subcontinent=='015')
			return north_africa;
		else if(subcontinent=='011')
			return western_africa;
		else if(subcontinent=='017')
			return middle_africa;
		else if(subcontinent=='014')
			return eastern_africa;
		else if(subcontinent=='018')
			return southern_africa;
		else if(subcontinent=='154')
			return northern_europe;
		else if(subcontinent=='155')
			return western_europe;
		else if(subcontinent=='151')
			return eastern_europe;
		else if(subcontinent=='039')
			return southern_europe;
		else if(subcontinent=='021')
			return north_america;
		else if(subcontinent=='029')
			return caribbean;
		else if(subcontinent=='013')
			return central_america;
		else if(subcontinent=='005')
			return south_america;
		else if(subcontinent=='143')
			return central_asia;
		else if(subcontinent=='030')
			return eastern_asia;
		else if(subcontinent=='034')
			return southern_asia;
		else if(subcontinent=='035')
			return southeastern_asia;
		else if(subcontinent=='145')
			return western_asia;
		else if(subcontinent=='053')
			return australia_and_newzealand;
		else if(subcontinent=='054')
			return melanesia;
		else if(subcontinent=='057')
			return micronesia;
		else if(subcontinent=='061')
			return polynesia;
		else if(subcontinent=='all')
			return {	'015': north_africa, "011": western_africa, "017": middle_africa, "014": eastern_africa, "018": southern_africa,
						"154": northern_europe, "155": western_europe, "151": eastern_europe, "039": southern_europe,
						"021": north_america, "029": caribbean, "013": central_america, "005": south_america,
						"143": central_asia, "030": eastern_asia, "034": southern_asia, "035": southeastern_asia, "145": western_asia,
						"053": australia_and_newzealand, "054": melanesia, "057": micronesia, "061": polynesia};
		else
			return {};
	}

	/**
	 *  Returns the provinces for the given country.
	 *  
	 *  @method get_codes_provinces
	 *  @param {String} The country as REGION-CODE or "all"
	 *  @return {Object} An object with province_code => province_name or counry_code => (province_code => province_name)
	 */
	this.get_codes_provinces = function(country) {
		var us_states = {	"US-AL":" Alabama","US-AK":" Alaska","US-AZ":" Arizona","US-AR":" Arkansas","US-CA":" California","US-CO":" Colorado",
							"US-CT":" Connecticut","US-DE":" Delaware","US-FL":" Florida","US-GA":" Georgia","US-HI":" Hawaii","US-ID":" Idaho",
							"US-IL":" Illinois","US-IN":" Indiana","US-IA":" Iowa","US-KS":" Kansas","US-KY":" Kentucky","US-LA":" Louisiana",
							"US-ME":" Maine","US-MD":" Maryland","US-MA":" Massachusetts","US-MI":" Michigan","US-MN":" Minnesota","US-MS":" Mississippi",
							"US-MO":" Missouri","US-MT":" Montana","US-NE":" Nebraska","US-NV":" Nevada","US-NH":" New Hampshire","US-NJ":" New Jersey",
							"US-NM":" New Mexico","US-NY":" New York","US-NC":" North Carolina","US-ND":" North Dakota","US-OH":" Ohio","US-OK":" Oklahoma",
							"US-OR":" Oregon","US-PA":" Pennsylvania","US-RI":" Rhode Island","US-SC":" South Carolina","US-SD":" South Dakota","US-TN":" Tennessee",
							"US-TX":" Texas","US-UT":" Utah","US-VT":" Vermont","US-VA":" Virginia","US-WA":" Washington","US-WV":" West Virginia",
							"US-WI":" Wisconsin","US-WY":" Wyoming","US-DC":" District of Columbia","US-AS":" American Samoa","US-GU":" Guam","US-MP":" Northern Mariana Islands",
							"US-PR":" Puerto Rico","US-UM":" United States Minor Outlying Islands","US-VI":" Virgin Islands, U.S."};

		if(country=="US") {
			return us_states;
		} else if(country=="all") {
			return { "US" : us_states };
		} else {
			return {};
		}
	}

	/**
	 *  Returns the name for the given code or an object with all REGION-CODES as keys and the names as values.
	 *  
	 *  @method get_codes_all
	 *  @param {String} code The REGION-CODE or "all"
	 *  @return {String|Object} The name for the given region or an object with REGION-CODE=>region_name.
	 */
	this.get_codes_all = function(code) {
		if(this.codes_all === null) {
			var continents    = this.get_codes_continents();
			var countries     = this.get_codes_countries("all");
			var subcontinents = this.get_codes_subcontinents("all");
			var provinces     = this.get_codes_provinces("all");
			
			var country_codes = {};
			// Adding Provinces
			jQuery.each(provinces, function(key, obj) {
				var cc = country_codes;
				jQuery.each(obj, function(k, v) {
					cc[k] = v;
				});
			});
			
			// Adding Country Codes
			jQuery.each(countries, function(key, obj) {
				var cc = country_codes;
				jQuery.each(obj, function(k, v) {
					cc[k] = v;
				});
			});

			// Adding Continent Codes
			country_codes = jQuery.extend({},country_codes,continents);

			// Adding Subcontinent Codes
			jQuery.each(subcontinents, function(key, obj) {
				var cc = country_codes;
				jQuery.each(obj, function(k, v) {
					cc[k] = v;
				});
			});

			// Adding world
			country_codes["world"] = "World";

			this.codes_all = country_codes;
		}
		if(code!=="all") {
			if(typeof(this.codes_all[code])!=="undefined") {
				return this.codes_all[code];
			} else {
				return "";
			}
		} else {
			return this.codes_all;
		}
	}
}

/**
 *  Class for the logic of the DisplayMode box
 *
 *  DisplayModes:   markers, regions
 *  MarkerModes:    coordinates, text
 *  HTML:
   
    <div id="optionbox_displaymode">
  		<div class=".box_value"></div>
		
		<!-- Display Modes -->
  		<input type="radio" value="regions" name="displaymode" />
		<input type="radio" value="markers" name="displaymode" />
		
			<!-- Marker Modes -->
			<input type="radio" value="text"        name="marker_mode" />
			<input type="radio" value="coordinates" name="marker_mode" />
    </div>
 	
 *  @class DisplayMode
 */
DisplayMode = (function() {

	function DisplayMode(mode) {
		this.box_id               = "#optionbox_displaymode";
		this.box_element          = jQuery(this.box_id);
		this.box_value_element    = this.box_element.find(".box_value");
		this.box_option_elements  = this.box_element.find("input[type=radio]");
		
		this.select_marker        = this.box_element.find("[value=markers]");
		this.select_region        = this.box_element.find("[value=regions]");
		this.select_text          = this.box_element.find("[value=text]");
		this.select_coordinates   = this.box_element.find("[value=coordinates]");
		this.select_textmode      = this.box_element.find("[value=textmode]");
		
		if(mode=="marker_by_coordinates") {
			this.last_value           = "coordinates";
		} else if(mode=="marker_by_name") {
			this.last_value           = "text";
		} else if(mode=="textmode") {
			this.last_value           = "textmode";
		} else {
			this.last_value           = "regions";
		}
		this.last_marker_mode     = "text";
		
		this.init();
		this.bind_methods();
	}
	
	DisplayMode.prototype = new EventTarget();
	
	DisplayMode.prototype.init = function() {
		if(this.last_value!==null) {
			if(this.last_value=="regions") {
				this.check_regions();
			} else if(this.last_value=="coordinates") {
				this.last_marker_mode = "coordinates";
				this.enable_marker_modes();
				this.check_markers();
			} else if(this.last_value=="text") {
				this.last_marker_mode = "text";
				this.enable_marker_modes();
				this.check_markers();
			} else if(this.last_value=="textmode") {
				this.check_textmode();
			} else {
				this.check_regions();
			}
			
		} else {
			this.find_first_setting();
		}
	}

	DisplayMode.prototype.find_first_setting = function() {
		var elements    = this.box_value_element;
		//this.last_value = elements.filter(":checked").val();
		var markermode = elements.filter("[name=marker_mode]");
		var mode       = markermode.filter(":checked");
		if(mode.length==0) {
			this.last_marker_mode = "text";
		} else if(mode.val() == "text") {
			this.last_marker_mode = "text";
		} else if(mode.val() == "coordinates") {
			this.last_marker_mode = "coordinates";
		}
		var displaymode = elements.filter("[name=displaymode]");
		mode            = displaymode.filter(":checked");
		if(mode.length==0) {
			this.check_regions();
			this.disable_marker_modes();
			this.last_value = "regions";
		} else if(mode.val() == "regions") {
			this.disable_marker_modes();
			this.last_value = "regions";
		} else if(mode.val() == "markers") {
			this.enable_marker_modes();
			this.last_value = "markers";
		} else if(mode.val() == "textmode") {
			this.disable_marker_modes();
			this.last_value = "textmode";
		}
	}
	
	DisplayMode.prototype.bind_methods = function() {
		var _this  = this;
		
		this.box_option_elements.change(function() {
			_this.on_change_internal( jQuery(this).val() );
		});
		
		this.box_option_elements.parents("li").bind("click tap", function(e) {
			var input = jQuery(this).find("input");
			if(input.is(":disabled")) {
				return;
			}
			input.attr("checked", "checked");
			input.change();
			e.preventDefault();
		});
		
		this.box_element.find(".box_header").bind("click tap", function(e) {
			var element = jQuery(this);
			element.siblings(".box_body").slideToggle(function() {
				element.parent().toggleClass("mapbox-active");
			});
			e.preventDefault();
		});
	}

	DisplayMode.prototype.get_value = function() {
		return this.box_value_element.filter(":checked").val();
	}

	DisplayMode.prototype.check_regions = function() {
		var elements = this.box_option_elements;
		this.select_region.attr("checked", "checked");
		this.select_textmode.removeAttr("checked");
		this.select_marker.removeAttr("checked");
		this.on_change_internal("regions");
	}

	DisplayMode.prototype.check_textmode = function() {
		var elements = this.box_option_elements;
		this.select_textmode.attr("checked", "checked");
		this.select_region.removeAttr("checked");
		this.select_marker.removeAttr("checked");
		this.on_change_internal("textmode");
	}
	
	DisplayMode.prototype.check_markers = function() {
		var elements = this.box_option_elements;
		this.select_marker.attr("checked", "checked");
		this.select_region.removeAttr("checked");
		this.on_change_internal("markers");
	}

	DisplayMode.prototype.check_markers_text = function() {
		var elements  = this.box_option_elements;
		
		this.select_marker.attr("checked", "checked");
		this.select_region.removeAttr("checked");
		
		this.select_text.attr("checked", "checked");
		this.select_coordinates.removeAttr("checked");
		
		this.on_change_internal("text");
	}
	
	DisplayMode.prototype.check_markers_coordinates = function() {
		var elements  = this.box_option_elements;
		
		this.select_marker.attr("checked", "checked");
		this.select_region.removeAttr("checked");
		
		this.select_text.removeAttr("checked");
		this.select_coordinates.attr("checked", "checked");
		
		this.on_change_internal("coordinates");
	}
	
	DisplayMode.prototype.disable_marker_modes = function() {
		var marker_elements = this.box_option_elements.filter("[name=marker_mode]");
		var mode            = marker_elements.filter(":checked").val();
		if(mode=="text" || mode=="coordinates") {
			this.last_marker_mode = mode;
		}
		marker_elements.parents("li").addClass("disabled_grey");
		marker_elements.removeAttr("checked").attr("disabled", "disabled");
	}
	
	DisplayMode.prototype.enable_marker_modes = function() {
		var marker_elements = this.box_option_elements.filter("[name=marker_mode]");
		var mode            = this.last_marker_mode;
		
		marker_elements.removeAttr("disabled");
		marker_elements.filter("[value=" + mode + "]").attr("checked", "checked");
		
		marker_elements.parents("li").removeClass("disabled_grey");
		this.on_change_internal(mode);
	}
	
	DisplayMode.prototype.on_change_internal = function(value) {
		// value = {markers, regions, text, coordinates}
		if(value=="markers") {
			if(this.last_value=="regions" || this.last_value=="textmode") {
				this.enable_marker_modes();
			}
			return;
		}
		if(value=="regions" || value=="textmode") {
			this.disable_marker_modes();
		}
		text = {regions:     this.translate_string("displaymode_regions", "Regions"), 
		        text:        this.translate_string("displaymode_marker_text", "Markers (Text)"), 
				coordinates: this.translate_string("displaymode_marker_coordinates", "Markers (Coordinates)"),
				textmode:    this.translate_string("displaymode_text", "Text"),};
		this.box_value_element.attr("title", text[value]).text(text[value]);
		
		if(this.last_value !== value) {
			this.last_value = value;
			this.fire("change");
		}
	}

	DisplayMode.prototype.get_value = function() {
		return this.last_value;
	}
	
	DisplayMode.prototype.translate_string = function(text_id, default_text) {
		if(typeof imb_object == "object" && imb_object!==null) {
			if(typeof imb_object[text_id] == "string") {
				return imb_object[text_id];
			}
		}
		return default_text;
	}
	
	return DisplayMode;
})();

/**
 * Class for the logic of the Resolution box
 *
 * Requires following structure for #optionbox_resolution:
 * <... id="#optionbox_resolution">
 * 		<.. class=".box_value">...</..>
 *		<input type="radio" value="countries" ... />
 *		<input type="radio" value="provinces" ... />
 *		<input type="radio" value="metros" ... />
 * </...>
 */
ResolutionMap = (function() {

	function ResolutionMap(value, resolutions) {
		this.box_id               = "#optionbox_resolution";
		this.box_element          = jQuery(this.box_id);
		this.box_value_element    = this.box_element.find(".box_value");
		this.box_option_elements  = this.box_element.find("input[type=radio]");
		this.countries            = null;
		this.provinces            = null;
		this.metros               = null;
		this.continents           = null;
		this.subcontinents        = null;
		this.last_value           = null;
		this.no_reaction          = false;
		
		this.init();
		this.set_available_resolutions(resolutions);
		this.set_value(value);
	}
	
	ResolutionMap.prototype = new EventTarget();
	
	ResolutionMap.prototype.init = function() {
		this.bind_methods();
	}

	ResolutionMap.prototype.bind_methods = function() {
		var _this = this;
		this.box_option_elements.change(function() {
			_this.on_change_internal( jQuery(this).val() );
		});
		this.box_option_elements.parent().bind("click tap", function(e) {
			if(jQuery(this).children("input").attr("disabled")!="disabled") {
				jQuery(this).children("input").attr("checked", "checked");
				jQuery(this).children("input").change();
			}
			e.preventDefault();
		});
		this.box_element.find(".box_header").bind("click tap", function(e) {
			var element = jQuery(this);
			element.siblings(".box_body").slideToggle(function() {
				element.parent().toggleClass("mapbox-active");
			});
			e.preventDefault();
		});
	}

	ResolutionMap.prototype.set_available_resolutions = function(resolutions) {
		var countries     = 1;
		var provinces     = 2;
		var metros        = 4;
		var continents    = 8;
		var subcontinents = 16;
		this.no_reaction = true;
		if(resolutions & continents) {
			this.enable_continents();
		} else {
			this.disable_continents();
		}
		if(resolutions & subcontinents) {
			this.enable_subcontinents();
		} else {
			this.disable_subcontinents();
		}
		if(resolutions & countries) {
			this.enable_countries();
		} else {
			this.disable_countries();
		}
		if(resolutions & provinces) {
			this.enable_provinces();
		} else {
			this.disable_provinces();
		}
		if(resolutions & metros) {
			this.enable_metros();
		} else {
			this.disable_metros();
		}
		this.no_reaction = false;
	}
	
	ResolutionMap.prototype.set_value = function(value) {
		this.no_reaction = true;
		if(value=="countries") {
			this.get_countries().trigger("click");
		} else if(value=="provinces") {
			this.get_provinces().trigger("click");
		} else if(value=="metros") {
			this.get_metros().trigger("click");
		} else if(value=="continents") {
			this.get_continents().trigger("click");
		} else if(value=="subcontinents") {
			this.get_subcontinents().trigger("click");
		}
		this.no_reaction = false;
	}
	
	ResolutionMap.prototype.get_value = function() {
		return this.box_option_elements.filter(":checked").val();
	}

	ResolutionMap.prototype.get_countries = function() {
		if(this.countries === null)
			this.countries = this.box_option_elements.filter("[value=countries]");
		return this.countries;
	}
	ResolutionMap.prototype.get_provinces = function() {
		if(this.provinces === null)
			this.provinces = this.box_option_elements.filter("[value=provinces]");
		return this.provinces;
	}
	ResolutionMap.prototype.get_metros = function() {
		if(this.metros === null)
			this.metros = this.box_option_elements.filter("[value=metros]");
		return this.metros;
	}
	ResolutionMap.prototype.get_continents = function() {
		if(this.continents === null)
			this.continents = this.box_option_elements.filter("[value=continents]");
		return this.continents;
	}
	ResolutionMap.prototype.get_subcontinents = function() {
		if(this.subcontinents === null)
			this.subcontinents = this.box_option_elements.filter("[value=subcontinents]");
		return this.subcontinents;
	}
	
	ResolutionMap.prototype.enable_continents = function() {
		this.transform_enable(this.get_continents());
		this.check_order();
	}
	ResolutionMap.prototype.disable_continents = function() {
		this.transform_disable(this.get_continents());
		this.check_order();
	}
	ResolutionMap.prototype.enable_subcontinents = function() {
		this.transform_enable(this.get_subcontinents());
		this.check_order();
	}
	ResolutionMap.prototype.disable_subcontinents = function() {
		this.transform_disable(this.get_subcontinents());
		this.check_order();
	}
	ResolutionMap.prototype.enable_countries = function() {
		this.transform_enable(this.get_countries());
		this.check_order();
	}

	ResolutionMap.prototype.disable_countries = function() {
		this.transform_disable(this.get_countries());
		this.check_order();
	}

	ResolutionMap.prototype.enable_provinces = function() {
		this.transform_enable(this.get_provinces());
		this.check_order();
	}

	ResolutionMap.prototype.disable_provinces = function() {
		this.transform_disable(this.get_provinces());
		this.check_order();
	}

	ResolutionMap.prototype.enable_metros = function() {
		this.transform_enable(this.get_metros());
		this.check_order();
	}

	ResolutionMap.prototype.disable_metros = function() {
		this.transform_disable(this.get_metros());
		this.check_order();
	}

	ResolutionMap.prototype.check_order = function() {
		var continents    = this.get_continents();
		var subcontinents = this.get_subcontinents();
		var countries     = this.get_countries();
		var provinces     = this.get_provinces();
		var metros        = this.get_metros();

		if(countries.is(":checked") && countries.is("[disabled]")) {
			if(!provinces.is("[disabled]")) {
				provinces.attr("checked", "checked");
				this.on_change_internal("provinces");
			} else if(!metros.is("[disabled]")) {
				metros.attr("checked", "checked");
				this.on_change_internal("metros");
			}
		} else if(provinces.is(":checked") && provinces.is("[disabled]")) {
			if(!countries.is("[disabled]")) {
				countries.attr("checked", "checked");
				this.on_change_internal("countries");
			} else if(!metros.is("[disabled]")) {
				metros.attr("checked", "checked");
				this.on_change_internal("metros");
			}
		} else if(metros.is(":checked") && metros.is("[disabled]")) {
			if(!provinces.is("[disabled]")) {
				provinces.attr("checked", "checked");
				this.on_change_internal("provinces");
			} else if(!countries.is("[disabled]")) {
				countries.attr("checked", "checked");
				this.on_change_internal("countries");
			}
		} else if(subcontinents.is(":checked") && subcontinents.is("[disabled]")) {
			if(!provinces.is("[disabled]")) {
				provinces.attr("checked", "checked");
				this.on_change_internal("provinces");
			} else if(!countries.is("[disabled]")) {
				countries.attr("checked", "checked");
				this.on_change_internal("countries");
			}
		} else if(continents.is(":checked") && continents.is("[disabled]")) {
			if(!subcontinents.is("[disabled]")) {
				subcontinents.attr("checked", "checked");
				this.on_change_internal("subcontinents");
			} else if(!countries.is("[disabled]")) {
				countries.attr("checked", "checked");
				this.on_change_internal("countries");
			}
		}
	}
	
	ResolutionMap.prototype.transform_enable = function(element) {
		element.removeAttr("disabled");
		element.siblings("span").css("color", "");
		element.parent().css("cursor", "pointer").removeClass("disabled");
	}
	
	ResolutionMap.prototype.transform_disable = function(element) {
		element.attr("disabled", "disabled");
		element.siblings("span").css("color", "#CCCCCC");
		element.parent().css("cursor", "default").addClass("disabled");
		
	}
	
	ResolutionMap.prototype.check_available_resolutions = function(level, code) {
		if(level!=="province") {
			this.enable_countries();
		} else {
			this.disable_countries();
		}
		if(level!=="world" && level!=="continent" && level!=="subcontinent") {
			this.enable_provinces();
		} else {
			this.disable_provinces();
		}
		if((level=="country" && code=="US") || (level=="province")) {
			this.enable_metros();
		} else {
			this.disable_metros();
		}
	}

	ResolutionMap.prototype.on_change_internal = function(value) {
		text = {countries: this.translate_string("resolution.label.countries", "Countries"), 
		        provinces: this.translate_string("resolution.label.provinces", "Provinces"), 
				metros:    this.translate_string("resolution.label.metros", "Metros"),
				continents:    this.translate_string("resolution.label.continents", "Continents"),
				subcontinents:    this.translate_string("resolution.label.subcontinents", "Subcontinents"),};
		this.box_value_element.text(text[value]);
		if(this.last_value !== value) {
			this.last_value = value;
			if(!this.no_reaction) {
				this.fire("change");
			}
		}
	}
	
	ResolutionMap.prototype.translate_string = function(text_id, default_text) {
		if(typeof imb_object == "object" && imb_object!==null) {
			if(typeof imb_object[text_id] == "string") {
				return imb_object[text_id];
			}
		}
		return default_text;
	}
	return ResolutionMap;
})();

TableMapper = (function() {

	function TableMapper(table, mode, colors) {
		return {
			"cols": generate_google_data_table_cols(mode),
			"rows": generate_google_data_table_rows(mode, table, colors)
		};
	}
	
	/**
	 *  Creates the columns.
	 *  
	 *  @method generate_google_data_table_cols
	 *  @protected
	 *  @static
	 *  @param {String} $mode The display mode from the Map_Table.
	 *  @return {Array} Returns an array that describes the columns depending on the mode.
	 */
	function generate_google_data_table_cols(mode) {
		if(mode=="marker_by_coordinates") {
			return generate_google_data_table_cols_coords();
		} else if(mode=="marker_by_name") {
			return generate_google_data_table_cols_name();
		} else if(mode=="regions") {
			return generate_google_data_table_cols_region();
		} else if(mode=="textmode") {
			return generate_google_data_table_cols_textmode();
		}
	}
	
	/**
	 *  Creates the rows.
	 *  
	 *  @method generate_google_data_table_rows
	 *  @protected
	 *  @static
	 *  @param {String} $mode The display mode from the Map_Table.
	 *  @param {Map_Table} $table The table where the rows come from.
	 *  @return {Array} Returns an array that have the rows encoded depending on the mode.
	 */
	function generate_google_data_table_rows(mode, table, colors) {
		if(mode=="marker_by_coordinates") {
			return generate_google_data_table_rows_coords(table, colors);
		} else if(mode=="marker_by_name") {
			return generate_google_data_table_rows_name(table, colors);
		} else if(mode=="regions") {
			return generate_google_data_table_rows_region(table, colors);
		} else if(mode=="textmode") {
			return generate_google_data_table_rows_textmode(table, colors);
		}
	}
	
	/**
	 *  Helper function to create the column headers.
	 *  
	 *  @method generate_google_data_table_cols_helper
	 *  @protected
	 *  @static
	 *  @param {String} $label Label of the column.
	 *  @param {String} $type The type of the column, for example: "number", "string".
	 *  @param {Sring} [$role=null] The role of the column, for example: "tooltip".
	 *  @param {String} [$pattern=""] The pattern of the column.
	 *  @param {String} [$id=""] The id of the column.
	 *  @return {Array} Returns a formated array for the given input parameters.
	 */
	function generate_google_data_table_cols_helper(label, type, _role, _pattern, _id) {
		var role    = null;
		var pattern = "";
		var id      = "";
		if(typeof _role !== "undefined")
			role = _role;
		if(typeof _pattern !== "undefined")
			role = _pattern;
		if(typeof _id !== "undefined")
			role = _id;
		var o = {
				 "id"      : id,
				 "label"   : label,
				 "pattern" : pattern,
				 "type"    : type
			 };
		if(role!==null) {
			o["p"] = {"role": role, "html": "true"};
		}
			
		return o;
	}
	
	/**
	 *  Creates the columns for the mode "marker_by_coordinates".
	 *  
	 *  @method generate_google_data_table_cols_coords
	 *  @return Returns a formated array of columns specifically for this mode.
	 */
	function generate_google_data_table_cols_coords() {
		return [
				   generate_google_data_table_cols_helper("Latitude",  "number"),
				   generate_google_data_table_cols_helper("Longitude", "number"),
				   generate_google_data_table_cols_helper("Name",      "string"),
				   generate_google_data_table_cols_helper("Color",     "number"),
				   generate_google_data_table_cols_helper("Size",      "number"),
				   generate_google_data_table_cols_helper("Tooltip",   "string", "tooltip") 
			   ];
	}
	
	/**
	 *  Creates the rows for the mode "marker_by_coordinates".
	 *  
	 *  @method generate_google_data_table_rows_coords
	 *  @param {Map_Table} $table The table where the rows should come from.
	 *  @return Returns a formated array of rows specifically for this mode.
	 */
	function generate_google_data_table_rows_coords(table, colors) {
		var o      = [];
		jQuery.each(table, function(key, row) {
			var row_cols = [
							{"v" : row["latitude"]},
							{"v" : row["longitude"]},
							{"v" : row["tooltip_title"]},
							{"v" : colors.indexOf(row["color"])+1},
							//array("v" => $row["size"]),
							{"v" : 1},
							{"v" : row["tooltip_text"]} 
						];
			
			o.push({"c" : row_cols});
		});
		return o;
	}
	
	/**
	 *  Creates the columns for the mode "marker_by_name".
	 *  
	 *  @method generate_google_data_table_cols_name
	 *  @return Returns a formated array of columns specifically for this mode.
	 */
	function generate_google_data_table_cols_name() {
		return [
				   generate_google_data_table_cols_helper("Location",  "string"),
				   generate_google_data_table_cols_helper("Name",      "string"),
				   generate_google_data_table_cols_helper("Color",     "number"),
				   generate_google_data_table_cols_helper("Size",      "number"),
				   generate_google_data_table_cols_helper("Tooltip",   "string", "tooltip") 
			   ];
	}
	
	/**
	 *  Creates the rows for the mode "marker_by_name".
	 *  
	 *  @method generate_google_data_table_rows_name
	 *  @param {Map_Table} $table The table where the rows should come from.
	 *  @return Returns a formated array of rows specifically for this mode.
	 */
	function generate_google_data_table_rows_name(table, colors) {
		var o      = [];
		jQuery.each(table, function(key, row) {
			var row_cols = [
							{"v" : row["location"]},
							{"v" : row["tooltip_title"]},
							{"v" : colors.indexOf(row["color"])+1},
							//array("v" => $row["size"]),
							{"v" : 1},
							{"v" : row["tooltip_text"]} 
						];
			
			o.push({"c" : row_cols});
		});
		return o;
	}
	
	/**
	 *  Creates the columns for the mode "regions".
	 *  
	 *  @method generate_google_data_table_cols_region
	 *  @return Returns a formated array of columns specifically for this mode.
	 */
	function generate_google_data_table_cols_region() {
		return [
				   generate_google_data_table_cols_helper("Location",  "string"),
				   generate_google_data_table_cols_helper("Color",     "number"),
			       generate_google_data_table_cols_helper("Tooltip",   "string", "tooltip") 
			   ];
	}
	
	/**
	 *  Creates the columns for the mode "textmode".
	 *  
	 *  @method generate_google_data_table_cols_textmode
	 *  @return Returns a formated array of columns specifically for this mode.
	 */
	function generate_google_data_table_cols_textmode() {
		return [
				   generate_google_data_table_cols_helper("Location",  "string"),
				   generate_google_data_table_cols_helper("Size",      "number"),
			       generate_google_data_table_cols_helper("Tooltip",   "string", "tooltip") 
			   ];
	}
	
	/**
	 *  Creates the rows for the mode "regions".
	 *  
	 *  @method generate_google_data_table_rows_region
	 *  @param {Map_Table} $table The table where the rows should come from.
	 *  @return Returns a formated array of rows specifically for this mode.
	 */
	function generate_google_data_table_rows_region(table, colors) {
		var o     = [];
		jQuery.each(table, function(key, row) {
			var row_cols = [
							{
							    "v" : row["location"], 
								"f" : row["tooltip_title"]
							},
							{"v" : colors.indexOf(row["color"])+1},
							{"v" : row["tooltip_text"]}
						];
			
			o.push({"c" : row_cols});
		});
		return o;
	}
	
	/**
	 *  Creates the rows for the mode "textmode".
	 *  
	 *  @method generate_google_data_table_rows_region
	 *  @param {Map_Table} $table The table where the rows should come from.
	 *  @return Returns a formated array of rows specifically for this mode.
	 */
	function generate_google_data_table_rows_textmode(table, colors) {
		var o     = [];
		jQuery.each(table, function(key, row) {
			var row_cols = [
							{
							    "v" : row["location"], 
								"f" : row["tooltip_title"]
							},
							{"v" : 1.0},
							{"v" : row["tooltip_text"]}
						];
			
			o.push({"c" : row_cols});
		});
		return o;
	}
	
	return TableMapper;
})();