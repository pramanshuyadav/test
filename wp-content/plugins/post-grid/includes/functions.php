<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access



function post_grid_get_media($media_source, $featured_img_size){
		
		
		$html_thumb = '';
		
		
		if($media_source == 'featured_image'){
				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), $featured_img_size );
				$thumb_url = $thumb['0'];
				
				if(!empty($thumb_url)){
					$html_thumb.= '<img src="'.$thumb_url.'" />';
					}
				else{
					
					$html_thumb.= '';
					}

			}
			
			
		elseif($media_source == 'empty_thumb'){


				$html_thumb.= '<img src="'.post_grid_plugin_url.'assets/frontend/css/images/placeholder.png" />';


			}			
			
			
			
			
		elseif($media_source == 'first_image'){

			global $post, $posts;
			$first_img = '';
			ob_start();
			ob_end_clean();
			$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
			
			if(!empty($matches[1][0]))
			$first_img = $matches[1][0];
			
			if(empty($first_img)) {
				$html_thumb.= '';
				}
			else{
				$html_thumb.= '<img src="'.$first_img.'" />';
				}

			
			}	
		
			

		return $html_thumb;
				
			
	
	
	}






function post_grid_reset_content_layouts(){
	

	$class_post_grid_functions = new class_post_grid_functions();
	$layout_content_list = $class_post_grid_functions->layout_content_list();
	update_option('post_grid_layout_content', $layout_content_list);
	
	
	}


add_action('wp_ajax_post_grid_reset_content_layouts', 'post_grid_reset_content_layouts');
add_action('wp_ajax_nopriv_post_grid_reset_content_layouts', 'post_grid_reset_content_layouts');


function post_grid_term_slug_list($post_id){
	$term_slug_list = '';
	
	$post_taxonomies = get_post_taxonomies($post_id);
	
	foreach($post_taxonomies as $taxonomy){
		
		$term_list[] = wp_get_post_terms(get_the_ID(), $taxonomy, array("fields" => "all"));
		
		}

	foreach($term_list as $term_key=>$term) 
		{
			foreach($term as $term_id=>$term){
				$term_slug_list .= $term->slug.' ';
				}
		}

	return $term_slug_list;

	}











function post_grid_posttypes($post_types){

	$html = '';
	$html .= '<select post_id="'.get_the_ID().'" class="post_types" multiple="multiple" size="6" name="post_grid_meta_options[post_types][]">';
	
		$post_types_all = get_post_types( '', 'names' ); 
		foreach ( $post_types_all as $post_type ) {

			global $wp_post_types;
			$obj = $wp_post_types[$post_type];
			
			if(in_array($post_type,$post_types)){
				$selected = 'selected';
				}
			else{
				$selected = '';
				}

			$html .= '<option '.$selected.' value="'.$post_type.'" >'.$obj->labels->singular_name.'</option>';
		}
		
	$html .= '</select>';
	return $html;
	}










