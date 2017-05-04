<?php 
$post_type = new osBxSliderPostTypes();
$slider_custom_meta = $post_type->os_bxslider_return_slider_custom_meta( $post_id );
$slides = isset( $slider_custom_meta['slides'] ) ? $slider_custom_meta['slides'] : '';
?>
<div id="os-bxslider-slider-wrapper">
<?php 
if( !empty( $slides ) ) {
    $i = 0;
    foreach ( $slides as $slideObj ) {
        if( count( $slides ) - 1 > $i ) {
            $i++;
            $attachment_id = isset( $slideObj['attachment_id'] ) ? $slideObj['attachment_id'] : '';
            $link = isset( $slideObj['link'] ) ? $slideObj['link'] : '';
            $link_target = isset( $slideObj['link_target'] ) ? $slideObj['link_target'] : '';
            $caption = isset( $slideObj['caption'] ) ? $slideObj['caption'] : '';
            ?>            
            <div class="os-bxslider-box">
                <div class="os-bxslider-header">
                    <div class="os-bxslider-caption">
                        <?php echo get_the_title( $attachment_id ) ;?>
                    </div>
                    <div class="os-bxslider-controls">
                        <span class="toggle up"></span>
                        <span class="delete"></span>                                        
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="os-bxslider-body">
                    <div class="os-bxslider-image"> 
                        <div class="os-bxslider-image-tag"><?php echo wp_get_attachment_image( $attachment_id, 'medium' ); ?></div> 
                        <input class="os-bxslider-image-id" type="hidden" value="<?php echo esc_attr( $attachment_id );?>" name="osbx[slides][<?php echo $i;?>][attachment_id]">                                      
                        <input type="button" value="Edit Image" name="add-image" id="add-image" class="button button-primary insert-media add_media" />
                    </div>
                    <div class="os-bxslider-properties">
                        <label>Slide Attributes</label>
                            <div class="attribute-box">
                                <label class="attribute">Link</label>
                                <div class="attribute-details hide">
                                    <div class="field">
                                        <label>Slider Link:</label>
                                        <input type="text" value="<?php echo esc_attr( $link ); ?>" name="osbx[slides][<?php echo $i;?>][link]" class="widefat">
                                    </div>
                                    <div class="field last">
                                        <label>Open Link in:</label>
                                        <select name="osbx[slides][<?php echo $i;?>][link_target]" id="">
                                            <option value="_self" <?php selected( esc_attr( $link_target ), '_self' ); ?>>Same Window</option>
                                            <option value="_blank" <?php selected( esc_attr( $link_target ), '_blank' ); ?>>New Tab or Window</option>
                                        </select>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="attribute-box last">
                                <label class="attribute">Caption</label>
                                <div class="attribute-details hide">
                                    <div class="field last">
                                        <textarea name="osbx[slides][<?php echo $i;?>][caption]" class="widefat"><?php echo $caption; ?></textarea>
                                    </div>
                                </div>    
                            </div>
                        </ul>
                    </div>
                    <div class="clear"></div>
                </div>    
            </div>           
        <?php
        }
    }
}
?>
</div>                                                                                    
<input type="button" class="button" value="Add Slide" id="add-slide" />

<div class="os-bxslider-box-wrap hide">
    <div class="os-bxslider-box">
        <div class="os-bxslider-header">
            <div class="os-bxslider-caption">
                Slide
            </div>
            <div class="os-bxslider-controls">
                <span class="toggle up"></span>
                <span class="delete"></span>                                        
            </div>
            <div class="clear"></div>
        </div>
        <div class="os-bxslider-body" style="display: block;">
            <div class="os-bxslider-image"> 
                <div class="os-bxslider-image-tag"></div>                                       
                <input class="os-bxslider-image-id" type="hidden" value="" name="osbx[slides][{id}][attachment_id]">
                <input type="button" value="Add Image" name="add-image" id="add-image" class="button button-primary insert-media add_media" />
            </div>
            <div class="os-bxslider-properties">
                <label>Slide Attributes</label>
                    <div class="attribute-box">
                        <label class="attribute">Link</label>
                        <div class="attribute-details hide">
                            <div class="field">
                                <label>Slider Link:</label>
                                <input type="text" value="" name="osbx[slides][{id}][link]" class="widefat">
                            </div>
                            <div class="field last">
                                <label>Open Link in:</label>
                                <select name="osbx[slides][{id}][link_target]" id="">
                                    <option value="_self" selected="selected">Same Window</option>
                                    <option value="_blank">New Tab or Window</option>
                                </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="attribute-box last">
                        <label class="attribute">Caption</label>
                        <div class="attribute-details hide">
                            <div class="field last">
                                <textarea name="osbx[slides][{id}][caption]" class="widefat"></textarea>
                            </div>
                        </div>    
                    </div>
                </ul>
            </div>
            <div class="clear"></div>
        </div>    
    </div>
</div>