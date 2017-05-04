<?php
/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access


function post_grid_posttype_register() {
 
        $labels = array(
                'name' => _x('Post Grid', 'post_grid'),
                'singular_name' => _x('Post Grid', 'post_grid'),
                'add_new' => _x('New Post Grid', 'post_grid'),
                'add_new_item' => __('New Post Grid'),
                'edit_item' => __('Edit Post Grid'),
                'new_item' => __('New Post Grid'),
                'view_item' => __('View Post Grid'),
                'search_items' => __('Search Post Grid'),
                'not_found' =>  __('Nothing found'),
                'not_found_in_trash' => __('Nothing found in Trash'),
                'parent_item_colon' => ''
        );
 
        $args = array(
                'labels' => $labels,
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
                'query_var' => true,
                'menu_icon' => null,
                'rewrite' => true,
                'capability_type' => 'post',
                'hierarchical' => false,
                'menu_position' => null,
                'supports' => array('title'),
				'menu_icon' => 'dashicons-media-spreadsheet',
				
          );
 
        register_post_type( 'post_grid' , $args );

}

add_action('init', 'post_grid_posttype_register');





/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function meta_boxes_post_grid()
	{
		$screens = array( 'post_grid' );
		foreach ( $screens as $screen )
			{
				add_meta_box('post_grid_metabox',__( 'Post Grid Options','post_grid' ),'meta_boxes_post_grid_input', $screen);
			}
	}
add_action( 'add_meta_boxes', 'meta_boxes_post_grid' );