function post_grid_layout_content_ajax(){
	
	$layout_key = $_POST['layout'];
	
	$class_post_grid_functions = new class_post_grid_functions();
	
	
	$post_grid_layout_content = get_option( 'post_grid_layout_content' );
	
	if(empty($post_grid_layout_content)){
		$layout = $class_post_grid_functions->layout_content($layout_key);
		}
	else{
		$layout = $post_grid_layout_content[$layout_key];
		
		}
	
	//$layout = $class_post_grid_functions->layout_content($layout_key);
	
	

	?>
    <div class="<?php echo $layout_key; ?>">
    <?php
    
		foreach($layout as $item_key=>$item_info){
			$item_key = $item_info['key'];
			?>
			

				<div class="item <?php echo $item_key; ?>" style=" <?php echo $item_info['css']; ?> ">
				
				<?php
				
				if($item_key=='thumb'){
					
					?>
					<img src="<?php echo post_grid_plugin_url; ?>assets/admin/images/thumb.png" />
					<?php
					}
					
				elseif($item_key=='title'){
					
					?>
					Lorem Ipsum is simply
					
					<?php
					}								
					
				elseif($item_key=='excerpt'){
					
					?>
					Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text
					<?php
					}	
					
				elseif($item_key=='excerpt_read_more'){
					
					?>
					Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text <a href="#">Read more</a>
					<?php
					}					
					
				elseif($item_key=='read_more'){
					
					?>
					<a href="#">Read more</a>
					<?php
					}												
					
				elseif($item_key=='post_date'){
					
					?>
					18/06/2015
					<?php
					}	
					
				elseif($item_key=='author'){
					
					?>
					PickPlugins
					<?php
					}					
					
				elseif($item_key=='categories'){
					
					?>
					<a hidden="#">Category 1</a> <a hidden="#">Category 2</a>
					<?php
					}
					
				elseif($item_key=='tags'){
					
					?>
					<a hidden="#">Tags 1</a> <a hidden="#">Tags 2</a>
					<?php
					}	
					
				elseif($item_key=='comments_count'){
					
					?>
					3 Comments
					<?php
					}
					
					// WooCommerce
				elseif($item_key=='wc_full_price'){
					
					?>
					<del>$45</del> - <ins>$40</ins>
					<?php
					}											
				elseif($item_key=='wc_sale_price'){
					
					?>
					$45
					<?php
					}					
									
				elseif($item_key=='wc_regular_price'){
					
					?>
					$45
					<?php
					}	
					
				elseif($item_key=='wc_add_to_cart'){
					
					?>
					Add to Cart
					<?php
					}	
					
				elseif($item_key=='wc_rating_star'){
					
					?>
					*****
					<?php
					}					
										
				elseif($item_key=='wc_rating_text'){
					
					?>
					2 Reviews
					<?php
					}	
				elseif($item_key=='wc_categories'){
					
					?>
					<a hidden="#">Category 1</a> <a hidden="#">Category 2</a>
					<?php
					}					
					
				elseif($item_key=='wc_tags'){
					
					?>
					<a hidden="#">Tags 1</a> <a hidden="#">Tags 2</a>
					<?php
					}																						
					
				else{
					
					echo $item_info['name'];
					
					}
				
				?>
				
				
				
				</div>
				<?php
			}
	
	?>
    </div>
    <?php
	
	die();
	
	}
	
add_action('wp_ajax_post_grid_layout_content_ajax', 'post_grid_layout_content_ajax');
add_action('wp_ajax_nopriv_post_grid_layout_content_ajax', 'post_grid_layout_content_ajax');









function post_grid_layout_hover_ajax(){
	
	$layout_key = $_POST['layout'];
	
	$class_post_grid_functions = new class_post_grid_functions();
	$layout = $class_post_grid_functions->layout_hover($layout_key);
	
	

	?>
    <div class="<?php echo $layout_key; ?>">
    <?php
    
		foreach($layout as $item_key=>$item_info){
			
			?>
			

				<div class="item <?php echo $item_key; ?>" style=" <?php echo $item_info['css']; ?> ">
				
				<?php
				
				if($item_key=='thumb'){
					
					?>
					<img src="<?php echo post_grid_plugin_url; ?>assets/admin/images/thumb.png" />
					<?php
					}
					
				elseif($item_key=='title'){
					
					?>
					Lorem Ipsum is simply
					
					<?php
					}								
					
				elseif($item_key=='excerpt'){
					
					?>
					Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text
					<?php
					}	
					
				elseif($item_key=='excerpt_read_more'){
					
					?>
					Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text <a href="#">Read more</a>
					<?php
					}					
					
				elseif($item_key=='read_more'){
					
					?>
					<a href="#">Read more</a>
					<?php
					}												
					
				elseif($item_key=='post_date'){
					
					?>
					18/06/2015
					<?php
					}	
					
				elseif($item_key=='author'){
					
					?>
					PickPlugins
					<?php
					}					
					
				elseif($item_key=='categories'){
					
					?>
					<a hidden="#">Category 1</a> <a hidden="#">Category 2</a>
					<?php
					}
					
				elseif($item_key=='tags'){
					
					?>
					<a hidden="#">Tags 1</a> <a hidden="#">Tags 2</a>
					<?php
					}	
					
				elseif($item_key=='comments_count'){
					
					?>
					3 Comments
					<?php
					}
					
					// WooCommerce
				elseif($item_key=='wc_full_price'){
					
					?>
					<del>$45</del> - <ins>$40</ins>
					<?php
					}											
				elseif($item_key=='wc_sale_price'){
					
					?>
					$45
					<?php
					}					
									
				elseif($item_key=='wc_regular_price'){
					
					?>
					$45
					<?php
					}	
					
				elseif($item_key=='wc_add_to_cart'){
					
					?>
					Add to Cart
					<?php
					}	
					
				elseif($item_key=='wc_rating_star'){
					
					?>
					*****
					<?php
					}					
										
				elseif($item_key=='wc_rating_text'){
					
					?>
					2 Reviews
					<?php
					}	
				elseif($item_key=='wc_categories'){
					
					?>
					<a hidden="#">Category 1</a> <a hidden="#">Category 2</a>
					<?php
					}					
					
				elseif($item_key=='wc_tags'){
					
					?>
					<a hidden="#">Tags 1</a> <a hidden="#">Tags 2</a>
					<?php
					}																						
					
				else{
					
					echo $item_info['name'];
					
					}
				
				?>
				
				
				
				</div>
				<?php
			}
	
	?>
    </div>
    <?php
	
	die();
	
	}
	
