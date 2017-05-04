<?php 
$post_type = new osBxSliderPostTypes();
$slider_custom_meta = $post_type->os_bxslider_return_slider_custom_meta( $post_id );
$slider_mode = isset( $slider_custom_meta['general']['slider-mode'] ) ? $slider_custom_meta['general']['slider-mode'] : 'horizontal';
$slider_speed = isset( $slider_custom_meta['general']['slider-speed'] ) ? $slider_custom_meta['general']['slider-speed'] : '500';
$slide_margin = isset( $slider_custom_meta['general']['slide-margin'] ) ? $slider_custom_meta['general']['slide-margin'] : '0';
$start_slide = isset( $slider_custom_meta['general']['start-slide'] ) ? $slider_custom_meta['general']['start-slide'] : '0';
$random_start = isset( $slider_custom_meta['general']['random-start'] ) ? $slider_custom_meta['general']['random-start'] : 'false';
$infinite_loop = isset( $slider_custom_meta['general']['infinite-loop'] ) ? $slider_custom_meta['general']['infinite-loop'] : 'true';
$hide_control_on_end = isset( $slider_custom_meta['general']['hide-control-on-end'] ) ? $slider_custom_meta['general']['hide-control-on-end'] : 'false';
$adaptive_height = isset( $slider_custom_meta['general']['adaptive-height'] ) ? $slider_custom_meta['general']['adaptive-height'] : 'false';
$responsive = isset( $slider_custom_meta['general']['responsive'] ) ? $slider_custom_meta['general']['responsive'] : 'true';
?>
<div id="os-bxslider-type-wrapper">
    <div class="option-box">
        <label>Mode</label>
        <p class="description">Type of transition between slides.</p>
        <select name="osbx[general][slider-mode]">
            <option value="horizontal" <?php selected( $slider_mode, 'horizontal' ); ?>>horizontal</option>
            <option value="vertical" <?php selected( $slider_mode, 'vertical' ); ?>>vertical</option>
            <option value="fade" <?php selected( $slider_mode, 'fade' ); ?>>fade</option>
        </select>        
    </div>
    <div class="option-box">
        <label>Speed</label>
        <p class="description">Slide transition duration (in ms).</p>
        <input type="text" name="osbx[general][slider-speed]" value="<?php echo esc_attr( $slider_speed );?>" />        
    </div>
    <div class="option-box">
        <label>Slide Margin</label>
        <p class="description">Margin between each slide.</p>
        <input type="text" name="osbx[general][slide-margin]" value="<?php echo esc_attr( $slide_margin );?>" />        
    </div>
    <div class="option-box">
        <label>Start Slide</label>
        <p class="description">Starting slide index (zero-based).</p>
        <input type="text" name="osbx[general][start-slide]" value="<?php echo esc_attr( $slider_speed );?>" />        
    </div>
    <div class="option-box">
        <label>Random Start</label>
        <p class="description">Start slider on a random slide.</p>
        <select name="osbx[general][random-start]">
            <option value="false" <?php selected( $random_start, 'false' ); ?>>false</option>
            <option value="true" <?php selected( $random_start, 'true' ); ?>>true</option>
        </select>        
    </div>
    <div class="option-box">
        <label>Infinite Loop</label>
        <p class="description">If true, clicking "Next" while on the last slide will transition to the first slide and vice-versa.</p>
        <select name="osbx[general][infinite-loop]">
            <option value="true" <?php selected( $infinite_loop, 'true' ); ?>>true</option>
            <option value="false" <?php selected( $infinite_loop, 'false' ); ?>>false</option>
        </select>        
    </div>
    <div class="option-box">
        <label>Hide Control On End</label>
        <p class="description">If true, "Next" control will be hidden on last slide and vice-versa<br>
        Note: Only used when infiniteLoop: false.</p>
        <select name="osbx[general][hide-control-on-end]">
            <option value="false" <?php selected( $hide_control_on_end, 'false' ); ?>>false</option>
            <option value="true" <?php selected( $hide_control_on_end, 'true' ); ?>>true</option>
        </select>        
    </div>
    <div class="option-box">
        <label>Adaptive Height</label>
        <p class="description">Dynamically adjust slider height based on each slide's height.</p>
        <select name="osbx[general][adaptive-height]">
            <option value="false" <?php selected( $adaptive_height, 'false' ); ?>>false</option>
            <option value="true" <?php selected( $adaptive_height, 'true' ); ?>>true</option>
        </select>        
    </div>
    <div class="option-box">
        <label>Responsive</label>
        <p class="description">Enable or disable auto resize of the slider. Useful if you need to use fixed width sliders..</p>
        <select name="osbx[general][responsive]">
            <option value="true" <?php selected( $responsive, 'true' ); ?>>true</option>
            <option value="false" <?php selected( $responsive, 'false' ); ?>>false</option>
        </select>        
    </div>
    <div class="clear"></div>
</div>                                                                                    