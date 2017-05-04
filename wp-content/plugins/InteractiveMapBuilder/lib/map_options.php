<?php
/**
 * Interactive Map Builder Plugin.
 *
 * @author    Fabian Vellguth <info@meisterpixel.com>
 * @link      http://meisterpixel.com/
 * @copyright 2014 meisterpixel.com
 */
 
class Map_Options {
	private $background_color            = "#ffffff";
	private $border_color                = "#666666";
	private $border_width                = "0";
	private $dataless_regions_color      = "#f5f5f5";
	private $enable_region_interactivity = "true";
	private $height                      = "350";
	private $keep_aspect_ratio           = "true";
	private $region                      = "world";
	private $marker_opacity              = "1.0";
	private $marker_size                 = "6";
	private $resolution                  = "countries";
	private $tooltip_enable              = "true";
	private $tooltip_color               = "#000000";
	private $tooltip_font_family         = "<global-font-name>";
	private $tooltip_font_size           = "<global-font-size>";
	private $tooltip_html                = "false";
	private $tooltip_trigger             = "focus";
	private $width                       = "auto";
	

	public function __construct($json=null) {
		if($json!==null)
			$this->load_db_string($json);
	}
	
	public function set_colors(Map_Table $table) {
		$colors = self::find_colors($table);
		$length = count($colors);
		
		if($length>0) {
			$this->color_max = (string)count($colors);
			$this->colors    = array_keys($colors);
		}
	}
	
	public static function find_colors(Map_Table $table) {
		$colors = array();
		$number = 1;
		$rows   = $table->get_rows();
		foreach($rows as $row) {
			if(!isset($colors[$row["color"]])) {
				$colors[$row["color"]] = $number;
				$number++;
			}
		}
		return $colors;
	}
	
	public function load_db_string($data) {
		$json = json_decode($data, true);
		
		$this->load_from_array($json);
	}
	
	public function load_from_array($json) {
		if(!is_array($json))
			return;
		
		$builder     = Interactive_Map_Builder::get_instance();
		$plugin_slug = $builder->get_plugin_slug();
		$invalid     = array();
		
		if(!$this->set_background_color($this->try_to_load($json, "background_color"))) {
			$invalid["background_color"] = __( "Background color is not valid.", $plugin_slug );
		}
		if(!$this->set_border_color($this->try_to_load($json, "border_color"))) {
			$invalid["border_color"] = __( "Border color is not valid.", $plugin_slug );
		}
		if(!$this->set_border_width($this->try_to_load($json, "border_width"))) {
			$invalid["border_width"] = __( "Border width is not valid.", $plugin_slug );
		}
		if(!$this->set_dataless_regions_color($this->try_to_load($json, "dataless_regions_color"))) {
			$invalid["dataless_regions_color"] = __( "Dataless region color is not valid.", $plugin_slug );
		}
		if(!$this->set_region_interactivity($this->try_to_load($json, "enable_region_interactivity"))) {
			$invalid["enable_region_interactivity"] = __( "Region interactivity couldn't be set.", $plugin_slug );
		}
		if(!$this->set_height($this->try_to_load($json, "height"))) {
			$invalid["height"] = __( "Height is not valid.", $plugin_slug );
		}
		if(!$this->set_aspect_ration($this->try_to_load($json, "keep_aspect_ratio"))) {
			$invalid["keep_aspect_ratio"] = __( "Value to keep aspect ratio couldn't be set.", $plugin_slug );
		}
		if(!$this->set_region_and_resolution($this->try_to_load($json, "region"), $this->try_to_load($json, "resolution"))) {
			$invalid["region"] = __( "Combination of region und resolution are not valid.", $plugin_slug );
		}
		if(!$this->set_marker_opacity($this->try_to_load($json, "marker_opacity"))) {
			$invalid["marker_opacity"] = __( "Marker opacity is not valid.", $plugin_slug );
		}
		if(!$this->set_marker_size($this->try_to_load($json, "marker_size"))) {
			$invalid["marker_size"] = __( "Marker size is not valid.", $plugin_slug );
		}
		if(!$this->set_tooltips($this->try_to_load($json, "tooltip_enable"))) {
			$invalid["tooltip_enable"] = __( "Tooltips could not be set.", $plugin_slug );
		}
		if(!$this->set_tooltip_colors($this->try_to_load($json, "tooltip_color"))) {
			$invalid["tooltip_color"] = __( "Tooltip color is not valid.", $plugin_slug );
		}
		if(!$this->set_tooltip_font_family($this->try_to_load($json, "tooltip_font_family"))) {
			$invalid["tooltip_font_family"] = __( "Tooltip font is not valid.", $plugin_slug );
		}
		if(!$this->set_tooltip_font_size($this->try_to_load($json, "tooltip_font_size"))) {
			$invalid["tooltip_font_size"] = __( "Tooltip font size is not valid.", $plugin_slug );
		}
		if(!$this->set_tooltip_trigger($this->try_to_load($json, "tooltip_trigger"))) {
			$invalid["tooltip_trigger"] = __( "Tooltip trigger is not valid.", $plugin_slug );
		}
		if(!$this->set_tooltip_html($this->try_to_load($json, "tooltip_html"))) {
			$invalid["tooltip_html"] = __( "Tooltip html is not valid.", $plugin_slug );
		}
		if(!$this->set_width($this->try_to_load($json, "width"))) {
			$invalid["width"] = __( "Width is not valid.", $plugin_slug );
		}
		return $invalid;
	}
	
