<?php 
$post_type = new osBxSliderPostTypes();
$slider_custom_meta = $post_type->os_bxslider_return_slider_custom_meta( $post_id );
$auto = isset( $slider_custom_meta['auto']['auto'] ) ? $slider_custom_meta['auto']['auto'] : '';
$pause = isset( $slider_custom_meta['auto']['pause'] ) ? $slider_custom_meta['auto']['pause'] : '4000';
$auto_controls = isset( $slider_custom_meta['auto']['auto-controls'] ) ? $slider_custom_meta['auto']['auto-controls'] : '';
$auto_direction = isset( $slider_custom_meta['auto']['auto-direction'] ) ? $slider_custom_meta['auto']['auto-direction'] : '';
$auto_hover = isset( $slider_custom_meta['auto']['auto-hover'] ) ? $slider_custom_meta['auto']['auto-hover'] : '';
$auto_delay = isset( $slider_custom_meta['auto']['auto-delay'] ) ? $slider_custom_meta['auto']['auto-delay'] : '0';
?>
<div id="os-bxslider-type-wrapper">
    <div class="option-box box-2">
        <label>Auto</label>
        <p class="description">Slides will automatically transition.</p>
        <select name="osbx[auto][auto]">
            <option value="false" <?php selected( $auto, 'false' ); ?>>false</option>
            <option value="true" <?php selected( $auto, 'true' ); ?>>true</option>
        </select>        
    </div>
    <div class="option-box box-2 last">
        <label>Pause</label>
        <p class="description">The amount of time (in ms) between each auto transition.</p>
        <input type="text" name="osbx[auto][pause]" value="<?php echo $pause; ?>" />        
    </div>
    <div class="option-box box-2">
        <label>Auto Start</label>
        <p class="description">Auto show starts playing on load. If false, slideshow will start when the "Start" control is clicked.</p>
        <select name="osbx[auto][auto-controls]">
            <option value="true" <?php selected( $auto_controls, 'true' ); ?>>true</option>
            <option value="false" <?php selected( $auto_controls, 'false' ); ?>>false</option>
        </select>        
    </div>
    <div class="option-box box-2 last">
        <label>Auto Direction</label>
        <p class="description">The direction of auto show slide transitions.<br>&nbsp;</p>
        <select name="osbx[auto][auto-direction]">
            <option value="next" <?php selected( $auto_direction, 'next' ); ?>>next</option>
            <option value="prev" <?php selected( $auto_direction, 'prev' ); ?>>prev</option>
        </select>        
    </div>
    <div class="option-box box-2">
        <label>Auto Hover</label>
        <p class="description">Auto show will pause when mouse hovers over slider.</p>
        <select name="osbx[auto][auto-hover]">
            <option value="false" <?php selected( $auto_hover, 'false' ); ?>>false</option>
            <option value="true" <?php selected( $auto_hover, 'true' ); ?>>true</option>
        </select>        
    </div>
    <div class="option-box box-2 last">
        <label>Auto Delay</label>
        <p class="description">Time (in ms) auto show should wait before starting.</p>
        <input type="text" name="osbx[auto][auto-delay]" value="<?php echo $auto_delay;?>" />        
    </div>

    <div class="clear"></div>
</div>                                                                                    