<?php 
$post_type = new osBxSliderPostTypes();
$slider_custom_meta = $post_type->os_bxslider_return_slider_custom_meta( $post_id );
$min_slides = isset( $slider_custom_meta['carousal']['min-slides'] ) ? $slider_custom_meta['carousal']['min-slides'] : '1';
$max_slides = isset( $slider_custom_meta['carousal']['max-slides'] ) ? $slider_custom_meta['carousal']['max-slides'] : '1';
$move_slides = isset( $slider_custom_meta['carousal']['move-slides'] ) ? $slider_custom_meta['carousal']['move-slides'] : '0';
$slide_width = isset( $slider_custom_meta['carousal']['slide-width'] ) ? $slider_custom_meta['carousal']['slide-width'] : '0';
?>
<div id="os-bxslider-type-wrapper">
    <div class="option-box box-2">
        <label>Min Slides</label>
        <p class="description">The minimum number of slides to be shown. Slides will be sized down if carousel becomes smaller than the original size.</p>
        <input type="text" name="osbx[carousal][min-slides]" value="<?php echo $min_slides;?>" />        
    </div>
    <div class="option-box box-2 last">
        <label>Max Slides</label>
        <p class="description">The maximum number of slides to be shown. Slides will be sized up if carousel becomes larger than the original size.</p>
        <input type="text" name="osbx[carousal][max-slides]" value="<?php echo $max_slides;?>" />        
    </div>
    <div class="option-box box-2">
        <label>Move Slides</label>
        <p class="description">The number of slides to move on transition. This value must be >= minSlides, and <= maxSlides. If zero (default), the number of fully-visible slides will be used.</p>
        <input type="text" name="osbx[carousal][move-slides]" value="<?php echo $move_slides;?>" />        
    </div>
    <div class="option-box box-2 last">
        <label>Slide Width</label>
        <p class="description">The width of each slide. This setting is required for all horizontal carousels!<br>&nbsp;</p>
        <input type="text" name="osbx[carousal][slide-width]" value="<?php echo $slide_width;?>" />        
    </div>
    <div class="clear"></div>
</div>                                                                                    