<?php
/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access
	
	$html .= '<div class="pagination '.$pagination_theme.'">';
	
		if($pagination_type=='pagination'){
			
			$html .= '<div class="paginate">';
			$big = 999999999; // need an unlikely integer
			$html .= paginate_links( array(
				'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format' => '?paged=%#%',
				'current' => max( 1, $paged ),
				'total' => $wp_query->max_num_pages,
				'prev_text'          => __('« Previous', post_grid_textdomain),
				'next_text'          => __('Next »', post_grid_textdomain),
				) );
		
			$html .= '</div >';	
			
			}
		elseif($pagination_type=='load_more'){
			
			if(!empty($paged))
				{
					$paged = $paged+1;
				}
			
			$html .= '<div grid_id="'.$post_id.'" class="load-more" paged="'.$paged.'" per_page="'.$posts_per_page.'" >'.__('Load more',post_grid_textdomain).'</div >';
	
			
			}
			

			
			
			
			
	$html .= '</div >';	