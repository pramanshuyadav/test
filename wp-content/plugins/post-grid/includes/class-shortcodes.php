<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access

class class_post_grid_shortcodes{
	
	
    public function __construct(){
		
		add_shortcode( 'post_grid', array( $this, 'post_grid_display' ) );

    }
	
	
	
	
	public function post_grid_display($atts, $content = null ) {
			$atts = shortcode_atts(
				array(
					'id' => "",
	
					), $atts);
	
				$html  = '';
				$post_id = $atts['id'];

				include post_grid_plugin_dir.'/grid-items/variables.php';
				include post_grid_plugin_dir.'/grid-items/query.php';
				include post_grid_plugin_dir.'/grid-items/custom-css.php';				



				$html.='<div id="post-grid-'.$post_id.'" class="post-grid">';




				if ( $wp_query->have_posts() ) :

				$html.='<div class="grid-items">';
				while ( $wp_query->have_posts() ) : $wp_query->the_post();

				
				$html.='<div  class="item skin '.$skin.' '.post_grid_term_slug_list(get_the_ID()).'">';

				include post_grid_plugin_dir.'/grid-items/layer-media.php';
				include post_grid_plugin_dir.'/grid-items/layer-content.php';
				include post_grid_plugin_dir.'/grid-items/layer-hover.php';	
				
				$html.='</div>';  // .item		

				endwhile;
				wp_reset_query();
				$html.='</div>';  // .grid-items	
				
				$html.='<div class="grid-nav-bottom">';	
							include post_grid_plugin_dir.'/grid-items/nav-bottom.php';
				$html.='</div>';  // .grid-nav-bottom	
				
				
				else:
				$html.='<div class="item">';
				$html.=__('No Post found',post_grid_textdomain);  // .item	
				$html.='</div>';  // .item					
				
				endif;
				
				include post_grid_plugin_dir.'/grid-items/scripts.php';	
				$html.='</div>';  // .post-grid
	

	
				return $html;
	
	
	}


	
	
	
	}

new class_post_grid_shortcodes();