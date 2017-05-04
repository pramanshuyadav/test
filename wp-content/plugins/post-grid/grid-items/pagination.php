<?php
/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access






		if($post_grid_pagination_display == 'pagination')
			{
				$html .= '<div class="paginate">';
				$big = 999999999; // need an unlikely integer
				$html .= paginate_links( array(
					'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format' => '?paged=%#%',
					'current' => max( 1, $paged ),
					'total' => $wp_query->max_num_pages
					) );
			
				$html .= '</div >';	
			}
			

			
		elseif($post_grid_pagination_display == 'load_more')
			{
				
			if($paged == 1)
				{
					$paged = 2;
				}
				
				
				
				$html .= '<div grid_id="'.$post_id.'" paged="'.$paged.'" per_page="'.$post_grid_post_per_page.'" class="load-more"><span>Load More</span>';
		
				$html .= '</div >';	
			}			
			
			
			
			