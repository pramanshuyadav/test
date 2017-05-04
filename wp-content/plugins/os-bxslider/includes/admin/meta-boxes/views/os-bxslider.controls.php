<?php 
$post_type = new osBxSliderPostTypes();
$slider_custom_meta = $post_type->os_bxslider_return_slider_custom_meta( $post_id );
$controls = isset( $slider_custom_meta['controls']['controls'] ) ? $slider_custom_meta['controls']['controls'] : '';
$next_text = isset( $slider_custom_meta['controls']['next-text'] ) ? $slider_custom_meta['controls']['next-text'] : 'Next';
$prev_text = isset( $slider_custom_meta['controls']['prev-text'] ) ? $slider_custom_meta['controls']['prev-text'] : 'Prev';
$auto_controls = isset( $slider_custom_meta['controls']['auto-controls'] ) ? $slider_custom_meta['controls']['auto-controls'] : '';
$start_text = isset( $slider_custom_meta['controls']['start-text'] ) ? $slider_custom_meta['controls']['start-text'] : 'Start';
$stop_text = isset( $slider_custom_meta['controls']['stop-text'] ) ? $slider_custom_meta['controls']['stop-text'] : 'Stop';
?>
<div id="os-bxslider-type-wrapper">
    <div class="option-box box-2">
        <label>Controls</label>
        <p class="description">If true, "Next" / "Prev" controls will be added.</p>
        <select name="osbx[controls][controls]">
            <option value="true" <?php selected( $controls, 'true' ); ?>>true</option>
            <option value="false" <?php selected( $controls, 'false' ); ?>>false</option>
        </select>        
    </div>
    <div class="option-box box-2 last">
        <label>Next Text</label>
        <p class="description">Text to be used for the "Next" control.</p>
        <input type="text" name="osbx[controls][next-text]" value="<?php echo $next_text;?>" />        
    </div>
    <div class="option-box box-2">
        <label>Prev Text</label>
        <p class="description">Text to be used for the "Prev" control.</p>
        <input type="text" name="osbx[controls][prev-text]" value="<?php echo $prev_text;?>" />        
    </div>
    <div class="option-box box-2 last">
        <label>Auto Controls</label>
        <p class="description">If true, "Start" / "Stop" controls will be added.</p>
        <select name="osbx[controls][auto-controls]">
            <option value="false" <?php selected( $auto_controls, 'false' ); ?>>false</option>
            <option value="true" <?php selected( $auto_controls, 'true' ); ?>>true</option>
        </select>        
    </div>
    <div class="option-box box-2">
        <label>Start Text</label>
        <p class="description">Text to be used for the "Start" control.</p>
        <input type="text" name="osbx[controls][start-text]" value="<?php echo $start_text;?>" />        
    </div>
    <div class="option-box box-2 last">
        <label>Stop Text</label>
        <p class="description">Text to be used for the "Stop" control.</p>
        <input type="text" name="osbx[controls][stop-text]" value="<?php echo $stop_text;?>" />        
    </div>
    <div class="clear"></div>
</div>                                                                                    