add_action('wp_ajax_post_grid_layout_hover_ajax', 'post_grid_layout_hover_ajax');
add_action('wp_ajax_nopriv_post_grid_layout_hover_ajax', 'post_grid_layout_hover_ajax');








function post_grid_layout_add_elements(){
	
	$item_key = $_POST['item_key'];
	$layout = $_POST['layout'];	
	$unique_id = $_POST['unique_id'];	

	$class_post_grid_functions = new class_post_grid_functions();
	$layout_items = $class_post_grid_functions->layout_items();



	$html = array();
	$html['item'] = '';
	$html['item'].= '<div class="item '.$item_key.'" >';	

    
    if($item_key=='thumb'){
		
        $html['item'].= '<img style="width:100%;" src="'.post_grid_plugin_url.'assets/admin/images/thumb.png" />';

        }
        
    elseif($item_key=='thumb_link'){
        
		$html['item'].= '<a href="#"><img style="width:100%;" src="'.post_grid_plugin_url.'assets/admin/images/thumb.png" /></a>';

        }	
		
		
    elseif($item_key=='title'){
        
		$html['item'].= 'Lorem Ipsum is simply';

        }		
		
    elseif($item_key=='title_link'){
        
		$html['item'].= '<a href="#">Lorem Ipsum is simply</a>';

        }
								
        
    elseif($item_key=='excerpt'){
        $html['item'].= 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text';
		

        }	
        
    elseif($item_key=='excerpt_read_more'){
        $html['item'].= 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text <a href="#">Read more</a>';

        }					
        
    elseif($item_key=='read_more'){
        $html['item'].= '<a href="#">Read more</a>';

        }												
        
    elseif($item_key=='post_date'){
        $html['item'].= '18/06/2015';

        }	
        
    elseif($item_key=='author'){
        $html['item'].= 'PickPlugins';

        }					
        
    elseif($item_key=='categories'){
        $html['item'].= '<a hidden="#">Category 1</a> <a hidden="#">Category 2</a>';

        }
        
    elseif($item_key=='tags'){
        $html['item'].= '<a hidden="#">Tags 1</a> <a hidden="#">Tags 2</a>';

        }	
        
    elseif($item_key=='comments_count'){
         $html['item'].= '3 Comments';

        }
        
        // WooCommerce
    elseif($item_key=='wc_full_price'){
        $html['item'].= '<del>$45</del> - <ins>$40</ins>';

        }											
    elseif($item_key=='wc_sale_price'){
        $html['item'].= '$45';

        }					
                        
    elseif($item_key=='wc_regular_price'){
         $html['item'].= '$45';

        }	
        
    elseif($item_key=='wc_add_to_cart'){
        $html['item'].= 'Add to Cart';

        }	
        
    elseif($item_key=='wc_rating_star'){
        $html['item'].= '*****';

        }					
                            
    elseif($item_key=='wc_rating_text'){
        $html['item'].= '2 Reviews';

        }	
    elseif($item_key=='wc_categories'){
        $html['item'].= '<a href="#">Category 1</a> <a href="#">Category 2</a>';

        }					
        
    elseif($item_key=='wc_tags'){
         $html['item'].= '<a href="#" >Tags 1</a> <a href="#">Tags 2</a>';

        }	
		
    elseif($item_key=='meta_key'){
         $html['item'].= 'Meta Key';

        }			
																							
        
    else{
        
        echo '';
        
        }
     $html['item'].= '</div>';

	$html['options'] = '';
	$html['options'].= '<div class="items">';
	$html['options'].= '<div class="header"><span class="remove">X</span>'.$layout_items[$item_key].'</div>';
	$html['options'].= '<div class="options">';
	
	if($item_key=='meta_key'){
		
		$html['options'].= 'Meta Key: <br /><input type="text" value="" name="post_grid_layout_content['.$layout.']['.$unique_id.'][field_id]" /><br /><br />';
		}
		
	if($item_key=='title'  || $item_key=='title_link'  || $item_key=='excerpt' || $item_key=='excerpt_read_more'){
		
		$html['options'].= 'Character limit: <br /><input type="text" value="20" name="post_grid_layout_content['.$layout.']['.$unique_id.'][char_limit]" /><br /><br />';
		}		
		
		

	$html['options'].= '
	<input type="hidden" value="'.$item_key.'" name="post_grid_layout_content['.$layout.']['.$unique_id.'][key]" />
	<input type="hidden" value="'.$layout_items[$item_key].'" name="post_grid_layout_content['.$layout.']['.$unique_id.'][name]" />
	CSS: <br />
	<textarea class="custom_css" name="post_grid_layout_content['.$layout.']['.$unique_id.'][css]" item_id="'.$item_key.'" style="width:50%" spellcheck="false" autocapitalize="off" autocorrect="off">font-size:12px;display:block;padding:10px 0;</textarea>';
	
	
	
	$html['options'].= '</div>';
	$html['options'].= '</div>';	



	echo json_encode($html);


	
	die();
	
	}
	