	public function get_db_string() {
		$a = array(
			'background_color'            => ($this->background_color),
			'border_color'                => ($this->border_color),
			'border_width'                => ($this->border_width),
			'dataless_regions_color'      => ($this->dataless_regions_color),
			'enable_region_interactivity' => ($this->enable_region_interactivity),
			'height'                      => ($this->height),
			'keep_aspect_ratio'           => ($this->keep_aspect_ratio),
			'region'                      => ($this->region),
			'marker_opacity'              => ($this->marker_opacity),
			'marker_size'                 => ($this->marker_size),
			'resolution'                  => ($this->resolution),
			'tooltip_enable'              => ($this->tooltip_enable),
			'tooltip_color'               => ($this->tooltip_color),
			'tooltip_font_family'         => ($this->tooltip_font_family),
			'tooltip_font_size'           => ($this->tooltip_font_size),
			'tooltip_trigger'             => ($this->tooltip_trigger),
			'tooltip_html'                => ($this->tooltip_html),
			'width'                       => ($this->width)
		);
		
		return json_encode($a);
	}
	
	protected function try_to_load($array, $key) {
		if(isset($array[$key])) {
			return (trim($array[$key]));
		}
		return "";
	}
	
	public function set_background_color($color) {
		if(self::is_valid_hex_color($color)) {
			$this->background_color = $color;
			return true;
		}
		return false;
	}
	
	public function set_border_color($color) {
		if(self::is_valid_hex_color($color)) {
			$this->border_color = $color;
			return true;
		}
		return false;
	}
	
	public function set_border_width($width) {
		if(self::is_valid_number($width)) {
			$this->border_width = $width;
			return true;
		}
		return false;
	}
	
	public function set_dataless_regions_color($color) {
		if(self::is_valid_hex_color($color)) {
			$this->dataless_regions_color = $color;
			return true;
		}
		return false;
	}
	
	public static function get_map_mode($display_mode) {
		if($display_mode=="marker_by_coordinates")
			return "markers";
		else if($display_mode=="marker_by_name")
			return "markers";
		else if($display_mode=="textmode")
			return "text";
		else
			return "regions";
	}
	
	public function set_region_interactivity($value) {
		$values = array("true", "false");
		
		if(in_array($value, $values)) {
			$this->enable_region_interactivity = $value;
			return true;
		}
		return false;
	}
	
	public function set_height($height) {
		if($height == "auto" || self::is_valid_number($height)) {
			$this->height = $height;
			return true;
		}
		return false;
	}
	
	public function set_aspect_ration($value) {
		$values = array("true", "false");
		
		if(in_array($value, $values)) {
			$this->keep_aspect_ratio = $value;
			return true;
		}
		return false;
	}
	
	public function get_region_code() {
		return $this->region;
	}
	
	public function get_region_name() {
		$tree  = Map_Regions::get_tree();
		foreach($tree as $node) {
			// Find the corresponding node for $this->region
			if(isset($node[1]) && $node[1] == $this->region) {
				return $node[2];
			}
		}
		return "";
	}
	
	public function set_region_and_resolution($region, $resolution) {
		$tree  = Map_Regions::get_tree();
		
		$resolution = self::get_resolution_number($resolution);
		$resolution = intval($resolution);
		$values     = array(1,2,4,8,16);
		if(!in_array($resolution, $values)) {
			$resolution = 0;
		}
		
		foreach($tree as $node) {
			// Find the corresponding node for $region
			if(isset($node[1]) && $node[1] == $region) {
				$this->region = $region;
				
				if($node[3] & $resolution) {
					$this->resolution = self::get_resolution_name($resolution);
					return true;
				} else if($node[3] & 1) {
					$this->resolution = "countries";
					return true;
				} else if($node[3] & 2) {
					$this->resolution = "provinces";
					return true;
				} else {
					$this->resolution = "metros";
					return true;
				}
				
				break;
			}
		}
		return false;
	}
	
