<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */

// If uninstall, not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-interactive-map-builder.php' );


$table1 = Interactive_Map::get_drop_table_sql();
$table2 = Click_Action::get_drop_table_sql();

global $wpdb;
$wpdb->query($table1);
$wpdb->query($table2);
delete_option(Interactive_Map_Builder::get_instance()->get_activation_option_key());
