<?php
/**
 * Interactive Map Builder Plugin.
 *
 * @author    Fabian Vellguth <info@meisterpixel.com>
 * @link      http://meisterpixel.com/
 * @copyright 2014 meisterpixel.com
 */
 
/**
 *  Class that handles all interactions with a map.
 * 
 *  @package Data Objects
 *  @class Interactive_Map
 */
class Interactive_Map {
	private $id          = -1;
	private $name        = null;
	private $description = null;
	private $javascript  = null;
	private $js_options  = null;
	private $js_table    = null;
	private $region      = null;
	private $created     = null;
	private $created_obj = null;
	private $changed     = null;
	private $changed_obj = null;
	private $display_mode = "marker_by_name";
	private $click_action = null;
	private $generated_id = null;
	private $template_html = "";
	private $template_css = "";
	private $template_js = "";
	private $image = null;
	private $table = null;
	private $is_import = false;
	
	/**
	 *  Public constructor.
	 *  
	 *  @constructor
	 */
	public function __construct($id, $name, $description, $js_options, $js_table, $display_mode, $region, $changed, $created, $click_action_id = -1, $image = null, $template_html="", $template_css="", $template_js="") {
		// Setting everthing up
		// 1   - Properties
		$this->set_id($id);
		$this->set_name($name);
		$this->set_description($description);
		$this->set_display_mode($display_mode);
		//$this->set_region($region);
		$this->set_changed($changed);
		$this->set_created($created);
		
		// 2   - Map Table
		$table = new Map_Table($js_table, $this->get_display_mode());
		$this->set_js_table($table);
		
		$this->set_table($js_table);
		//$this->table = $js_table;
		
		// 3   - Map Options
		$options = new Map_Options($js_options);
		$this->set_js_options($options);
		
		// 4   - Click Action
		$action = null;
		if($click_action_id === -1) {
			//$action = Click_Action::create_empty();
			$action = Click_Action::get_default_click_action();
		} else if((int)$click_action_id === -2) {
			// Use the template given by the $template_ variables
			$action = Click_Action::create_empty();
			$action->set_id(-2);
			$action->set_html($template_html);
			$action->set_css($template_css);
			$action->set_javascript($template_js);
		} else {
			$action = Click_Action::get($click_action_id);
			if(!$action)
				$action = Click_Action::get_default_click_action();
		}
		$this->set_click_action($action);

		// 5   - Image
		$this->set_image($image);
		
		// 6   - Set the template vars
		$this->set_template_html($template_html);
		$this->set_template_css($template_css);
		$this->set_template_js($template_js);
	}
	