	public static function get_resolution_name($number) {
		if($number==4) {
			return "metros";
		} else if($number==2) {
			return "provinces";
		} else if($number==8) {
			return "continents";
		} else if($number==16) {
			return "subcontinents";
		} else {
			return "countries";
		}
	}
	
	public static function get_resolution_number($string) {
		if($string=="metros") {
			return 4;
		} else if($string=="provinces") {
			return 2;
		} else if($string=="countries"){
			return 1;
		} else if($string=="continents"){
			return 8;
		} else if($string=="subcontinents"){
			return 16;
		}
	}
	
	public function set_marker_opacity($value) {
		$value = (float)$value;
		if($value>1 || $value<0) {
			$value = 1;
		}
		$this->marker_opacity = $value;
		return true;
	}
	
	public function set_tooltips($value) {
		$values = array("true", "false");
		
		if(in_array($value, $values)) {
			$this->tooltip_enable = $value;
			return true;
		}
		return false;
	}
	
	protected function get_tooltip_trigger() {
		if($this->tooltip_enable=="true") {
			return $this->tooltip_trigger;
		} else {
			return "none";
		}
	}
	
	public function set_tooltip_colors($color) {
		if(self::is_valid_hex_color($color)) {
			$this->tooltip_color = $color;
			return true;
		}
		return false;
	}
	
	public function set_tooltip_font_family($family) {
		$this->tooltip_font_family = $family;
		return true;
	}
	
	public function set_tooltip_font_size($size) {
		if($size == "<global-font-size>" || self::is_valid_number($size)) {
			$this->tooltip_font_size = $size;
			return true;
		}
		return false;
	}
	
	public function set_tooltip_trigger($trigger) {
		if($trigger=="selection") {
			$this->tooltip_trigger = "selection";
		} else {
			$this->tooltip_trigger = "focus";
		}
		return true;
	}
	
	public function set_tooltip_html($is_html) {
		if($is_html=="true") {
			$this->tooltip_html = "true";
		} else {
			$this->tooltip_html = "false";
		}
		return true;
	}
	
	public function set_width($width) {
		if($width == "auto" || self::is_valid_number($width)) {
			$this->width = $width;
			return true;
		}
		return false;
	}
	
	public function set_marker_size($size) {
		if(self::is_valid_number($size)) {
			$this->marker_size = $size;
			return true;
		}
		return false;
	}
	
	public function to_array(Interactive_Map $map) {
		$colors     = $map->get_js_table()->get_colors();
		$color_max  = count($colors);
		$colors_arr = array("#666666");
		
		if($color_max>0) {
			$colors_arr = array_keys($colors);
		} else {
			$color_max = "1";
		}
	
		if(self::get_map_mode($map->get_display_mode())=="text") {
			$colors_arr = array("#000000");
			$color_max  = 1;
		}
		$default_options = array();
		$default_options["backgroundColor"]                  = array();
		$default_options["backgroundColor"]["fill"]          = ($this->background_color);
		$default_options["backgroundColor"]["stroke"]        = ($this->border_color);
		$default_options["backgroundColor"]["strokeWidth"]   = ($this->border_width);
		$default_options["colorAxis"]                        = array();
		$default_options["colorAxis"]["minValue"]            = "1";
		$default_options["colorAxis"]["maxValue"]            = (string)$color_max;
		$default_options["colorAxis"]["colors"]              = $colors_arr;
		//$default_options["colorAxis"]["values"]              = "null";
		$default_options["datalessRegionColor"]              = ($this->dataless_regions_color);
		$default_options["displayMode"]                      = (self::get_map_mode($map->get_display_mode()));
		$default_options["enableRegionInteractivity"]        = ($this->enable_region_interactivity);
		$default_options["height"]                           = ($this->height);
		$default_options["keepAspectRatio"]                  = ($this->keep_aspect_ratio);
		$default_options["legend"]                           = "none";
		/*
		$default_options["legend"]                           = array();
		$default_options["legend"]["numberFormat"]           = "auto";
		$default_options["legend"]["textStyle"]              = array();
		$default_options["legend"]["textStyle"]["color"]     = "black";
		$default_options["legend"]["textStyle"]["fontName"]  = "<global-font-name>";
		$default_options["legend"]["textStyle"]["fontSize"]  = "<global-font-size>";
		*/
		$default_options["region"]                           = ($this->region);
		$default_options["magnifyingGlass"]["enable"]        = "true";
		$default_options["magnifyingGlass"]["zoomFactor"]    = "5";
		$default_options["markerOpacity"]                    = ($this->marker_opacity);
		$default_options["resolution"]                       = ($this->resolution);
		$default_options["sizeAxis"]                         = array();
		$default_options["sizeAxis"]["maxSize"]              = $this->marker_size;
		$default_options["sizeAxis"]["maxValue"]             = "6";
		$default_options["sizeAxis"]["minSize"]              = $this->marker_size;
		$default_options["sizeAxis"]["minValue"]             = "6";
		$default_options["tooltip"]                          = array();
		$default_options["tooltip"]["textStyle"]             = array();
		$default_options["tooltip"]["textStyle"]["color"]    = ($this->tooltip_color);
		$default_options["tooltip"]["textStyle"]["fontName"] = ($this->tooltip_font_family);
		$default_options["tooltip"]["textStyle"]["fontSize"] = ($this->tooltip_font_size);
		$default_options["tooltip"]["trigger"]               = ($this->get_tooltip_trigger());
		$default_options["tooltip"]["isHtml"]                = ($this->tooltip_html);
		$default_options["width"]                            = ($this->width);
		$default_options["animation"]                        = array();
		$default_options["animation"]["duration"]            = "1000";
		$default_options["animation"]["easing"]              = "out";
		
		return $default_options;
	}
	
