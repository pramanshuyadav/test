<?php
/*
* @Author: 		PickPlugins
* Copyright: 	2015 PickPlugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

		$html .= '<div class="post-grid-filter" >';
		
		$html.= '<div class="filter" data-filter="all">All</div>';
		foreach($post_grid_taxonomy_category as $id)
			{
				
				$term = get_term( $id, $post_grid_taxonomy );
				$term_slug = $term->slug;
				$term_name = $term->name;
				$html .= '<div class="filter" data-filter=".'.$term_slug.'" >'.$term_name.'</div>';
			}
		
		
		 
		$html .= '</div >';