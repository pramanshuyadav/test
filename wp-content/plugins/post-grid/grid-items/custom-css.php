<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access
	
	
	
	if($items_height_style == 'auto_height'){
		$items_media_height = 'auto';
		}
	elseif($items_height_style == 'fixed_height'){
		$items_media_height = $items_fixed_height;
		}
	else{
		$items_media_height = '220px';
		}
	
	
	
	
		
	if(!empty($custom_css)){
		$html .= '<style type="text/css">'.$custom_css.'</style>';	
		}
		
		$html .= '<style type="text/css">';
		
		$html .= '#post-grid-'.$post_id.' {
			padding:'.$container_padding.';
			background: '.$container_bg_color.' url('.$container_bg_image.') repeat scroll 0 0;
		}';

	
	if($skin=='flip-y' || $skin=='flip-x'){
		
	$html .= '#post-grid-'.$post_id.' .item{
		height:'.$items_media_height.' !important;
		}';	
		
		}




	$html .= '#post-grid-'.$post_id.' .item{
		margin:'.$items_margin.';

		}';
	

	
	$html .= '#post-grid-'.$post_id.' .item .layer-media{
		height:'.$items_media_height.';
		overflow: hidden;
		}';	


	$html .= '
	@media only screen and (min-width: 1024px ) {
	#post-grid-'.$post_id.' .item{width:'.$items_width_desktop.'}
	
	}
	
	@media only screen and ( min-width: 768px ) and ( max-width: 1023px ) {
	#post-grid-'.$post_id.' .item{width:'.$items_width_tablet.'}
	}
	
	@media only screen and ( min-width: 320px ) and ( max-width: 767px ) {
	#post-grid-'.$post_id.' .item{width:'.$items_width_mobile.'}
	}
			
			
			
			</style>';	