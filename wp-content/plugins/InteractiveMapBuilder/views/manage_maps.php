<div id="manage_maps_page" class="wrap plugin_interactive_maps">

	<h2>
		<?php echo esc_html( get_admin_page_title() ); ?>
		<a class="add-new-h2" href="<?php echo admin_url("admin.php?page=interactive_map_builder_new"); ?>"><?php echo __( 'Add New Map', $this->plugin_slug ); ?></a>
		<a id="import_link" class="add-new-h2" href="<?php echo admin_url("admin.php?page=interactive_map_builder_new"); ?>"><?php echo __( 'Import', $this->plugin_slug ); ?></a>
		<span class="h2_subtitle">Interactive Map Builder »</span>
	</h2>
	
	<div class="error below-h2" id="message">
		<p class="custom_success"></p>
		<p class="custom_error"></p>
		<p class="success_delete"><?php _e('Map was successfully deleted.', $this->plugin_slug ); ?></p>
		<p class="error_delete"><?php _e('Map could not be deleted.', $this->plugin_slug ); ?></p>
		<p class="success_copy"><?php _e('Map was successfully copied.', $this->plugin_slug ); ?></p>
		<p class="error_copy"><?php _e('Map could not be copied.', $this->plugin_slug ); ?></p>
		<p class="success_custom"></p>
		<p class="error_timeout"><?php _e('Could not get an answer from the server. Check your internet connection and retry.', $this->plugin_slug ); ?></p>
		<p class="error_error"><?php _e('An error occurred.', $this->plugin_slug ); ?></p>
		<p class="error_abort"><?php _e('The request has been aborted.', $this->plugin_slug ); ?></p>
		<p class="error_parsererror"><?php _e('An error occurred on the server. ', $this->plugin_slug ); ?></p>
		<p class="error_incomplete"><?php _e('The answer from the server was incomplete.', $this->plugin_slug ); ?></p>
		<p class="error_custom"></p>
	</div>
	
	<div class="messagebg"></div>

	<!-- Delete Box -->
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

	<!-- Import Box -->
	<div class="messagebox import" id="import_box">
		<div class="messagebox_header">
			<button type="button" class="close" data-dismiss="modal"></button>
			<h3><?php echo __( 'Import map', $this->plugin_slug );  ?></h3>
		</div>
		<div class="messagebox_body">
			<p class="messagebox_desciption"><?php echo __( 'Please pick a data format to import the map.', $this->plugin_slug );  ?></p>
			<ul class="tab_list">
				<li setclass="show_file_tab" class="selected_tab"><?php echo __( 'File', $this->plugin_slug );  ?></li>
				<li setclass="show_text_tab"><?php echo __( 'Text', $this->plugin_slug );  ?></li>
			</ul>
		</div>
		<div class="tab_inner show_file_tab">
			<div class="file_import">
				<form method="post" action="<?php echo admin_url("admin.php?page=interactive_map_builder_new"); ?>" enctype="multipart/form-data">
					<?php wp_nonce_field('interactive-map-builder-nonce'); ?>
					<div class="file_area">
						<label for="input" class="layover2"><?php echo __( 'Select a file:', $this->plugin_slug );  ?></label>
						<input name="file" type="file" id="input">
					</div>
					<div class="messagebox_footer">
						<span class="spinner"></span>
						<?php submit_button( __( 'Import', $this->plugin_slug ), 'primary', "text_import_submit", false, array("id" => "import_file_confirm_button" )); ?>
					</div>
				</form>
			</div>
			<div class="text_import">
				<form method="post" action="<?php echo admin_url("admin.php?page=interactive_map_builder_new"); ?>">
					<?php wp_nonce_field('interactive-map-builder-nonce'); ?>
					<div class="text_area">
						<label for="import_text" class="layover"><?php echo __( 'Insert import data here...', $this->plugin_slug );  ?></label>
						<textarea name="text" id="import_text"></textarea>
					</div>
					<div class="messagebox_footer">
						<span class="spinner"></span>
						<?php submit_button( __( 'Import', $this->plugin_slug ), 'primary', "file_import_submit", false, array("id" => "import_text_confirm_button" )); ?>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- Export Box -->
	<div class="messagebox export" id="export_box">
		<div class="messagebox_header">
			<button type="button" class="close" data-dismiss="modal"></button>
			<h3><?php echo __( 'Export map', $this->plugin_slug );  ?></h3>
		</div>
		<div class="messagebox_body">
			<p class="messagebox_desciption"><?php echo __( 'There are two different ways to export a map. The text can be used to copy the map to the clipboard. The file can be used to store a backup copy of a map.', $this->plugin_slug );  ?></p>
			<ul class="tab_list">
				<li setclass="show_file_tab" class="selected_tab"><?php echo __( 'File', $this->plugin_slug );  ?></li>
				<li setclass="show_text_tab"><?php echo __( 'Text', $this->plugin_slug );  ?></li>
			</ul>
		</div>
		<div class="tab_inner show_file_tab">
			<div class="file_import">
				<div class="file_area" style="padding: 20px;">
					<div style="float: left; padding: 15px;"><?php echo __( 'Save map:', $this->plugin_slug );  ?></div>			
					<a href="" class="download_map"><?php echo __( 'Download', $this->plugin_slug );  ?></a>
				</div>
				<div class="messagebox_footer"></div>
			</div>
			<div class="text_import">
				<div class="text_area">
					<textarea id="export_text"></textarea>
				</div>
				<div class="messagebox_footer"></div>
			</div>
		</div>
	</div>

	<script type='text/javascript'>
		var maps = {};
		var page_config = {};
		page_config["base_url"]       = "<?php echo $base_url; ?>";
		page_config["delete_url"]     = "<?php echo admin_url("admin.php?page=interactive_map_builder&delete="); ?>";
		page_config["download_url"]   = "<?php echo admin_url("admin.php?page=interactive_map_builder_clickactions&export="); ?>";
		page_config["first_pop"]      = true; // Dont change this
		page_config["enable_history"] = true;
		
		page_config["map_click_opens_dialog"] = true;
		page_config["map_click_runs_action"]  = true;
	</script>

	<div id="loading_area">
		
		<!-- AREA above map table -->
		<div class="manage_maps_top">
			<div class="maps_count">
				<?php if(count($maps)>0) :?>
					<span class="maps_num"><?php printf( _n( 'Displaying map %1$d of %3$d', 'Displaying maps %1$d-%2$d of %3$d', $map_to-$map_from+1, $this->plugin_slug ), $map_from, $map_to, $count); ?></span>
				<?php endif; ?>
			</div>
		</div>
		
		<div id="outer_area" class="<?php echo (count($maps)==0) ? "no-items" : ""; ?>">
			<span id="table_spinner" class="spinner"></span>
			
			<!-- TABLE with maps -->
			<table cellspacing="0" class="manage_maps_table" thisurl="<?php echo $this_url; ?>" baseurl="<?php echo $base_url; ?>">
				<?php
					$type_href    = View_Helper::getSortUrl($this->plugin_slug, "type");
					$name_href    = View_Helper::getSortUrl($this->plugin_slug, "name");
					$region_href  = View_Helper::getSortUrl($this->plugin_slug, "region");
					$short_href   = View_Helper::getSortUrl($this->plugin_slug, "mapid");
					$changed_href = View_Helper::getSortUrl($this->plugin_slug, "changed", "desc");
					$created_href = View_Helper::getSortUrl($this->plugin_slug, "created", "desc");
				?>
				<thead>
					<tr>
						<th class="nosorting mark-cell">
							<span><?php echo __( 'Preview', $this->plugin_slug );  ?></span>
							<div class="preview_sizes preview_big"></div>
							<div class="preview_sizes preview_normal"></div>
							<div class="preview_sizes preview_small preview_size_selected"></div>
						</th>
						<th class="<?php echo View_Helper::getCssSortable("name", "desc");     ?> name-cell"><a href="<?php echo $name_href;     ?>"><span class="sort_caption"><?php echo __( 'Map Name', $this->plugin_slug );     ?></span><span class="sorting-indicator"></span></a></th>
						<th class="<?php echo View_Helper::getCssSortable("mapid");   ?> code-cell"><a href="<?php echo $short_href;   ?>"><span class="sort_caption"><?php echo __( 'Shortcode', $this->plugin_slug );   ?></span><span class="sorting-indicator"></span></a></th>
						<th colspan="1" class="<?php echo View_Helper::getCssSortable("changed");          ?> time-cell"><a href="<?php echo $changed_href;  ?>"><span class="sort_caption"><?php echo __( 'Last change', $this->plugin_slug );  ?></span><span class="sorting-indicator"></span></a></th>
						<th class="info_th"></th>
						<th class="more_th"></th>
					</tr>
				</thead>

				<tbody>
					<?php foreach($maps as $map) : ?>
						<?php
							$id            = $map->get_id();
							$name          = esc_html($map->get_name());
							$image         = $map->get_image();
							$description   = esc_html($map->get_description());
							$has_image     = $image !== null && $image !== "";
							$region        = __( $map->get_region(), $this->plugin_slug );
							$url           = admin_url("admin.php?page=interactive_map_builder_new&map=$id");
							$changed_title = date( __( 'Y/m/d g:i:s A', $this->plugin_slug  ),$map->get_date_changed());
							$changed_diff  = human_time_diff( date("U", $map->get_date_changed()), current_time('timestamp') );
							$copy_url      = admin_url("admin.php?page=interactive_map_builder&copy=$id");
							$entries       = $map->get_js_table()->get_length();
							
							if($map->is_regions())
								$entries .= " " . _n( 'Region', 'Regions', $entries, $this->plugin_slug );
							else if($map->is_markers())
								$entries .= " " . _n( 'Marker', 'Markers', $entries, $this->plugin_slug );
							else
								$entries .= " " . _n( 'Text Marker', 'Text Markers', $entries, $this->plugin_slug );
								
							$click_action_name = esc_html($map->get_click_action()->get_name());
						?>
						<tr mapid="<?php echo $id; ?>" mapname="<?php echo $map->get_name(); ?>">
						
							<!-- PREVIEW -->
							<td>
								<?php if($has_image) : ?>
									<div class="preview_container">
										<div class="shadow">
											<img src="<?php echo $image; ?>" class="preview_image" />
											<div class="newflag"><?php echo __( 'New', $this->plugin_slug );?></div>
										</div>
									</div>
								<?php endif; ?>
							</td>
							
							<!-- NAME and DESCRIPTION -->
							<td>
								<a class="map_link" href="<?php echo $url; ?>">
									<strong><?php echo $name; ?></strong>
									<div class="description_container">
										<small title="<?php echo $description; ?>"><?php echo $description; ?></small>
									</div>
								</a>
							</td>
							
							<!-- SHORTCODE -->
							<td><strong>[interactive_map id="<?php echo $id; ?>"]</strong></td>
							
							<!-- LAST UPDATE -->
							<td><abbr title="<?php echo $changed_title; ?>"><?php printf( __( '%s ago', $this->plugin_slug ), $changed_diff ); ?></abbr></td>
							
							<!-- ADDITIONAL INFO -->
							<td>
								<div class="map_detail">
									<ul>
										<li title="<?php echo __( 'Region', $this->plugin_slug ); ?>"><span class="detail_title region_title"></span> <span class="detail_value"><?php echo $region; ?></span></li>
										<li title="<?php echo __( 'Entries in data table', $this->plugin_slug ); ?>"><span class="detail_title entry_title"></span> <span class="detail_value"><?php echo $entries; ?></span></li>
										<li title="<?php echo __( 'Map Template', $this->plugin_slug ); ?>" class="<?php echo ($click_action_name=="") ? "no_value" : ""; ?>"><span class="detail_title clickaction_title"></span> <span class="detail_value"><?php echo $click_action_name; ?></span></li>
									</ul>
								</div>
							</td>
							
							<!-- MORE Select-->
							<td>
								<div class="more_indicator hide_overflow">
									<span><?php echo __( 'More', $this->plugin_slug );?></span>
									<ul class="inner_body">
										<li><a class="delete_link" href="#"><?php echo __( 'Delete', $this->plugin_slug );?></a></li>
										<hr>
										<li><a class="export_link" href="#"><?php echo __( 'Export', $this->plugin_slug );?></a></li>
										<li><a class="copy_link" href="<?php echo $copy_url; ?>"><?php echo __( 'Create a copy', $this->plugin_slug );?></a></li>
									</ul>
								</div>
								
								<!-- invisible dom element with export data -->
								<div class="export_data">
									<textarea><?php echo $map->get_export(); ?></textarea>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				
				</tbody>
				<tfoot>
					<?php if(count($maps)==0) :?>
						<tr>
							<td colspan="5">
								<h5><?php echo __( 'There are no maps yet.', $this->plugin_slug );?></h5>
								<br/>
								<a href="<?php echo admin_url("admin.php?page=interactive_map_builder_new"); ?>" class="add-new-h2"><?php echo __( 'Create first map', $this->plugin_slug );?></a></td>
						</tr>
					<?php endif; ?>
				</tfoot>
			</table>
		</div>
		
		<?php if(count($maps)>0) :?>
			<?php $args = array(
				'base'         => $page_url.'%_%',
				'format'       => '&p=%#%',
				'total'        => $pages,
				'current'      => $pno,
				'show_all'     => false,
				'end_size'     => 1,
				'mid_size'     => 2,
				'prev_next'    => True,
				'prev_text'    => __('« Previous', $this->plugin_slug ),
				'next_text'    => __('Next »', $this->plugin_slug ),
				'type'         => 'plain',
				'add_args'     => False,
				'add_fragment' => ''
			); ?>
			<div class="tablenav bottom">
				<div class="tablenav-pages maps_pagination00">
					<span class="pagination-links">
						<?php echo paginate_links( $args ); ?> 
					</span>
				</div>
			</div>
		<?php endif; ?>
	</div>
	
</div>
