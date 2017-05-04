<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */

	$iframe_name     = "action_preview";
	$click_action_id = $map->get_click_action()->get_id();
	$iframe_url      = admin_url("admin.php?page=interactive_map_builder_clickactions&test=" . $click_action_id . "&map=" . $map->get_id());
	if($map->is_import()) {
		$iframe_url  = "";
	}
	$custom_template_description  = __('This is the custom template. It is used to edit the HTML, CSS and JavaScript right from the map builder. It will automatically switch to this template as soon as you click "Update template". You can also activate the mode by selecting "custom" template.', $this->plugin_slug);
	$default_template_description = __('This is the default template. This can be used to create a custom template. As soon as you start editing the HTML, CSS or HTML, it will automatically switch to "custom template". Note that you have to press "Update preview" below the JavaScript field in order to see any changes.', $this->plugin_slug);
?>
<script type='text/javascript'>
		var page_config = {};
		page_config["map_click_opens_dialog"] = true;
		page_config["map_click_runs_action"]  = true;
		page_config["click_actions"]          = <?php echo json_encode(Click_Action::get_actions_json()); ?>;
		page_config["table"]                  = <?php echo json_encode($map->get_table()); ?>;
		page_config["map_is_import"]          = <?php echo ($map->is_import()) ? "true" : "false" ?>;
</script>
<script type='text/javascript'>
	// Raw data table
	var data_table = <?php echo $map->get_js_table()->to_json(); ?>;
	
	// Raw table converted to Gtable
	var gtable = <?php echo $map->get_js_table()->to_gtable($map); ?>;
	
	// Map options
	var default_options = <?php echo $map->get_js_options()->to_json($map); ?>;
	var map_id = <?php echo $map->get_id(); ?>;

	var click_actions = <?php echo $map->get_js_table()->click_actions_to_json(); ?>;
	
	var current_mode = "<?php echo $map->get_display_mode(); ?>";
	var iframe_url   = "<?php echo admin_url("admin.php?page=interactive_map_builder_clickactions&test="); ?>";
	var iframe_name  = "#<?php echo $iframe_name;?>";
	
	// Additional data
	var regions = <?php echo json_encode(Map_Regions::get_tree($this->plugin_slug)); ?>;
	
	function open_dialog(location) {
		if(!page_config["map_click_opens_dialog"])
			return;
			
		var link = jQuery(".add_to_table").eq(0);
		var form = jQuery.data( document.body, "form");
		// Set Region/Marker-Text
		form.find("[name=location]").val(location);
		link.trigger("click");
		
	}
	function receive_map_event(i) {
		set_click_value();
		if(!page_config["map_click_opens_dialog"])
			return;
			
		var table = jQuery.data( document.body, "table");
		var rows = table.find("tbody tr");
		var length = rows.length;
		
		if(i>=0 && i<length) {
			var row = rows.eq(length-i-1);
			row.trigger("click");
		}
	}
	function receive_map_ready_event() {
		console.log("map ready");
		jQuery.data( document.body, "preview").hide_loader();
	}
	function set_click_value() {
		var iframe = jQuery("iframe")[0];
		iframe.contentWindow.map_generator["maps"][1]["actions"] = click_actions;
	}
	function set_message(text) {
		var box = jQuery("#message");
		box.addClass("updated").removeClass("error");
		if(text!="") {
			box.find("p").show().find(".message").html(text);
			box.css("visibility", "visible");
			box.slideDown();
		} else {
			box.css("visibility", "hidden");
		}
	}
	function set_error_message(text) {
		var box = jQuery("#message");
		box.addClass("error").removeClass("updated");
		if(text!="") {
			box.find("p").show().find(".message").html(text);
			box.css("visibility", "visible");
			box.slideDown();
		} else {
			box.css("visibility", "hidden");
		}
	}
	function get_map_table() {
		return gtable;
	}
	function get_map_options() {
		//console.log(default_options);
		return default_options;
	}
	function has_to_run_click_action(){
		return page_config["map_click_runs_action"];
	}
</script>
<?php
	$classes = array();
	
	// Edit mode
	if($map->get_id() == -1) {
		$classes[] = "new_mode";
	} else {
		$classes[] = "edit_mode";
	}
	
	// Display modes
	if($map->get_display_mode() == "marker_by_coordinates") {
		$classes[] = "marker_coord_mode";
	} elseif($map->get_display_mode() == "marker_by_name") {
		$classes[] = "marker_text_mode";
	} elseif($map->get_display_mode() == "textmode") {
		$classes[] = "text_mode";
	} else {
		$classes[] = "region_mode";
	}
	
	// Using Html Tooltips?
	if($map->get_js_options()->has_html_tooltips()) {
		$classes[] = "html_tooltips";
	}
?>

