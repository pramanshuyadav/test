<?php 
$post_type = new osBxSliderPostTypes();
$slider_custom_meta = $post_type->os_bxslider_return_slider_custom_meta( $post_id );
$slider_type = isset( $slider_custom_meta['slider_type'] ) ? $slider_custom_meta['slider_type'] : '';
?>
<div id="os-bxslider-type-wrapper">
    <a href="javascript:;" class="slider_type<?php echo ( $slider_type == 'slider_caption' ) ? ' active' : '';?>" id="slider_caption" title="Slider with captions"></a>
    <a href="javascript:;" class="slider_type<?php echo ( $slider_type == 'carousel' ) ? ' active' : '';?>" id="carousel" title="Carousel"></a>
    <a href="javascript:;" class="slider_type<?php echo ( $slider_type == 'thumbnail' ) ? ' active' : '';?>" id="thumbnail" title="Thumbnail"></a>
    <a href="javascript:;" class="slider_type last<?php echo ( $slider_type == 'ticker' ) ? ' active' : '';?>" id="ticker" title="Ticker" ></a>
    <input type="hidden" name="osbx[slider_type]" id="slider_type" value="<?php echo $slider_type;?>" />
    <div class="clear"></div>
</div>                                                                                    