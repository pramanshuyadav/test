<?php 
$post_type = new osBxSliderPostTypes();
$slider_custom_meta = $post_type->os_bxslider_return_slider_custom_meta( $post_id );
$slider_type = isset( $slider_custom_meta['slider_type'] ) ? $slider_custom_meta['slider_type'] : '';
$shortcode = '[os-bxslider id="' . $post_id . '"]';
$shortcode_function = '<?php echo do_shortcode( [os-bxslider id="' . $post_id . '"] );?>';
?>
<div id="os-bxslider-type-wrapper">
	<p><b>Shortcode: </b></p>
    <div class="option-box" style="margin: 15px 0 0 0; border-bottom: none;">
        <input type="text" readonly="readonly" id="shortcode_<?php echo $post_id;?>" class="shortcode" value="<?php echo esc_attr( $shortcode ); ?>">         
    </div>
    <em>Copy and paste this shortcode into your post, page or custom post types etc.</em>
	<p><b>Template Code: </b></p>
    <div class="option-box" style="margin: 15px 0 0 0; border-bottom: none;">
        <input type="text" readonly="readonly" id="shortcode_function_<?php echo $post_id;?>" class="shortcode" value="<?php echo esc_attr( $shortcode_function ); ?>">      
    </div>
    <em>Copy and paste this function into your page templates like header.php, front-page.php, etc.</em>    
    <div class="clear"></div>
</div>                                                                                    