<div id="new_maps_page" class="wrap <?php echo implode(" ", $classes); ?>">
	<h2 class="new_title"><?php echo __( 'Add New Map', $this->plugin_slug );  ?><span class="h2_subtitle">Interactive Map Builder »</span></h2>
	<h2 class="edit_title"><?php echo __( 'Edit Map', $this->plugin_slug );  ?><span class="h2_subtitle">Interactive Map Builder »</span></h2>
	
	<div class="updated below-h2" id="message"><p><span class="message"></span> <span class="back_link"><a href="<?php echo admin_url("admin.php?page=interactive_map_builder"); ?>"><?php echo __("Back to All Maps", $this->plugin_slug );  ?></a></span></p></div>
	
	<!-- Add/Save Buttons -->
	<div class="page_links">
	
		<span class="spinner"></span>
		
		<!-- Add button -->
		<a id="add_map" class="button button-primary button-large map_transaction" href="<?php echo admin_url("admin.php?page=interactive_map_builder_new"); ?>">
			<span><?php echo __('Add map', $this->plugin_slug); ?></span>
		</a>
		
		<!-- Save button -->
		<a id="save_map" class="button button-primary button-large map_transaction" href="<?php echo admin_url("admin.php?page=interactive_map_builder_new"); ?>">
			<span><?php echo __('Save map', $this->plugin_slug); ?></span>
		</a>
	</div>
	
	<!-- Container for all option boxes -->
	<div class="site_container">
	
		<!-- Main content -->
		<div id="map-body-content">
		
			<!-- Map title -->
			<div id="titlewrap">
			
				<!-- Label for map title -->
				<label for="title" 
				       id="title-prompt-text" 
					   class="" 
					   style="<?php if($map->get_name()!=="") echo "display: none;" ?>"><?php echo __('Enter title here', $this->plugin_slug); ?></label>
					   
				<!-- Input for map title -->
				<input id="title" 
				       type="text" 
					   autocomplete="off"  
					   value="<?php echo $map->get_name(); ?>" 
					   size="30" 
					   name="post_title" />
			</div>
						
			<!-- map preview -->
			<div class="map_outer">
				
				<div class="map_container">
					<div id="resize_box" style="position: relative;">
						<div class="loader spinner"></div>
						<iframe id  ="<?php echo $iframe_name;?>" 
								name="<?php echo $iframe_name;?>" 
								frameborder="0"
								style=""
								src ="<?php echo $iframe_url; ?>"></iframe>
					</div>
				</div>
				
				<div class="map_info">
					<div class="left">
						<!-- "Preview" -->
						<h3><?php echo __('Preview', $this->plugin_slug); ?></h3>
						
						<!-- Show/Hide data table 
						<span toggle="<?php echo __('Show data table', $this->plugin_slug); ?>" 
						      class="show_table"><?php echo __('Hide data table', $this->plugin_slug); ?></span>-->
							  
					</div>
					<div class="right">
						
						<div id="map_click_control" class="more_indicator option_multiselect width_200">
							<span class="mouse_icon"></span>
							<ul class="inner_body">						
								<li><p class="more_title"><?php echo __('If a region or marker is being clicked in the preview, then:', $this->plugin_slug); ?></p></li>
								<hr>
								<li class="value selected" what="click_action"><span><?php echo __('Execute Click Action', $this->plugin_slug); ?></span></li>
								<li class="value selected" what="open_dialog"><span><?php echo __('Open form for editing or adding', $this->plugin_slug); ?></span></li>
							</ul>
						</div>
						
						<!-- Links to change the width of the preview -->
						<div id="preview_width" class="more_indicator option_select width_auto"  style="float: right;">
							<span class="show_selected_here"><?php echo __('Full Width', $this->plugin_slug); ?></span>
							<ul class="inner_body">
								<li class="value selected" value="100"><span><?php echo __('Full Width', $this->plugin_slug); ?></span></li>
								<li class="value" value="50"><span>50%</span></li>
								<li class="value" value="25"><span>25%</span></li>
								<li class="value" value="10"><span>10%</span></li>
								
							</ul>
						</div>
						
					</div>
					<br style="clear: both;"/>
				</div>
			</div>
			
			<div class="map_below">
				<div id="map_template_title" class="map_info collapsed">
					<div class="left">
						<!-- "Preview" -->
						<h3><?php echo __('Map Template', $this->plugin_slug); ?></h3>
					</div>
					<div class="right">
						<div class="button_indicator00">
							<?php if($click_action_id==-1) : ?>
								<span class="title_cap"><?php echo __('Default template', $this->plugin_slug);?></span>
							<?php elseif($click_action_id==-2) : ?>
								<span class="title_cap"><?php echo __('Custom', $this->plugin_slug);?></span>
							<?php else: ?>
								<span class="title_cap"><?php echo esc_html($map->get_click_action()->get_name()); ?></span>
							<?php endif; ?>
							
						</div>	
						<div class="box_handle"></div>
					</div>
					<br style="clear: both;"/>
				</div>
				<div id="map_template_body" class="template_code">
					<?php
						$template = $map->get_click_action();
						
						//$selected_action->get_html();
						$template_html = esc_html($template->get_html());
						$template_css = esc_html($template->get_css());
						$template_js = esc_html($template->get_javascript());
					?>
					<form action="<?php echo admin_url("admin.php?page=interactive_map_builder_clickactions&test=-1"); ?>" 
						  method="post"
						  id="form"
						  target="<?php echo $iframe_name;?>">
					<table class="template_table">
						<tr class="template_row">
							<td class="single_row line_heighter">
								<label for="template">Selected template</label>
							</td>
							<td>
								<select id="template" autocomplete="off">
									<option value="-1" <?php echo ($click_action_id==-1) ? 'selected=""':""; ?>><?php echo __('Default template', $this->plugin_slug); ?></option>
									<option value="-2" <?php echo ($click_action_id==-2) ? 'selected=""':""; ?>><?php echo __('Custom', $this->plugin_slug); ?></option>
							
									<optgroup label="<?php echo __('Saved templates', $this->plugin_slug); ?>">
									<?php foreach($click_actions as $click_action) : ?>
										<?php if($click_action->get_id() == $click_action_id) :?>
											<option value="<?php echo $click_action->get_id(); ?>" selected=""><?php echo $click_action->get_name(); ?></option>
										<?php else: ?>
											<option value="<?php echo $click_action->get_id(); ?>"><?php echo $click_action->get_name(); ?></option>
										<?php endif; ?>
									<?php endforeach; ?>
									</optgroup>
								</select>
								
							</td>
						</tr>
						<tr class="template_row">
							<td class="line_heighter">
								Description
							</td>
							<td>
								<div id="template_description">
									<?php if($click_action_id==-1) : ?>
										<?php echo $default_template_description;?>
									<?php elseif($click_action_id==-2) : ?>
										<?php echo $custom_template_description;?>
									<?php else: ?>
										<?php echo nl2br(esc_html($map->get_click_action()->get_description())); ?>
									<?php endif; ?>
								</div>
							</td>
						</tr>
						<tr class="template_row">
							<td class="line_heighter">HTML</td>
							<td>
								<table class="code_table for_html">
									<tr>
										<th colspan="2"><pre>&lt;!-- <?php echo __("Available Placeholders", $this->plugin_slug); ?></pre></th>
									</tr>
									<tr>
										<th><pre> -  %%map%% </pre></th><td><pre><?php echo __("Placeholder for the generated html of the map. (REQUIRED)", $this->plugin_slug); ?></pre></td>
									</tr>
									<tr>
										<th><pre> -  {cssid} </pre></th><td><pre><?php echo __("Placeholder for the generated css id.", $this->plugin_slug); ?> --&gt;</pre></td>
									</tr>
								</table>
								<textarea id="html_textarea" name="html" autocomplete="off"><?php echo $template_html; ?></textarea>
							</td>
						</tr>
						<tr class="template_row">
							<td class="line_heighter">CSS</td>
							<td>
								<table class="code_table for_css">
									<tr>
										<th colspan="2"><pre>/* <?php echo __("Available Placeholders", $this->plugin_slug); ?></pre></th>
									</tr>
									<tr>
										<th><pre> -  {cssid} </pre></th><td><pre><?php echo __("Placeholder for the generated css id.", $this->plugin_slug); ?> */</pre></td>
									</tr>
								</table>
								<textarea id="css_textarea" name="css" autocomplete="off"><?php echo $template_css; ?></textarea>
							</td>
						</tr>
						<tr class="template_row">
							<td class="line_heighter">
								<span>JavaScript</span>
								<ul class="template_tips">
									<li><small>Executed when a marker/region is clicked.</small></li>
									<li><small>Click value comes from the table below.</small></li>
								</ul>
								
								
							</td>
							<td>
								<table class="code_table">
									<tr>
										<th><pre><i>var</i> click_value; </pre></th><td><pre>// Click Value of the clicked region or marker.</pre></td>
									</tr>
									<tr>
										<th><pre><i>var</i> i; </pre></th><td><pre>// The row number in the Data Table.</pre></td>
									</tr>
									<tr>
										<th><pre><i>var</i> click_values; </pre></th><td><pre>// All other Click Values. click_values[i]=click_value</pre></td>
									</tr>
									<tr>
										<th><pre><i>var</i> cssid; </pre></th><td><pre>// The generated value of the {cssid} placeholder in the HTML and CSS.</pre></td>
									</tr>
									<tr>
										<th><pre><i>var</i> storage; </pre></th><td><pre>// Storage specifically for the clicked region/marker. Keeps the value until the next click. Initial value is null.</pre></td>
									</tr>
									<tr>
										<th><pre><i>var</i> map_storage; </pre></th><td><pre>// Storage available to all clicked regions/markers. Keeps the value until the next click. Initial value is null.</pre></td>
									</tr>
									<tr>
										<th><pre><i>var</i> jQuery, $; </pre></th><td><pre>// jQuery library. Version depends on the installed Wordpress version.</pre></td>
									</tr>
								</table>
								<textarea id="js_textarea" name="js" autocomplete="off"><?php echo $template_js; ?></textarea>
							</td>
						</tr>
						<tr class="template_row update_row">
							<td></td>
							<td>
								<?php submit_button( __( 'Update Preview', $this->plugin_slug ), 'primary big', "file_import_submit", false, array("id" => "update_template")); ?>
							</td>
						</tr>
					</table>
					</form>
				</div>
				<div class="map_info">
					<div class="left">
						<!-- "Preview" -->
						<h3><?php echo __('Map Elements', $this->plugin_slug); ?></h3>
						
						<!-- Show/Hide data table 
						<span toggle="<?php echo __('Show data table', $this->plugin_slug); ?>" 
						      class="show_table"><?php echo __('Hide data table', $this->plugin_slug); ?></span>-->
							  
					</div>
					<div class="right">
						<span class="title_cap"><span id="table_count">0</span> <span id="table_count_regions">regions</span><span id="table_count_markers">markers</span><span id="table_count_text">text markers</span></span>
						<div class="button_indicator00">
							<?php submit_button( __( 'Add Region', $this->plugin_slug ), 'primary small show_table add_to_table', "file_import_submit", false, array("id" => "add_region")); ?>
							<?php submit_button( __( 'Add Marker', $this->plugin_slug ), 'primary small show_table add_to_table', "file_import_submit", false, array("id" => "add_marker")); ?>
							<?php submit_button( __( 'Add Text', $this->plugin_slug ), 'primary small show_table add_to_table', "file_import_submit", false, array("id" => "add_text")); ?>

						</div>				
						
					</div>
					<br style="clear: both;"/>
				</div>
				
				<!-- Data table -->
				<div class="table_container" id="data_table" style="padding: 0">
