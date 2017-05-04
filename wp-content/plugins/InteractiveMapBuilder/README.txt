=== Interactive Map Builder ===
Tags: map, maps, wordpress, plugin, world, countries, interactive, clickable, customizable, color, tooltip, marker, regions, builder, svg
Requires at least: 3.5.1
Tested up to: 3.8.1
Stable tag: 2.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin is a tool to create maps within the Wordpress admin panel. The created maps can be styled and customized in many diffenent ways.

== Description ==

The plugin uses the Google Geo Chart API to create maps. The map builder of this plugin offers an easy interface 
to customize and style the maps in many different ways. Additionally the maps can be made interactive by reacting to
mouse clicks.

A documentation is included to this plugin. Just open `DOCUMENTATION.html` in the `InteractiveMapBuilder` folder.

== Installation ==

The installation procedure is similar to other plugins:

1. Upload the folder `InteractiveMapBuilder` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. A menu entry should appear on the left side and the plugin should be ready.

== Frequently Asked Questions ==

= How do I insert a map to a post or a page? =

First, you have to find the map under "All Maps". Then you have to copy shortcode from the shortcode column. This text can 
be inserted to pages and posts. The map should appear within the page after saving it. A shortcode usually looks like 
this: [interactive_map id="1"]

= A click action doesn't work. What can I do? =

If a click action doesn't work as expected, it usually has an error. In this case, you would have to locate the error.
Depending on the browser, there are diffentent approaches. This link might help to find the error:
http://codex.wordpress.org/Using_Your_Browser_to_Diagnose_JavaScript_Errors

== Changelog ==

= 2.0 =
* Bigger update. Includes:
	- New: Map Builder allows to edit the HTML, CSS and JavaScript
	- New: Text labels
	- New: HTML Tooltips
	- New: Selectable tooltips trigger "hover" and "click"
	- New: Virtual Composer ready. List of maps will be displayed in the composer.
	- New: Continent and Subcontinent border resolutions
	- Changed: The "Click Actions" were renamed to "Map Templates"
	
= 1.0.1 = April 4, 2014
* Minor changes. Improved compatibility to other plugins.
= 1.0.0 = March 23, 2014
* The very first version that was distributed.
