<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

<link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_template_directory_uri(); ?>/fav/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="<?php echo get_template_directory_uri(); ?>/fav/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/fav/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_template_directory_uri(); ?>/fav/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/fav/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_template_directory_uri(); ?>/fav/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/fav/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_template_directory_uri(); ?>/fav/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/fav/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="<?php echo get_template_directory_uri(); ?>/fav/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/fav/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="<?php echo get_template_directory_uri(); ?>/fav/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/fav/favicon-16x16.png">
<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/fav/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/fav/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<link href='https://fonts.googleapis.com/css?family=Roboto:400,300italic,300,500,700,700italic,100' rel='stylesheet' type='text/css'>
<link rel='stylesheet' id='meris-main-css'  href='<?php echo get_template_directory_uri(); ?>/css/style.css?ver=1.0.4' type='text/css' media='all' />
<link rel="stylesheet" href="<?php echo get_template_directory_uri();?>/css/locations.css" type="text/css" />
    
<?php wp_head();?>
    
<script type="text/javascript">
  WebFontConfig = {
  google: { families: [ 'Roboto:400,300italic,300,500,700,700italic,100:latin' ] }
  };
  (function() {
  var wf = document.createElement('script');
  wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
  '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
  wf.type = 'text/javascript';
  wf.async = 'true';
  var s = document.getElementsByTagName('script')[0];
  s.parentNode.insertBefore(wf, s);
  })(); 
</script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-76544087-1', 'auto');
  ga('send', 'pageview');
</script>

</head>
<body <?php body_class(); ?>>
<?php
global $enable_home_page;
$enable_home_page = meris_options_array ( 'enable_home_page' );
if (is_home ()) {
	$wrapper_class = 'homepage header-wrapper';
} else {
	$wrapper_class = 'blog-list-page both-aside header-wrapper';
}
if (('page' == get_option ( 'show_on_front' ) && ('' != get_option ( 'page_for_posts' )) && $wp_query->get_queried_object_id () == get_option ( 'page_for_posts' )) || $enable_home_page == "") {
	$wrapper_class = 'blog-list-page both-aside header-wrapper';
}
?>
	<div class="<?php echo $wrapper_class;?>">
		<!--Header-->
<?php
/*$enable_sticky_header = meris_options_array ( 'enable_sticky_header' );
if ($enable_sticky_header != "") {
	get_template_part ( "header","sticky");
 }*/
