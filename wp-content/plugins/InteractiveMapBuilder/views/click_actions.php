<?php
	$iframe_name      = "action_preview";
	$iframe_url       = admin_url("admin.php?page=interactive_map_builder_clickactions&test=".$selected_action->get_id());
?>
<?php // nano inline template ?>
<?php function color_circle() { ?><span class="color_circle"></span><?php } ?>

<?php // micro templates ?>
<?php function title_bar($caption) { ?>
	<div class="title">
		<div class="left"><h3><span><?php echo $caption; ?></span></h3></div>
	</div>
<?php } ?>

<?php function table_content($content, $name) { ?>

	<textarea name="<?php echo $name; ?>"><?php echo $content; ?></textarea>
	<p><?php echo $content; ?></p>

<?php } ?>

<script type='text/javascript'>
		var page_config              = {};
		page_config["base_mode"]     = "show";
		page_config["base_action"]   = <?php echo $selected_action->get_id(); ?>;
		page_config["click_actions"] = <?php echo json_encode(Click_Action::get_actions_json()); ?>;
		page_config["new_action"]    = <?php echo json_encode($new_action->to_array()); ?> 
</script>

<script type='text/javascript'>

	function receive_map_event(i) {
		$rows = jQuery(".table_container tbody tr");
		$rows.removeClass("selected_row");
		if(i>=0 && i<=2) {
			$rows.eq(i).addClass("selected_row");
			
		}
	}
	function get_code_error(error, line, state) {
		var message = "";
		jQuery("#message").find("p").hide();
		if(line)
			query = ".runtime_error.error_with_line";
		else
			query = ".runtime_error.error_without_line";
		
		var box = jQuery("#message " + query);
		
		var count   = parseInt( box.find(".error_occurred").text() );
		if(isNaN(count))
			count = 1;
		if(jQuery("#message").css("visibility")=="hidden")
			box.find(".error_message").text("");

		if(box.find(".error_message").text() == error.toString())
			count++;
		else
			count=1;
		box.find(".error_message").text(error);
		box.show();
		console.log(count);
		box.find(".error_occurred").text(count);
		if(line)
			box.find(".error_line").text(line);
		if(count>1)
			box.find(".error_occurred").show();
		else
			box.find(".error_occurred").hide();
		
		jQuery("#message").removeClass("updated").addClass("error").css("visibility", "visible");
		jQuery("#message").slideDown();
		throw(error);
	}
