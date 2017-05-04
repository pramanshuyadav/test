<?php
/**
 * Interactive Map Builder Plugin.
 *
 * @author    Fabian Vellguth <info@meisterpixel.com>
 * @link      http://meisterpixel.com/
 * @copyright 2014 meisterpixel.com
 */

/**
 * Class that handles all interactions with click actions.
 *  
 * @author Fabian Vellguth
 * @package Data Objects
 */
class Click_Action {
	protected $map_needle = '%%map%%';
	protected $css_needle = '{cssid}';
	
	private $id;
	private $name;
	private $description;
	private $sample_table_1;
	private $sample_table_2;
	private $sample_table_3;
	private $html;
	private $javascript;
	private $css;
	private $changed;
	private $created;
	
	private $validation_messages=array();
	
    /**
     * Constructor for a Click Action Object.
     *
     *
     * @param int 		$id 			(optional) Id of the click action.
     * @param string  	$name			(optional) Name of the click action.
	 * @param string  	$description	(optional) Description of the click action.
	 * @param string  	$sample_table_1	(optional) First Value in the Sample Table.
     * @param string  	$sample_table_2	(optional) Second Value in the Sample Table.
	 * @param string  	$sample_table_3	(optional) Third Value in the Sample Table.
	 * @param string  	$html			(optional) HTML of the click action. This should contain the $map_needle.
	 * @param string  	$javascript		(optional) Javascript of the click action.
	 * @param string  	$css			(optional) CSS of the click action.
	 * @param string  	$changed		(optional) Last change of the click action.
	 * @param string  	$created		(optional) Date when click action was created.
     * @return void
     */
	public function __construct($id = -1, $name="", $description="", $sample_table_1="", $sample_table_2="", $sample_table_3="", $html="%%map%%", $javascript="", $css="", $changed=null, $created=null) {
		$this->set_id($id);
		$this->set_name($name);
		$this->set_description($description);
		$this->set_sample_table_1($sample_table_1);
		$this->set_sample_table_2($sample_table_2);
		$this->set_sample_table_3($sample_table_3);
		$this->set_html($html);
		$this->set_javascript($javascript);
		$this->set_css($css);
		$this->set_changed($changed);
		$this->set_created($created);
	}
	
	public static function get_default_click_action() {
		$builder = Interactive_Map_Builder::get_instance();
		return new Click_Action(-1, 
								"Default template", 
								__('This is the default template. This can be used to create a custom template. As soon as you start editing the HTML, CSS or HTML, it will automatically switch to "custom template". Note that you have to press "Update preview" below the JavaScript field in order to see any changes.', $builder->get_plugin_slug()), 
								"", 
								"", 
								"", 
								'<div id="{cssid}">'."\n".'  %%map%%'."\n".'</div>', 
								"", 
								'#{cssid} {'."\n \n".'}', 
								"", 
								"");
	}
	
