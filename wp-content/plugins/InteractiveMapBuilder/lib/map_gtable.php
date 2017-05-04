<?php
/**
 * Interactive Map Builder Plugin.
 *
 * @author    Fabian Vellguth <info@meisterpixel.com>
 * @link      http://meisterpixel.com/
 * @copyright 2014 meisterpixel.com
 */
 
/**
 *  Class that is used as a container for the static generation functions for the google data tables.
 *  
 *  @class Map_GTable
 */
class Map_GTable {

	/**
	 *  Private constructor. Just to prevent that objects are created from the outside.
	 *  
	 *  @private
	 *  @constructor
	 */
	private function __construct() {
	}
	
	/**
	 *  Generates and converts to the Google Data Table. Only funcion accessible from outside.
	 *  
	 *  @method generate_google_data_table
	 *  @static
	 *  @param {Interactive_Map} $map The map where the data table belongs to.
	 *  @return {Array} Returns an array that will be converted and outputted to javascript. Within the javascript the
	 *                  Data Table will be created with: var table = new google.visualization.DataTable(this_array);
	 */
	public static function generate_google_data_table($mode, Map_Table $table) {
		return array(
		           "cols" => self::generate_google_data_table_cols($mode),
				   "rows" => self::generate_google_data_table_rows($mode, $table)
		       );
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
	protected static function generate_google_data_table_cols($mode) {
		if($mode=="marker_by_coordinates") {
			return self::generate_google_data_table_cols_coords();
		} else if($mode=="marker_by_name") {
			return self::generate_google_data_table_cols_name();
		} else if($mode=="regions") {
			return self::generate_google_data_table_cols_region();
		} else if($mode=="textmode") {
			return self::generate_google_data_table_cols_textmode();
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
	protected static function generate_google_data_table_rows($mode, Map_Table $table) {
		if($mode=="marker_by_coordinates") {
			return self::generate_google_data_table_rows_coords($table);
		} else if($mode=="marker_by_name") {
			return self::generate_google_data_table_rows_name($table);
		} else if($mode=="regions") {
			return self::generate_google_data_table_rows_region($table);
		} else if($mode=="textmode") {
			return self::generate_google_data_table_rows_textmode($table);
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
	protected static function generate_google_data_table_cols_helper($label, $type, $role=null, $pattern="", $id="") {
		$o = array(
				 "id"      => $id,
				 "label"   => $label,
				 "pattern" => $pattern,
				 "type"    => $type
			 );
		if($role!==null) {
			$o["p"] = array("role" => $role, "html" => "true");
		}
			
		return $o;
	}
	
	/**
	 *  Creates the columns for the mode "marker_by_coordinates".
	 *  
	 *  @method generate_google_data_table_cols_coords
	 *  @return Returns a formated array of columns specifically for this mode.
	 */
	protected static function generate_google_data_table_cols_coords() {
		return array(
				   self::generate_google_data_table_cols_helper("Latitude",  "number"),
				   self::generate_google_data_table_cols_helper("Longitude", "number"),
				   self::generate_google_data_table_cols_helper("Name",      "string"),
				   self::generate_google_data_table_cols_helper("Color",     "number"),
				   self::generate_google_data_table_cols_helper("Size",      "number"),
				   self::generate_google_data_table_cols_helper("Tooltip",   "string", "tooltip") 
			   );
	}
	
	/**
	 *  Creates the rows for the mode "marker_by_coordinates".
	 *  
	 *  @method generate_google_data_table_rows_coords
	 *  @param {Map_Table} $table The table where the rows should come from.
	 *  @return Returns a formated array of rows specifically for this mode.
	 */
	protected static function generate_google_data_table_rows_coords(Map_Table $table) {
		$o      = array();
		$rows   = $table->get_rows();
		$colors = $table->get_colors();
		foreach($rows as $row) {
			$row_cols = array(
							array("v" => $row["latitude"]),
							array("v" => $row["longitude"]),
							array("v" => $row["tooltip_title"]),
							array("v" => $colors[$row["color"]]),
							//array("v" => $row["size"]),
							array("v" => 1),
							array("v" => $row["tooltip_text"]) 
						);
			
			$o[] = array("c" => $row_cols);
		}
		return $o;
	}
	
	/**
	 *  Creates the columns for the mode "marker_by_name".
	 *  
	 *  @method generate_google_data_table_cols_name
	 *  @return Returns a formated array of columns specifically for this mode.
	 */
	protected static function generate_google_data_table_cols_name() {
		return array(
				   self::generate_google_data_table_cols_helper("Location",  "string"),
				   self::generate_google_data_table_cols_helper("Name",      "string"),
				   self::generate_google_data_table_cols_helper("Color",     "number"),
				   self::generate_google_data_table_cols_helper("Size",      "number"),
				   self::generate_google_data_table_cols_helper("Tooltip",   "string", "tooltip") 
			   );
	}
	
	/**
	 *  Creates the rows for the mode "marker_by_name".
	 *  
	 *  @method generate_google_data_table_rows_name
	 *  @param {Map_Table} $table The table where the rows should come from.
	 *  @return Returns a formated array of rows specifically for this mode.
	 */
	protected static function generate_google_data_table_rows_name(Map_Table $table) {
		$o      = array();
		$rows   = $table->get_rows();
		$colors = $table->get_colors();
		foreach($rows as $row) {
			$row_cols = array(
							array("v" => $row["location"]),
							array("v" => $row["tooltip_title"]),
							array("v" => $colors[$row["color"]]),
							//array("v" => $row["size"]),
							array("v" => 1),
							array("v" => $row["tooltip_text"]) 
						);
			
			$o[] = array("c" => $row_cols);
		}
		return $o;
	}
	
	/**
	 *  Creates the columns for the mode "regions".
	 *  
	 *  @method generate_google_data_table_cols_region
	 *  @return Returns a formated array of columns specifically for this mode.
	 */
	protected static function generate_google_data_table_cols_region() {
		return array(
				   self::generate_google_data_table_cols_helper("Location",  "string"),
				   self::generate_google_data_table_cols_helper("Color",     "number"),
			       self::generate_google_data_table_cols_helper("Tooltip",   "string", "tooltip") 
			   );
	}
	
	/**
	 *  Creates the columns for the mode "textmode".
	 *  
	 *  @method generate_google_data_table_cols_textmode
	 *  @return Returns a formated array of columns specifically for this mode.
	 */
	protected static function generate_google_data_table_cols_textmode() {
		return array(
				   self::generate_google_data_table_cols_helper("Location",  "string"),
				   self::generate_google_data_table_cols_helper("Size",      "number"),
			       self::generate_google_data_table_cols_helper("Tooltip",   "string", "tooltip") 
			   );
	}
	
	/**
	 *  Creates the rows for the mode "regions".
	 *  
	 *  @method generate_google_data_table_rows_region
	 *  @param {Map_Table} $table The table where the rows should come from.
	 *  @return Returns a formated array of rows specifically for this mode.
	 */
	protected static function generate_google_data_table_rows_region(Map_Table $table) {
		$o      = array();
		$rows   = $table->get_rows();
		$colors = $table->get_colors();
		foreach($rows as $row) {
			$row_cols = array(
							array(
							    "v" => $row["location"], 
								"f" => $row["tooltip_title"]
							),
							array("v" => $colors[$row["color"]]),
							array("v" => $row["tooltip_text"])
						);
			
			$o[] = array("c" => $row_cols);
		}
		return $o;
	}
	
	/**
	 *  Creates the rows for the mode "textmode".
	 *  
	 *  @method generate_google_data_table_rows_textmode
	 *  @param {Map_Table} $table The table where the rows should come from.
	 *  @return Returns a formated array of rows specifically for this mode.
	 */
	protected static function generate_google_data_table_rows_textmode(Map_Table $table) {
		$o      = array();
		$rows   = $table->get_rows();
		$colors = $table->get_colors();
		foreach($rows as $row) {
			$row_cols = array(
							array(
							    "v" => $row["location"], 
								"f" => $row["tooltip_title"]
							),
							array("v" => 1.0),
							array("v" => $row["tooltip_text"])
						);
			
			$o[] = array("c" => $row_cols);
		}
		return $o;
	}

}
?>