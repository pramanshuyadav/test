<?php
/**
 * Class that is supposed to be a container for static functions that are needed within the view files.
 * 
 * @package View
 */
class View_Helper {
	/**
	 * Constructor.
	 * 
	 * This is private, because the class is supposed to be a container for static view helper and shouldn't
	 * have any instances.
	 */
	private function __construct() { }
	
	/**
	 * Creates the URL for the sorted maps table.
	 *
	 * @param string $page_slug
	 * @param string $sortname
	 * @param string $default_order
	 */
	public static function getSortUrl($page_slug, $sortname, $default_order="asc") {
		if(!self::isSortSelected($sortname) || !isset($_GET['order'])) 
			return admin_url("admin.php?page=$page_slug&orderby=$sortname&order=$default_order");
			
		if($_GET['order'] == "asc")
			return admin_url("admin.php?page=$page_slug&orderby=$sortname&order=desc");
		else
			return admin_url("admin.php?page=$page_slug&orderby=$sortname&order=asc");
	}
	
	public static function isSortSelected($sortname) {
		if(isset($_GET) && isset($_GET['orderby']) && $_GET['orderby'] == $sortname)
			return true;
		return false;
	}
	
	public static function getCssSortable($sortname, $default_order="asc") {
		if(!self::isSortSelected($sortname) || !isset($_GET['order']))
			return "sortable $default_order";
		if($_GET['order'] == "asc")
			return "sorted asc";
		else
			return "sorted desc";
	}
}

?>