	/**
	 *  Returns the CREATE TABLE for the map table.
	 *  
	 *  @method get_create_sql
	 *  @return {String} Returns the sql create command as a string.
	 */
	public static function get_create_sql() {
		$builder    = Interactive_Map_Builder::get_instance();
		$table_name = $builder->get_config("maps_table");
		$sql = "CREATE TABLE $table_name (
			  id  mediumint  unsigned NOT NULL AUTO_INCREMENT,
			  name tinytext  NOT NULL,
			  description text,
			  js_options text,
			  js_table text,
			  picture text,
			  template_html text,
			  template_css text,
			  template_js text,
			  display_mode tinytext  NOT NULL,
			  time_created timestamp DEFAULT '0000-00-00 00:00:00' NOT NULL  ,
			  time_changed timestamp DEFAULT NOW() ON UPDATE now()  ,
			  click_action_id  mediumint DEFAULT -1,
			  UNIQUE KEY id (id)
			) DEFAULT CHARSET=utf8;
			";
		return $sql;
	}
	
	public static function get_drop_table_sql() {
		$builder    = Interactive_Map_Builder::get_instance();
		$table_name = $builder->get_config("maps_table");
		return "DROP TABLE $table_name";
	}
	
	/**
	 *  Method to get all maps from the database.
	 *  
	 *  @method get_maps
	 *  @static
	 *  @return {Array} Returns an array with maps.
	 */
	public static function get_maps($offset = null, $limit = null, $order_by = null, $order_dir = null) {
		global $wpdb;
		$maps_array = array();
		$builder    = Interactive_Map_Builder::get_instance();
		$table_name = $builder->get_config("maps_table");
		
		$sql  = "SELECT * FROM $table_name";
		if($order_by!==null && $order_dir!==null) {
			$sql .= " ORDER BY $order_by $order_dir";
		}
		if($offset!==null && $limit!==null) {
			$sql .= " LIMIT " . intval($offset) . ", " . intval($limit);
		}
		$maps = $wpdb->get_results($sql);
		foreach($maps as $map) {
			$maps_array[] = new Interactive_Map($map->id, 
			                                    $map->name, 
												$map->description, 
												$map->js_options, 
												$map->js_table, 
												$map->display_mode, 
												"", 
												$map->time_changed, 
												$map->time_created, 
												$map->click_action_id, 
												$map->picture,
												$map->template_html,
												$map->template_css,
												$map->template_js);
		}
		
		return $maps_array;
	}
	
	public static function get_map_count() {
		global $wpdb;
		$builder    = Interactive_Map_Builder::get_instance();
		$table_name = $builder->get_config("maps_table");
		return $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
	}
	
	/**
	 *  Method to get a specific map from the database.
	 *  
	 *  @method get_map
	 *  @param {String|Int} $id The id of the map.
	 *  @return {Interactive_Map|Boolean} Returns the map if id exists or false otherwise.
	 */
	public static function get_map($id) {
		global $wpdb;
		$maps_array = array();
		$builder    = Interactive_Map_Builder::get_instance();
		$table_name = $builder->get_config("maps_table");
		
		$sql = "SELECT * FROM $table_name WHERE id=" . esc_sql($id);
		$map = $wpdb->get_row($sql);
		if($map===null)
			return false;
		$map_object = new Interactive_Map($map->id, 
		                                  $map->name, 
										  $map->description, 
										  $map->js_options, 
										  $map->js_table, 
										  $map->display_mode, 
										  "", 
										  $map->time_changed, 
										  $map->time_created, 
										  $map->click_action_id, 
										  $map->picture,
										  $map->template_html,
										  $map->template_css,
										  $map->template_js);
		
		return $map_object;
	}
	
	protected static function mask($array, $key, $null_value) {
		if(is_array($array) && isset($array[$key]) && $array[$key]!="")
			return (stripslashes_deep($array[$key]));
		else
			return $null_value;
	}
	
	public static function fill_from_post() {
		$request      = $_REQUEST;
		$id           = self::mask($request, 'id', '-1');
		$name         = self::mask($request, 'title', 'No Name');
		$table        = self::mask($request, 'table', '');
		$description  = "";
		$options      = "";
		$display_mode = "";
		$region       = "";
		$map = new Interactive_Map($id, $name, $description, $options, "", $display_mode, $region, null, null);
		$map->set_js_table($table);
		return $map;
	}
	
	/**
	 *  Generates the data table for the click action preview with the given click action values.
	 *  
	 *  @method generate_sample_table
	 *  @static
	 *  @param {String} $tab1 The click action value for Germany in the preview.
	 *  @param {String} $tab2 The click action value for Spain in the preview.
	 *  @param {String} $tab3 The click action value for Italy in the preview.
	 *  @return {Map_Table} Returns the data table.
	 */
	public static function generate_sample_table($tab1="", $tab2="", $tab3="") {
		$table = new Map_Table();
		$table->add_row("germany", "52.516667", "13.383333", "#bbbbbb", "50", "Test", "Another Text", $tab1);
		$table->add_row("spain", "40.433333", "-3.7", "#bbbbbb", "50", "", "", $tab2);
		$table->add_row("italy", "41.9", "12.483333", "#bbbbbb", "50", "", "", $tab3);
		return $table;
	}
	
	/**
	 *  Generates the map for the click action preview.
	 *  
	 *  @method get_preview
	 *  @static
	 *  @return {Interactive_Map} Returns the map.
	 */
	public static function get_preview() {
		$map = new Interactive_Map(0,"Preview","This is the map for the preview","","","regions","150",NULL,NULL);

		$map->set_js_table(self::generate_sample_table("This is Germany.", "This is Spain.", "This is Italy."));
		
		$options = new Map_Options();
		$options->set_dataless_regions_color("#f4f4f4");
		$options->set_region_interactivity("true");
		$options->set_region_and_resolution("150", "countries");
		$options->set_width("auto");
		$options->set_height("250");
		$options->set_tooltips("false");
		$options->set_marker_size("6");
		$map->set_js_options($options);
		
		return $map;
	}
	
	public function delete() {
		global $wpdb;
		
		$builder    = Interactive_Map_Builder::get_instance();
		$table_name = $builder->get_config("maps_table");
		
		$result = $wpdb->delete( $table_name, array( 'id' => $this->get_id()) );
		
		if($result===false) {
			return false;
		}
		return true;
	}
	
	/**
	 *  Saves the map to the database. 
	 *  
	 *  If the id is set to -1, then the map will be inserted. The new id is automatically set
	 *  and is available over get_id() after the call of this method.
	 *  Otherwise the map with the corresponding id will be updated.
	 *  
	 *  @method save
	 *  @return {Boolean} Returns true if saving was successful and false otherwise.
	 */
	public function save() {
		global $wpdb;
		
		$builder    = Interactive_Map_Builder::get_instance();
		$table_name = $builder->get_config("maps_table");
		
		if($this->get_id() != "-1") {
			$result = $wpdb->update( 
				$table_name , 
				array( 
					'name'            => $this->get_name(),
					'description'     => $this->get_description(),
					'js_options'      => $this->get_js_options()->get_db_string(),
					'js_table'        => $this->get_js_table()->get_db_string(),
					'display_mode'    => $this->get_display_mode(),
					'click_action_id' => $this->get_click_action()->get_id(),
					//'time_created'    => 'NULL',
					//'time_changed'    => 'NULL',
					'picture'         => $this->get_image(),
					'template_html'   => $this->get_template_html(),
					'template_css'    => $this->get_template_css(),
					'template_js'     => $this->get_template_js(),
				), 
				array( 'id' => $this->get_id() ), 
				array( 
					'%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'
				), 
				array( '%d' ) 
			);
		} else {
			$result = $wpdb->insert( 
				$table_name , 
				array( 
					'id'              => $this->get_id(),
					'name'            => $this->get_name(),
					'description'     => $this->get_description(),
					'js_options'      => $this->get_js_options()->get_db_string(),
					'js_table'        => $this->get_js_table()->get_db_string(),
					'display_mode'    => $this->get_display_mode(),
					'click_action_id' => $this->get_click_action()->get_id(),
					'time_created'    => 'NULL',
					//'time_changed'    => 'NULL',
					'picture'         => $this->get_image(),
					'template_html'   => $this->get_template_html(),
					'template_css'    => $this->get_template_css(),
					'template_js'     => $this->get_template_js(),
				), 
				array( 
					'%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'
				)
			);
			if($result!==false) {
				$this->set_id($wpdb->insert_id);
			}
		}
		
		if($result===false) {
			return false;
		}
		return true;
	}
	
	/**
	 *  Sets the id of the map.
	 *  
	 *  @method set_id
	 *  @param {Int|String} $id The id of the map.
	 */
	public function set_id($id) {
		$this->id = $id;
	}
	
	/**
	 *  Returns the id of the map.
	 *  
	 *  @method get_id
	 *  @return {Int|String} Returns the id.
	 */
	public function get_id() {
		return $this->id;
	}
	
	/**
	 *  Sets the name of the map.
	 *  
	 *  @method set_name
	 *  @param {String} $name The name of the map.
	 */
	public function set_name($name) {
		if($name=="" && $this->name===null) {
			$this->name = __("(No title)", Interactive_Map_Builder::get_instance()->get_plugin_slug());
		} else {
			$this->name = $name;
		}
	}
	
	/**
	 *  Sets an empty name to the map. This is used when creating the from for a new map.
	 *  
	 *  @method empty_name
	 */
	public function empty_name() {
		$this->name = "";
	}
	
	/**
	 *  Returns the name of the map.
	 *  
	 *  @method get_name
	 *  @return {String} Returns the name.
	 */
	public function get_name() {
		return $this->name;
	}
	
	/**
	 *  Sets the description of the map.
	 *  
	 *  @method set_description
	 *  @param {String} $description The description of the map.
	 */
	public function set_description($description) {
		$this->description = $description;
	}
	
	/**
	 *  Returns the description of the map.
	 *  
	 *  @method get_description
	 *  @return {String} Returns the description.
	 */
	public function get_description() {
		return $this->description;
	}
	public function set_js_options(Map_Options $options) {
		$this->js_options = $options;
	}
	public function get_js_options() {
		return $this->js_options;
	}
	public function set_js_table(Map_Table $table) {
		$this->js_table = $table;
	}
	public function get_js_table() {
		return $this->js_table;
	}
	public function set_changed($changed) {
		$this->changed_obj = null;
		$this->changed     = $changed;
	}
	public function get_changed() {
		return $this->changed;
	}
	public function set_created($created) {
		$this->created_obj = null;
		$this->created     = $created;
	}
	
	public function get_created() {
		return $this->created;
	}
	
	/**
	 *  Sets the region id of the map.
	 *  
	 *  @method set_region
	 *  @param {String} $region The region id.
	 */
	public function set_region($region) {
		$this->region = $region;
	}
	
	/**
	 *  Returns the region id of the map.
	 *  
	 *  @method get_region
	 *  @return {String} Returns the region id.
	 */
	public function get_region() {
		return $this->get_js_options()->get_region_name();
	}
	
	/**
	 *  Returns the time as Timestamp when the map was created.
	 *  
	 *  @method get_date_created
	 *  @return {Timestamp} Returns the time as Timestamp.
	 */
	public function get_date_created() {
		if($this->created_obj===null)
			$this->created_obj = strtotime($this->created);
		return $this->created_obj;
	}
	
	/**
	 *  Returns the time as Timestamp when the map was modified the last time.
	 *  
	 *  @method get_date_changed
	 *  @return {Timestamp} Returns the time as Timestamp.
	 */
	public function get_date_changed() {
		if($this->changed_obj===null)
			$this->changed_obj = strtotime($this->changed);
		return $this->changed_obj;
	}
	
	/**
	 *  Returns the display mode.
	 *  
	 *  @method get_display_mode
	 *  @return {String} Returns one of these values: "marker_by_coordinates", "marker_by_name", "regions".
	 */
	public function get_display_mode() {
		return $this->display_mode;
	}
	
	/**
	 *  Sets the display mode to one of the following:
	 *   - marker_by_coordinates   Markers are found by coordinates.
	 *   - marker_by_name          Markers are found by the name of the location.
	 *   - regions                 Regions (,also found by the name of the location).
	 *  
	 *  @method set_display_mode
	 *  @param {String} $mode Possible values: "marker_by_coordinates", "marker_by_name", "regions". Mode changes only
	 *                        if it is one of the three.
	 */
	public function set_display_mode($mode) {
		$modes = array("marker_by_coordinates", "marker_by_name", "regions", "textmode");
		
		if(in_array($mode, $modes)) {
			$this->display_mode = $mode;
		}
	}
	
	/**
	 *  Indicates if the map shows markers.
	 *  
	 *  @method is_markers
	 *  @return {Boolean} Returns true if the map shows markers and false otherwise.
	 */
	public function is_markers() {
		return $this->display_mode == "marker_by_coordinates" || $this->display_mode == "marker_by_name";
	}
	
	/**
	 *  Indicates if the map shows regions.
	 *  
	 *  @method is_regions
	 *  @return {Boolean} Returns true if the map shows regions and false otherwise.
	 */
	public function is_regions() {
		return $this->display_mode == "regions";
	}
	
	/**
	 *  Sets the click action for this map.
	 *  
	 *  @method set_click_action
	 *  @param {Click_Action} $action The click action.
	 */
	public function set_click_action(Click_Action $action) {
		$this->click_action = $action;
	}
	
	/**
	 *  Returns the click action that belongs to the map.
	 *  
	 *  @method get_click_action
	 *  @return {Click_Action} Returns the click action.
	 */
	public function get_click_action() {
		return $this->click_action;
	}
	
	/**
	 *  Returns the image as string.
	 *  
	 *  @method get_image
	 *  @return {String} The image encoded as a string.
	 */
	public function get_image() {
		return $this->image;
	}
	
	/**
	 *  Sets the image of the map.
	 *  The string should contain a png encoded image string. It will later be displayed
	 *  by using <img src="<?php echo $map->get_image(); ?>" />.
	 *  
	 *  @method set_image
	 *  @param {String} $img The image encoded as string.
	 */
	public function set_image($img) {
		$this->image= $img;
	}
	
	/**
	 *  Sets the template html.
	 *  
	 *  @method set_template_html
	 *  @param {String} $html The html of the template.
	 */
	public function set_template_html($html) {
		$this->template_html = $html;
	}
	
	/**
	 *  Returns the html of the map template.
	 *  
	 *  @method get_template_html
	 *  @return {String} The HTML of the template.
	 */
	public function get_template_html() {
		return $this->template_html;
	}
	
	/**
	 *  Sets the CSS of the template.
	 *  
	 *  @method set_template_css
	 *  @param {String} $css The CSS of the template.
	 */
	public function set_template_css($css) {
		$this->template_css = $css;
	}
	
	/**
	 *  Returns the css of the map template.
	 *  
	 *  @method get_template_css
	 *  @return {String} The CSS of the template.
	 */
	public function get_template_css() {
		return $this->template_css;
	}
	
	/**
	 *  Sets the JavaScript of the template.
	 *  
	 *  @method set_template_js
	 *  @param {String} $js The JavaScript of the template.
	 */
	public function set_template_js($js) {
		$this->template_js = $js;
	}
	
	/**
	 *  Returns the JavaScript of the map template.
	 *  
	 *  @method get_template_js
	 *  @return {String} The JavaScript of the template.
	 */
	public function get_template_js() {
		return $this->template_js;
	}
	
	public function set_table($table) {
		$this->table = $table;
	}
	
	public function get_table() {
		return $this->table;
	}
	
	/**
	 *  Gets and creates the generated_id.
	 *  
	 *  The idea is to generate a unique number for each call of to_html(). This is important for the
	 *  generation of html-ids. Otherwise it could happen that two calls of to_html() would create the
	 *  same html output.
	 *  
	 *  @method get_generated_id
	 *  @return {Int} Returns a unique number for this map and call of to_html().
	 */
	private function get_generated_id() {
		if($this->generated_id === null)
			$this->generated_id = Interactive_Map_Builder::get_instance()->generate_map_id();
		return $this->generated_id;
	}
	
	/**
	 *  Resets the generated id.
	 *  
	 *  @method reset_generated_id
	 */
	private function reset_generated_id() {
		$this->generated_id = null;
	}
	
	/**
	 *  Renders the map to html. Multiple calls within a document are possible.
	 *  
	 *  @method to_html
	 *  @return {String} The generated HTML.
	 */
	public function to_html() {
		// 1   - Buffering
		ob_start();
		
		// 2   - First, reset the generated_id for this map, just in case that this map has already been included somewhere.
		$this->reset_generated_id();
		
		// 3   - If no other map has been included yet, include google api and scripts.
		$builder = Interactive_Map_Builder::get_instance();
		if(!$builder->has_included_api()) {
			$builder->set_api_included();
			$builder->enqueue_map_scripts();
			//$builder->enqueue_styles();
			//wp_enqueue_script( $this->plugin_slug . '-meisterbox', plugins_url( 'js/meisterbox.js', __FILE__ ), array( 'jquery' ), $builder->get_version(), true);
			
			//include_once( dirname(__FILE__) . '/../views/api.php' );
		}
		
		// Create array with js data
		$js_data               = array();
		$js_data["id"]         = $this->get_generated_id();
		$js_data["options"]    = $this->get_js_options()->to_array($this);
		$js_data["gtable"]     = $this->get_js_table()->to_gtable($this,false);
		$js_data["actions"]    = $this->get_js_table()->click_actions_to_array();
		$js_data["javascript"] = $this->get_click_action()->get_javascript();
		$js_data["cssid"]      = $this->get_click_action()->get_css_substitute($this->get_generated_id());
		$builder->enqueue_data($js_data);
		
		// 4   - Include the view for this map.
		include( dirname(__FILE__) . '/../views/map.php' );
		
		// 5   - Fetch all ouput
		$output = ob_get_contents();  
		ob_end_clean();  
		
		// 6   - Returning output
		return $output; 
	}
	
	public function __toString() {
		return $this->to_html();
	}
	
	public function get_export() {
		$o = array();
		$o = array( 
			'name'            => $this->get_name(),
			'description'     => $this->get_description(),
			'js_options'      => $this->get_js_options()->get_db_string(),
			'js_table'        => $this->get_js_table()->get_db_string(),
			'display_mode'    => $this->get_display_mode(),
			'template_id'     => $this->get_click_action()->get_id(),
			'template_html'   => $this->get_template_html(),
			'template_css'    => $this->get_template_css(),
			'template_js'     => $this->get_template_js(),
		);
		return json_encode($o);
	}
	
	public function set_import($text) {
		$this->mark_as_import();
		
		$array = json_decode($text);
		if($array===null)
			return false;
		
		if(array_key_exists("name", $array)) {
			$this->set_name($array->{"name"});
		}
		if(array_key_exists("description", $array)) {
			$this->set_description($array->{"description"});
		}
		if(array_key_exists("js_options", $array)) {
			$options = new Map_Options($array->{"js_options"});
			$this->set_js_options($options);
		}
		if(array_key_exists("display_mode", $array)) {
			$this->set_display_mode($array->{"display_mode"});
		}
		if(array_key_exists("js_table", $array)) {
			$table = new Map_Table($array->{"js_table"}, $this->get_display_mode());
			$this->set_js_table($table);
			$this->set_table($array->{"js_table"});
		}
		// Template was Custom?
		if(array_key_exists("template_id", $array) && $array->{"template_id"} == -2) {
			$action = Click_Action::create_empty();
			$action->set_id(-2);

			if(array_key_exists("template_html", $array)) {
				$html = $array->{"template_html"};
				$action->set_html($html);
				$this->set_template_html($html);
			}
			if(array_key_exists("template_css", $array)) {
				$css = $array->{"template_css"};
				$action->set_css($css);
				$this->set_template_css($css);
			}
			if(array_key_exists("template_js", $array)) {
				$js = $array->{"template_js"};
				$action->set_javascript($js);
				$this->set_template_js($js);
			}
			$this->set_click_action($action);
		}
		return true;
	}
	public function mark_as_import() {
		$this->is_import = true;
	}
	public function is_import() {
		return $this->is_import;
	}
}

?>