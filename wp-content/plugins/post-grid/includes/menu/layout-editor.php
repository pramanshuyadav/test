<?php	


/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access



if(empty($_POST['post_grid_hidden']))
	{
		$post_grid_layout_content = get_option( 'post_grid_layout_content' );


	}
else
	{	
		if($_POST['post_grid_hidden'] == 'Y') {
			//Form data sent
			
			//$post_grid_layout_content = stripslashes_deep($_POST['post_grid_layout_content']);			
			$post_grid_layout_content = get_option( 'post_grid_layout_content' );
			
			$post_grid_layout_content = array_merge($post_grid_layout_content, stripslashes_deep($_POST['post_grid_layout_content']));
			update_option('post_grid_layout_content', $post_grid_layout_content);
		

			?>
			<div class="updated"><p><strong><?php _e('Changes Saved.', 'post_grid' ); ?></strong></p></div>
	
			<?php
			} 
	}

?>

<div class="wrap">

	<div id="icon-tools" class="icon32"><br></div><?php echo "<h2>".__(post_grid_plugin_name.' - Layout Editor', 'post_grid')."</h2>";?>
		<form  method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
            <input type="hidden" name="post_grid_hidden" value="Y">
            <?php settings_fields( 'post_grid_plugin_options' );
                    do_settings_sections( 'post_grid_plugin_options' );
                
				
				if(!empty($_GET['layout_content'])){
					$layout_content = sanitize_text_field($_GET['layout_content']); 
					}
				else{
					$layout_content = 'flat'; 
					}
				
				
				//var_dump($layout_content);
				
				$class_post_grid_functions = new class_post_grid_functions();
				
            ?>
		<div class="layout-editor para-settings">
        
        
        
			<?php
            
            ?>

            <div class="layout-items">
            
            <?php
            
            $layout_items = $class_post_grid_functions->layout_items();
            
            foreach($layout_items as $item_key=>$name){
                
                ?>
                <div class="item" layout="<?php echo $layout_content; ?>" item_key="<?php echo $item_key; ?>" ><i class="fa fa-plus"></i> <?php echo $name; ?></div>
                <?php
                
                }
            ?>
            
            </div>


            
            <div class="layout-list">
            
            <?php if(isset($_GET['layout_content'])) {?>
                <div class="idle  ">
                <div class="name">Content: <?php echo $layout_content; ?></div>     
       
                <div class="layer-content">
                <div id="layout-container" class="<?php echo $layout_content; ?>">
                <?php
                
                
					if(empty($post_grid_layout_content)){
						$layout = $class_post_grid_functions->layout_content($layout_content);
						}
					else{
						$layout = $post_grid_layout_content[$layout_content];
						
						}
					


                
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
                            
                        elseif($item_key=='thumb_link'){
                            
                            ?>
                            <a href="#"><img style="width:100%; height:auto;" src="<?php echo post_grid_plugin_url; ?>assets/admin/images/thumb.png" /></a>
                            <?php
                            }
							
                        elseif($item_key=='title'){
                            
                            ?>
                            Lorem Ipsum is simply
                            
                            <?php
                            }								
                            
                        elseif($item_key=='title_link'){
                            
                            ?>
                            <a href="#">Lorem Ipsum is simply</a>
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
                
                <?php } ?>
                
                 <?php if(isset($_GET['layout_hover'])) {?>
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
                
                <?php } ?>                   
            </div>
                    
        	<br />
            <div class="css-editor expandable">
            
                <?php
				
					if(empty($layout)){$layout = array(); 
					
					echo 'you haven\'t selecetd any layout.';
					
					}
					$i=0;
					foreach($layout as $key=>$items){
						
						?>
                        <div class="items">
                        <div class="header"><span class="remove">X</span><?php echo $items['name']; ?></div>
                        	<div class="options">
							<?php
                            
                             foreach($items as $item_key=>$item_info){
                                 

								 if($item_key=='css'){
									 
									?>
	<br />
    								CSS: <br />
									<textarea autocorrect="off" autocapitalize="off" spellcheck="false"  style="width:50%" class="custom_css" item_id="<?php echo $items['key']; ?>" name="post_grid_layout_content[<?php echo $layout_content; ?>][<?php echo $i; ?>][<?php echo $item_key; ?>]"><?php echo $item_info; ?></textarea><br />
		
									
		
									<?php
									 
									 }
									 
								elseif($item_key=='char_limit'){
										?>
                                        	
                                        	Character limit: <br />
											<input type="text"  name="post_grid_layout_content[<?php echo $layout_content; ?>][<?php echo $i; ?>][<?php echo $item_key; ?>]" value="<?php echo $items['char_limit']; ?>" /><br />
	
										<?php
										
										} 
									 
									 
								else{
									?>
										<input type="hidden"  name="post_grid_layout_content[<?php echo $layout_content; ?>][<?php echo $i; ?>][<?php echo $item_key; ?>]" value="<?php echo $item_info; ?>" />

									<?php

									}
									
									if($item_key=='field_id'){
										?>
                                        	
                                        	Meta Key: <br />
											<input type="text"  name="post_grid_layout_content[<?php echo $layout_content; ?>][<?php echo $i; ?>][<?php echo $item_key; ?>]" value="<?php echo $item_info; ?>" /><br />
	
										<?php
										
										}
									
									
									
                                 
                                
                                 }
                            ?>
							</div>
                        </div>
                        
                        <?php
						
						 $i++;
						}
				
				?>
            
            </div>
        
       
        
        </div>
    


 <script>
 jQuery(document).ready(function($)
	{
		$(function() {
		$( ".css-editor" ).sortable();
		//$( ".items-container" ).disableSelection();
		});

})

</script>








        <p class="submit">
            <input class="button button-primary" type="submit" name="Submit" value="<?php _e('Save Changes','post_grid' ); ?>" />
        </p>


		</form>


</div>