	public function has_html_tooltips() {
		if($this->tooltip_html=="false") {
			return false;
		} else {
			return true;
		}
	}
	
	public function to_json(Interactive_Map $map) {
		/*$colors     = $map->get_js_table()->get_colors();
		$color_max  = count($colors);
		$colors_arr = array("#666666");
		
		if($color_max>0) {
			$colors_arr = array_keys($colors);
		} else {
			$color_max = "1";
		}
	
		$default_options = array();
		$default_options["backgroundColor"]                  = array();
		$default_options["backgroundColor"]["fill"]          = ($this->background_color);
		$default_options["backgroundColor"]["stroke"]        = ($this->border_color);
		$default_options["backgroundColor"]["strokeWidth"]   = ($this->border_width);
		$default_options["colorAxis"]                        = array();
		$default_options["colorAxis"]["minValue"]            = "1";
		$default_options["colorAxis"]["maxValue"]            = (string)$color_max;
		$default_options["colorAxis"]["colors"]              = $colors_arr;
		//$default_options["colorAxis"]["values"]              = "null";
		$default_options["datalessRegionColor"]              = ($this->dataless_regions_color);
		$default_options["displayMode"]                      = (self::get_map_mode($map->get_display_mode()));
		$default_options["enableRegionInteractivity"]        = ($this->enable_region_interactivity);
		$default_options["height"]                           = ($this->height);
		$default_options["keepAspectRatio"]                  = ($this->keep_aspect_ratio);
		$default_options["legend"]                           = "none";
		/*
		$default_options["legend"]                           = array();
		$default_options["legend"]["numberFormat"]           = "auto";
		$default_options["legend"]["textStyle"]              = array();
		$default_options["legend"]["textStyle"]["color"]     = "black";
		$default_options["legend"]["textStyle"]["fontName"]  = "<global-font-name>";
		$default_options["legend"]["textStyle"]["fontSize"]  = "<global-font-size>";
		*//*
		$default_options["region"]                           = ($this->region);
		$default_options["magnifyingGlass"]["enable"]        = "true";
		$default_options["magnifyingGlass"]["zoomFactor"]    = "5";
		$default_options["markerOpacity"]                    = ($this->marker_opacity);
		$default_options["resolution"]                       = ($this->resolution);
		$default_options["sizeAxis"]                         = array();
		$default_options["sizeAxis"]["maxSize"]              = $this->marker_size;
		$default_options["sizeAxis"]["maxValue"]             = "6";
		$default_options["sizeAxis"]["minSize"]              = $this->marker_size;
		$default_options["sizeAxis"]["minValue"]             = "6";
		$default_options["tooltip"]                          = array();
		$default_options["tooltip"]["textStyle"]             = array();
		$default_options["tooltip"]["textStyle"]["color"]    = ($this->tooltip_color);
		$default_options["tooltip"]["textStyle"]["fontName"] = ($this->tooltip_font_family);
		$default_options["tooltip"]["textStyle"]["fontSize"] = ($this->tooltip_font_size);
		$default_options["tooltip"]["trigger"]               = ($this->get_tooltip_trigger());
		$default_options["width"]                            = ($this->width);
		$default_options["animation"]                        = array();
		$default_options["animation"]["duration"]            = "1000";
		$default_options["animation"]["easing"]              = "out";
		//return $default_options;*/

		return json_encode($this->to_array($map));
	}
	
	public static function is_valid_hex_color($color) {
		return preg_match('/^#[a-f0-9]{6}$/i', strtolower($color));
    }
	
	public static function is_valid_number($number) {
		return preg_match('/^\d+$/', $number);
	}
}

?>