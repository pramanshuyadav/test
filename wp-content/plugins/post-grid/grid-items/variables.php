<?php
/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access

	global $post;
	$post_grid_meta_options = get_post_meta( $post_id, 'post_grid_meta_options', true );
	
	if(!empty($post_grid_meta_options['post_types']))
	$post_types = $post_grid_meta_options['post_types'];
	




	if(!empty($post_grid_meta_options['post_status']))
	$post_status = $post_grid_meta_options['post_status'];	
	

	
	//var_dump($offset);
	
	if(!empty($post_grid_meta_options['posts_per_page'])){
		$posts_per_page = $post_grid_meta_options['posts_per_page'];
		}
	else{
		$posts_per_page = -1;
		}
	
	
	if(!empty($post_grid_meta_options['exclude_post_id']))	
	$exclude_post_id = $post_grid_meta_options['exclude_post_id'];
	
	if(!empty($post_grid_meta_options['query_order']))
	$query_order = $post_grid_meta_options['query_order'];	
	
	if(!empty($post_grid_meta_options['query_orderby']))
	$query_orderby = $post_grid_meta_options['query_orderby'];
	
	//var_dump($query_orderby);
	$str_orderby = '';
	foreach($query_orderby as $orderby){
		
		$str_orderby.= $orderby.' ';
		
		}
	$query_orderby = $str_orderby;
	//var_dump($query_orderby);
	
	
	
	
	if(!empty($post_grid_meta_options['keyword'])){
		
		$keyword = $post_grid_meta_options['keyword'];	
		}
	else{
		$keyword = '';
		}
	
	if(!empty($post_grid_meta_options['layout']['content']))
	$layout_content = $post_grid_meta_options['layout']['content'];	
	
	if(!empty($post_grid_meta_options['layout']['hover']))
	$layout_hover = $post_grid_meta_options['layout']['hover'];
	
	if(!empty($post_grid_meta_options['skin'])){
		$skin = $post_grid_meta_options['skin'];	
		}
	else{
		$skin = 'flat';	
		
		}
	
	if(!empty($post_grid_meta_options['custom_js']))
	$custom_js = $post_grid_meta_options['custom_js'];	
	
	if(!empty($post_grid_meta_options['custom_css']))
	$custom_css = $post_grid_meta_options['custom_css'];
		

	
	if(!empty($post_grid_meta_options['width']['desktop'])){
		
		$items_width_desktop = $post_grid_meta_options['width']['desktop'];
		}
	else{
		$items_width_desktop = '';
		
		}
		
		
	if(!empty($post_grid_meta_options['width']['tablet'])){
		
		$items_width_tablet = $post_grid_meta_options['width']['tablet'];
		}
	else{
		$items_width_tablet = '';
		
		}		
		
	if(!empty($post_grid_meta_options['width']['mobile'])){
		
		$items_width_mobile = $post_grid_meta_options['width']['mobile'];
		}
	else{
		$items_width_mobile = '';
		
		}	
		
		
	if(!empty($post_grid_meta_options['height']['style'])){
		
		$items_height_style = $post_grid_meta_options['height']['style'];
		}
	else{
		$items_height_style = 'auto_height';
		
		}				
			
	if(!empty($post_grid_meta_options['height']['fixed_height'])){
		
		$items_fixed_height = $post_grid_meta_options['height']['fixed_height'];
		}
	else{
		$items_fixed_height = '';
		
		}
		
		
	if(!empty($post_grid_meta_options['media_source'])){
		
		$media_source = $post_grid_meta_options['media_source'];
		}
	else{
		$media_source = array();
		
		}
		
	if(!empty($post_grid_meta_options['featured_img_size'])){
		
		$featured_img_size = $post_grid_meta_options['featured_img_size'];
		}
	else{
		$featured_img_size = 'full';
		
		}		
		
			
			
	if(!empty($post_grid_meta_options['margin'])){
		
		$items_margin = $post_grid_meta_options['margin'];
		}
	else{
		$items_margin = '';
		
		}
		
	if(!empty($post_grid_meta_options['container']['padding'])){
		
		$container_padding = $post_grid_meta_options['container']['padding'];
		}
	else{
		$container_padding = '';
		
		}	
		
	if(!empty($post_grid_meta_options['container']['bg_color'])){
		
		$container_bg_color = $post_grid_meta_options['container']['bg_color'];
		}
	else{
		$container_bg_color = '';
		
		}		
		
		
	if(!empty($post_grid_meta_options['container']['bg_image'])){
		
		$container_bg_image = $post_grid_meta_options['container']['bg_image'];
		}
	else{
		$container_bg_image = '';
		
		}				
		
	
	
		
		
	if(!empty($post_grid_meta_options['nav_bottom']['pagination_type'])){
		
		$pagination_type = $post_grid_meta_options['nav_bottom']['pagination_type'];
		}
	else{
		$pagination_type = 'none';
		
		}		
		
	if(!empty($post_grid_meta_options['nav_bottom']['pagination_theme'])){
		
		$pagination_theme = $post_grid_meta_options['nav_bottom']['pagination_theme'];
		}
	else{
		$pagination_theme = 'lite';
		
		}




		
		if(empty($exclude_post_id))
			{
				$exclude_post_id = array();
			}
		else
			{
				$exclude_post_id = explode(',',$exclude_post_id);
			}
		

		
		if ( get_query_var('paged') ) {
		
			$paged = get_query_var('paged');
		
		} elseif ( get_query_var('page') ) {
		
			$paged = get_query_var('page');
		
		} else {
		
			$paged = 1;
		
		}
