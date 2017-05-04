<?php
/*
Plugin Name: Post Grid
Plugin URI: http://pickplugins.com
Description: Awesome post grid for query post from any post type and display on grid.
Version: 2.0.5
Author: pickplugins
Author URI: http://pickplugins.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


class PostGrid{
	
	
	public function __construct(){
		
		define('post_grid_plugin_url', plugins_url('/', __FILE__) );
		define('post_grid_plugin_dir', plugin_dir_path(__FILE__) );
		define('post_grid_wp_url', 'https://wordpress.org/plugins/post-grid/' );
		define('post_grid_wp_reviews', 'http://wordpress.org/support/view/plugin-reviews/post-grid' );
		define('post_grid_pro_url','http://paratheme.com/items/post-grid-awesome-grid-for-any-post-type/' );
		define('post_grid_demo_url', 'http://paratheme.com/demo/post-grid/' );
		define('post_grid_conatct_url', 'http://paratheme.com/contact/' );
		define('post_grid_qa_url', 'http://paratheme.com/qa/' );
		define('post_grid_plugin_name', 'Post Grid' );
		define('post_grid_version', '2.0.5' );
		define('post_grid_customer_type', 'free' );		
		define('post_grid_share_url', 'https://wordpress.org/plugins/post-grid/' );
		define('post_grid_tutorial_video_url', '//www.youtube.com/embed/WynnnEl0ncM' );
		define('post_grid_textdomain', 'post_grid' );		
		

		
		include( 'includes/class-functions.php' );
		include( 'includes/class-shortcodes.php' );
		include( 'includes/class-settings.php' );		
		include( 'includes/meta.php' );		
		include( 'includes/functions.php' );

		add_action( 'wp_enqueue_scripts', array( $this, 'post_grid_scripts_front' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'post_grid_scripts_admin' ) );
		add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' ); 
		
		add_action( 'plugins_loaded', array( $this, 'post_grid_load_textdomain' ));
		
		register_activation_hook( __FILE__, array( $this, 'post_grid_install' ) );
		register_deactivation_hook( __FILE__, array( $this, 'post_grid_deactivation' ) );
		//register_uninstall_hook( __FILE__, array( $this, 'post_grid_uninstall' ) );
		
		}
		
	public function post_grid_load_textdomain() {
	  load_plugin_textdomain( 'post_grid', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' ); 
	}
		
	public function post_grid_install(){
		
		
		$class_post_grid_functions = new class_post_grid_functions();
		
		$post_grid_layout_content = get_option('post_grid_layout_content');
		if(empty($post_grid_layout_content)){
			$layout_content_list = $class_post_grid_functions->layout_content_list();
			update_option('post_grid_layout_content', $layout_content_list);
			}
		
		
		//$layout_hover_list = $class_post_grid_functions->layout_hover_list();		
		//update_option('post_grid_layout_hover', $layout_hover_list);
		
		do_action( 'post_grid_action_install' );
		

		
		}		
		
	public function post_grid_uninstall(){
		
		do_action( 'post_grid_action_uninstall' );
		}		
		
	public function post_grid_deactivation(){
		
		do_action( 'post_grid_action_deactivation' );
		}
		
	
		
	public function post_grid_scripts_front(){
		wp_enqueue_script('jquery');

		wp_enqueue_style('post_grid_style', post_grid_plugin_url.'/assets/frontend/css/style.css');
		wp_enqueue_script('post_grid_scripts', plugins_url( '/assets/frontend/js/scripts.js' , __FILE__ ) , array( 'jquery' ));
		wp_localize_script('post_grid_scripts', 'post_grid_ajax', array( 'post_grid_ajaxurl' => admin_url( 'admin-ajax.php')));

		wp_enqueue_style('font-awesome', post_grid_plugin_url.'assets/frontend/css/font-awesome.css');		

		wp_enqueue_style('style.skins', post_grid_plugin_url.'assets/global/css/style.skins.css');
		wp_enqueue_style('style.layout', post_grid_plugin_url.'assets/global/css/style.layout.css');
		
		}
		
		
	public function post_grid_scripts_admin(){
			
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-droppable');
		
		wp_enqueue_script('post_grid_admin_js', plugins_url( 'assets/admin/js/scripts.js' , __FILE__ ) , array( 'jquery' ));
		wp_localize_script( 'post_grid_admin_js', 'post_grid_ajax', array( 'post_grid_ajaxurl' => admin_url( 'admin-ajax.php')));

		wp_enqueue_style('post_grid_admin_style', post_grid_plugin_url.'assets/admin/css/style.css');

		//ParaAdmin
		wp_enqueue_style('ParaAdmin', post_grid_plugin_url.'assets/admin/ParaAdmin/css/ParaAdmin.css');		
		wp_enqueue_script('ParaAdmin', plugins_url( 'assets/admin/ParaAdmin/js/ParaAdmin.js' , __FILE__ ) , array( 'jquery' ));
		wp_enqueue_style('font-awesome', post_grid_plugin_url.'assets/frontend/css/font-awesome.css');	

		wp_enqueue_script('codemirror', plugins_url( 'assets/admin/js/codemirror.js' , __FILE__ ) , array( 'jquery' ));
		wp_enqueue_script('codemirror-simplescrollbars', plugins_url( 'assets/admin/js/simplescrollbars.js' , __FILE__ ) , array( 'jquery' ));
		wp_enqueue_script('codemirror-css', plugins_url( 'assets/admin/js/css.js' , __FILE__ ) , array( 'jquery' ));	
		wp_enqueue_script('codemirror-javascript', plugins_url( 'assets/admin/js/javascript.js' , __FILE__ ) , array( 'jquery' ));				
		wp_enqueue_style('codemirror', post_grid_plugin_url.'assets/admin/css/codemirror.css');
		wp_enqueue_style('codemirror-simplescrollbars', post_grid_plugin_url.'assets/admin/css/simplescrollbars.css');		
			
		wp_enqueue_script('layout-editor', plugins_url( 'assets/admin/js/layout-editor.js' , __FILE__ ) , array( 'jquery' ));	
		
		wp_enqueue_style('style.skins', post_grid_plugin_url.'assets/global/css/style.skins.css');
		wp_enqueue_style('style.layout', post_grid_plugin_url.'assets/global/css/style.layout.css');		
		
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'post_grid_color_picker', plugins_url('/assets/admin/js/color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
		
		
		}
		
		
	
	}

new PostGrid();

