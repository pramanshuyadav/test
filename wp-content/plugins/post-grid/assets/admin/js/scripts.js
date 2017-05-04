jQuery(document).ready(function($)
	{


		$(document).on('click', '#post-grid-upgrade', function()
			{

				jQuery.ajax(
					{
				type: 'POST',
				context: this,
				url: post_grid_ajax.post_grid_ajaxurl,
				data: {"action": "post_grid_upgrade_action",},
				success: function(data)
						{	
							$(this).html('Upgrade Done!');
							$(this).parent().fadeOut();
							

						}
					});
				

			})



		$(document).on('click', '.reset-content-layouts', function()
			{
				
				if(confirm("Do you really want to reset ?" )){
					
					jQuery.ajax(
						{
					type: 'POST',
					context: this,
					url: post_grid_ajax.post_grid_ajaxurl,
					data: {"action": "post_grid_reset_content_layouts",},
					success: function(data)
							{	
								$(this).html('Reset Done!');
															
								
							}
						});
					
					}
				
				

				
			})




		$(document).on('change', '.select-layout-hover', function()
			{

				var layout = $(this).val();		
				
				jQuery.ajax(
					{
				type: 'POST',
				url: post_grid_ajax.post_grid_ajaxurl,
				data: {"action": "post_grid_layout_hover_ajax","layout":layout},
				success: function(data)
						{	
							jQuery(".layer-hover").html(data);
														
							
						}
					});
				
			})	

		$(document).on('change', '.select-layout-content', function()
			{
				var layout = $(this).val();		
			
				
				jQuery.ajax(
					{
				type: 'POST',
				url: post_grid_ajax.post_grid_ajaxurl,
				data: {"action": "post_grid_layout_content_ajax","layout":layout},
				success: function(data)
						{	
							//jQuery(".layout-content").html(data);
							jQuery(".layer-content").html(data);
						}
					});
				
			})	

		
	
		
		$(document).on('click', '.post_types', function()
			{
				
				var post_types = $(this).val();
				var post_id = $(this).attr('post_id');	
		
				
				jQuery.ajax(
					{
				type: 'POST',
				url: post_grid_ajax.post_grid_ajaxurl,
				data: {"action": "post_grid_get_categories","post_types":post_types,"post_id":post_id},
				success: function(data)
						{	

							jQuery(".categories-container").html(data);
							
						}
					});
				
			})
		
		

		
		
		
		
		$(".post_grid_taxonomy").click(function()
			{
				


				var taxonomy = jQuery(this).val();
				
				jQuery(".post_grid_loading_taxonomy_category").css('display','block');

						jQuery.ajax(
							{
						type: 'POST',
						url: post_grid_ajax.post_grid_ajaxurl,
						data: {"action": "post_grid_get_taxonomy_category","taxonomy":taxonomy},
						success: function(data)
								{	
									jQuery(".post_grid_taxonomy_category").html(data);
									jQuery(".post_grid_loading_taxonomy_category").fadeOut('slow');
								}
							});

		
			})
		



	});	