</script>
<?php if($selected_action->get_id()==-1): ?>
<div id="click_actions_page" class="wrap welcome_mode">
<?php else: ?>
<div id="click_actions_page" class="wrap">
<?php endif; ?>
	<h2><?php echo esc_html( get_admin_page_title() ); 
	?> <a id="add_action" class="add-new-h2" href="<?php echo admin_url("admin.php?page=interactive_map_builder_new"); ?>"><?php echo __( 'Add Template', $this->plugin_slug );  
	?></a> <a id="import_action" class="add-new-h2" href="<?php echo admin_url("admin.php?page=interactive_map_builder_new"); ?>"><?php echo __( 'Import', $this->plugin_slug );
	?></a><span class="h2_subtitle">Interactive Map Builder »</span></h2>
	
	<div class="error below-h2" id="message">
		<p class="runtime_error error_without_line">
			<span><?php echo __( 'Click Action caused a JavaScript error:', $this->plugin_slug ); ?></span>
			<span class="error_message"></span>
			<span class="error_occurred">1</span>
			<a href="#">Bearbeiten</a>
		</p>
		<p class="runtime_error error_with_line">
			<span><?php printf( __( 'Click Action caused a JavaScript error in line %1$s:', $this->plugin_slug ), '<span class="error_line">1</span>'); ?></span>
			<span class="error_message"></span>
			<span class="error_occurred">1</span>
			<a href="#">Bearbeiten</a>
		</p>
		<p class="success"></p>
		<p class="error_custom"></p>
		<p class="error_incomplete"><?php _e('The answer from the server was incomplete.', $this->plugin_slug ); ?></p>
		<p class="error_timeout"><?php _e('Could not get an answer from the server. Check your internet connection and retry.', $this->plugin_slug ); ?></p>
		<p class="error_error"><?php _e('An error occurred.', $this->plugin_slug ); ?></p>
		<p class="error_abort"><?php _e('The request has been aborted.', $this->plugin_slug ); ?></p>
		<p class="error_parsererror"><?php _e('An error occurred on the server. ', $this->plugin_slug ); ?></p>
	</div>
	
	<div class="messagebg"></div>

	<div class="messagebox warning" id="warning_box">
		<div class="messagebox_header">
		  <button type="button" class="close" data-dismiss="modal"></button>
		  <h3>Warning</h3>
		</div>
		<div class="messagebox_body">
		  <div class="icon_text">
			<span>If you leave the page, all changes will be lost.</span>
		  </div>
		</div>
		<div class="messagebox_footer">
			<button class="button_blue btn-return" data-dismiss="modal">Stay on page</button>
			<button class="button_blue btn-danger proceed-btn">Leave page and discard changes</button>
		</div>
	</div>

	<div class="messagebox delete" id="delete_box">
		<div class="messagebox_header">
		  <button type="button" class="close" data-dismiss="modal"></button>
		  <h3><?php echo __( 'Please confirm', $this->plugin_slug );  ?></h3>
		</div>
		<div class="messagebox_body">
		  <div class="icon_text">
			<span><?php printf( __( "Are you sure you want to delete %s? This cannot be undone.", $this->plugin_slug ), '<span id="delete_action_name"></span>'); ?></span>
		  </div>
		</div>
		<div class="messagebox_footer">
			<?php submit_button( __( 'Delete', $this->plugin_slug ), 'delete', "delete", false, array("id" => "delete_confirm_button" )); ?>
		</div>
	</div>

	<div class="messagebox import" id="import_box">
		<div class="messagebox_header">
		  <button type="button" class="close" data-dismiss="modal"></button>
		  <h3><?php echo __( 'Import Click Action', $this->plugin_slug );  ?></h3>
		</div>
		<div class="messagebox_body">
		  <div class="textarea">
			<label for="import_text" class="layover"><?php echo __( 'Insert import data here...', $this->plugin_slug );  ?></label>
			<textarea id="import_text"></textarea>
		  </div>
		</div>
		<div class="messagebox_footer">
			<?php submit_button( __( 'Import', $this->plugin_slug ), 'primary', "file_import_submit", false, array("id" => "import_confirm_button" )); ?>
		</div>
	</div>

	<div class="messagebox export" id="export_box">
		<div class="messagebox_header">
		  <button type="button" class="close" data-dismiss="modal"></button>
		  <h3><?php echo __( 'Export', $this->plugin_slug );  ?></h3>
		</div>
		<div class="messagebox_body">
		  <div class="textarea">
			<textarea id="export_text"><?php echo __( "If you leave the page, all changes won't be saved.", $this->plugin_slug );  ?></textarea>
		  </div>
		</div>
		<div class="messagebox_footer"></div>
	</div>

	<form action="<?php echo admin_url("admin.php?page=interactive_map_builder_clickactions&test=2"); ?>" 
	      method="post"
		  id="form"
		  target="<?php echo $iframe_name;?>">
		
		<input type="hidden" name="id" value="<?php echo $selected_action->get_id(); ?>">
		
		<div style="position: relative; height: 22px; width: 100%">
			<div id="main_spinner" class="spinner" style="position: absolute;right:0;top:0;"></div>
		</div>
		
		<div class="page_body">
		
			<div class="head">
				<div class="mode_container show_mode_stuff">
					<a class="add-new-h2"  
					   id="delete_link"
					   href ="#"
					   ><span><?php echo __('Delete', $this->plugin_slug); ?></span></a>
					<a class="add-new-h2 do_space" 
					   id="export_link"
					   href ="#"
					   ><span><?php echo  __('Export', $this->plugin_slug); ?></span></a>
					<a class="add-new-h2 do_space" 
					   id="copy_link"
					   href ="#"
					   ><span class="spinner"></span><span><?php echo __('Create copy', $this->plugin_slug); ?></span></a>
					<a class="add-new-h2 do_space" 
						id="edit_link"
					   href ="#"
					   ><span><?php echo __('Edit', $this->plugin_slug); ?></span></a>
				</div>
				<div class="mode_container new_mode_stuff">
					<span class="spinner"></span>
					<button class="button button-primary do_smallspace" id="add_button"><span><?php echo __('Add', $this->plugin_slug); ?></span></button>
				</div>
				<div class="mode_container edit_mode_stuff">
					<span class="spinner"></span>
					<button class="button button-primary button-large do_smallspace" id="edit_button" type="submit"><span><?php echo __('Save', $this->plugin_slug); ?></span></button>
				</div>
				
			</div>
		</div>
		
		<div class="left_menu_container" style="margin-left: 320px;">
		
			<!-- LEFT CONTENT with menu -->
			<div class="left_side">
				
				<div class="actions">
					<div class="box_header">
						<span class="box_title" ><?php echo __('Map Templates', $this->plugin_slug);?> (<span id="action_count"><?php echo count($click_actions);?></span><span class="add_one"><?php echo __("+1 new", $this->plugin_slug); ?></span>)</span>
						<span class="box_handle"></span>
						<span class="box_value" ></span>
					</div>

					<div class="box_body">
						
						<div class="elements">

							<div id="name_input_container" class="field highlighted">
								<div class="field_inner">
									<label class="layover" for="action_name"><?php echo __("Enter name here", $this->plugin_slug); ?></label>
									<input name="name" type="text" class="action_name" id="action_name" value="" />
									<div class="small_arrow"></div>
								</div>
							</div>
							
							<div id="loading_content">
								<?php $highlighted_id = $selected_action->get_id(); ?>
								<?php include( 'click_actions_menu.php' ); ?>
							</div>

						</div>
						<div class="welcome_message">
							<p><?php echo __("There are no click actions yet.", $this->plugin_slug); ?></p>
							<br/>
							<a href="<?php echo admin_url("admin.php?page=interactive_map_builder_new"); ?>" class="add-new-h2" id="create_first"><?php echo __( 'Create first click action', $this->plugin_slug );?></a></td>
						</div>
						<div class="element clone_element">
							<div class="link"  ><a href="" target="" actionid=""></a></div>
							<div class="nolink"></div>
							<div class="small_arrow"></div>
						</div>
						
					</div>
				</div>
			</div>
			
			<!-- RIGHT MAIN CONTENT -->
			<div class="right_side">
			
				<!-- Preview and Data table container -->
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
							<h3><?php echo __('Example', $this->plugin_slug); ?></h3>
							<?php if($page=="edit"):?>
							<span class="show_table" toggle="<?php echo __('Hide data table', $this->plugin_slug); ?>"><?php echo __('edit data table', $this->plugin_slug); ?></span>
							<?php else: ?>
							<span class="show_table" toggle="<?php echo __('Hide data table', $this->plugin_slug); ?>"><?php echo __('Show data table', $this->plugin_slug); ?></span>
							<?php endif; ?>
						</div>
						<div class="right">
							
							<div id="preview_width" class="more_indicator option_select width_auto"  style="float: right;">
								<span class="show_selected_here"><?php echo __('Full Width', $this->plugin_slug); ?></span>
								<ul class="inner_body">
									<li class="value selected" value="100"><span><?php echo __('Full Width', $this->plugin_slug); ?></span></li>
									<li class="value" value="50"><span>50%</span></li>
									<li class="value" value="25"><span>25%</span></li>
									<li class="value" value="10"><span>10%</span></li>
									
								</ul>
							</div>
							
							<button class="form_submit button-primary button-large"><?php echo __('Update Preview', $this->plugin_slug); ?></button>
						</div>
						<br style="clear: both;"/>
					</div>
					
					<div class="table_container" style="padding: 0; border-top: 0;">
						<div style="padding: 0 5px; overflow: hidden;">
							<table>
								<thead>
									<tr>
										<th class="region"><?php echo __('Region', $this->plugin_slug); ?></th>
										<th class="color" ><?php echo __('Color', $this->plugin_slug);  ?></th>
										<th class="cli_ac"><?php echo __('Click Value', $this->plugin_slug); ?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><?php echo __('Germany', $this->plugin_slug);  ?></td>
										<td><?php color_circle(); ?></td>
										<td id="tab1"><?php table_content(esc_html($selected_action->get_sample_table_1()), "data_table_value_1"); ?></td>
									</tr>
									<tr>
										<td><?php echo __('Spain', $this->plugin_slug);    ?></td>
										<td><?php color_circle(); ?></td>
										<td id="tab2"><?php table_content(esc_html($selected_action->get_sample_table_2()), "data_table_value_2"); ?></td>
									</tr>
									<tr>
										<td><?php echo __('Italy', $this->plugin_slug);    ?></td>
										<td><?php color_circle(); ?></td>
										<td id="tab3"><?php table_content(esc_html($selected_action->get_sample_table_3()), "data_table_value_3"); ?></td>
									</tr>
								</tbody>
								
							</table>
						</div>
					</div>
				</div>
				
				<!-- Description container -->
				<div class="description_container">
					<?php title_bar(__('Description', $this->plugin_slug)); ?>
					<div class="container_body">
						<p id="s_desciption"><?php echo nl2br(esc_html($selected_action->get_description())); ?></p>
						<label for="description" class="layover"><?php echo __("Add a description here...", $this->plugin_slug); ?></label>
						<textarea id="description" name="description"><?php echo esc_html($selected_action->get_description()); ?></textarea>
					</div>
				</div>

				<div class="bottom_forms">
				
					<!-- JavaScript container -->
					<div class="js_container">
						<?php title_bar(__('JavaScript', $this->plugin_slug)); ?>
						<div class="container_body">
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
							<textarea id="js_textarea" name="js"><?php echo esc_html($selected_action->get_javascript()); ?></textarea>
						</div>
					</div>
					
					<!-- HTML container -->
					<div class="html_container">
						<?php title_bar(__('HTML', $this->plugin_slug)); ?>
						<div class="container_body">
							
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
							<textarea id="html_textarea" name="html"><?php echo esc_html($selected_action->get_html()); ?></textarea>								
						</div>
					</div>
					
					<!-- CSS container -->
					<div class="css_container">
						<?php title_bar(__('CSS', $this->plugin_slug)); ?>
						<div class="container_body">
							
							<table class="code_table for_css">
								<tr>
									<th colspan="2"><pre>/* <?php echo __("Available Placeholders", $this->plugin_slug); ?></pre></th>
								</tr>
								<tr>
									<th><pre> -  {cssid} </pre></th><td><pre><?php echo __("Placeholder for the generated css id.", $this->plugin_slug); ?> */</pre></td>
								</tr>
							</table>
							<textarea id="css_textarea" name="css"><?php echo esc_html($selected_action->get_css()); ?></textarea>
						</div>
					</div>
					
				</div>

			</div>
		</div>
	</form>
</div>
