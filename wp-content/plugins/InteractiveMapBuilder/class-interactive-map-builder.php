<?php
/**
 * Interactive Map Builder Plugin.
 *
 * @author    Fabian Vellguth <info@meisterpixel.com>
 * @link      http://meisterpixel.com/
 * @copyright 2014 meisterpixel.com
 */

require_once('helper/view_helper.php');
require_once('helper/help_tabs.php');
require_once('lib/interactive_map.php');
require_once('lib/click_action.php');
require_once('lib/map_options.php');
require_once('lib/map_regions.php');
require_once('lib/map_table.php');
require_once('lib/map_gtable.php');
require_once('lib/virtual-composer.php');

/**
 * Class that handles all interactions with the plugin.
 *
 * @package Core
 * @author  Fabian Vellguth <info@meisterpixel.com>
 */
class Interactive_Map_Builder {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 * @var     string
	 */
	protected $version = '2.0';

	/**
	 * Unique identifier for the plugin.
	 *
	 * @since    1.0.0
	 * @var      string
	 */
	protected $plugin_slug = 'interactive_map_builder';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 *  This is just for switching to less-mode.
	 *  This is not used in the distributed version.
	 */
	protected static $less_path = "css/";
	
	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 *  Contains the slugs of the pages.
	 *  
	 *  @since    1.0.0
	 *  @var      array
	 */
	protected $plugin_screen_hook_suffix_array = array();
	
	/**
	 *  Contains config variables.
	 *  
	 *  @since    1.0.0
	 *  @var      array
	 */
	protected $config = array();

	/**
	 *  If set, this will be added to the table names in the database.
	 *  
	 *  @since    1.0.0
	 *  @var      string
	 */
	protected $prefix = "";
	
	/**
	 *  Determines if the map api has already beeen included in the page.
	 *  
	 *  @since    1.0.0
	 *  @var      bool
	 */
	protected $api_included = false;
	
	/**
	 *  The starting map id on a page.
	 *  
	 *  @since    1.0.0
	 *  @var      int
	 */
	protected $mapid = 1;
	
	protected $js_data = array();
	
	protected $is_demo_mode = false;
	
	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		global $wpdb;
		
		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		
		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Set Database Prefix for the Plugin
		$this->set_prefix($wpdb->prefix . 'plugin_imb_');
		
		// Add Table names to Config
		$this->add_config("maps_table",    $this->get_prefix() . 'maps'   );
		$this->add_config("actions_table", $this->get_prefix() . 'actions');
		
		// AJAX Requests
		add_action('wp_ajax_imb_save_clickaction', array($this, 'ajax_clickaction_save'));
		add_action('wp_ajax_imb_copy_clickaction', array($this, 'ajax_clickaction_copy'));
		add_action('wp_ajax_imb_save_map', array($this, 'ajax_map_save'));
		add_action('wp_ajax_imb_delete_map', array($this, 'ajax_map_delete'));
		add_action('wp_ajax_imb_copy_map', array($this, 'ajax_map_copy'));
		add_action('wp_ajax_translate_table', array($this, 'translate_table'));
		
		// Add shortcode
		add_shortcode( 'interactive_map', array($this, 'shortcode_function') );
		add_shortcode( 'interactive_map_export_clickaction' , array($this, 'shortcode_export_click_action') );
		add_shortcode( 'interactive_map_export_map' , array($this, 'shortcode_export_map') );
		
