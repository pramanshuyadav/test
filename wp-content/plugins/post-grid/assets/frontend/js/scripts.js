jQuery(document).ready(function($)
	{










		$(document).on('keyup', '.nav-search .search', function()
			{
				var keyword = $(this).val();
				var grid_id = $(this).attr('grid_id');				
				
				if(keyword.length>3){
					$(this).addClass('loading');
					
					$('.pagination').fadeOut();
					
					$.ajax(
						{
					type: 'POST',
					context: this,
					url:post_grid_ajax.post_grid_ajaxurl,
					data: {"action": "post_grid_ajax_search", "grid_id":grid_id,"keyword":keyword,},
					success: function(data)
							{	
								
								$('.grid-items').html(data);
								$(this).removeClass('loading');
		
							}
						});

	
					
					}
				
			})





		$(document).on('click', '.nav-filter .filter', function()
			{
				$('.nav-filter .filter').removeClass('active');
				
				
				if($(this).hasClass('active'))
					{
						//$(this).removeClass('active');
					}
				else
					{
						$(this).addClass('active');
					}
				
			})

		$(document).on('click', '.post-grid .load-more', function()
			{
				
				
				var paged = parseInt($(this).attr('paged'));
				var per_page = parseInt($(this).attr('per_page'));
				var grid_id = parseInt($(this).attr('grid_id'));
				var terms = $('.post-grid-filter .active').attr('terms-id');
				
				
				if(terms == null || terms == '')
					{
						terms = '';
					}
						
				$(this).addClass('loading');

				
			$.ajax(
				{
			type: 'POST',
			context: this,
			url:post_grid_ajax.post_grid_ajaxurl,
			data: {"action": "post_grid_ajax_load_more", "grid_id":grid_id,"per_page":per_page,"paged":paged,"terms":terms,},
			success: function(data)
					{	
					
						//$('.grid-items').append(data);
						var $grid = $('.grid-items').masonry({});				
						
						  // append items to grid
							$grid.append( data )
							// add and lay out newly appended items
							.masonry( 'appended', data );
							$grid.masonry( 'reloadItems' );
							$grid.masonry( 'layout' );


						$(this).attr('paged',(paged+1));
						
						if($(this).hasClass('loading'))
							{
								$(this).removeClass('loading');
							}
						
					}
				});

				//alert(per_page);
			})

		
		

	});	