add_action('wp_ajax_post_grid_layout_add_elements', 'post_grid_layout_add_elements');
add_action('wp_ajax_nopriv_post_grid_layout_add_elements', 'post_grid_layout_add_elements');











	
	function post_grid_share_plugin(){
			
			?>
<iframe src="//www.facebook.com/plugins/like.php?href=https://wordpress.org/plugins/post-grid/%2F&amp;width&amp;layout=standard&amp;action=like&amp;show_faces=true&amp;share=true&amp;height=80&amp;appId=652982311485932" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:80px;" allowTransparency="true"></iframe>
            
            <br />
            <!-- Place this tag in your head or just before your close body tag. -->
            <script src="https://apis.google.com/js/platform.js" async defer></script>
            
            <!-- Place this tag where you want the +1 button to render. -->
            <div class="g-plusone" data-size="medium" data-annotation="inline" data-width="300" data-href="<?php echo post_grid_share_url; ?>"></div>
            
            <br />
            <br />
            <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo post_grid_share_url; ?>" data-text="<?php echo post_grid_plugin_name; ?>" data-via="ParaTheme" data-hashtags="WordPress">Tweet</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>



            <?php

		
		}
	
	
	

		
	
	function post_grid_upgrade_notice()
		{
			$post_grid_upgrade = get_option('post_grid_upgrade');
			
			
			$html= '';

			if($post_grid_upgrade == 'done')
				{
					
				}
			else{
				$html.= '<div class="update-nag">';
				$html.='Data update required for Post grid latest version. <span id="post-grid-upgrade">Click to update</span>';
				$html.= '</div>';
				}


			echo $html;
		}
	
	add_action('admin_notices', 'post_grid_upgrade_notice');
				

	function post_grid_upgrade_action()
		{
			$post_grid_upgrade = get_option('post_grid_upgrade');
			if($post_grid_upgrade=='done'){
				
				}
			else{
				$wp_query = new WP_Query(
										array (
											'post_type' => 'post_grid',
											'post_status' => 'publish',

											) );
			
						
			if ( $wp_query->have_posts() ) :
				while ( $wp_query->have_posts() ) : $wp_query->the_post();

					$post_grid_themes = get_post_meta( get_the_ID(), 'post_grid_themes', true );
					
					if($post_grid_themes=='mixit' || $post_grid_themes=='isotope' || $post_grid_themes=='isotope-lite'){
						$filter = 'yes';
						}
					else{
						$filter = 'no';
						}
					
					
					
					$post_grid_masonry_enable = get_post_meta( get_the_ID(), 'post_grid_masonry_enable', true );		
					
					$post_grid_bg_img = get_post_meta( get_the_ID(), 'post_grid_bg_img', true );		
					$post_grid_thumb_size = get_post_meta( get_the_ID(), 'post_grid_thumb_size', true );
					$post_grid_empty_thumb = get_post_meta( get_the_ID(), 'post_grid_empty_thumb', true );		
						
					$post_grid_post_per_page = get_post_meta( get_the_ID(), 'post_grid_post_per_page', true );		
					$post_grid_pagination_display = get_post_meta( get_the_ID(), 'post_grid_pagination_display', true );		
					
					if($post_grid_pagination_display=='no'){
						
						$post_grid_pagination_display='none';
						}
					
					
					$post_grid_excerpt_count = get_post_meta( get_the_ID(), 'post_grid_excerpt_count', true );		
					$post_grid_read_more_text = get_post_meta( get_the_ID(), 'post_grid_read_more_text', true );					
					$post_grid_exclude_post_id = get_post_meta( get_the_ID(), 'post_grid_exclude_post_id', true );		
					
					$post_grid_bg_img = get_post_meta( get_the_ID(), 'post_grid_bg_img', true );
					
					$post_grid_items_width = get_post_meta( get_the_ID(), 'post_grid_items_width', true );
					$post_grid_items_width_mobile = get_post_meta( get_the_ID(), 'post_grid_items_width_mobile', true );				
					$post_grid_thumb_height = get_post_meta( get_the_ID(), 'post_grid_thumb_height', true );	
				
					$post_grid_query_order = get_post_meta( get_the_ID(), 'post_grid_query_order', true );
					$post_grid_query_orderby = get_post_meta( get_the_ID(), 'post_grid_query_orderby', true );
							
					$post_grid_posttype = get_post_meta( get_the_ID(), 'post_grid_posttype', true );
					$post_grid_taxonomy = get_post_meta( get_the_ID(), 'post_grid_taxonomy', true );
					$post_grid_taxonomy_category = get_post_meta( get_the_ID(), 'post_grid_taxonomy_category', true );				
					
					if(!empty($post_grid_taxonomy_category)){
						
						$i= 0;
						foreach($post_grid_taxonomy_category as $term){
							
							$terms[$i] = $post_grid_taxonomy.','.$term;
							$i++;
							}
						$post_grid_taxonomy_category = $terms;
						}
					else{
						$post_grid_taxonomy_category = array();
						}			

					
					$post_grid_meta_author_display = get_post_meta( get_the_ID(), 'post_grid_meta_author_display', true );
					$post_grid_meta_avatar_display = get_post_meta( get_the_ID(), 'post_grid_meta_avatar_display', true );			
					$post_grid_meta_date_display = get_post_meta( get_the_ID(), 'post_grid_meta_date_display', true );				
					$post_grid_meta_categories_display = get_post_meta( get_the_ID(), 'post_grid_meta_categories_display', true );
					$post_grid_meta_tags_display = get_post_meta( get_the_ID(), 'post_grid_meta_tags_display', true );		
					$post_grid_meta_comments_display = get_post_meta( get_the_ID(), 'post_grid_meta_comments_display', true );		
					
					
					
					$post_grid_items = get_post_meta( get_the_ID(), 'post_grid_items', true );		
					$post_grid_wrapper = get_post_meta( get_the_ID(), 'post_grid_wrapper', true );
					$post_grid_before_after = get_post_meta( get_the_ID(), 'post_grid_before_after', true );			
					$post_grid_items_display = get_post_meta( get_the_ID(), 'post_grid_items_display', true );		
					
					$post_grid_post_title_linked = get_post_meta( get_the_ID(), 'post_grid_post_title_linked', true );		
					$post_grid_post_thumbnail_linked = get_post_meta( get_the_ID(), 'post_grid_post_thumbnail_linked', true );
					$post_grid_post_thumbnail_external = get_post_meta( get_the_ID(), 'post_grid_post_thumbnail_external', true );					
					
					$post_grid_hover_items_zoom_display = get_post_meta( get_the_ID(), 'post_grid_hover_items_zoom_display', true );			
					$post_grid_hover_items_link_display = get_post_meta( get_the_ID(), 'post_grid_hover_items_link_display', true );		
					$post_grid_hover_items_share_display = get_post_meta( get_the_ID(), 'post_grid_hover_items_share_display', true );		
					
					$post_grid_title_color = get_post_meta( get_the_ID(), 'post_grid_title_color', true );
					$post_grid_title_font_size = get_post_meta( get_the_ID(), 'post_grid_title_font_size', true );
				
					$post_grid_content_color = get_post_meta( get_the_ID(), 'post_grid_content_color', true );
					$post_grid_content_font_size = get_post_meta( get_the_ID(), 'post_grid_content_font_size', true );
					
					$post_grid_hover_items_hover_effect_in = get_post_meta( get_the_ID(), 'post_grid_hover_items_hover_effect_in', true );
					
					$post_grid_mixitup_post_per_page = get_post_meta( get_the_ID(), 'post_grid_mixitup_post_per_page', true );
					$post_grid_mixitup_default_filter = get_post_meta( get_the_ID(), 'post_grid_mixitup_default_filter', true );		
						
					$terms_info = get_term_by('slug', $post_grid_mixitup_default_filter, $post_grid_taxonomy);
						
					$post_grid_mixitup_default_filter = $terms_info->term_id;
						
					$post_grid_custom_css = get_post_meta( get_the_ID(), 'post_grid_custom_css', true ); /*ok*/	
				
				
				
				
				

				
					$post_grid_meta_options = array(
					
													'post_types'=>$post_grid_posttype,
													'categories'=>$post_grid_taxonomy_category,													
													'categories_relation'=>'OR',
													'post_status'=>array('publish'),													
													'offset'=>0,													
													'posts_per_page'=>$post_grid_post_per_page,													
													'exclude_post_id'=>$post_grid_exclude_post_id,
													'query_order'=>$post_grid_query_order,													
													'query_orderby'=>array($post_grid_query_orderby),														
													'query_orderby_meta_key'=>'',
													'meta_query'=>array(),
													'meta_query_relation'=>'OR',
													'keyword'=>'',
													'layout'=>array(
																	'content'=>'flat',
																	'hover'=>'flat',
																	),
													
													'skin'=>'flat',	
													'custom_js'=>'',
													'custom_css'=>$post_grid_custom_css,
													'width'=>array(
																	'desktop'=>$post_grid_items_width,
																	'tablet'=>$post_grid_items_width,
																	'mobile'=>$post_grid_items_width_mobile,																	
																	),
																	
													'height'=>array(
																	'style'=>'auto_height',
																	'fixed_height'=>$post_grid_thumb_height,															
																	),																	
													'media_source'=>array(
																		'featured_image' => array ('id' => 'featured_image','title' => 'Featured Image','checked' => 'yes',),
																		'first_image' => array ('id' => 'first_image','title' => 'First images from content','checked' => 'yes',),
																		'first_gallery' => array ('id' => 'first_gallery','title' => 'First gallery from content','checked' => 'yes',),
																		'first_youtube' => array ('id' => 'first_youtube','title' => 'First youtube video from content','checked' => 'yes',),
																		'first_vimeo' => array ('id' => 'first_vimeo','title' => 'First vimeo video from content','checked' => 'yes',),
																		'first_mp3' => array ('id' => 'first_mp3','title' => 'First MP3 from content','checked' => 'yes',),
																		'first_soundcloud' => array ('id' => 'first_soundcloud','title' => 'First SoundCloud from content','checked' => 'yes',),
																		'empty_thumb' => array ('id' => 'empty_thumb','title' => 'Empty thumbnail','checked' => 'yes',),
										  ),																	
													'featured_img_size'=>$post_grid_thumb_size,																		
													'margin'=>'5px',																													
													
													'container'=>array(
																	'padding'=>'10px',
																	'bg_color'=>'',
																	'bg_image'=>$post_grid_bg_img,																	
																	),
																	
																	
													'nav_top'=>array(
																	'filter'=>$filter,
																	'active_filter'=>$post_grid_mixitup_default_filter,																	
																	'search'=>'none',																
																	),
																																		
													'nav_bottom'=>array(
																	'pagination_type'=>$post_grid_pagination_display,
																	'pagination_theme'=>'lite',																
																	),
																	

														);
				

				
				
				
				
				
				
					update_post_meta(get_the_ID(), 'post_grid_meta_options', $post_grid_meta_options );
				
				endwhile;
				wp_reset_query();
			endif;
	
				}
	
			update_option('post_grid_upgrade','done');
			die();
			
		}	
		
		
		
add_action('wp_ajax_post_grid_upgrade_action', 'post_grid_upgrade_action');
add_action('wp_ajax_nopriv_post_grid_upgrade_action', 'post_grid_upgrade_action');
		
		