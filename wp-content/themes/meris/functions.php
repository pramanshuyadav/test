<?php
error_reporting(0);
define( 'MERIS_THEME_BASE_URL', get_template_directory_uri());
define( 'MERIS_OPTIONS_FRAMEWORK', get_template_directory().'/admin/' ); 
define( 'MERIS_OPTIONS_FRAMEWORK_URI',  MERIS_THEME_BASE_URL. '/admin/'); 
define('MERIS_OPTIONS_PREFIXED' ,'meris_');
include('aq_resizer.php');
//require_once('wp_bootstrap_navwalker.php');
/**
 * Required: include options framework.
 */
 
load_template( trailingslashit( get_template_directory() ) . 'admin/options-framework.php' );

/**
 * Theme setup
 */
 
load_template( trailingslashit( get_template_directory() ) . 'includes/theme-setup.php' );

/**
 * Theme Functions
 */
 
load_template( trailingslashit( get_template_directory() ) . 'includes/theme-functions.php' );

/**
 * Theme breadcrumb
 */
load_template( trailingslashit( get_template_directory() ) . 'includes/class-breadcrumb.php');
/**
 * Theme widget
 */
 
load_template( trailingslashit( get_template_directory() ) . 'includes/theme-widget.php' );
/**
 * Theme Metabox
 */
 
load_template( trailingslashit( get_template_directory() ) . 'includes/metabox-options.php' );

// Our custom post type function
function create_posttype() {

	register_post_type( 'gallery',
	// CPT Options
		array(
			'labels' => array(
				'name' => __( 'Gallery' ),
				'singular_name' => __( 'Gallery' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'Gallery'),
			'supports' => array( 'title', 'editor', 'comments', 'excerpt', 'custom-fields', 'thumbnail', 'page-attributes'  ),
			'hierarchical' => true
		)
	);
register_post_type( 'addresses',
	// CPT Options
		array(
			'labels' => array(
				'name' => __( 'Manage Address' ),
				'singular_name' => __( 'Address' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'addresses'),
			'supports' => array( 'title', 'editor', 'comments', 'excerpt', 'custom-fields' , 'thumbnail'),
		)
	);
}

function functCustomContactMap($atts=null)
{
	global $wpdb;
	 
	 $result='';
	 ?>
	 <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri();?>/css/shadowbox.css">
<script type="text/javascript" src="<?php echo get_template_directory_uri();?>/js/shadowbox.js"></script>
<script type="text/javascript">
Shadowbox.init({
    overlayOpacity: 0,
	resizeDuration: .7
});
</script>
<script type="text/javascript">
function mouseOverClick(postname)
{
	    jQuery("#"+postname+"_hover").addClass("onhoverlocation");
	
}
function onmouseoutClick(postname)
{
	    jQuery("#"+postname+"_hover").removeClass("onhoverlocation");
}
jQuery(".various").fancybox({
		maxWidth	: 250,
		maxHeight	: 352,
		fitToView	: false,
		width		: '100%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});</script><?php 

$args = array(
	'posts_per_page'   => -1,
	'orderby'          => 'title',
	'order'            => 'ASC',
	'post_type'        => 'addresses',
	'post_status'      => 'publish',
	'suppress_filters' => true 
);
$posts_array = get_posts( $args );
//echo "<pre>";
//print_r($posts_array);
	$result.='<div id="location_background"><div id="location_map" style="height:500px;background-repeat:no-repeat;">';
	$result.='<ul>';
	for($i=0;$i<count($posts_array);$i++)
	{


		$result.='<li id="'.$posts_array[$i]->post_name.'" style="margin-left:'.get_field('padding_left',$posts_array[$i]->ID).'px;margin-top:'.get_field('padding_top',$posts_array[$i]->ID).'px;">
		<a id="'.$posts_array[$i]->post_name.'_hover" href="'.site_url().'/city.php?id='.$posts_array[$i]->ID.'"  rel="shadowbox;height=352;width=250"></a></li>';
		//<em>'.$posts_array[$i]->post_title.'</em>
	}
	
    $result.='</ul>';
    $result.='</div></div>';
  $iPhone = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
    $Android= stripos($_SERVER['HTTP_USER_AGENT'],"Android");
    $result.='<ul class="list-group locationlistbox" style="margin-left:0px;" >';
    for($j=0;$j<count($posts_array);$j++)
	{
		/*$result.='<li class="list-group-item col-md-3" ><a onmouseout="return onmouseoutClick(\''.$posts_array[$j]->post_name.'\')" onmouseover="return mouseOverClick(\''.$posts_array[$j]->post_name.'\');" href="'.site_url().'/city.php?id='.$posts_array[$j]->ID.'"  rel="shadowbox;height=352;width=250">'.$posts_array[$j]->post_title.'</a></li>';*/
if($iPhone==true || $Android==true)
		{
		$result.='<li class="list-group-item col-md-3 col-sm-6 col-xs-6" ><a class="various fancybox.iframe" onmouseout="return onmouseoutClick(\''.$posts_array[$j]->post_name.'\')" onmouseover="return mouseOverClick(\''.$posts_array[$j]->post_name.'\');" href="'.site_url().'/city.php?id='.$posts_array[$j]->ID.'" >'.$posts_array[$j]->post_title.'</a></li>';
	}
	else
	{
		$result.='<li class="list-group-item col-md-3 col-sm-6 col-xs-6"  ><a onmouseout="return onmouseoutClick(\''.$posts_array[$j]->post_name.'\')" onmouseover="return mouseOverClick(\''.$posts_array[$j]->post_name.'\');" href="'.site_url().'/city.php?id='.$posts_array[$j]->ID.'"  rel="shadowbox;height=352;width=250">'.$posts_array[$j]->post_title.'</a></li>';
	}
	}
  $result.='</ul>';
	return $result;
	
}
add_shortcode('custom_contactmap','functCustomContactMap');

// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );



/* Custom Post Type for  Partner Logos*/

function partnerLogo() {

	$labels = array(

		'name'                  => _x( 'Partner Logos', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Partner Logos', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Partner Logos', 'text_domain' ),
		'name_admin_bar'        => __( 'Partner Logos', 'text_domain' ),
		'archives'              => __( 'Item Archives', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'All Items', 'text_domain' ),
		'add_new_item'          => __( 'Add New Item', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Item', 'text_domain' ),
		'edit_item'             => __( 'Edit Item', 'text_domain' ),
		'update_item'           => __( 'Update Item', 'text_domain' ),
		'view_item'             => __( 'View Item', 'text_domain' ),
		'search_items'          => __( 'Search Item', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
	

	);
	$args = array(
		'label'                 => __( 'Partner Logos', 'text_domain' ),
		'description'           => __( 'Partner Logos Description', 'text_domain' ),
		'labels'                => $labels,
		'supports' => array( 'title', 'editor', 'thumbnail' ),
		//'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'partner-logos', $args );

}
add_action( 'init', 'partnerLogo', 0 );
