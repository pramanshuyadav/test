 jQuery(document).ready(function(){
    jQuery("form.contact-form").submit(function(){
	var Name    = jQuery(this).find("input#contact-name").val();
	var Email   = jQuery(this).find("input#contact-email").val();
	var Message = jQuery(this).find("textarea#contact-msg").val();
	var     obj = jQuery(this);
    jQuery('.fa-spinner').remove();
	jQuery(this).find("#loading").append('<i class="fa fa-spinner fa-2 fa-spin"></i>');
	
	 jQuery.ajax({
				 type:"POST",
				 dataType:"json",
				 url:meris_params.ajaxurl,
				 data:"contact-name="+Name+"&contact-email="+Email+"&contact-msg="+Message+"&action=meris_contact",
				 success:function(data){
					 if(data.error==0){
						   obj.find("#loading").html(data.msg);	
						  }
				obj.find('.fa-spinner').remove();
				obj[0].reset();
				return false;
				},
				error:function(){
					obj.find("#loading").html("Error.");
					obj.find('.fa-spinner').remove();
					return false;
					}});
	 return false;
	 });
				
	//
	jQuery(".slogan-wrapper").parents(".col-md-12").css({"padding":0});
	//
	
	jQuery(".site-nav-toggle").click(function(){
		jQuery(".site-nav").slideToggle("fast");			
		jQuery(".menu-bg-change").toggleClass( "highlight" );				
		jQuery(".site-nav-toggle i").toggleClass( "fa-bars close-arrow" );
	});

	jQuery(".site-search-toggle").click(function(){
		jQuery(".search-form").toggle();			
	});
			
/* ------------------------------------------------------------------------ */
/*  sticky header             	  								  	    	*/
/* ------------------------------------------------------------------------ */
	 // sticky header resizing control
	jQuery(window).on('resize', function() {
	
	
	  if (jQuery(".site-nav").length) {
	  if (jQuery(window).width() > 919) {
	  //jQuery(".site-nav").show();
	  } else {
	  //jQuery(".site-nav").hide();
	  }
	  }
	  
		if(jQuery(".sticky-header").length ) {
			if(jQuery(window).width() < 765) {
				jQuery('body.admin-bar header.sticky-header').css('top', '46px');
			} else {
				jQuery('body.admin-bar header.sticky-header').css('top', '32px');
			}
		}
		
	});
	
	jQuery( window ).scroll(function() {
	if( jQuery( 'header.sticky-header' ).length ) {
		var scrollTop    = jQuery(window).scrollTop();
		var headerHeight = jQuery( 'header.sticky-header' ).outerHeight();
	if(jQuery(".slider-above-header").length){
			headerHeight = headerHeight + jQuery(".slider-above-header").outerHeight();
			}
	  if(jQuery("body.admin-bar").length){
		if(jQuery(window).width() < 765) {
				headerHeight = headerHeight+46;
				jQuery('body.admin-bar header.sticky-header').css('top', '46px');
			} else {
				headerHeight = headerHeight+23;
				jQuery('body.admin-bar header.sticky-header').css('top', '32px');
			}
			
	  }
	  else{
		  jQuery('body header.sticky-header').css('top', '0');
		  }
	  
	  if(scrollTop>=jQuery( 'header.theme-header' ).outerHeight()-headerHeight){
		  jQuery( 'header.sticky-header' ).show();
		  }else{
		  jQuery( 'header.sticky-header' ).hide();
			  }
		
	   }
	});
	jQuery('#live-chat').live('click',function(){
		 jQuery('#wp-live-chat').show();
		 jQuery('#wp-live-chat-1').trigger('click');
	});
	jQuery('#changepage').on('change', function(e) {
		//alert(jQuery(this).val());
		if(jQuery(this).val() !=""){
			window.location.href = jQuery(this).val();
		}
	});
	jQuery('.menu-item-has-children a').bind('touchstart', function(e) {
		$this = jQuery(this);
		$currentId = $this.parent().attr('id');
		/*jQuery('.menu-item-has-children').each(function(){
			console.log($currentId);
			if(jQuery(this).attr('id') != $currentId){
				jQuery(this).find('ul').hide('slow');
			}
		});*/
//		jQuery('.site-nav  ul li ul').hide();
		if($this.next('ul').is(':visible')){
			$this.next('ul').hide('slow');
		}else{
			$this.next('ul').hide('slow');
		}
		
	});
	jQuery('.menu-item-has-children a').live('click',function(){
		$this = jQuery(this);
		$currentId = $this.parent().attr('id');
		jQuery('.menu-item-has-children').each(function(){
			console.log($currentId);
			if(jQuery(this).attr('id') != $currentId){
				jQuery(this).find('ul').hide('slow');
			}
		});
//		jQuery('.site-nav  ul li ul').hide();
		if($this.next('ul').is(':visible')){
			$this.next('ul').hide('slow');
		}else{
			//$this.next('ul').show('slow');
		}
		
	});
	
	jQuery( window ).resize(function(){
		if(jQuery("body").hasClass('page-template-homepage')){
			if(jQuery( window ).width() < 768){
				$content = jQuery("body .blog-list .col-md-4 .left-side").html();
				if($content != ''){
					jQuery("body .blog-list .col-md-4 .left-side").html("");
					jQuery("body #mobile-only-content .col-md-4").html($content);	
				}
				
			}else{
				$content = jQuery("body #mobile-only-content .col-md-4").html();
				if($content != ''){
					jQuery("body #mobile-only-content .col-md-4").html("");
					jQuery("body .blog-list .col-md-4 .left-side").html($content);	
				}
			}
		}
	});
	if(jQuery( window ).width() < 768){
		if(jQuery("body").hasClass('page-template-homepage')){
			$content = jQuery("body .blog-list .col-md-4 .left-side").html();
			jQuery("body .blog-list .col-md-4 .left-side").html("");
			jQuery("body #mobile-only-content .col-md-4").html($content);
		}
	}
	jQuery('.inner-page1 .site-nav-toggle').live('click',function(){
		jQuery('.site-nav').height(jQuery('.inner-page1').height()+'px');	
	});
 });
 
 
 
 if(typeof meris_js_var !== 'undefined' && typeof meris_js_var.global_color !== 'undefined'){
 less.modifyVars({
        '@color-main': meris_js_var.global_color
    });
   }
 jQuery(window).load(function(){
	 setTimeout( function(){
		 jQuery('#wp-live-chat').hide();
	 }  , 1000 );

 });
 
