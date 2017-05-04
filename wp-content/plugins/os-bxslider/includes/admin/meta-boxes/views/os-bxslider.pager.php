<?php 
$post_type = new osBxSliderPostTypes();
$slider_custom_meta = $post_type->os_bxslider_return_slider_custom_meta( $post_id );
$pager = isset( $slider_custom_meta['pager']['pager'] ) ? $slider_custom_meta['pager']['pager'] : '';
$type = isset( $slider_custom_meta['pager']['type'] ) ? $slider_custom_meta['pager']['type'] : '';
$short_separator = isset( $slider_custom_meta['pager']['short-separator'] ) ? $slider_custom_meta['pager']['short-separator'] : '/';
?>
<div id="os-bxslider-type-wrapper">
    <div class="option-box box-2">
        <label>Pager</label>
        <p class="description">If true, a pager will be added.<br>&nbsp;</p>
        <select name="osbx[pager][pager]">
            <option value="true" <?php selected( $pager, 'true' ); ?>>true</option>
            <option value="false" <?php selected( $pager, 'false' ); ?>>false</option>
        </select>        
    </div>
    <div class="option-box box-2 last">
        <label>Pager Type</label>
        <p class="description">If 'full', a pager link will be generated for each slide. <br>If 'short', a x / y pager will be used (ex. 1 / 5).</p>
        <select name="osbx[pager][type]">
            <option value="full" <?php selected( $type, 'full' ); ?>>full</option>
            <option value="short" <?php selected( $type, 'short' ); ?>>short</option>
        </select>        
    </div>
    <div class="option-box box-2">
        <label>Pager Short Separator</label>
        <p class="description">If pagerType: 'short', pager will use this value as the separating character.</p>
        <input type="text" name="osbx[pager][short-separator]" value="<?php echo $short_separator;?>" />        
    </div>    
    <div class="clear"></div>
</div>                                                                                    