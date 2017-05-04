<?php
/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access

	$class_post_grid_functions = new class_post_grid_functions();
	
	$post_grid_layout_content = get_option( 'post_grid_layout_content' );
	
	if(empty($post_grid_layout_content)){
		$layout = $class_post_grid_functions->layout_content($layout_content);
		}
	else{
		$layout = $post_grid_layout_content[$layout_content];
		
		}
	
	//$layout = $class_post_grid_functions->layout_content($layout_content);
	
	

	$html.='<div class="layer-content">';	
	
	foreach($layout as $item_key=>$item_info){
		
		$item_key = $item_info['key'];
		
		if(!empty($item_info['char_limit'])){
			$char_limit = $item_info['char_limit'];	
			}
			
		
		
		if($item_key=='title'){
			$html.='<div class="element '.$item_key.'" style="'.$item_info['css'].'" >';

				$html.= wp_trim_words(get_the_title(), $char_limit,'');

			$html.='</div>';
			}
			
		elseif($item_key=='title_link'){

			

				$html.= '<a class="element '.$item_key.'" style="'.$item_info['css'].'" href="'.get_permalink().'">'.wp_trim_words(get_the_title(), $char_limit,'').'</a>';


			}	
			
		elseif($item_key=='thumb'){
			
			$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );
			$thumb_url = $thumb['0'];
	

			$html.='<div class="element '.$item_key.'" style="'.$item_info['css'].'" >';
			if(!empty($thumb_url)){
				$html.= '<img src="'.$thumb_url.'" />';
				}
			$html.='</div>';
			}			
			
		elseif($item_key=='thumb_link'){
			
			$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );
			$thumb_url = $thumb['0'];
	

			$html.='<div class="element '.$item_key.'" style="'.$item_info['css'].'" >';
			
			if(!empty($thumb_url)){
				$html.= '<a href="'.get_permalink().'"><img src="'.$thumb_url.'" /></a>';
				}
				
			$html.='</div>';
			}			
			
			
		elseif($item_key=='excerpt'){
			$html.='<div class="element '.$item_key.'" style="'.$item_info['css'].'" >';
				$html.= wp_trim_words(get_the_excerpt(), $char_limit,'');
			$html.='</div>';
			}

		elseif($item_key=='content'){
			$html.='<div class="element element_'.$item_id.' '.$item_key.'" style="'.$item_info['css'].'" >';
				$html.= apply_filters( 'the_content', get_the_content() );
			$html.='</div>';
			}	


		elseif($item_key=='read_more'){

				$html.= '<a class="element '.$item_key.'" style="'.$item_info['css'].'" href="'.get_permalink().'">'.__('Read more.', post_grid_textdomain).'</a>';

			}			
	
		elseif($item_key=='excerpt_read_more'){
			$html.='<div class="element '.$item_key.'" style="'.$item_info['css'].'" >';
				$html.= wp_trim_words(get_the_excerpt(), $char_limit,'').' <a class="read-more" href="'.get_permalink().'">'.__('Read more.', post_grid_textdomain).'</a>';
			$html.='</div>';
			}
			
		elseif($item_key=='post_date'){
			$html.='<div class="element '.$item_key.'" style="'.$item_info['css'].'" >';
				$html.= get_the_date();
			$html.='</div>';
			}			
			
		elseif($item_key=='author'){
			$html.='<div class="element '.$item_key.'" style="'.$item_info['css'].'" >';
				$html.= get_the_author();
			$html.='</div>';
			}	
			
		elseif($item_key=='categories'){
			
			$html.='<div class="element '.$item_key.'" style="'.$item_info['css'].'" >';
				$categories = get_the_category();
				$separator = ' ';
				$output = '';
				if ( ! empty( $categories ) ) {
					foreach( $categories as $category ) {
						$html .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all posts in %s', 'textdomain' ), $category->name ) ) . '">' . esc_html( $category->name ) . '</a>' . $separator;
					}
					$html.= trim( $output, $separator );
				}
			$html.='</div>';
		}					
			
		elseif($item_key=='tags'){
			$html.='<div class="element '.$item_key.'" style="'.$item_info['css'].'" >';
				$posttags = get_the_tags();
				if ($posttags) {
				  foreach($posttags as $tag){
					$html.= '<a href="'.get_tag_link($tag->term_id).'">'.$tag->name . '</a> , ';
					}
				}
			$html.='</div>';
		}
		
		elseif($item_key=='comments_count'){
			$html.='<div class="element '.$item_key.'" style="'.$item_info['css'].'" >';
			
				$comments_number = get_comments_number( get_the_ID() );
				
				if(comments_open()){
					
					if ( $comments_number == 0 ) {
							$html.= __('No Comments',post_grid_textdomain);
						} elseif ( $comments_number > 1 ) {
							$html.= $comments_number . __(' Comments',post_grid_textdomain);
						} else {
							$html.= __('1 Comment',post_grid_textdomain);
						}
		
					}
			$html.='</div>';
		}		
		
		elseif($item_key=='wc_gallery'){
			$html.='<div class="element '.$item_key.'" style="'.$item_info['css'].'" >';
			
				$is_product = get_post_type( get_the_ID() );
				$active_plugins = get_option('active_plugins');
				if(in_array( 'woocommerce/woocommerce.php', (array) $active_plugins ) && $is_product=='product'){
				global $woocommerce, $product;
				
				$gallery_attachment_ids = array_filter($product->get_gallery_attachment_ids());
				$gallery_html = '';
				if(!empty($gallery_attachment_ids)){
					
					foreach($gallery_attachment_ids as $id){
						
						$gallery_html.= '<a href="'.wp_get_attachment_url($id).'"><img src="'.wp_get_attachment_thumb_url($id).'" /></a>';
						}
					
					}
	
				
				
				$html.= $gallery_html;
				}
			$html.='</div>';
		}		
		
		elseif($item_key=='wc_full_price'){
			$html.='<div class="element '.$item_key.'" style="'.$item_info['css'].'" >';
			
				$is_product = get_post_type( get_the_ID() );
				$active_plugins = get_option('active_plugins');
				if(in_array( 'woocommerce/woocommerce.php', (array) $active_plugins ) && $is_product=='product'){
				global $woocommerce, $product;
				
				$full_price = $product->get_price_html();
				
				$html.=$full_price;
				}
			$html.='</div>';
		}		
		
		
		
		elseif($item_key=='wc_sale_price'){
			$html.='<div class="element '.$item_key.'" style="'.$item_info['css'].'" >';
			
				$is_product = get_post_type( get_the_ID() );
				$active_plugins = get_option('active_plugins');
				if(in_array( 'woocommerce/woocommerce.php', (array) $active_plugins ) && $is_product=='product'){
				global $woocommerce, $product;
				
				$currency_symbol = get_woocommerce_currency_symbol();
				$sale_price = $product->get_sale_price();
				
				if(!empty($sale_price)){
					$html.=$currency_symbol.$sale_price;
					}
				else{
					$html.= '';
					}
				
				}
		$html.='</div>';
		}		
		
		elseif($item_key=='wc_regular_price'){
			$html.='<div class="element '.$item_key.'" style="'.$item_info['css'].'" >';
			
				$is_product = get_post_type( get_the_ID() );
				$active_plugins = get_option('active_plugins');
				if(in_array( 'woocommerce/woocommerce.php', (array) $active_plugins ) && $is_product=='product'){
				global $woocommerce, $product;
				
				$currency_symbol = get_woocommerce_currency_symbol();
				
				$regular_price = $product->get_regular_price();
				
				if(!empty($regular_price)){
					$html.=$currency_symbol.$regular_price;
					}
				else{
					$html.= '';
					}
				}
			$html.='</div>';
		}		
		
		
		elseif($item_key=='wc_add_to_cart'){
			$html.='<div class="element '.$item_key.'" style="'.$item_info['css'].'" >';
			
				$is_product = get_post_type( get_the_ID() );
				$active_plugins = get_option('active_plugins');
				if(in_array( 'woocommerce/woocommerce.php', (array) $active_plugins ) && $is_product=='product'){
				global $woocommerce, $product;
				
					
					$add_to_cart = do_shortcode('[add_to_cart show_price="false" id="'.get_the_ID().'"]');
					$html.= $add_to_cart;
					
				}
			$html.='</div>';
		}			
		
		elseif($item_key=='wc_rating_star'){
			$html.='<div class="element '.$item_key.'" style="'.$item_info['css'].'" >';
			
				$is_product = get_post_type( get_the_ID() );
				$active_plugins = get_option('active_plugins');
				if(in_array( 'woocommerce/woocommerce.php', (array) $active_plugins ) && $is_product=='product'){
				global $woocommerce, $product;
				
				$rating = $product->get_average_rating();
				$rating = (($rating/5)*100);
				
				if( $rating > 0 ){
					
					$rating_html = '<div class="woocommerce woocommerce-product-rating"><div class="star-rating" style="color:#444; padding-bottom:10px;" title="'.__('Rated',post_grid_textdomain).' '.$rating.'"><span style="width:'.$rating.'%;"></span></div></div>';
					
					}
				else{
					$rating_html = '';
					
					}
	
				$html.= $rating_html;
					
				}
			$html.='</div>';
		}			
		
		elseif($item_key=='wc_rating_text'){
			$html.='<div class="element '.$item_key.'" style="'.$item_info['css'].'" >';
			
				$is_product = get_post_type( get_the_ID() );
				$active_plugins = get_option('active_plugins');
				if(in_array( 'woocommerce/woocommerce.php', (array) $active_plugins ) && $is_product=='product'){
				global $woocommerce, $product;
				
				$rating = $product->get_average_rating();
				//$rating = (($rating/5)*100);
				
				if( $rating > 0 ){
					
					$rating_html = $rating.' out of 5';
					
					}
				else{
					$rating_html = '';
					
					}
	
				$html.= $rating_html;
					
				}
			$html.='</div>';
		}
		
		elseif($item_key=='wc_categories'){
			$html.='<div class="element '.$item_key.'" style="'.$item_info['css'].'" >';
			
				$is_product = get_post_type( get_the_ID() );
				$active_plugins = get_option('active_plugins');
				if(in_array( 'woocommerce/woocommerce.php', (array) $active_plugins ) && $is_product=='product'){
				global $woocommerce, $product;
				
				$categories = $product->get_categories();
				
	
				$html.= $categories;
					
				}
			$html.='</div>';
		}		
		
		
		elseif($item_key=='wc_tags'){
			$html.='<div class="element '.$item_key.'" style="'.$item_info['css'].'" >';
			
				$is_product = get_post_type( get_the_ID() );
				$active_plugins = get_option('active_plugins');
				if(in_array( 'woocommerce/woocommerce.php', (array) $active_plugins ) && $is_product=='product'){
				global $woocommerce, $product;
				
				$tags = $product->get_tags();
				
	
				$html.= $tags;
					
				}
			$html.='</div>';
		}		
		
		elseif($item_key=='wc_sku'){
			$html.='<div class="element '.$item_key.'" style="'.$item_info['css'].'" >';
			
				$is_product = get_post_type( get_the_ID() );
				$active_plugins = get_option('active_plugins');
				if(in_array( 'woocommerce/woocommerce.php', (array) $active_plugins ) && $is_product=='product'){
				global $woocommerce, $product;
				
				$sku = $product->get_sku();
				
	
				$html.= $sku;
					
				}
			$html.='</div>';
		}
		
		elseif($item_key=='zoom'){
			$html.='<div class="element '.$item_key.'" style="'.$item_info['css'].'" >';
			$html.= '<i class="fa fa-search"></i>';
			$html.='</div>';

		}		
		
		elseif($item_key=='share_button'){
			$html.='<div class="element '.$item_key.'" style="'.$item_info['css'].'" >';
			$html.= '
			
			<span class="fb">
				<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u='.get_permalink().'"> </a>
			</span>
			<span class="twitter">
				<a target="_blank" href="https://twitter.com/intent/tweet?url='.get_permalink().'&text='.get_the_title().'"></a>
			</span>
			<span class="gplus">
				<a target="_blank" href="https://plus.google.com/share?url='.get_permalink().'"></a>
			</span>
			
			';
			$html.='</div>';

		}			
		
		elseif($item_key=='hr'){

			$html.= '<hr class="element '.$item_key.'" style="'.$item_info['css'].'" />';

		}		
		
		elseif($item_key=='meta_key'){
			
			$meta_value = get_post_meta(get_the_ID(), $item_info['field_id'],true);
			if(!empty($meta_value)){
				
				$html.='<div class="element '.$item_key.'" style="'.$item_info['css'].'" >';
				$html.= do_shortcode($meta_value);
				$html.='</div>';
				
				}


		}					
					
			

		}
	
	
	
	
	$html.='</div>'; // .layer-content