<div style="padding: 0 5px; overflow: hidden;">
						<table>
							<thead>
								<tr>
									<th class="rm_column">
										<span class="region_title"><?php echo __('Region', $this->plugin_slug); ?></span>
										<span class="marker_title"><?php echo __('Marker', $this->plugin_slug); ?></span>
										<span class="text_title"><?php echo __('Text Location', $this->plugin_slug); ?></span>
									</th>
									<th class="coordinate_column"  ><?php echo __('Marker Coordinates', $this->plugin_slug); ?></th>
									<th class="text_label"  ><?php echo __('Text Label', $this->plugin_slug); ?></th>
									<th class="color_column"       ><?php echo __('Color', $this->plugin_slug); ?></th>
									<th class="tooltip_column"     ><?php echo __('Tooltip', $this->plugin_slug); ?></th>
									<th class="clickaction_column" ><?php echo __('Click Value', $this->plugin_slug); ?></th>
									<th class="additional_column" style="width: 60px;"></th>
								</tr>
							</thead>
							
							<tbody>

							</tbody>
							
							<tfoot>
								<tr>
									<td colspan="5" class="maptable_no_entries">- <?php echo __('No entries in table', $this->plugin_slug); ?> -</td>
								</tr>
								
								<tr class="clone_row">
									<td class="rm_column"></td>
									<td class="coordinate_column">(<span class="latitude"></span>, <span class="longitude"></span>)</td>
									<td class="text_label"></td>
									<td class="color_column"><span class="color_circle"></span></td>
									<td class="tooltip_column">
										<span class="tooltip_title_cell hide_for_htmltooltips"></span>
										<span class="tooltip_text_cell"></span>
									</td>
									<td class="clickaction_column"></td>
									<td class="delete_cell"><span><?php echo __('Delete', $this->plugin_slug); ?></span></td>
								</tr>
							</tfoot>
						</table>