		//delete_option($this->get_activation_option_key());
	}
	
	/**
	 *  AJAX translate table function to convert the table to the google data table.
	 */
	function translate_table() {
		$data = array();

		if(!isset($_REQUEST['table']))
			$_REQUEST['table'] = array();
			
		$table = null;
		if(isset($_REQUEST['table']) && isset($_REQUEST['mode'])) {
			$raw_table = stripslashes_deep($_REQUEST['table']);
			$table     = new Map_Table();
			$table->load_from_array($raw_table, false);
			$mode            = $_REQUEST['mode'];
			$data["raw"]     = $raw_table;
			$data["table"]   = Map_GTable::generate_google_data_table($mode, $table);
			$data["colors"]  = array_keys($table->get_colors());
			$data["actions"] = $table->click_actions_to_array();
			if(sizeof($data["colors"]) > 0)
				$data["maxvalue"] = sizeof($data["colors"]);
			else
				$data["maxvalue"] = "1";
		}
		
		echo json_encode($data);
		die();
	}
	
	/**
	 *  The shortcode function to render a map.
	 */
	function shortcode_function( $atts ) {
		extract( shortcode_atts( array(
			'id' => '-1',
		), $atts ) );
		
		$map = Interactive_Map::get_map($id);
		
		if($map)
			return $map->to_html();
		else
			return "";
	}
	
	/**
	 *  The shortcode function to render a map.
	 */
	function shortcode_export_click_action( $atts ) {
		extract( shortcode_atts( array(
			'id' => '-1',
		), $atts ) );
		
		$map = Interactive_Map::get_map($id);
		
		if($map)
			return $map->get_click_action()->toJSON();
		else
			return "";
	}
	
	/**
	 *  The shortcode function to render a map.
	 */
	function shortcode_export_map( $atts ) {
		extract( shortcode_atts( array(
			'id' => '-1',
		), $atts ) );
		
		$map = Interactive_Map::get_map($id);
		
		if($map)
			return $map->get_export();
		else
			return "";
	}
	
	/**
	 *  This function is called to render the content of the preview iframe.
	 */
	public function ttt() {
		$action = null;
		if(isset($_GET['test']) && $_GET['test']==-1) { // normal preview

			$map    = Interactive_Map::get_preview();
			$action = $map->get_click_action();
			if(isset($_REQUEST['html'])) {
				$action->set_html(stripslashes_deep($_REQUEST['html']));
			}
			if(isset($_REQUEST['css'])) {
				$action->set_css(stripslashes_deep($_REQUEST['css']));
			}
			if(isset($_REQUEST['js'])) {
				$action->set_javascript(stripslashes_deep($_REQUEST['js']));
			}
			if(isset($_REQUEST['data_table_value_1'])) {
				$action->set_sample_table_1(stripslashes_deep($_REQUEST['data_table_value_1']));
			}
			if(isset($_REQUEST['data_table_value_2'])) {
				$action->set_sample_table_2(stripslashes_deep($_REQUEST['data_table_value_2']));
			}
			if(isset($_REQUEST['data_table_value_3'])) {
				$action->set_sample_table_3(stripslashes_deep($_REQUEST['data_table_value_3']));
			}
			$table = Interactive_Map::generate_sample_table($action->get_sample_table_1(), $action->get_sample_table_2(), $action->get_sample_table_3());
			$map->set_js_table($table);
			
			wp_enqueue_script( $this->plugin_slug . '-preview', plugins_url( 'js/preview.js', __FILE__ ), array( 'jquery' ), $this->version );
			wp_enqueue_style($this->plugin_slug . '-preview', plugins_url( 'css/preview.css', __FILE__ ));
			
			include_once( 'views/preview.php' );
			die();
		
		} else if(isset($_GET['test']) && $_GET['test']==-2 && isset($_GET['map'])) { // normal preview
			$map    = Interactive_Map::get_preview();
			$action = $map->get_click_action();
			if(isset($_REQUEST['html'])) {
				$action->set_html(stripslashes_deep($_REQUEST['html']));
			}
			if(isset($_REQUEST['css'])) {
				$action->set_css(stripslashes_deep($_REQUEST['css']));
			}
			if(isset($_REQUEST['js'])) {
				$action->set_javascript(stripslashes_deep($_REQUEST['js']));
			}
			if(isset($_REQUEST['data_table_value_1'])) {
				$action->set_sample_table_1(stripslashes_deep($_REQUEST['data_table_value_1']));
			}
			if(isset($_REQUEST['data_table_value_2'])) {
				$action->set_sample_table_2(stripslashes_deep($_REQUEST['data_table_value_2']));
			}
			if(isset($_REQUEST['data_table_value_3'])) {
				$action->set_sample_table_3(stripslashes_deep($_REQUEST['data_table_value_3']));
			}
			$table = Interactive_Map::generate_sample_table($action->get_sample_table_1(), $action->get_sample_table_2(), $action->get_sample_table_3());
			$map->set_js_table($table);
			$map2 = Interactive_Map::get_map((int)$_GET['map']);
			$template_html = $map2->get_template_html();
			$template_css = $map2->get_template_css();
			$template_js = $map2->get_template_js();
			$map->get_click_action()->set_id(-2);
			$map->get_click_action()->set_html($template_html);
			$map->get_click_action()->set_css($template_css);
			$map->get_click_action()->set_javascript($template_js);
			
			wp_enqueue_script( $this->plugin_slug . '-preview', plugins_url( 'js/preview.js', __FILE__ ), array( 'jquery' ), $this->version );
			wp_enqueue_style($this->plugin_slug . '-preview', plugins_url( 'css/preview.css', __FILE__ ));
			
			include_once( 'views/preview.php' );
			die();
		} else if(isset($_GET['test']) && $_GET['test']>0 ) { // test site

			$map    = Interactive_Map::get_preview();
			$action = Click_Action::get($_GET['test']);
			
			if($action!==false) {
				if(isset($_REQUEST['html'])) {
					$action->set_html(stripslashes_deep($_REQUEST['html']));
				}
				if(isset($_REQUEST['css'])) {
					$action->set_css(stripslashes_deep($_REQUEST['css']));
				}
				if(isset($_REQUEST['js'])) {
					$action->set_javascript(stripslashes_deep($_REQUEST['js']));
				}
				if(isset($_REQUEST['data_table_value_1'])) {
					$action->set_sample_table_1(stripslashes_deep($_REQUEST['data_table_value_1']));
				}
				if(isset($_REQUEST['data_table_value_2'])) {
					$action->set_sample_table_2(stripslashes_deep($_REQUEST['data_table_value_2']));
				}
				if(isset($_REQUEST['data_table_value_3'])) {
					$action->set_sample_table_3(stripslashes_deep($_REQUEST['data_table_value_3']));
				}
				$table = Interactive_Map::generate_sample_table($action->get_sample_table_1(), $action->get_sample_table_2(), $action->get_sample_table_3());
				$map->set_click_action($action);
				$map->set_js_table($table);
			}
			
			wp_enqueue_script( $this->plugin_slug . '-preview', plugins_url( 'js/preview.js', __FILE__ ), array( 'jquery' ), $this->version );
			wp_enqueue_style($this->plugin_slug . '-preview', plugins_url( 'css/preview.css', __FILE__ ));
			
			include_once( 'views/preview.php' );
			die();
			
		} else if(array_key_exists("export", $_GET)) {
			$map  = Interactive_Map::get_map($_GET["export"]);
			$name = $map->get_name(); //str_replace(" ", "", $map->get_name());
			
			header('Content-type: application/json');
			header('Content-Disposition: attachment; filename="' . $name . '.mapfile.json"');
			if($map) {
				echo $map->get_export();
			}
			die();
		}
	}

	public function set_api_included() {
		$this->api_included = true;
	}
	
	public function has_included_api() {
		return $this->api_included;
	}
	
	/**
	 *  Generates a unique map id.
	 */
	public function generate_map_id() {
		return $this->mapid++;
	}
	
	/**
	 *  AJAX Copy action.
	 */
	public function ajax_map_copy() {
		$data            = array();
		$data["message"] = "";
		$data["success"] = false;
		$data["newid"]   = 0;
		
		if(isset($_REQUEST) && isset($_REQUEST['id'])) {
			$id  = $_REQUEST['id'];
			$map = Interactive_Map::get_map($id);
			
			if(!$map) {
				// Map id doesn't exist.
				$data["message"] = __( "Map could not be found and therefore not be cpoied.", $this->plugin_slug );
			} else if($this->is_demo() && !$this->user_can("create_new_maps")) {
				$data["message"] = __("Sorry, but this is a demo and you have created too many maps. Come back later or edit an existing map.", $this->plugin_slug );
			} else {
				$map->set_id(-1);
				$result = $map->save();
				if($result) {
					$data["message"] = __( "Map was successfully copied.", $this->plugin_slug );
					$data["success"] = true;
					$data["newid"]   = $map->get_id();
				} else {
					$data["message"] = __( "Map couldn't be copied.", $this->plugin_slug );
				}
				$this->increment_user("create_new_maps");
			}
		}
		
		echo json_encode($data);

		die();
	}
	
	public function ajax_map_delete() {
		$data            = array();
		$data["message"] = "";
		$data["success"] = false;
		
		if(isset($_REQUEST) && isset($_REQUEST['id'])) {
			$id  = $_REQUEST['id'];
			$map = Interactive_Map::get_map($id);
			
			if(!$map) {
				// Map id doesn't exist.
				$data["message"] = __( "Map could not be found and therefore not be deleted.", $this->plugin_slug );
			} else if($this->is_demo()) {
				$data["message"] = __( "Map could not be deleted because of the demo mode.", $this->plugin_slug );
			} else {
				$result = $map->delete();
				if($result) {
					$data["message"] = __( "Map was successfully deleted.", $this->plugin_slug );
					$data["success"] = true;
				} else {
					$data["message"] = __( "Map couldn't be deleted.", $this->plugin_slug );
				}
			}
		}
		
		echo json_encode($data);

		die();
	}
	public function ajax_map_save() {
		$data    = array();
		
		// Expecting an id
		if(!isset($_REQUEST['id'])) {
			$data["error"] = __( "Map couldn't be saved, because the data was incomplete.", $this->plugin_slug );
			echo json_encode($data);
			die();
		}
		$id = stripslashes_deep( $_REQUEST['id'] );
		
		// Expecting map name
		if(!isset($_REQUEST['title'])) {
			$data["error"] = __( "Map couldn't be saved, because the data was incomplete.", $this->plugin_slug );
			echo json_encode($data);
			die();
		}
		$name = stripslashes_deep( $_REQUEST['title'] );
		
		// Expecting map mode
		if(!isset($_REQUEST['mode'])) {
			$data["error"] = __( "Map couldn't be saved, because the data was incomplete.", $this->plugin_slug );
			echo json_encode($data);
			die();
		}
		$mode = stripslashes_deep( $_REQUEST['mode'] );
		
		// Expecting map mode
		if(!isset($_REQUEST['click_action'])) {
			$data["error"] = __( "Map couldn't be saved, because the data was incomplete.", $this->plugin_slug );
			echo json_encode($data);
			die();
		}
		$click_action = (int)stripslashes_deep( $_REQUEST['click_action'] );
		
		// Image
		if(!isset($_REQUEST['image'])) {
			$data["error"] = __( "Map couldn't be saved, because the data was incomplete.", $this->plugin_slug );
			echo json_encode($data);
			die();
		}
		$image = stripslashes_deep( $_REQUEST['image'] );
		
		$map = new Interactive_Map($id, $name, "", "", "", $mode, "", null, null, $click_action, $image);
		
		$table = new Map_Table();
		if(isset($_REQUEST['table'])) {
			$raw_table = stripslashes_deep($_REQUEST['table']);
			$map->set_table($raw_table);
			$table->load_db_string($raw_table);
			//$table->load_from_array($raw_table, false);
		}
		$map->set_js_table($table);
		
		$options = new Map_Options();
		if(isset($_REQUEST['options'])) {
			$raw_options = stripslashes_deep($_REQUEST['options']);
			$options->load_from_array($raw_options);
		}
		$map->set_js_options($options);
		
		// Check demo mode
		if($this->is_demo() && $map->get_id()==-1) {
			if(!$this->user_can("create_new_maps")) {
				$data["error"] = __("Sorry, but this is a demo and you have created too many maps. Come back later or edit an existing map.", $this->plugin_slug );
				echo json_encode($data);
				die();
			} else {
				$this->increment_user("create_new_maps");
			}
		}
		
		// Set html
		if(isset($_REQUEST['html']) && isset($_REQUEST['css']) && isset($_REQUEST['js'])) {
			$map->set_template_html(stripslashes_deep($_REQUEST['html']));
			$map->set_template_css(stripslashes_deep($_REQUEST['css']));
			$map->set_template_js(stripslashes_deep($_REQUEST['js']));
		}
		
		$was_new = $map->get_id()==-1;
		$result  = $map->save();
		
		if($result) {
			if($was_new)
				$data["message"] = __("Map was successfully created and saved.", $this->plugin_slug );
			else
				$data["message"] = __("Map was successfully saved.", $this->plugin_slug );
		}
		//echo json_encode(array("r" => $_REQUEST, "save" => $result));
		echo json_encode($data);
		die();
	}
	
	public function ajax_clickaction_copy() {
		$data            = array();
		$data["message"] = "";
		$data["success"] = false;
		
		if(!isset($_REQUEST) || !array_key_exists("form", $_REQUEST) || !array_key_exists("id", $_REQUEST["form"])) {
		
		}
		
		$id     = $_REQUEST['form']['id'];
		$action = Click_Action::get($id);
		
		if(!$action) {
			// Map id doesn't exist.
			$data["message"] = __( "Click Action could not be found and therefore not be copied.", $this->plugin_slug );
		} else {
			$action->set_id(-1);
			$action->set_name($action->get_name() . __(" - New", $this->plugin_slug));
			
			if($action->save()) {
				$data["message"] = __( "Map was successfully copied.", $this->plugin_slug );
				$data["success"] = true;
				$data["id"]   = $action->get_id();
			} else {
				$data["message"] = __( "Click Action couldn't be copied.", $this->plugin_slug );
			}
		}
		
		$data["click_actions"] = Click_Action::get_actions_json();
		$data["order"]         = Click_Action::get_order();
		
		echo json_encode($data);

		die();
	}
	
	public function ajax_clickaction_save() {
		// Input Values:
		//     -id:  The id of the current Click Action.
		//   -mode:  The current mode of the page.
		//   -form:  The form elements.
		if(!isset($_REQUEST['mode']))
			die();
		
		
		$mode                  = $_REQUEST['mode'];
		$click_action          = Click_Action::fill_from_post();
		$data                  = array();
		$data["success"]       = false;
		
		if($mode=="edit") {
			
			if(!$click_action->is_valid() ) {
				$data["message"] = __( "Click Action couldn't be saved: ", $this->plugin_slug ) . $click_action->get_validation_messages();
			} else {
				$success = $click_action->save();
				
				if($success) {
					$data["message"] = sprintf(__("All changes for %s were successfully saved.", $this->plugin_slug), "'<b>" . esc_html($click_action->get_name()) . "</b>'");
					$data["id"]      = $click_action->get_id();
					$data["success"] = true;
				} else {
					$data["message"] = sprintf(__("Changes for %s coundn't be saved.", $this->plugin_slug), "'<b>" . esc_html($click_action->get_name()) . "</b>'");
				}
			}
			
		} else if($mode=="new") {
			
			if(!$click_action->is_valid() ) {
				$data["message"] = __( "Click Action couldn't be created: ", $this->plugin_slug ) . $click_action->get_validation_messages();
			} else if($this->is_demo() && !$this->user_can("create_new_actions")) {
				$data["message"] = __("Sorry, but this is a demo and you have created too many actions. Come back later or edit an existing action.", $this->plugin_slug );
			} else {
				$success = $click_action->save();
				
				if($success) {
					$data["message"] = __( "Click Action was successfully created.", $this->plugin_slug );
					$data["id"]      = $click_action->get_id();
					$data["success"] = true;
				} else {
					$data["message"] = __( "Click Action couldn't be created.", $this->plugin_slug );
				}
				
				$this->increment_user("create_new_actions");
			}
			
		} else if($mode=="delete") {
			if($this->is_demo()) {
				$data["message"] = __( "Click Action could not be deleted because of the demo mode.", $this->plugin_slug );
			} else if($click_action->delete()) {
				$data["message"] = sprintf(__("%s was successfully deleted.", $this->plugin_slug), "'<b>" . esc_html($click_action->get_name()) . "</b>'");
				$data["id"]      = -2;
				$data["success"] = true;
			} else {
				$data["message"] = sprintf(__("%s coundn't be deleted.", $this->plugin_slug), "'<b>" . esc_html($click_action->get_name()) . "</b>'");
			}
							
		} else if($mode=="import") {
			if(!isset($_REQUEST['import_data'])) {
				$data["message"] = __( "Click Action couldn't be imported.", $this->plugin_slug);
			} else if($this->is_demo() && !$this->user_can("create_new_actions")) {
				$data["message"] = __("Sorry, but this is a demo and you have created too many actions. Come back later or edit an existing action.", $this->plugin_slug );
			} else {
				$raw         = stripslashes_deep( $_REQUEST['import_data'] );
				//$raw          = $_REQUEST['import_data'];
				$click_action = Click_Action::import($raw);
				
				if($click_action) {
					if(!$click_action->is_valid() ) {
						$data["message"] = __( "Click Action couldn't be imported:", $this->plugin_slug) . $click_action->get_validation_messages();
					} else {
						$success = $click_action->save();
						if($success) {
							$data["id"]   = $click_action->get_id();
							$data["mode"] = "show";
							$data["message"] = sprintf(__("%s was successfully imported.", $this->plugin_slug), "'<b>" . esc_html($click_action->get_name()) . "</b>'");
							$data["success"] = true;
							$this->increment_user("create_new_actions");
						} else {
							$data["message"] = sprintf(__("Imported Action %s coundn't be saved.", $this->plugin_slug), "'<b>" . esc_html($click_action->get_name()) . "</b>'");
						}
					}
				} else {
					$data["message"] = __( "Import Data doesn't seem to be valid.", $this->plugin_slug);
				}
			}
		} else if($mode=="copy") {
			$click_action->set_id(-1);
			$click_action->set_name($click_action->get_name() . __(" - New", $this->plugin_slug));

			if($this->is_demo() && !$this->user_can("create_new_actions")) {
				$data["message"] = __("Sorry, but this is a demo and you have created too many actions. Come back later or edit an existing action.", $this->plugin_slug );
			} else if($click_action->save()) {
				$data["message"] = __("Click Action was successfully copied.", $this->plugin_slug);
				$data["id"]      = $click_action->get_id();
				$data["success"] = true;
				$this->increment_user("create_new_actions");
			} else {
				$data["message"] = sprintf(__("Copy of %s coundn't be created.", $this->plugin_slug), "'<b>" . esc_html($click_action->get_name()) . "</b>'");
				$data["success"] = false;
			}
			
			//}
		} else {
			$data["message"] = __("Unknown request: ", $this->plugin_slug) . $mode;
		}
		
		$click_actions         = Click_Action::get_click_actions(null, null, "name", "asc");
		$data["click_actions"] = Click_Action::get_actions_json($click_actions);
		$data["menu"]          = Click_Action::render_click_action_menu($click_actions);
		
		// Output Values:
		//       -id:   The id that should be displayed
		//     -mode:   The mode of the page. Values: edit, new, delete, import
		//  -message:   A message that should be displayed.		
		echo json_encode($data);
		
		die();
	}
	
	//protected function get_click_actions
	
	protected function handle_action_edit() {
		//if(isset(
	}
	
	
	public function get_prefix() {
		return $this->prefix;
	}
	
	public function set_prefix($value) {
		$this->prefix = $value;
	}
	
	public function get_config($name) {
		if(isset($this->config[$name]))
			return $this->config[$name];
		else
			return false;
	}
	
	public function add_config($name, $value) {
		$this->config[$name] = $value;
	}
	
	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		$create_map_table    = Interactive_Map::get_create_sql();
		$create_action_table = Click_Action::get_create_sql();
		
		dbDelta($create_map_table);
		dbDelta($create_action_table);
		
		// Check if this is the first activation
		$option_key = Interactive_Map_Builder::get_instance()->get_activation_option_key();
		if(get_option($option_key)===false) {
			self::first_activate();
			update_option($option_key, true);
		}
		
		
	}
	
	public static function first_activate() {
		include_once( 'lib/data/us_map_and_bars.php' );
		include_once( 'lib/data/germany_and_text.php' );
		include_once( 'lib/data/gb_and_images.php' );
		include_once( 'lib/data/more_click_actions.php' );
	}
	
	public function get_activation_option_key() {
		return $this->get_plugin_slug() . '_first_activation';
	}
	
	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		// TODO: Define deactivation functionality here
	}

	public static function have_to_use_less() {
		return false;
	}
	
	public static function enqueue_less_script() {
		wp_enqueue_script( 'lesscss', plugins_url( self::$less_path . 'less.min.js', __FILE__ ) );
	}
	
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix_array ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( in_array($screen->id, $this->plugin_screen_hook_suffix_array) ) {
				
			wp_enqueue_style($this->plugin_slug .'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ), array(), $this->version);
			
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_style($this->plugin_slug . '-codemirror', plugins_url( 'external/codemirror/codemirror.css', __FILE__ ));
			wp_enqueue_style($this->plugin_slug . '-codemirror-fullscreen', plugins_url( 'external/codemirror/fullscreen.css', __FILE__ ));
			wp_enqueue_style($this->plugin_slug . '-fontawesome', plugins_url( 'external/font-awesome/css/font-awesome.min.css', __FILE__ ));
			
		}

	}

	public function enqueue_admin_lessstyles() {
		echo '<link rel="stylesheet/less" href="' . plugins_url( self::$less_path . 'admin.less' . '?r=' . rand(0,10000000) , __FILE__ ) . '" type="text/css" media="screen" />' . "\n";
	}
	
	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if(!isset( $this->plugin_screen_hook_suffix_array)) {
			return;
		}
		
		$screen = get_current_screen();
		if(in_array($screen->id, $this->plugin_screen_hook_suffix_array)) {

			// Add admin.js - This contains the main functionalities.
			$admin_script = $this->plugin_slug.'-admin-script';
			wp_enqueue_script($admin_script, plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery'), $this->version );
			
			// Additional functionalities mainly for the click action page.
			wp_enqueue_script($this->plugin_slug.'-click-action-script', plugins_url( 'js/click_action.js', __FILE__ ), array( 'jquery'), $this->version );
			
			// Additional functionalities mainly for the map builder.
			wp_enqueue_script($this->plugin_slug.'-map-builder-script', plugins_url( 'js/map_builder.js', __FILE__ ), array( 'jquery', 'jquery-ui-draggable', 'jquery-ui-resizable', 'jquery-ui-slider', 'iris', 'jquery-ui-sortable'), $this->version . rand(0,10000000) );
			
			/**
			 *  CodeMirror (http://codemirror.net/)
			 *  
			 *  This is used to highlight the JavaScript, HTML and CSS.
			 *  
			 *  Basis:
			 *   - codemirror.js
			 *  Has the following addons and modes:
			 *   - mode/css/css.js
			 *   - mode/javascript/javascript.js
			 *   - mode/xml/xml.js
			 *   - mode/htmlmixed/htmlmixed.js
			 *   - mode/runmode/runmode.js
			 *   - addon/edit/closetag.js
			 *   - addon/fold/xml-fold.js
			 *   - addon/edit/matchtags.js
			 *   - addon/mode/overlay.js
			 *   - addon/selection/active-line.js
			 *   - addon/display/fullscreen.js
			 */
			wp_enqueue_script($this->plugin_slug.'-codemirror', plugins_url( 'external/codemirror/codemirror-compressed.js', __FILE__ ));
			
			/**
			 *  Autosize (http://www.jacklmoore.com/autosize/)
			 *  jQuery PlugIn
			 *  
			 *  This is used to resize the textarea automatically when content id added.
			 */
			wp_enqueue_script($this->plugin_slug.'-autosize', plugins_url( 'external/autosize/jquery.autosize.min.js', __FILE__ ));
			
			// Get js vars to translate
			$vars = $this->js_locale_vars();
			
			// Add script variables
			$vars['ajax_url'] = admin_url( 'admin-ajax.php' );
			
			wp_localize_script($admin_script, 'imb_object', $vars);
		}

	}
	
	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		//wp_enqueue_script( $this->plugin_slug . '-meisterbox', plugins_url( 'js/meisterbox.js', __FILE__ ), array( 'jquery' ), $this->get_version(), true );
		//wp_enqueue_script( $this->plugin_slug . '-generator', plugins_url( 'js/generator.js', __FILE__ ), array( 'jquery' ), $this->get_version(), true);
	}
	
	public function enqueue_data($data) {
		$this->js_data[$data["id"]] = $data;
		
		wp_localize_script($this->plugin_slug.'-generator', 
		                   "map_generator", 
						   array( "maps" => $this->js_data ));
	}
	
	public function enqueue_map_scripts() {
		wp_enqueue_script( $this->plugin_slug.'-meisterbox', 
		                   plugins_url( 'js/meisterbox.js', __FILE__ ), 
						   array( 'jquery' ), 
						   $this->get_version(), 
						   true);
		
		wp_enqueue_script( $this->plugin_slug.'-googlejsapi', 
		                   "https://www.google.com/jsapi",
						   array( 'jquery' ), 
						   $this->get_version(), 
						   true);
		wp_enqueue_script( $this->plugin_slug.'-generator', 
		                   plugins_url( 'js/generator.js', __FILE__ ), 
						   array( 'jquery', $this->plugin_slug.'-googlejsapi' ), 
						   $this->get_version(), 
						   true);
		wp_localize_script($this->plugin_slug.'-generator', 
		                   "map_generator", 
						   array( "maps" => array() ));
	}
	
	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-meisterbox-styles', plugins_url( 'css/meisterbox.css', __FILE__ ), array(), $this->version );
	}
	
	/**
	 *  Variables that will be translated and will be used in the JavaScript.
	 *  
	 *  @return array
	 */
	private function js_locale_vars() {
		return array(
			"select_continent"=>__("- For more detail: Select continent -", $this->plugin_slug),
			"select_subcontinent"=>__("- For more detail: Select subcontinent -", $this->plugin_slug),
			"select_country"=>__("- For more detail: Select country -", $this->plugin_slug),
			"select_state"=>__("- For more detail: Select state -", $this->plugin_slug),
			"displaymode_regions"=>__("Regions", $this->plugin_slug),
			"displaymode_marker_text"=>__("Markers from text", $this->plugin_slug),
			"displaymode_marker_coordinates"=>__("Markers from coordinates", $this->plugin_slug),
			"displaymode_text"=>__("Text", $this->plugin_slug),
			"resolution.label.countries" =>__("Countries", $this->plugin_slug),
			"resolution.label.provinces" =>__("Provinces", $this->plugin_slug),
			"resolution.label.metros" =>__("Metros", $this->plugin_slug),
			"resolution.label.continents" =>__("Continents", $this->plugin_slug),
			"resolution.label.subcontinents" =>__("Subcontinents", $this->plugin_slug),
			
			// For click action page:
			"iframe_url" => admin_url("admin.php?page=interactive_map_builder_clickactions&test="),
			"click_action_page_url" => admin_url("admin.php?page=interactive_map_builder_clickactions"),
			
		);
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		$capability = apply_filters( 'imap_builder_capability', 'install_plugins');
		if($this->is_demo()) {
			$capability = "subscriber";
		}
		$slug       = $this->plugin_slug;
		
		// Main Menu
		$this->plugin_screen_hook_suffix_array[] = add_menu_page(
			__( 'Page Title', $this->plugin_slug ),
			__( 'I Map Builder', $this->plugin_slug ),
			$capability,
			$slug,
			null,
			"dashicons-admin-site"
		);

		// Submenu:  Manage Maps
		$manage_maps = add_submenu_page(
			$this->plugin_slug,
			__( 'All Maps', $this->plugin_slug ),     // Page Title
			__( 'All Maps', $this->plugin_slug ), // Menu Title
			$capability,
			$slug,
			array( $this, 'display_manage_maps' )
		);
		$this->plugin_screen_hook_suffix_array[] = $manage_maps;
		add_action('load-'.$manage_maps, array($this, 'add_manage_maps_help'));
		
		// Submenu:  Map Builder
		$map_builder = add_submenu_page(
			$this->plugin_slug,
			__( 'Map Builder', $this->plugin_slug ), // Page Title
			__( 'Map Builder', $this->plugin_slug ),     // Menu Title
			$capability,
			$slug . '_new',
			array( $this, 'display_map_builder' )
		);
		$this->plugin_screen_hook_suffix_array[] = $map_builder;
		add_action('load-'.$map_builder, array($this, 'add_map_builder_help'));
		
		// Submenu:  Click Actions
		$click_actions = add_submenu_page(
			$this->plugin_slug,
			__( 'Map Templates', $this->plugin_slug ), // Page Title
			__( 'Map Templates', $this->plugin_slug ), // Menu Title
			$capability,
			$slug . '_clickactions',
			array( $this, 'display_plugin_click_actions' )
		);
		$this->plugin_screen_hook_suffix_array[] = $click_actions;
		add_action('load-'.$click_actions, array($this, 'add_click_action_help'));
		
		// Catching iframe loads
		add_action( 'load-' . $this->plugin_screen_hook_suffix_array[count($this->plugin_screen_hook_suffix_array)-1], array( $this, 'ttt') );
	}

	public function add_manage_maps_help() {
		Help_Tabs::create_managemaps_helptabs();
	}
	
	public function add_map_builder_help() {
		Help_Tabs::create_mapbuilder_helptabs();
	}
	
	public function add_click_action_help() {
		Help_Tabs::create_clickaction_helptabs();
	}
	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}
	
	/**
	 *  Displays the list of maps.
	 *  
	 *  @since 1.0.0
	 */
	public function display_manage_maps() {
		// Posts per page
		$ppp = 5;
		// Current page
		$pno = 1;
		// Number of maps
		$count = Interactive_Map::get_map_count();
		
		// Max number of pages
		$pages = ceil($count / $ppp);
		if($count==0)
			$pages = 1;
		
		$delete_confirm = null;
		if(isset($_GET) && isset($_GET['delete'])) {
			$id  = $_GET['delete'];
			$map = Interactive_Map::get_map($id);
			
			if(!$map) {
				// Map id doesn't exist.
				$arr = urlencode_deep(array( "msg" => "Map could not be found", "type" => "c"));
				wp_safe_redirect(add_query_arg($arr, admin_url("admin.php?page=interactive_map_builder")));
				//wp_safe_redirect(admin_url("admin.php?page=interactive_map_builder"));
			} else {
				$result = $map->delete();
				if($result) {
					$arr = urlencode_deep(array( "msg" => "Map was successfully deleted", "type" => "g"));
					wp_safe_redirect(add_query_arg($arr, admin_url("admin.php?page=interactive_map_builder")));
				} else {
					$arr = urlencode_deep(array( "msg" => "Map couldn't be deleted. ", "type" => "c"));
					wp_safe_redirect(add_query_arg($arr, admin_url("admin.php?page=interactive_map_builder")));
				}
			}
		}
		
		if(isset($_GET) && isset($_GET['copy'])) {
			$id  = $_GET['copy'];
			$map = Interactive_Map::get_map($id);
			
			if(!$map) {
				// Map id doesn't exist.
				$arr = urlencode_deep(array( "msg" => "Map could not be found", "type" => "c"));
				wp_safe_redirect(add_query_arg($arr, admin_url("admin.php?page=interactive_map_builder")));
				//wp_safe_redirect(admin_url("admin.php?page=interactive_map_builder"));
			} else {
				$map->set_id("-1");
				$map->set_name(sprintf(__("Copy of %s", $this->plugin_slug), $map->get_name()));
				$map->save();
				$arr = urlencode_deep(array( "msg" => "Map was successfully copied", "type" => "g", "par" => $map->get_id()));
				wp_safe_redirect(add_query_arg($arr, admin_url("admin.php?page=interactive_map_builder")));
			}
		}
		
		$orderby_values  = array("changed" => "time_changed", "mapid" => "id", "name" => "name");
		$orderby         = "changed";
		
		$order_values  = array("asc", "desc");
		$order         = "desc";
		
		if(isset($_GET) && isset($_GET['orderby']) && isset($_GET['order'])) {
			$orderby = $_GET['orderby'];
			$order   = $_GET['order'];
			
			if(!array_key_exists($orderby, $orderby_values) || !in_array($order, $order_values)) {
				wp_safe_redirect(admin_url("admin.php?page=interactive_map_builder"));
			}
		} else {
			$_GET['orderby'] = $orderby;
			$_GET['order']   = $order;
		}
		
		if(isset($_GET) && isset($_GET['p'])) {
			$number = intval($_GET['p']);
			
			if($number < 1) {
				wp_safe_redirect(admin_url("admin.php?page=interactive_map_builder&orderby=$orderby&order=$order&p=1"));
			} else if($pages < $number) {
				wp_safe_redirect(admin_url("admin.php?page=interactive_map_builder&orderby=$orderby&order=$order&p=$pages"));
			} else {
				$pno = $number;
			}
		}
		
		$view_message = false;
		if(isset($_GET) && isset($_GET['msg'])) {
			$view_message = $_GET['msg'];
		}
		
		$highlighted_id = -1;
		if(isset($_GET) && isset($_GET['par'])) {
			$highlighted_id = $_GET['par'];
		}
		
		$maps     = Interactive_Map::get_maps( ($pno-1) * $ppp, $ppp, $orderby_values[$orderby], $order);
		$map_from = ($pno-1) * $ppp + 1;
		$map_to   = $map_from + count($maps) - 1;
		$page_url = admin_url("admin.php?page=interactive_map_builder&orderby=$orderby&order=$order");
		$this_url = admin_url("admin.php?page=interactive_map_builder&orderby=$orderby&order=$order&p=$pno");
		$base_url = admin_url("admin.php?page=interactive_map_builder");
		include_once( 'views/manage_maps.php' );
	}
	
	/**
	 *  Displays the map builder.
	 *  
	 *  @since 1.0.0
	 */
	public function display_map_builder() {
		
		// 1   - Find out if an existing map will be edited or a new map will be created
		//       Expects that the url has somewhere map={id}
		$map = null;
		if(isset($_GET) && isset($_GET['map'])) {
			$id  = $_GET['map'];
			$map = Interactive_Map::get_map($id);
			
			if(!$map) {
				// Map id doesn't exist.
				wp_safe_redirect(admin_url("admin.php?page=interactive_map_builder"));
			} 
		} else {
			$js_options = "";
			$map        = new Interactive_Map(-1, "", "", $js_options, "", "", "013", "", "");
			$map->empty_name();
		}
		
		if( isset($_POST) && !empty($_POST) ) {
			global $wp_filesystem;
			WP_Filesystem();
			
			// We have a post!
			$text = "";
			if( !empty($_FILES) ) {
				$upload_errors = array(
						0 => "There is no error, the file uploaded with success",
						1 => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
						2 => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
						3 => "The uploaded file was only partially uploaded",
						4 => "No file was uploaded",
						6 => "Missing a temporary folder",
				);
				if(array_key_exists("file", $_FILES) 
				   && array_key_exists("tmp_name", $_FILES["file"])
				   && array_key_exists("error", $_FILES["file"])) {
				   
					switch ($_FILES["file"]["error"]) { 
						case 0:
							$text = $wp_filesystem->get_contents($_FILES['file']['tmp_name']);// file_get_contents($_FILES['file']['tmp_name']);
							$map->set_import($text);
							break;
						case 1:
							break;
						case 2:
							break;
						case 3:
							break;
						case 4:
							break;
						case 6:
							break;
						default:
						
							break;
					}
					
				} else {
					// Send message that files were not correctly send.
				}
			} else if(array_key_exists("text", $_POST)) {
				$text = stripslashes_deep($_POST["text"]);
				$map->set_import($text);
			}
			
			
		}
		
		// 2   - View Setup
		
		// 2.1 - Collect additional needed data for the view
		$click_actions = Click_Action::get_actions();
		
		// 2.2 - include view
		include_once( 'views/map_builder.php' );
	}

	/**
	 *  Displays the click action page.
	 *  
	 *  @since 1.0.0
	 */
	public function display_plugin_click_actions() {
		// pages: "show", "edit", "new", "delete", "export", "import"
		$page            = "show";
		$new_action      = Click_Action::get_default_click_action();
		$click_actions   = Click_Action::get_click_actions(null, null, "name", "asc");
		$selected_action = $new_action;
		$highlighted_id  = null;
		if(count($click_actions)>0) {
			$highlighted_id  = $click_actions[0]->get_id();
			$selected_action = $click_actions[0];
		}
		if(isset($_GET['action'])) {
			$exists = false;
			$id     = $_GET['action'];
			foreach($click_actions as $click_action) {
				if($click_action->get_id() == $id) {
					$exists          = true;
					$highlighted_id  = $id;
					$selected_action = $click_action;
					break;
				}
			}
			if(!$exists) {
				wp_redirect(admin_url("admin.php?page=interactive_map_builder_clickactions"));
			}
		}
		
		include_once( 'views/click_actions.php' );
	}

	public function get_plugin_slug() {
		return $this->plugin_slug;
	}
	
	public function get_version() {
		return $this->version;
	}
	
	public function is_demo() {
		if(current_user_can('install_plugins'))
			return false;
		else
			return $this->is_demo_mode;
	}
	
	public function user_can($action, $max=6) {
		$client_ip = $_SERVER['REMOTE_ADDR'];
		$ip_user   = get_option( 'interactive_map_builder_demo_user_can' );
		
		if($action=="create_new_maps") {
			$max=5;
		} else if($action=="create_new_actions") {
			$max=3;
		}
		
		if($ip_user === false) {
			return true;
		} else {
			if(array_key_exists($client_ip, $ip_user)) {
				$user = $ip_user[$client_ip];
				
				if(array_key_exists($action, $user) && $user[$action] > $max) {
					return false;
				}
			}
			return true;
		}
	}
	
	public function increment_user($action) {
		$client_ip = $_SERVER['REMOTE_ADDR'];
		$ip_user   = get_option( 'interactive_map_builder_demo_user_can' );
		
		if($ip_user === false || !is_array($ip_user) ) {
			$ip_user = array($client_ip => array($action => 1));
			
		} else {
			if(array_key_exists($client_ip, $ip_user)) {
				$user = $ip_user[$client_ip];
				
				if(array_key_exists($action, $user)) {
					$ip_user[$client_ip][$action] = (int)$user[$action]+1; 
				} else {
					$ip_user[$client_ip][$action] = 1;
				}
			} else {
				$ip_user[] = array($client_ip => array($action => 1));
			}
		}
		update_option( 'interactive_map_builder_demo_user_can', $ip_user );
	}
}

?>