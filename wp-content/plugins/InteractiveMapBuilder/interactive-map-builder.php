<?php
/**
 * Interactive Map Builder.
 *
 * @package   Interactive_Map_Builder
 * @author    Fabian Vellguth <info@meisterpixel.com>
 * @license   GPL-2.0+
 * @link      http://meisterpixel.com
 * @copyright 2014 meisterpixel.com
 *
 * @wordpress-plugin
 * Plugin Name: Interactive Map Builder
 * Plugin URI:  http://www.meisterpixel.com/interactive-map-builder
 * Description: This plugin is a tool to create maps within the Wordpress admin panel. The created maps can be styled and customized in many diffenent ways.
 * Version:     2.0
 * Author:      Fabian Vellguth
 * Author URI:  http://meisterpixel.com
 * Text Domain: interactive_map_builder
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
	
require_once( plugin_dir_path( __FILE__ ) . 'class-interactive-map-builder.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'Interactive_Map_Builder', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Interactive_Map_Builder', 'deactivate' ) );

Interactive_Map_Builder::get_instance();

require_once('lib/virtual-composer.php');

?>