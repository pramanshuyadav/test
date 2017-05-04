<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access

class class_post_grid_settings{

	public function __construct(){
		
		add_action('admin_menu', array( $this, 'post_grid_menu_init' ));
		
		}


	public function post_grid_menu_license(){
		include('menu/license.php');	
	}
	
	public function post_grid_menu_settings(){
		include('menu/settings.php');	
	}
	
	public function post_grid_layout_editor(){
		include('menu/layout-editor.php');	
	}	
	
	
	
	public function post_grid_menu_init() {
		
		add_submenu_page('edit.php?post_type=post_grid', __('Layout Editor','post_grid'), __('Layout Editor','post_grid'), 'manage_options', 'post_grid_layout_editor', array( $this, 'post_grid_layout_editor' ));
		
		add_submenu_page('edit.php?post_type=post_grid', __('Settings','post_grid'), __('Settings','post_grid'), 'manage_options', 'post_grid_menu_settings', array( $this, 'post_grid_menu_settings' ));	
	
		
			
	
	}



	
	
	}
	
new class_post_grid_settings();