function meta_boxes_post_grid_input( $post ) {
	
	global $post;
	wp_nonce_field( 'meta_boxes_post_grid_input', 'meta_boxes_post_grid_input_nonce' );
	
	
	$post_grid_meta_options = get_post_meta( $post->ID, 'post_grid_meta_options', true );
	

	if(!empty($post_grid_meta_options['post_types'])){
		$post_types = $post_grid_meta_options['post_types'];
		}
	else{
		$post_types = array('post');
		}	
	

	
	if(!empty($post_grid_meta_options['post_status'])){
		$post_status = $post_grid_meta_options['post_status'];
		}
	else{
		$post_status = array('publish');
		}	
	

	
	if(!empty($post_grid_meta_options['posts_per_page'])){
		$posts_per_page = $post_grid_meta_options['posts_per_page'];
		
		}
	else{
		$posts_per_page = 10;
		}
	
	
	if(!empty($post_grid_meta_options['exclude_post_id']))	
	$exclude_post_id = $post_grid_meta_options['exclude_post_id'];
	
	
	if(!empty($post_grid_meta_options['query_order'])){
		$query_order = $post_grid_meta_options['query_order'];
		}
	else{
		$query_order = 'DESC';
		}	
	
	if(!empty($post_grid_meta_options['query_orderby'])){
		$query_orderby = $post_grid_meta_options['query_orderby'];
		}
	else{
		$query_orderby = array('date');
		}

	

	
	if(!empty($post_grid_meta_options['keyword']))
	$keyword = $post_grid_meta_options['keyword'];		
	
	
	if(!empty($post_grid_meta_options['layout']['content'])){
		
		$layout_content = $post_grid_meta_options['layout']['content'];	
		}
	else{
		$layout_content = 'flat';	
		}
	
	
	if(!empty($post_grid_meta_options['layout']['hover']))
	$layout_hover = $post_grid_meta_options['layout']['hover'];		
	
	
	if(!empty($post_grid_meta_options['skin'])){
		$skin = $post_grid_meta_options['skin'];
		}
	else{
		$skin = 'flat';
		}
		
	
	if(!empty($post_grid_meta_options['custom_js'])){
		$custom_js = $post_grid_meta_options['custom_js'];
		}
	else{
		$custom_js = '/*Write your js code here*/';
		}
		
	
	if(!empty($post_grid_meta_options['custom_css'])){
		$custom_css = $post_grid_meta_options['custom_css'];
		}
	else{
		$custom_css = '/*Write your CSS code here*/';
		}
	
	if(!empty($post_grid_meta_options['width']['desktop'])){
		
		$items_width_desktop = $post_grid_meta_options['width']['desktop'];
		}
	else{
		$items_width_desktop = '280px';
		
		}
		
		
	if(!empty($post_grid_meta_options['width']['tablet'])){
		
		$items_width_tablet = $post_grid_meta_options['width']['tablet'];
		}
	else{
		$items_width_tablet = '280px';
		
		}		
		
	if(!empty($post_grid_meta_options['width']['mobile'])){
		
		$items_width_mobile = $post_grid_meta_options['width']['mobile'];
		}
	else{
		$items_width_mobile = '90%';
		
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
		$items_fixed_height = '180px';
		
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
		$featured_img_size = 'medium';
		
		}				
			
			
			
			
			
			
	if(!empty($post_grid_meta_options['margin'])){
		
		$items_margin = $post_grid_meta_options['margin'];
		}
	else{
		$items_margin = '10px';
		
		}
		
	if(!empty($post_grid_meta_options['container']['padding'])){
		
		$container_padding = $post_grid_meta_options['container']['padding'];
		}
	else{
		$container_padding = '10px';
		
		}	
		
	if(!empty($post_grid_meta_options['container']['bg_color'])){
		
		$container_bg_color = $post_grid_meta_options['container']['bg_color'];
		}
	else{
		$container_bg_color = '#fff';
		
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
		
		
		
		
		
		
		
		
		
?>

    <div class="para-settings post-grid-metabox">



        <ul class="tab-nav"> 
            <li nav="1" class="nav1 active"><i class="fa fa-code"></i> <?php _e('Shortcodes','post_grid'); ?></li>
            <li nav="2" class="nav2"><i class="fa fa-cubes"></i> <?php _e('Query Post','post_grid'); ?></li>
            <li nav="3" class="nav3"><i class="fa fa-object-group"></i> <?php _e('Layout','post_grid'); ?></li>
            <li nav="4" class="nav3"><i class="fa fa-magic"></i> <?php _e('Layout settings','post_grid'); ?></li>            
            <li nav="5" class="nav4"><i class="fa fa-sliders"></i> <?php _e('Naviagtions','post_grid'); ?></li>            
            <li nav="6" class="nav6"><i class="fa fa-css3"></i> <?php _e('Custom Scripts','post_grid'); ?></li>           
            
                     
                       
        </ul> <!-- tab-nav end -->
        
		<ul class="box">
            <li style="display: block;" class="box1 tab-box active">
                <div class="option-box">
                    <p class="option-title"><?php _e('Shortcode','post_grid'); ?></p>
                    <p class="option-info"><?php _e('Copy this shortcode and paste on page or post where you want to display post grid. <br />Use PHP code to your themes file to display post grid.','post_grid'); ?></p>
                    <textarea cols="50" rows="1" style="background:#bfefff" onClick="this.select();" >[post_grid <?php echo 'id="'.$post->ID.'"';?>]</textarea>
                <br /><br />
                PHP Code:<br />
                <textarea cols="50" rows="1" style="background:#bfefff" onClick="this.select();" ><?php echo '<?php echo do_shortcode("[post_grid id='; echo "'".$post->ID."']"; echo '"); ?>'; ?></textarea>  
                </div>
               
            </li>
            <li style="display: none;" class="box2 tab-box ">
                <div class="option-box">
                    <p class="option-title"><?php _e('Post Types','post_grid'); ?></p>
                    <p class="option-info"><?php _e('Select post types you want to query post , can be select multiple. <br />Hint: Ctrl + click to select mulitple','post_grid'); ?></p>
                    <?php
					echo post_grid_posttypes($post_types);
					?>

                </div>
   
                <div class="option-box">
                    <p class="option-title"><?php _e('Post Status','post_grid'); ?></p>
                    <p class="option-info"><?php _e('Display post from following post status, <br />Hint: Ctrl + click to select mulitple','post_grid'); ?></p>
                    
                    <select class="post_status" name="post_grid_meta_options[post_status][]" multiple >
                        <option value="publish" <?php if(in_array("publish",$post_status)) echo "selected"; ?>>Publish</option>
                        <option value="pending" <?php if(in_array("pending",$post_status)) echo "selected"; ?>>Pending</option>
                        <option value="draft" <?php if(in_array("draft",$post_status)) echo "selected"; ?>>Draft</option>
                        <option value="auto-draft" <?php if(in_array("auto-draft",$post_status)) echo "selected"; ?>>Auto draft</option>
                        <option value="future" <?php if(in_array("future",$post_status)) echo "selected"; ?>>Future</option>
                        <option value="private" <?php if(in_array("private",$post_status)) echo "selected"; ?>>Private</option>                    
                        <option value="inherit" <?php if(in_array("inherit",$post_status)) echo "selected"; ?>>Inherit</option>                    
                        <option value="trash" <?php if(in_array("trash",$post_status)) echo "selected"; ?>>Trash</option>
                        <option value="any" <?php if(in_array("any",$post_status)) echo "selected"; ?>>Any</option>                                          
                    </select> 
                    
                </div>                         
                        
                <div class="option-box">
                    <p class="option-title"><?php _e('Posts per page','post_grid'); ?></p>
                    <p class="option-info"><?php _e('Number of post each pagination. -1 to display all. default is 10 if you left empty.','post_grid'); ?></p>
                    <input type="text" placeholder="3" name="post_grid_meta_options[posts_per_page]" value="<?php if(!empty($posts_per_page)) echo $posts_per_page; else echo ''; ?>" />
                </div>                        

                <div class="option-box">
                    <p class="option-title"><?php _e('Exclude by post ID','post_grid'); ?></p>
                    <p class="option-info"><?php _e('you can exclude post by ID, comma(,) separated','post_grid'); ?></p>
                    
                    <input type="text" placeholder="5,3" name="post_grid_meta_options[exclude_post_id]" value="<?php if(!empty($exclude_post_id)) echo $exclude_post_id; else echo ''; ?>" />  
                </div>
                              
                
                <div class="option-box">
                    <p class="option-title"><?php _e('Post query order','post_grid'); ?></p>
                    <p class="option-info"><?php _e('Query order ascending or descending','post_grid'); ?></p>
                    
                    <select class="query_order" name="post_grid_meta_options[query_order]" >
                    <option value="ASC" <?php if($query_order=="ASC") echo "selected"; ?>>Ascending</option>
                    <option value="DESC" <?php if($query_order=="DESC") echo "selected"; ?>>Descending</option>
                    </select>
                    
                </div>
                
                
                <div class="option-box">
                    <p class="option-title"><?php _e('Post query orderby','post_grid'); ?></p>
                    <p class="option-info"><?php _e('Query orderby parameter, can select multiple','post_grid'); ?></p>
                    
                        <select class="query_orderby" name="post_grid_meta_options[query_orderby][]"  multiple>
                        <option value="ID" <?php if(in_array("ID",$query_orderby)) echo "selected"; ?>>ID</option>
                        <option value="date" <?php if(in_array("date",$query_orderby)) echo "selected"; ?>>Date</option>
                        <option value="rand" <?php if(in_array("rand",$query_orderby)) echo "selected"; ?>>Rand</option>                    
                        <option value="comment_count" <?php if(in_array("comment_count",$query_orderby)) echo "selected"; ?>>Comment Count</option>
                        <option value="author" <?php if(in_array("author",$query_orderby)) echo "selected"; ?>>Author</option>               
                        <option value="title" <?php if(in_array("title",$query_orderby)) echo "selected"; ?>>Title</option>
                        <option value="name" <?php if(in_array("name",$query_orderby)) echo "selected"; ?>>Name</option>                    
                        <option value="type" <?php if(in_array("type",$query_orderby)) echo "selected"; ?>>Type</option>
                        </select>
                        <br />

                    
                </div>                 

                <div class="option-box">
                    <p class="option-title"><?php _e('Search keyword','post_grid'); ?></p>
                    <p class="option-info"><?php _e('Query post by search keyword, please follow the reference https://codex.wordpress.org/Class_Reference/WP_Query#Search_Parameter','post_grid'); ?></p>
                    
                    <input type="text" placeholder="Keyword" name="post_grid_meta_options[keyword]" value="<?php if(!empty($keyword)) echo $keyword; else echo ''; ?>" />
                    
                </div>                
                
            </li>
            <li style="display: none;" class="box3 tab-box ">
            
            
            
                <div class="option-box">
                    <p class="option-title"><?php _e('Layout','post_grid'); ?></p>
                    <p class="option-info"><?php _e('Choose your layout','post_grid'); ?></p>
                    
                    <?php
                    $class_post_grid_functions = new class_post_grid_functions();
					?>

                    <div class="layout-list">
                    <div class="idle  ">
                    <div class="name">Content
                    
                    <select class="select-layout-content" name="post_grid_meta_options[layout][content]" >
                    <?php
					
					$layout_content_list = $class_post_grid_functions->layout_content_list();
                    foreach($layout_content_list as $layout_key=>$layout_info){
						?>
                        <option <?php if($layout_content==$layout_key) echo 'selected'; ?>  value="<?php echo $layout_key; ?>"><?php echo $layout_key; ?></option>
                        <?php
						
						}
					?>
                    </select>
                    <a target="_blank" class="edit-layout" href="<?php echo admin_url().'edit.php?post_type=post_grid&page=post_grid_layout_editor&layout_content='.$layout_content;?>" >Edit</a>
                    </div>     
                    
                    <script>
						jQuery(document).ready(function($)
							{
								$(document).on('change', '.select-layout-content', function()
									{
						
										
										var layout = $(this).val();		
										
										$('.edit-layout').attr('href', '<?php echo admin_url().'edit.php?post_type=post_grid&page=post_grid_layout_editor&layout_content='; ?>'+layout);
										})
								
							})
					</script>
                    
                    
                    
                    
                    
                    
                    
                    <?php
					
					if(empty($layout_content)){
						$layout_content = 'flat-left';
						}
					
                    
					?>
                    
                                   
                    <div class="layer-content">
                    <div class="<?php echo $layout_content; ?>">
                    <?php
					$post_grid_layout_content = get_option( 'post_grid_layout_content' );
					
					if(empty($post_grid_layout_content)){
						$layout = $class_post_grid_functions->layout_content($layout_content);
						}
					else{
						$layout = $post_grid_layout_content[$layout_content];
						
						}
					
                  //  $layout = $class_post_grid_functions->layout_content($layout_content);
					
					//var_dump($layout);
					
					foreach($layout as $item_key=>$item_info){
						
						$item_key = $item_info['key'];
						
						
						
						?>
                        

							<div class="item <?php echo $item_key; ?>" style=" <?php echo $item_info['css']; ?> ">
							
                            <?php
                            
							if($item_key=='thumb'){
								
								?>
                                <img style="width:100%; height:auto;" src="<?php echo post_grid_plugin_url; ?>assets/admin/images/thumb.png" />
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
								
								
								
							else{
								
								echo $item_info['name'];
								
								}
							
							?>
                            
                            
                            
                            </div>
							<?php
						}
					
					
					?>
                    </div>
                    </div>
                    </div>
                    <div class="hover">
                    <div class="name">
                    
                    <select class="select-layout-hover" name="post_grid_meta_options[layout][hover]" >
                    <?php
					
					$layout_hover_list = $class_post_grid_functions->layout_hover_list();
                    foreach($layout_hover_list as $layout_key=>$layout_info){
						?>
                        <option  value="<?php echo $layout_key; ?>"><?php echo $layout_key; ?></option>
                        <?php
						
						}
					?>
                    </select>
                    
                    Hover</div>
                    <div class="layer-hover">
                    <div class="title">Hello Title</div>
                    <div class="content">There are many variations of passages of Lorem Ipsum available, but the majority have.</div> 
                    </div>
 
                    
                    </div>                    
                    </div>

                </div> 
            
            
            
                <div class="option-box">
                    <p class="option-title"><?php _e('Skins','post_grid'); ?></p>
                    <p class="option-info"><?php _e('Select grid Skins','post_grid'); ?></p>
                    
                    <?php
                    
					
					$skins = $class_post_grid_functions->skins();
					
					
					?>
                    
                    <div class="skin-list">
                    
                    <?php 
					//var_dump($skin);
					foreach($skins as $skin_slug=>$skin_info){
						
						?>
                        <div class="skin-container">
                        
                        
                        <?php
                        
						if($skin==$skin_slug){
							
							$checked = 'checked';
							$selected_skin = 'selected';							
							}
						else{
							$checked = '';
							$selected_skin = '';	
							}
						
						?>
                        <div class="checked <?php echo $selected_skin; ?>">
                        
                        <label><input <?php echo $checked; ?> type="radio" name="post_grid_meta_options[skin]" value="<?php echo $skin_slug; ?>" ><?php echo $skin_info['name']; ?></label>

                        
                        </div>
                        
                        
                        <div class="skin <?php echo $skin_slug; ?>">
                        
                        
                        <?php
                        
						include post_grid_plugin_dir.'skins/'.$skin_slug.'/index.php';
						
						?>
                        </div>
                        </div>
                        <?php
						
						}
					
					?>
                    
                    
                    
                    </div>
                    
                    
                </div>
                 
            </li>
            <li style="display: none;" class="box4 tab-box ">
            
                <div class="option-box">
                    <p class="option-title"><?php _e('Grid Items Width','post_grid'); ?></p>
                    <p class="option-info"><?php _e('Grid item width for different device','post_grid'); ?></p>
					
                    
                    
                    <div class="">
                    Desktop:(min-width:1024px)<br>
                    <input type="text" name="post_grid_meta_options[width][desktop]" value="<?php echo $items_width_desktop; ?>" />
                  	</div>                      
                    <br>
                    <div class="">
                    Tablet:( min-width:768px )<br>
                    <input type="text" name="post_grid_meta_options[width][tablet]" value="<?php echo $items_width_tablet; ?>" />
                  	</div>                   
                    <br>
                    <div class="">
                    Mobile:( min-width : 320px, )<br>
                    <input type="text" name="post_grid_meta_options[width][mobile]" value="<?php echo $items_width_mobile; ?>" />
                  	</div>

                </div>
                
                
                <div class="option-box">
                    <p class="option-title"><?php _e('Media Height','post_grid'); ?></p>
                    <p class="option-info"><?php _e('Grid item media height','post_grid'); ?></p>
					
                    <label><input <?php if($items_height_style=='auto_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[height][style]" value="auto_height" />Auto height</label><br />
                    <label><input <?php if($items_height_style=='fixed_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[height][style]" value="fixed_height" />Fixed height</label><br />                 
                    
                    <div class="">

                    <input type="text" name="post_grid_meta_options[height][fixed_height]" value="<?php echo $items_fixed_height; ?>" />
                  	</div>                      

                </div>                
                
                
                <div class="option-box">

                    <p class="option-title"><?php _e('Featured Image size','post_grid'); ?></p>
                    <select name="post_grid_meta_options[featured_img_size]" >
                    <option value="full" <?php if($featured_img_size=="full")echo "selected"; ?>><?php _e('Full','post_grid'); ?></option>
                    <option value="thumbnail" <?php if($featured_img_size=="thumbnail")echo "selected"; ?>><?php _e('Thumbnail','post_grid'); ?></option>
                    <option value="medium" <?php if($featured_img_size=="medium")echo "selected"; ?>><?php _e('Medium','post_grid'); ?></option>
                    <option value="large" <?php if($featured_img_size=="large")echo "selected"; ?>><?php _e('Large','post_grid'); ?></option>       
                       
                    </select>
                    
                    
                    <p class="option-title"><?php _e('Media source','post_grid'); ?></p>
                    <p class="option-info"><?php _e('Grid item media source','post_grid'); ?></p>
                	<?php
                    if(empty($media_source)){
						
						$media_source = $class_post_grid_functions->media_source();
						}
					
					
					?>
                
                
                
                
                    
                    <div class="media-source-list expandable">
                    	
                        <?php
                        foreach($media_source as $source_key=>$source_info){
							?>
							<div class="items">
                                <div class="header">
                                <input type="hidden" name="post_grid_meta_options[media_source][<?php echo $source_info['id']; ?>][id]" value="<?php echo $source_info['id']; ?>" />
                                <input type="hidden" name="post_grid_meta_options[media_source][<?php echo $source_info['id']; ?>][title]" value="<?php echo $source_info['title']; ?>" />
                                
                                <input <?php if(!empty($source_info['checked'])) echo 'checked'; ?> type="checkbox" name="post_grid_meta_options[media_source][<?php echo $source_info['id']; ?>][checked]" value="<?php echo $source_info['checked']; ?>" />                                
                                                           
                                
                                <?php echo $source_info['title']; ?>
                                </div>
                            </div>
	
							<?php
							
							
							}
						
						?>
                        
                        
                                           
                        
                        
                    
                  	</div>                      

<script>
jQuery(document).ready(function($)
	{
		$( ".media-source-list" ).sortable({revert: "invalid"});

	})
</script>

                </div>                 
                
                
                
                
                
                
                <div class="option-box">
                    <p class="option-title"><?php _e('Grid Items Margin','post_grid'); ?></p>
                    <p class="option-info"><?php _e('Grid item margin','post_grid'); ?></p>
                    
                    <div class="">
                    <input type="text" name="post_grid_meta_options[margin]" value="<?php echo $items_margin; ?>" />
                  	</div>                      

                </div>
                
                
                <div class="option-box">
                    <p class="option-title"><?php _e('Grid Container options','post_grid'); ?></p>
                    <p class="option-info"><?php _e('Grid container ','post_grid'); ?></p>
                    
                    <div class="">
                    Padding: <br>
                    <input type="text" name="post_grid_meta_options[container][padding]" value="<?php echo $container_padding; ?>" />
                  	</div>
                     <br>
                    <div class="">
                    Background color: <br>
                    <input type="text" class="color" name="post_grid_meta_options[container][bg_color]" value="<?php echo $container_bg_color; ?>" />
                  	</div>
                    <br>
                    <div class="">
                    Background image: <br>
                    <img class="bg_image_src" onClick="bg_img_src(this)" src="<?php echo post_grid_plugin_url; ?>assets/admin/bg/dark_embroidery.png" />
                    <img class="bg_image_src" onClick="bg_img_src(this)" src="<?php echo post_grid_plugin_url; ?>assets/admin/bg/dimension.png" />
                    <img class="bg_image_src" onClick="bg_img_src(this)" src="<?php echo post_grid_plugin_url; ?>assets/admin/bg/eight_horns.png" />                     
                    
                    <br>
                    
                    <input type="text" id="container_bg_image" class="container_bg_image" name="post_grid_meta_options[container][bg_image]" value="<?php echo $container_bg_image; ?>" /> <div onClick="clear_container_bg_image()" class="button clear-container-bg-image"> Clear</div>
                    
                    <script>
					
					function bg_img_src(img){
						
						src =img.src;
						
						document.getElementById('container_bg_image').value  = src;
						
						}
					
					function clear_container_bg_image(){

						document.getElementById('container_bg_image').value  = '';
						
						}					
					
					
					</script>
                    
                    
                    
                    
                  	</div>                    
                    
                                                        

                </div>                           
            
            
            </li>
            <li style="display: none;" class="box5 tab-box ">
            
                <div class="option-box">
                    <p class="option-title"><?php _e('Navigation','post_grid'); ?></p>
                    <p class="option-info"><?php _e('Customize navigation layout.','post_grid'); ?></p>
                    
                    
                    <div class="grid-layout">
                    	<div class="grid-up">
						Premium Features
                        
                        </div>
                        <div class="grid-container">
                        <img src="<?php echo post_grid_plugin_url; ?>assets/admin/images/grid.png" />
                        </div>
                    	<div class="grid-bottom">
                        
                         	<label><input <?php if($pagination_type=='none') echo 'checked'; ?>  type="radio" name="post_grid_meta_options[nav_bottom][pagination_type]" value="none" />None</label>                       
                        	<label><input <?php if($pagination_type=='pagination') echo 'checked'; ?> type="radio" name="post_grid_meta_options[nav_bottom][pagination_type]" value="pagination" />Pagination</label>


                            
                        </div> 
                        
                        
                    </div>

                    
                </div>
                
                
                <div class="option-box">
                    <p class="option-title"><?php _e('Pagination themes','post_grid'); ?></p>
                    <p class="option-info"><?php _e('Themes for pagination','post_grid'); ?></p>
                      
                    <label><input <?php if($pagination_theme=='lite') echo 'checked'; ?> type="radio" name="post_grid_meta_options[nav_bottom][pagination_theme]" value="lite" />Lite</label>
                    <label><input <?php if($pagination_theme=='dark') echo 'checked'; ?> type="radio" name="post_grid_meta_options[nav_bottom][pagination_theme]" value="dark" />Dark</label> 


                </div>
                
                

                
                
            
            </li>
            
            <li style="display: none;" class="box6 tab-box ">
            
                <div class="option-box">
                    <p class="option-title"><?php _e('Custom Js','post_grid'); ?></p>
                    <p class="option-info"><?php _e('Add your custom js','post_grid'); ?></p>
                    
                    <textarea id="custom_js" name="post_grid_meta_options[custom_js]" ><?php echo $custom_js; ?></textarea>

                </div>
                
                
                <div class="option-box">
                    <p class="option-title"><?php _e('Custom CSS','post_grid'); ?></p>
                    <p class="option-info"><?php _e('Add your custom CSS','post_grid'); ?></p>
                    
                    <textarea id="custom_css" name="post_grid_meta_options[custom_css]" ><?php echo $custom_css; ?></textarea>
                    

                </div>                
                
    <script>
	
		var editor = CodeMirror.fromTextArea(document.getElementById("custom_js"), {
		  lineNumbers: true,
		  scrollbarStyle: "simple"
		});
		
		var editor = CodeMirror.fromTextArea(document.getElementById("custom_css"), {
		  lineNumbers: true,
		  scrollbarStyle: "simple"
		});		
		


    </script>
                
                
                
                
            
            </li>
            
            
        </ul>

    
    </div>
    
    
   
    
<?php


	
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function meta_boxes_post_grid_save( $post_id ) {

  /*
   * We need to verify this came from the our screen and with proper authorization,
   * because save_post can be triggered at other times.
   */

  // Check if our nonce is set.
  if ( ! isset( $_POST['meta_boxes_post_grid_input_nonce'] ) )
    return $post_id;

  $nonce = $_POST['meta_boxes_post_grid_input_nonce'];

  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $nonce, 'meta_boxes_post_grid_input' ) )
      return $post_id;

  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return $post_id;



	/* OK, its safe for us to save the data now. */
	
	// Sanitize user input.
	$post_grid_meta_options = stripslashes_deep( $_POST['post_grid_meta_options'] );
	update_post_meta( $post_id, 'post_grid_meta_options', $post_grid_meta_options );	
	
		
}
add_action( 'save_post', 'meta_boxes_post_grid_save' );






?>