	/**
	 * Returns the SQL Create for the Click Action Table as a string.
	 *
	 * @return string
	 */
	public static function get_create_sql() {
		$builder    = Interactive_Map_Builder::get_instance();
		$table_name = $builder->get_config("actions_table");
		$sql = "CREATE TABLE $table_name (
			  id  mediumint  unsigned NOT NULL AUTO_INCREMENT,
			  name varchar(50) CHARACTER SET utf8  ,
			  description text,
			  sample_table_1 text,
			  sample_table_2 text,
			  sample_table_3 text,
			  html text,
			  javascript text,
			  css text,
			  time_created timestamp DEFAULT '0000-00-00 00:00:00' NOT NULL  ,
			  time_changed timestamp DEFAULT NOW() ON UPDATE now()  ,
			  UNIQUE KEY id (id)
			) DEFAULT CHARSET=utf8;
			";
		return $sql;
	}
	
	public static function get_drop_table_sql() {
		$builder    = Interactive_Map_Builder::get_instance();
		$table_name = $builder->get_config("actions_table");
		return "DROP TABLE $table_name";
	}
	
	/**
	 * Returns all click actions from the database.
	 * 
	 * @return (Click_Action)[]
	 */
	public static function get_actions() {
		global $wpdb;
		$actions_array = array();
		$builder    = Interactive_Map_Builder::get_instance();
		$table_name = $builder->get_config("actions_table");
		
		$sql     = "SELECT * FROM $table_name ORDER BY name";
		$actions = $wpdb->get_results($sql);
		
		foreach($actions as $action)
			$actions_array[] = new Click_Action($action->id, 
			                                    $action->name, 
												$action->description, 
												$action->sample_table_1, 
												$action->sample_table_2, 
												$action->sample_table_3, 
												$action->html, 
												$action->javascript, 
												$action->css, 
												$action->time_changed, 
												$action->time_created);
			
		return $actions_array;
	}
	
	/**
	 * Returns all click actions as a json object.
	 *
	 * @return array
	 */
	public static function get_actions_json($click_actions=null) {
		$actions = $click_actions;
		if($actions===null)
			$actions = self::get_actions();
			
		$o = array();
		// Default Click Action Values
		$o["-1"]              = self::get_default_click_action()->to_array();
		foreach($actions as $action) {
			$o[$action->get_id()] = $action->to_array();
		}
		return $o;
	}
	
	public static function render_click_action_menu($click_actions) {
		// 1   - Buffering
		ob_start();
		
		// 2   - Nothing to highlight
		$highlighted_id = -2;
		
		// 3   - Include the view for this map.
		include( dirname(__FILE__) . '/../views/click_actions_menu.php' );
		
		// 4   - Fetch all ouput
		$output = ob_get_contents();  
		ob_end_clean();  
		
		return $output;
	}
	
	public function to_array() {
		$a                    = array();
		$a["id"]              = $this->get_id();
		$a["name"]            = $this->get_name();
		$a["name_esc"]        = esc_html($this->get_name());
		$a["description"]     = $this->get_description();
		$a["description_esc"] = nl2br(esc_html($this->get_description()));
		$a["tab1"]            = $this->get_sample_table_1();
		$a["tab1_esc"]        = esc_html($this->get_sample_table_1());
		$a["tab2"]            = $this->get_sample_table_2();
		$a["tab2_esc"]        = esc_html($this->get_sample_table_2());
		$a["tab3"]            = $this->get_sample_table_3();
		$a["tab3_esc"]        = esc_html($this->get_sample_table_3());
		$a["html"]            = $this->get_html();
		$a["js"]              = $this->get_javascript();
		$a["css"]             = $this->get_css();
		$a["export"]          = $this->toJSON();
		$a["link_show"]       = $this->get_pagelink("show");
		$a["link_edit"]       = $this->get_pagelink("edit");
		$a["link_delete"]     = $this->get_pagelink("delete");
		$a["link_export"]     = $this->get_pagelink("export");
		return $a;
	}
	
	public function get_pagelink($mode=null) {
		switch($mode) {
			case "show":
				return admin_url("admin.php?page=interactive_map_builder_clickactions&action=".$this->get_id()."&mode=show");
				break;
			case "edit":
				return admin_url("admin.php?page=interactive_map_builder_clickactions&action=".$this->get_id()."&mode=edit");
				break;
			case "delete":
				return admin_url("admin.php?page=interactive_map_builder_clickactions&action=".$this->get_id()."&mode=delete");
				break;
			case "export":
				return admin_url("admin.php?page=interactive_map_builder_clickactions&action=".$this->get_id()."&mode=export");
				break;
			default:
				return admin_url("admin.php?page=interactive_map_builder_clickactions&action=".$this->get_id()."&mode=show");
		}
	}
	
	/**
	 * Returns the click action with the id $id or false.
	 *
	 * @return Click_Action|bool
	 */
	public static function get($id) {
		global $wpdb;
		$builder    = Interactive_Map_Builder::get_instance();
		$table_name = $builder->get_config("actions_table");
		
		$sql    = "SELECT * FROM $table_name WHERE id=" . esc_sql($id);
		$action = $wpdb->get_row($sql);
		if($action===null)
			return false;
		$action_object = new Click_Action($action->id, 
										$action->name, 
										$action->description, 
										$action->sample_table_1, 
										$action->sample_table_2, 
										$action->sample_table_3, 
										$action->html, 
										$action->javascript, 
										$action->css, 
										$action->time_changed, 
										$action->time_created);
			
		return $action_object;
	}
	
	public static function get_click_actions($offset = null, $limit = null, $order_by = null, $order_dir = null) {
		global $wpdb;
		$actions_array = array();
		$builder       = Interactive_Map_Builder::get_instance();
		$table_name    = $builder->get_config("actions_table");
		
		$sql  = "SELECT * FROM $table_name";
		if($order_by!==null && $order_dir!==null) {
			$sql .= " ORDER BY $order_by $order_dir";
		}
		if($offset!==null && $limit!==null) {
			$sql .= " LIMIT " . intval($offset) . ", " . intval($limit);
		}
		$actions = $wpdb->get_results($sql);
		foreach($actions as $action) {
			$actions_array[] = new Click_Action($action->id, 
										$action->name, 
										$action->description, 
										$action->sample_table_1, 
										$action->sample_table_2, 
										$action->sample_table_3, 
										$action->html, 
										$action->javascript, 
										$action->css, 
										$action->time_changed, 
										$action->time_created);
		}
			
		return $actions_array;
	}
	
	public static function create_new() {
		return new Click_Action();		// Created
	}
	public static function create_empty() {
		return new Click_Action();		// Created
	}
	public function get_id() {
		return $this->id;
	}
	public function get_name() { 
		return $this->name; 
	} 
	public function get_description() { 
		return $this->description; 
	} 
	public function get_sample_table_1() { 
		return $this->sample_table_1; 
	} 
	public function get_sample_table_2() { 
		return $this->sample_table_2; 
	} 
	public function get_sample_table_3() { 
		return $this->sample_table_3; 
	} 
	public function get_html() { 
		return $this->html; 
	}
	public function get_generated_html($id) {
		$html = $this->get_html();
		$html = $this->replace_map_needle($html, $this->get_map_substitute($id));
		$html = $this->replace_css_needle($html, $this->get_css_substitute($id));
		return $html;
	}
	protected function get_map_substitute($id) {
		return '<div class="interactive_map_finder" id="interactive_map_' . $id . '" imap="' . $id . '"></div>';
	}
	public function get_css_substitute($id) {
		return 'imap_' . $id;
	}
	protected function replace_map_needle($text, $substitute) {
		return preg_replace('/'.$this->map_needle.'/', $substitute, $text, 1);
	}
	protected function replace_css_needle($text, $substitute) {
		return str_replace($this->css_needle, $substitute, $text);
	}
	public function get_javascript() { 
		return $this->javascript; 
	} 
	public function get_css() { 
		return $this->css; 
	} 
	public function get_generated_css($id) {
		$css = $this->get_css();
		$css = $this->replace_css_needle($css, $this->get_css_substitute($id));
		return $css;
	}
	public function get_changed() { 
		return $this->changed; 
	} 
	public function get_created() { 
		return $this->created; 
	} 
	public function set_id($x) {
		$this->id = $x;
	}
	public function set_name($x) { 
		$this->name = $x; 
	} 
	public function set_description($x) { 
		$this->description = $x; 
	} 
	public function set_sample_table_1($x) { 
		$this->sample_table_1 = $x; 
	} 
	public function set_sample_table_2($x) { 
		$this->sample_table_2 = $x; 
	} 
	public function set_sample_table_3($x) { 
		$this->sample_table_3 = $x; 
	} 
	public function set_html($x) { 
		$this->html = $x; 
	} 
	public function set_javascript($x) { 
		$this->javascript = $x; 
	} 
	public function set_css($x) { 
		$this->css = $x; 
	} 
	public function set_changed($x) { 
		$this->changed = $x; 
	} 
	public function set_created($x) { 
		$this->created = $x; 
	}
	
	public function delete() {
		global $wpdb;
		
		$builder    = Interactive_Map_Builder::get_instance();
		$table_name = $builder->get_config("actions_table");
		
		$result = $wpdb->delete( $table_name, array( 'id' => $this->get_id()) );
		
		if($result===false) {
			return false;
		}
		return true;
	}
	
	public function save() {
		global $wpdb;

		$builder    = Interactive_Map_Builder::get_instance();
		$table_name = $builder->get_config("actions_table");
		
		if($this->get_id() != "-1") {
			$result = $wpdb->update( 
				$table_name , 
				array( 
					'name'           => $this->get_name(),
					'description'    => $this->get_description(),
					'sample_table_1' => $this->get_sample_table_1(),
					'sample_table_2' => $this->get_sample_table_2(),
					'sample_table_3' => $this->get_sample_table_3(),
					'html'           => $this->get_html(),
					'javascript'     => $this->get_javascript(),
					'css'            => $this->get_css(),
					//'time_changed'   => NULL
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
					'id'             => $this->get_id(),
					'name'           => $this->get_name(),
					'description'    => $this->get_description(),
					'sample_table_1' => $this->get_sample_table_1(),
					'sample_table_2' => $this->get_sample_table_2(),
					'sample_table_3' => $this->get_sample_table_3(),
					'html'           => $this->get_html(),
					'javascript'     => $this->get_javascript(),
					'css'            => $this->get_css(),
					'time_created'   => 'NULL',
					'time_changed'   => 'NULL',
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
	
	public static function mask($array, $key, $null_value, $stripslashes=true) {
		if(is_array($array) && isset($array[$key]) && $array[$key]!="")
			if($stripslashes)
				return (stripslashes_deep($array[$key]));
			else
				return $array[$key];
		else
			return $null_value;
	}
	
	public static function fill_from_post() {
		$action  = new Click_Action();
		$form    = $_REQUEST['form'];
		$action->set_id(self::mask($form, 'id', ''));
		$action->set_name(trim(self::mask($form, 'name', '')));
		$action->set_description(self::mask($form, 'description', ''));
		$action->set_sample_table_1(self::mask($form, 'data_table_value_1', ''));
		$action->set_sample_table_2(self::mask($form, 'data_table_value_2', ''));
		$action->set_sample_table_3(self::mask($form, 'data_table_value_3', ''));
		$action->set_html(self::mask($form, 'html', ''));
		$action->set_javascript(self::mask($form, 'js', ''));
		$action->set_css(self::mask($form, 'css', ''));
		return $action;
	}
	
	public static function get_order() {
		$actions = self::get_actions();
		$order   = array();
		foreach($actions as $action)
			$order[] = $action->get_id();
		return $order;
	}
	
	public static function import($data) {
		$obj = json_decode($data, true);
		
		if(!self::is_valid_import_array($obj))
			return false;
		
		$action  = new Click_Action();
		$action->set_id(-1);
		/* 
		UTF-8 Decoding
		$action->set_name(utf8_decode(trim(self::mask($obj, 'name', ''))));
		$action->set_description(utf8_decode(self::mask($obj, 'description', '')));
		$action->set_sample_table_1(utf8_decode(self::mask($obj, 'tab1', '')));
		$action->set_sample_table_2(utf8_decode(self::mask($obj, 'tab2', '')));
		$action->set_sample_table_3(utf8_decode(self::mask($obj, 'tab3', '')));
		$action->set_html(utf8_decode(self::mask($obj, 'html', '')));
		$action->set_javascript(utf8_decode(self::mask($obj, 'js', '')));
		$action->set_css(utf8_decode(self::mask($obj, 'css', '')));*/
		
		$action->set_name((trim(self::mask($obj, 'name', '', false))));
		$action->set_description((self::mask($obj, 'description', '', false)));
		$action->set_sample_table_1((self::mask($obj, 'tab1', '', false)));
		$action->set_sample_table_2((self::mask($obj, 'tab2', '', false)));
		$action->set_sample_table_3((self::mask($obj, 'tab3', '', false)));
		$action->set_html((self::mask($obj, 'html', '', false)));
		$action->set_javascript((self::mask($obj, 'js', '', false)));
		$action->set_css((self::mask($obj, 'css', '', false)));

		return $action;
	}
	
	public static function is_valid_import_array($a) {
		if(!is_array($a))
			return false;
		
		$expected_keys = array("js", "html", "name");
		foreach($expected_keys as $key)
			if(!isset($a[$key]))
				return false;
		
		return true;
	}
	public function toJSON() {
		$a                = array();
		//$a["id"]          = $this->get_id();
		/*
		UTF-8 Decoding
		$a["name"]        = utf8_encode($this->get_name());
		$a["description"] = utf8_encode($this->get_description());
		$a["tab1"]        = utf8_encode($this->get_sample_table_1());
		$a["tab2"]        = utf8_encode($this->get_sample_table_2());
		$a["tab3"]        = utf8_encode($this->get_sample_table_3());
		$a["html"]        = utf8_encode($this->get_html());
		$a["js"]          = utf8_encode($this->get_javascript());
		$a["css"]         = utf8_encode($this->get_css());*/
		
		$a["name"]        = $this->get_name();
		$a["description"] = $this->get_description();
		$a["tab1"]        = $this->get_sample_table_1();
		$a["tab2"]        = $this->get_sample_table_2();
		$a["tab3"]        = $this->get_sample_table_3();
		$a["html"]        = $this->get_html();
		$a["js"]          = $this->get_javascript();
		$a["css"]         = $this->get_css();
		return json_encode($a);
	}
	public function validate_data() {
		if($this->get_name() == "")
			return "Please pick a name for the click action. An empty field is not allowed.";
		
		return "";
	}
	
	public function is_valid() {
		$builder = Interactive_Map_Builder::get_instance();
		$slug    = $builder->get_plugin_slug();
		if($this->get_name() == "")
			$this->set_validation_message(__("Please pick a name for the click action. An empty field is not allowed.", $slug));
		if(strpos($this->get_html(), $this->map_needle)===false)
			$this->set_validation_message(sprintf(__("Couldn't find %s in the HTML", $slug), "<b>%%map%%</b>"));
		return !$this->has_validation_messages();
	}
	
	public function reset_validation_messages() {
		$this->validation_messages = array();
	}
	
	private function set_validation_message($text) {
		$this->validation_messages[] = $text;
	}
	
	public function get_validation_messages() {
		$output = "";
		if($this->has_validation_messages()) {
			$output .= "<ol>";
			foreach($this->validation_messages as $message)
				$output .= "<li>$message</li>";
			$output .= "</ol>";
		}
		return $output;
	}
	
	private function has_validation_messages() {
		return count($this->validation_messages) > 0;
	}
}

?>
