<?php
/**
 * Interactive Map Builder Plugin.
 *
 * @author    Fabian Vellguth <info@meisterpixel.com>
 * @link      http://meisterpixel.com/
 * @copyright 2014 meisterpixel.com
 */
 
/**
 *  Class that is used to handle all operations on the data table and ensures a specfic structure.
 *  
 *  @class Map_Table
 */
class Map_Table {
	/**
	 *  Array that contains all rows.
	 *  
	 *  @type Array
	 */
	private $rows = array();
	
	/**
	 *  Public constructor for the Map_Table Class.
	 *  
	 *  @constructor
	 *  @param {String} [$json=null] A json-encoded string that was created with get_db_string().
	 */
	public function __construct($json=null) {
		if($json!==null)
			$this->load_db_string($json);
	}
	
	/**
	 *  Loads the string that was created with get_db_string().
	 *  
	 *  @method load_db_string
	 *  @param {String} $data A json-encoded string for the Map_Table.
	 */
	public function load_db_string($data) {
		$json = json_decode($data, true);
		
		$this->load_from_array($json);
	}
	
	/**
	 *  Adds rows from the given array.
	 *  
	 *  @method load_from_array
	 *  @protected
	 *  @param {Array} $array An array with rows. Each row will be validated with 
	 *                        is_valid_row() and (if valid) will be added.
	 */
	public function load_from_array($array, $decode=false) {
		if(!is_array($array))
			return;
		
		foreach($array as $row) {
			$valid_row = $this->is_valid_row($row);
			
			/*if($decode) {
				$this->add_row(utf8_decode($row['location']), 
							   utf8_decode($row['latitude']), 
							   utf8_decode($row['longitude']),
							   utf8_decode($row['color']), 
							   "1", 
							   utf8_decode($row['tooltip_title']),
							   utf8_decode($row['tooltip_text']), 
							   utf8_decode($row['click_action_value']));
			} else {*/
				$this->add_row($row['location'], 
							   $row['latitude'], 
							   $row['longitude'],
							   $row['color'], 
							   "1", 
							   $row['tooltip_title'],
							   $row['tooltip_text'], 
							   $row['click_action_value']);
			/*}*/
		}
	}
	
	/**
	 *  Checks if the given array has the wanted entries.
	 *  
	 *  @method is_valid_row
	 *  @protected
	 *  @param {Array} $row The row that will be validated.
	 *  @return {Boolean} Returns if the row is valid.
	 */
	protected function is_valid_row($row) {
		if(!is_array($row))
			return false;
		
		$expected_keys = array("location", "latitude", "longitude", "color", "size", "tooltip_title", "tooltip_text", "click_action_value");
		
		foreach($expected_keys as $key) {
			if(!array_key_exists($key, $row))
				return false;
		}
		
		return true;
	}
	
	/**
	 *  Encodes the object to a string that can be stored somewhere and can be decoded with load_db_string().
	 *  
	 *  @method get_db_string
	 *  @return {String} Returns the json-encoded string.
	 */
	public function get_db_string() {
		$output = array();
		
		foreach($this->rows as $row) {
			/*$o = array("location"           => utf8_encode($row['location']), 
					   "latitude"           => utf8_encode($row['latitude']), 
					   "longitude"          => utf8_encode($row['longitude']), 
					   "color"              => utf8_encode($row['color']), 
					   "size"               => utf8_encode($row['size']), 
					   "tooltip_title"      => utf8_encode($row['tooltip_title']), 
					   "tooltip_text"       => utf8_encode($row['tooltip_text']), 
					   "click_action_value" => utf8_encode($row['click_action_value']));*/
			$o = array("location"           => ($row['location']), 
					   "latitude"           => ($row['latitude']), 
					   "longitude"          => ($row['longitude']), 
					   "color"              => ($row['color']), 
					   "size"               => ($row['size']), 
					   "tooltip_title"      => ($row['tooltip_title']), 
					   "tooltip_text"       => ($row['tooltip_text']), 
					   "click_action_value" => ($row['click_action_value']));
			$output[] = $o;
		}
		
		return json_encode($output);
	}
	
	/**
	 *  Adds a row to the table.
	 *  
	 *  @method add_row
	 *  @param {String} $location
	 *  @param {String} $latitude
	 *  @param {String} $longitude
	 *  @param {String} $color
	 *  @param {String} $size
	 *  @param {String} $tooltip_title
	 *  @param {String} $tooltip_text
	 *  @param {String} $click_action_value
	 */
	public function add_row($location, $latitude, $longitude, $color, $size, $tooltip_title, $tooltip_text, $click_action_value) {
		$row = array("location"           => $location, 
					 "latitude"           => $latitude, 
					 "longitude"          => $longitude, 
					 "color"              => $color, 
					 "size"               => $size, 
					 "tooltip_title"      => $tooltip_title, 
					 "tooltip_text"       => $tooltip_text, 
					 "click_action_value" => $click_action_value);
					 
		$this->rows[] = $row;
	}
	
	/**
	 *  Returns all rows.
	 *  
	 *  @method get_rows
	 *  @return {Array} Returns an array with all rows.
	 */
	public function get_rows() {
		return $this->rows;
	}
	
	/**
	 *  Returns the number of rows in the table.
	 *  
	 *  @method get_length
	 *  @return {Int} Returns number of rows.
	 */
	public function get_length() {
		return count($this->rows);
	}
	
	/**
	 *  Clears/deletes all rows.
	 *  
	 *  @method clear
	 */
	public function clear() {
		$this->rows = array();
	}
	
	/**
	 *  Returns the colors in the table and gives each color a unique number, starting with 1.
	 *  Array for example look like this:
	 *  
	 *     array( "#AACCEE" => 1, "#FFFFFF" => 2, "#EEEEEE" => 3, ...)
	 *  
	 *  @method get_colors
	 *  @return {Array} Returns the colors in the table. See description above.
	 */
	public function get_colors() {
		$colors = array();
		$number = 1;
		$rows   = $this->get_rows();
		foreach($rows as $row) {
			if(!isset($colors[$row["color"]])) {
				$colors[$row["color"]] = $number;
				$number++;
			}
		}
		return $colors;
	}
	
	public function click_actions_to_array() {
		$actions = array();
		$table   = $this->get_rows();

		foreach($table as $row) {
			$actions[] = $row["click_action_value"];
		}
		return $actions;
	}
	
	/**
	 *  Encodes the click action values from the table.
	 *  
	 *  @method click_actions_to_json
	 *  @return {String} Returns an encoded string with the click action values.
	 */
	public function click_actions_to_json() {
		/*$actions = array();
		$table   = $this->get_rows();

		foreach($table as $row) {
			$actions[] = $row["click_action_value"];
		}*/
		return json_encode($this->click_actions_to_array());
	}
	
	/**
	 *  Converts the table to the google data table and encodes this to a json-encoded string.
	 *  
	 *  @method to_gtable
	 *  @return {String} Returns this table as a json-encoded string for the google data table.
	 */
	public function to_gtable(Interactive_Map $map, $encode=true) {
		if($encode) {
			return json_encode(Map_GTable::generate_google_data_table($map->get_display_mode(), $map->get_js_table()));
		} else {
			return Map_GTable::generate_google_data_table($map->get_display_mode(), $map->get_js_table());
		}
	}
	
	/**
	 *  Basically another name for the method get_db_string().
	 *  
	 *  @method to_json
	 *  @return {String} Returns the json-encoded string.
	 */
	public function to_json() {
		return $this->get_db_string();
	}
	
}

?>