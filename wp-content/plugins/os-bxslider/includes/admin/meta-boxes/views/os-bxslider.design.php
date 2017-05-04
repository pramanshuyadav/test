<?php 
$post_type = new osBxSliderPostTypes();
$slider_custom_meta = $post_type->os_bxslider_return_slider_custom_meta( $post_id );
$bgcolor = isset( $slider_custom_meta['design']['bgcolor'] ) ? $slider_custom_meta['design']['bgcolor'] : '#ffffff';
$pager_type= isset( $slider_custom_meta['design']['pager-type'] ) ? $slider_custom_meta['design']['pager-type'] : 'round';
$pager_color = isset( $slider_custom_meta['design']['pager-color'] ) ? $slider_custom_meta['design']['pager-color'] : '#666666';
$pager_hover_color = isset( $slider_custom_meta['design']['pager-hover-color'] ) ? $slider_custom_meta['design']['pager-hover-color'] : '#000000';
$next_button = isset( $slider_custom_meta['design']['next-button'] ) ? $slider_custom_meta['design']['next-button'] : '';
$prev_button = isset( $slider_custom_meta['design']['prev-button'] ) ? $slider_custom_meta['design']['prev-button'] : '';
?>
<div id="os-bxslider-type-wrapper">
    <div class="option-box box-2">
        <label>Background Color</label>
        <p class="description">Slider wrapper background color.</p>
        <input name="osbx[design][bgcolor]" type="color" value="<?php echo esc_attr( $bgcolor );?>" class="small" />        
    </div>
    <div class="option-box box-2 last">
        <label>Pager Type</label>
        <p class="description">Pager display type. eg: round or square, default value is round.</p>
        <select name="osbx[design][pager-type]">
            <option value="round" <?php selected( $pager_type, 'round' ); ?>>round</option>
            <option value="square" <?php selected( $pager_type, 'square' ); ?>>square</option>
        </select> 
    </div>
    <div class="option-box box-2">
        <label>Pager Color</label>
        <p class="description">Pager display color.</p>
        <input name="osbx[design][pager-color]" type="color" value="<?php echo esc_attr( $pager_color );?>" class="small" />        
    </div>
    <div class="option-box box-2 last">
        <label>Pager Hover Color</label>
        <p class="description">Pager display hover color.</p>
        <input name="osbx[design][pager-hover-color]" type="color" value="<?php echo esc_attr( $pager_hover_color );?>" class="small" />        
    </div>
     <div class="option-box box-2 last">
        <label>Prev Button</label>
        <p class="description">If 'prev button' is set, this image is displayed as prev button image otherwise shown default image.</p>
        <div class="upload_wrap">
            <input name="osbx[design][prev-button]" type="text" value="<?php echo esc_attr( $prev_button );?>" />
            <a href="javascript:;" class="upload insert-media prev_button"></a>
        </div>          
    </div>   
    <div class="option-box box-2">
        <label>Next Button</label>
        <p class="description">If 'next button' is set, this image is displayed as next button image otherwise shown default image.</p>
        <div class="upload_wrap">
            <input name="osbx[design][next-button]" type="text" value="<?php echo esc_attr( $next_button );?>" />
            <a href="javascript:;" class="upload insert-media next_button"></a>
        </div>        
    </div>
    <div class="clear"></div>
</div>                                                                                    