</div>
						<!-- Form to change a row in the map table data -->
						<div class="data_table_form">
						
							<div class="data_table_form_arrow"></div>
							
							<div class="databox">
								<div class="box_header">
									<!-- Box titles -->
									<span class="box_title edit_entry_title"><?php echo __('Edit', $this->plugin_slug); ?></span>
									<span class="box_title add_entry_title"><?php echo __('Add', $this->plugin_slug); ?></span>
								</div>
								
								<div class="box_body">

									<ul>
										<!-- Input for Region name -->
										<li class="top_bottom rm_box">
											<label for="data_form_location" class="region_name_input"><?php echo __("Region's name", $this->plugin_slug); ?></label>
											<label for="data_form_location" class="marker_text_input"><?php echo __("Marker Location", $this->plugin_slug); ?></label>
											<label for="data_form_location" class="text_location_input"><?php echo __("Text Location", $this->plugin_slug); ?></label>
											<input id="data_form_location" name="location" type="text">
											
											<small class="region_name_input"><?php echo __('For example: "Germany" or "Asia"', $this->plugin_slug); ?></small>
											<small class="text_location_input"><?php echo __('For example: "Germany" or "Asia"', $this->plugin_slug); ?></small>
											<small class="marker_text_input">
												<?php echo __('For example: "Berlin" or "New York City".', $this->plugin_slug); ?>
												<br/>
												<?php echo __("Using text to find the markers location, might cause longer loading time. Especially if there this a big number of markers, it is recommended to pick the coordinate mode on the right side instead of text mode.", $this->plugin_slug); ?>
											</small>
											
											<div class="data_table_lat_long_arrow"></div>
											
											<div class="bottom_bar">
												<!-- DELETE -->
												<div class="left">
													<div class="spinner" id="lat_long_spinner"></div>
												</div>
												<div class="right">
													<!-- CANCEL -->
													<a href="#" id="data_table_lat_long_close"><span><?php echo __( 'Close', $this->plugin_slug ); ?></span></a>
													
													<!-- CHANGE -->
													
													<?php submit_button( __( 'Search', $this->plugin_slug ), 'primary big', "search_lat_long", false, array("id" => "data_table_lat_long_search")); ?>
													
												</div>
											</div>
										</li>
										
										<!-- Input for Text Label 
										<li class="top_bottom textlabel_box">
											<label for="data_form_location" class="text_location_input"><?php echo __("Text Label", $this->plugin_slug); ?></label>
											<input id="data_form_location" name="location" type="text">

											<small class="text_location_input"><?php echo __('This text will be displayed at the given location.', $this->plugin_slug); ?></small>
											
										</li>-->
										
										<!-- Input for Marker Coordinates -->
										<li class="marker_coord_input left_right">
											<label><?php echo __("Marker Coordinates", $this->plugin_slug); ?></label> 
											
											<br/>
											
											<!-- Latitude -->
											<label for="data_form_latitude"><?php echo __("Latitude", $this->plugin_slug); ?></label>
											<input name="latitude" 
											       type="text"	
												   id="data_form_latitude" />
											
											<br/>
											
											<!-- Longitude -->
											<label for="data_form_longitude"><?php echo __("Longitude", $this->plugin_slug); ?></label>
											<input name="longitude" 
											       type="text"
												   id="data_form_longitude"/>
											
											<br/>
												   
											<small class="left_space"><?php echo __("If there are only a few markers to display, it's is often easier to use the text mode for markers on the right side.", $this->plugin_slug); ?></small>
											
											<br style="clear: both;" />
											<a id="find_coordinates_link" href=""><span><?php echo __("Find coordinates", $this->plugin_slug); ?></span></a>
											<br style="clear: both;" />
										</li>
										
										<!-- Color -->
										<li class="colorpicker_parent left_right">
											
											<label for="data_form_color"><?php echo __('Color', $this->plugin_slug); ?></label> 
											<input class="aaacolorpicker" 
											       type="text" 
												   name="form_color"
												   value="#FFFFFF"
												   id="data_form_color">
										</li>
										
										<!-- Tooltip -->
										<li class="top_bottom">
											<label class="hide_in_textmode hide_for_htmltooltips" for="data_form_tooltip_title"><?php echo __('Tooltip', $this->plugin_slug); ?></label> 
											<label class="show_in_textmode" for="data_form_tooltip_title"><?php echo __('Text Label', $this->plugin_slug); ?><span id="and_tooltip_title" class="hide_for_htmltooltips"><?php echo __(' & Tooltip title', $this->plugin_slug); ?></span></label> 
											<input type="text"
											       name="tooltip_title"
												   class=""
												   id="data_form_tooltip_title">
											<small class="text_location_input"><?php echo __('This text will be displayed at the given location on the map.', $this->plugin_slug); ?></small>
											<br class="show_in_textmode">
											<small class="text_location_input hide_for_htmltooltips"><?php echo __('It will also be used for the tooltip title. If you want to hide the title, you might want to try the HTML mode for tooltips which can be activated in the right column.', $this->plugin_slug); ?></small>
											<div class="show_in_textmode_space"></div>
										    <label class="show_in_textmode hide_for_htmltooltips" for="tooltip_text"><?php echo __('Tooltip text', $this->plugin_slug); ?></label> 
											<label class="show_in_textmode show_for_htmltooltips" for="tooltip_text" id="tooltip_html_label"><?php echo __('Tooltip (HTML)', $this->plugin_slug); ?></label> 
											<textarea id="tooltip_text" name="tooltip_text"></textarea>		
										</li>
										
										<!-- Click Action -->
										<li class="top_bottom">
											<label for="data_form_clickaction"><?php echo __('Click Value', $this->plugin_slug); ?></label> 
											<textarea name="click_action"
											          style="height: 121px;"
													  id="data_form_clickaction"
													  wrap="off"></textarea>
											<?php if($map->get_click_action()->get_id()==-1) : ?>
												<div id="example_box" class="hide_me">
											<?php else: ?>
												<div id="example_box">
											<?php endif; ?>
												<small>Example:</small>
												
												<div id="example_click_action_value" id="aaa"><?php echo nl2br(esc_html($map->get_click_action()->get_sample_table_1())); ?></div>
											</div>
										</li>
									</ul>
									
									<div class="bottom_bar">
										<!-- DELETE -->
										<div class="left">
											<a id="data_table_form_delete"  href="#"><span><?php echo __('Delete', $this->plugin_slug); ?></span></a>
										</div>
										<div class="right">
											<!-- CANCEL -->
											<a href="#" id="data_table_form_cancel"><span><?php echo __('Cancel', $this->plugin_slug); ?></span></a>
											
											<!-- CHANGE -->
											<?php submit_button( __( 'Change', $this->plugin_slug ), 'primary big', "change_entry", false, array("id" => "data_table_form_change")); ?>
											
											<!-- ADD -->
											<?php submit_button( __( 'Add', $this->plugin_slug ), 'primary big', "add_entry", false, array("id" => "data_table_form_add")); ?>
										</div>
									</div>
									
								</div>
							</div>
							
						</div>
						
				</div>
				
			</div>
			
			
		</div>
		
		
		<!-- right side content -->
		<div class="container_right">
			
			<!-- Box: Region -->
			<div id="optionbox_region" class="mapbox">
				<div class="box_header" title="<?php echo __('Click to toggle', $this->plugin_slug); ?>">
					<span class="box_title" ><?php echo __('Region', $this->plugin_slug); ?></span>
					<span class="box_handle"></span>
					<span class="box_value" ><?php echo __('World', $this->plugin_slug); ?></span>
				</div>
				<div class="box_body">
					<ul>
						
						<li id="world_area">
							<div class="mapbox_pointer"> </div>
							<span><?php echo __('World', $this->plugin_slug); ?></span>
						</li>
						<li id="continent_area">
							<div class="mapbox_pointer"> </div>
							<select id    = "continent_select" 
									name  = "action" 
									class = "region_select"></select>
						</li>
						<li id="subcontinent_area" class="hidden_field">
							<div class="mapbox_pointer"> </div>
							<select id    = "subcontinent_select" 
									name  = "action"
									class = "region_select"></select>
						</li>
						<li id="country_area" class="hidden_field">
							<div class="mapbox_pointer"> </div>
							<select id    = "country_select" 
									name  = "action"
									class = "region_select"></select>
						</li>
						<li id="province_area" class="hidden_field">
							<div class="mapbox_pointer"> </div>
							<select id    = "province_select" 
									name  = "action"
									class = "region_select"></select>
						</li>
					</ul>
				</div>
			</div>
			
			<!-- Box: Resolution of map borders -->
			<div id="optionbox_resolution" class="mapbox">
				<div class="box_header" title="<?php echo __('Click to toggle', $this->plugin_slug); ?>">
					<span class="box_title"><?php echo __('Resolution', $this->plugin_slug); ?></span>
					<span class="box_handle"></span>
					<span class="box_value"><?php echo __('Countries', $this->plugin_slug); ?></span>
				</div>
				<div class="box_body">
					<ul>
						<li class="has_info_tooltip">
							<input type    = "radio" 
								   name    = "resolution" 
								   value   = "continents" 
								   checked = "checked" />
							<span><?php echo __('Contintent borders', $this->plugin_slug); ?></span>
						</li>
						<li class="has_info_tooltip">
							<input type    = "radio" 
								   name    = "resolution" 
								   value   = "subcontinents" 
								   checked = "checked" />
							<span><?php echo __('Subcontinent borders', $this->plugin_slug); ?></span>
						</li>
						<li class="has_info_tooltip">
							<input type    = "radio" 
								   name    = "resolution" 
								   value   = "countries" 
								   checked = "checked" />
							<span><?php echo __('Country borders', $this->plugin_slug); ?></span>
						</li>
						<li class="has_info_tooltip">
							<input type  = "radio" 
								   name  = "resolution" 
								   value = "provinces" />
							<span><?php echo __('Province borders', $this->plugin_slug); ?></span>
						</li>
						<li class="has_info_tooltip">
							<input type  = "radio" 
								   name  = "resolution" 
								   value = "metros" />
							<span><?php echo __('Metro borders', $this->plugin_slug); ?></span>
						</li>
					</ul>
				</div>
			</div>

			<!-- Box: Display Mode -->
			<div id="optionbox_displaymode" class="mapbox">
				<div class="box_header" title="<?php echo __('Click to toggle', $this->plugin_slug); ?>">
					<span class="box_title" ><?php echo __('Display Mode', $this->plugin_slug); ?></span>
					<span class="box_handle"></span>
					<span class="box_value" ><?php echo __('Regions', $this->plugin_slug); ?></span>
				</div>
				<div class="box_body">
					<ul>
						<li>
							<input type    = "radio" 
								   name    = "displaymode" 
								   value   = "textmode" 
								   checked = "checked"
								   id      = "text_radio" />
							<label for="region_radio"><?php echo __('Text', $this->plugin_slug); ?></label>
							<div id="regions_pic" style="background-position: -114px 0;"></div>
						</li>
						<li>
							<input type    = "radio" 
								   name    = "displaymode" 
								   value   = "regions" 
								   checked = "checked"
								   id      = "region_radio" />
							<label for="region_radio"><?php echo __('Regions', $this->plugin_slug); ?></label>
							<div id="regions_pic"></div>
						</li>
						<li>
							<input type  = "radio" 
								   name  = "displaymode" 
								   value = "markers"
								   id    = "marker_radio" />
							<label for="marker_radio"><?php echo __('Markers', $this->plugin_slug); ?></label>
							<div id="markers_pic"></div>
						</li>
						
						<li class="sublist">
							<div class="mmain">
								<div class="lleft">
									<input id="marker_text_mode" type="radio" value="text" name="marker_mode">
								</div>
								<div class="rright">
									<label for="marker_text_mode"><?php echo __('Use text to find the coordinates', $this->plugin_slug); ?></label>
								</div>
								<div style="clear: both;"></div>
							</div>
						</li>
						<li class="sublist">
							<div class="mmain">
								<div class="lleft">
									<input id="marker_coords_mode" type="radio" value="coordinates" name="marker_mode">
								</div>
								<div class="rright">
									<label for="marker_coords_mode"><?php echo __('Use coordinates', $this->plugin_slug); ?></label>
								</div>
								<div style="clear: both;"></div>
							</div>
						</li>
					</ul>
				</div>
			</div>
			
			<!-- Box: Marker Styling -->
			<div id="optionbox_markerstyling" class="mapbox">
				<div class="box_header" title="<?php echo __('Click to toggle', $this->plugin_slug); ?>">
					<span class="box_title for_markers"><?php echo __('Marker Styling', $this->plugin_slug); ?></span>
					<span class="box_title for_text"><?php echo __('Text Styling', $this->plugin_slug); ?></span>
					<span class="box_handle"></span>
					<span class="box_value"></span>
				</div>
				<div class="box_body">
				
					<div class="map_styling">
						<div class="row_styling map_marker_opacity">
							<label class="for_markers" for="mapstyle_markersize"><?php echo __('Marker Size', $this->plugin_slug); ?></label>
							<label class="for_text" for="mapstyle_markersize"><?php echo __('Font size', $this->plugin_slug); ?></label>
							<input class="input_field" id="mapstyle_markersize" name="Background-Color" type="text" value="7" />
							<div style="clear: both;"></div>
						</div>
						<div class="row_styling row_with_value map_marker_opacity">
							<label class="for_markers" for="mapstyle_markeropacity"><?php echo __('Marker Opacity', $this->plugin_slug); ?></label>
							<label class="for_text" for="mapstyle_markeropacity"><?php echo __('Font Opacity', $this->plugin_slug); ?></label>
							<div class="value_unit">%</div>
							<input class="input_field" id="mapstyle_markeropacity" name="Background-Color" type="text" value="100" />
							<div style="clear: both;"></div>
						</div>
					</div>
				
				</div>
			</div>
			
			<!-- Box: Map Styling -->
			<div id="optionbox_mapstyling" class="mapbox">
				<div class="box_header" title="<?php echo __('Click to toggle', $this->plugin_slug); ?>">
					<span class="box_title" ><?php echo __('Map Styling', $this->plugin_slug); ?></span>
					<span class="box_handle"></span>
					<span class="box_value" ></span>
				</div>
				<div class="box_body">
				
				<div class="map_styling">
					<div class="row_styling map_background colorpicker_parent">
						<label for="mapstyle_bgcolor"><?php echo __('Background Color', $this->plugin_slug); ?></label>
						<div class="colorpicker_container">
							<input class="input_field aaacolorpicker" id="mapstyle_bgcolor" name="Background-Color" type="text" value="#FFFFFF" />
						</div>
						<div style="clear: both;"></div>
					</div>
					<div class="row_styling map_background colorpicker_parent">
						<label for="mapstyle_datalesscolor"><?php echo __('Dataless Region Color', $this->plugin_slug); ?></label>
						<input class="input_field aaacolorpicker" id="mapstyle_datalesscolor" name="Background-Color" type="text" value="#FFFFFF" />
						<div style="clear: both;"></div>
					</div>
					<div class="row_styling row_with_value map_height">
						<label for="mapstyle_height"><?php echo __('Height', $this->plugin_slug); ?></label>
						<div class="value_unit">px</div>
						<input class="input_field"  id="mapstyle_height" name="Background-Color" type="text" value="300" />
						<div style="clear: both;"></div>
					</div>
					<div class="row_styling row_with_value map_width">
						<label for="mapstyle_width"><?php echo __('Width', $this->plugin_slug); ?></label>
						<div class="value_unit">px</div>
						<input class="input_field"  id="mapstyle_width" name="Background-Color" type="text" value="500" />
						<div style="clear: both;"></div>
					</div>
					<div class="row_styling map_ratio">
						<label for="mapstyle_aspectratio"><?php echo __('Keep aspect ratio', $this->plugin_slug); ?></label>
						<div class="checkbox_container">
							<input id="mapstyle_aspectratio" type="checkbox" name="mapstyle_aspectratio" value="1" />
						</div>
						<div style="clear: both;"></div>
					</div>
					<div class="row_styling map_region_interactivity">
						<label for="mapstyle_interactivity"><?php echo __('Region Interactivity', $this->plugin_slug); ?></label>
						<div class="checkbox_container">
							<input id="mapstyle_interactivity" type="checkbox" name="mapstyle_interactivity" value="1" />
						</div>
						<div style="clear: both;"></div>
					</div>
					<div class="row_styling row_with_value map_border_size">
						<label for="mapstyle_bordersize"><?php echo __('Border Size', $this->plugin_slug); ?></label>
						<div class="value_unit">px</div>
						<input class="input_field" id="mapstyle_bordersize" name="Background-Color" type="text" value="100" />
						<div style="clear: both;"></div>
					</div>
					<div class="row_styling map_border_color colorpicker_parent">
						<label for="mapstyle_bordercolor"><?php echo __('Border Color', $this->plugin_slug); ?></label>
						<input class="input_field aaacolorpicker" id="mapstyle_bordercolor" name="Background-Color" type="text" value="7" />
						<div style="clear: both;"></div>
					</div>
					
				</div>
				
				</div>
			</div>
			
			<!-- Box: Tooltip Styling -->
			<div id="optionbox_tooltip" class="mapbox">
				<div class="box_header" title="<?php echo __('Click to toggle', $this->plugin_slug); ?>">
					<span class="box_title"><?php echo __('Tooltip Styling', $this->plugin_slug); ?></span>
					<span class="box_handle"></span>
					<span class="box_value"></span>
					<div style="clear: both;"></div>
				</div>
				<div class="box_body">
				
					<div class="map_styling">
						<div class="row_styling map_show_tooltip">
							<label for="show_tooltips"><?php echo __('Show tooltips', $this->plugin_slug); ?></label>
							<div class="checkbox_container">
								<input id="show_tooltips" type="checkbox" name="zutat" value="salami" />
							</div>
							<div class="float_clear"></div>
						</div>
						<div id="tooltip_trigger_group">
						<div class="row_styling map_show_tooltip">
							<label for="tooltip_trigger"><?php echo __('Trigger', $this->plugin_slug); ?></label>
							<select style="float: right;" id="tooltip_trigger">
								<option value="focus">Hover</option>
								<option value="selection">Click</option>
							</select>
							
							<div class="float_clear"></div>
						</div>
						<div class="row_styling map_show_tooltip">
							<label for="use_html"><?php echo __('Use HTML', $this->plugin_slug); ?></label>
							<div class="checkbox_container">
								<input id="use_html" type="checkbox" name="zutat" value="salami" />
							</div>
							<div class="float_clear"></div>
						</div>
						<div id="tooltip_text_styling_group">
							<div class="row_styling map_background colorpicker_parent">
								<label for="tooltip_background_color"><?php echo __('Font Color', $this->plugin_slug); ?></label>
								<input class="input_field aaacolorpicker" id="tooltip_background_color" name="Background-Color" type="text" value="#000000" />
								<div class="float_clear"></div>
							</div>
							<div class="row_styling map_tooltip_fontname">
								<label for="tooltip_font_name"><?php echo __('Font Name', $this->plugin_slug); ?></label>
								<input class="input_field" id="tooltip_font_name" name="Background-Color" type="text" value="<global-font-name>" />
								<div class="float_clear"></div>
							</div>
							<div class="row_styling map_tooltip_fontsize">
								<label for="tooltip_font_size"><?php echo __('Font Size', $this->plugin_slug); ?></label>
								<input class="input_field" id="tooltip_font_size" name="Background-Color" type="text" value="<global-font-size>" />
								<div class="float_clear"></div>
							</div>
						</div>
						</div>
					</div>
				
				</div>
			</div>

		</div>
	</div>
	
	<br style="clear: both;" />
	
	<div id="click_action_data" style="visibility: hidden; display: none">
		<?php foreach($click_actions as $click_action) : ?>
			<?php $description = (nl2br(esc_html($click_action->get_description()))); ?>
			<?php $tab1        = (nl2br(esc_html($click_action->get_sample_table_1()))); ?>
		<div id="data_of_click_action_<?php echo $click_action->get_id(); ?>">
			<span class="description"><?php echo $description; ?></span>
			<span class="example"><?php echo $tab1; ?></span>
			<span class="html"><?php echo esc_html($click_action->get_html()); ?></span>
			<span class="css"><?php echo esc_html($click_action->get_css()); ?></span>
			<span class="js"><?php echo esc_html($click_action->get_javascript()); ?></span>
		</div>
		<?php endforeach; ?>
		<?php $default_template = Click_Action::get_default_click_action(); ?>
		<div id="data_of_click_action_<?php echo $default_template->get_id(); ?>">
			<span class="description"><?php echo $default_template_description; ?></span>
			<span class="example"></span>
			<span class="html"><?php echo esc_html($default_template->get_html()); ?></span>
			<span class="css"><?php echo esc_html($default_template->get_css()); ?></span>
			<span class="js"><?php echo esc_html($default_template->get_javascript()); ?></span>
			
		</div>
		<span id="custom_template_description"><?php echo $custom_template_description; ?></span>
		<span id="switching_from_custom_template"><?php echo __('Please note that your custom template will be overwritten by the new selected template. Do you want to proceed?', $this->plugin_slug); ?></span>
		
	</div>
	
	<div id="invisible_container" style="visibility: hidden; display: none"></div>
	
	<img id="preview" />
</div>
