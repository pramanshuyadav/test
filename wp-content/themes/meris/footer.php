<!--Footer-->
<div class="container-fluid footer">
	<div class="row">
		<footer>
			<div class="footer-first-row">
				<div class="col-md-7 col-sm-11 col-xs-12 displaynone">
				<div class="col-md-3 col-sm-3 col-xs-12 adform1 tex-center">
					<a class="adform" href="https://remote.theadformteam.com/owa" target="_blank">adform OWA</a>
					</div>
				<div class="col-md-3 col-sm-3 col-xs-12 quick-track1 text-center">
				<a class="quick-track-rdp" href="https://rdp.theadformteam.com" target="_blank">RDP<br>
				<span>(Remote Desktop)</span></a>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-12 emailus1 tex-center">
					<a class="emailus" href="mailto:sales@adformgroup.com">Email Us</a>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-12 tex-center quick-track1 text-center">
					<a class="quick-track" href="<?php echo site_url();?>/customer-care/order-status/">Quick Track<br>
<span>Coming soon...</span></a>
					</div>
					
				</div>
				<div class="col-md-3 col-sm-7 col-xs-12 bg-color1 pandding-chat displaynone">
				
					<div class="col-md-6 col-sm-7 col-xs-12">
					<div class="phone-n">
						<a href="javascript:void(0);" id="live-chat">
							800.345.3543</a>
					</div>
					</div>
					<div class="col-md-6 col-sm-5 col-xs-12">
					<div class="chat-div">
						<!--<a href="javascript:void(0);" id="live-chat"><span>LIVE CHAT</span></a>-->
						<a href="javascript:void(0)" onclick="window.open('https://c1.websitealive.com/6892/visitor/window/?code_id=6229&dl='+escape(document.location.href),'wsa_6892_0','height=200,width=200')"/>LIVE CHAT</a>
					</div>
					</div>
				</div>
				<div class="mobile-menu">
					<ul>
						<li><a class="icon1" target="_blank" href="https://remote.theadformteam.com/owa">adform OWA</a></li>
						<li><a class="rdp-icon-mobile" target="_blank" href="https://rdp.theadformteam.com">RDP (Remote Desktop)</a></li>
						<li><a class="icon2" href="mailto:mailto:sales@adformgroup.com">Email Us</a></li>
						<li><a class="icon3" href="http://vps49294.vps.ovh.ca/adform/customer-care/order-status/">Quick Track</a></li>
						<li><a class="icon4" href="javascript:void(0);">800.345.3543</a></li>
						<li><a class="icon5" onclick="window.open('https://c1.websitealive.com/6892/visitor/window/?code_id=6229&amp;dl='+escape(document.location.href),'wsa_6892_0','height=200,width=200')" href="javascript:void(0)">LIVE CHAT</a></li>
						<!--<li><span>Region:</span> <img width="22" height="13" alt="" src="/adform/wp-content/themes/meris/images/us.png"> <span>United states <br>
<a href="http://vps49294.vps.ovh.ca/adform/contact-us/">Change</a></span></li>-->
					</ul>
				</div>
				<div class="col-md-2 col-sm-4 col-xs-12 bg-color1 bg-color2">
					<div class="region-div"><span >Region:</span> <img src="<?php echo site_url();?>/wp-content/themes/meris/images/us.png" width="22" height="13" alt=""/> <span>United states <br>
<a href="<?php echo site_url();?>/contact-us/">Change</a></span></div>
				</div>
				<?php /* ?><div class="col-md-2 col-sm-4 col-xs-4 bg-color1">
					<div class="social-icon-div">
						<?php
						for($i = 0; $i < 9; $i ++) {
							$social_icon = esc_attr ( meris_options_array ( 'social_icon_' . $i ) );
							$social_link = esc_url ( meris_options_array ( 'social_link_' . $i ) );
							if ($social_link != "") {
								echo '<a href="' . $social_link . '" target="_blank"><i class="' . $social_icon . '"></i></a>';
							}
						}
						?>					
					</div>
				</div><?php */ ?>
				<div class="col-xs-12 searcg-border">
				<div class="search-form">
						<form role="search" action="<?php echo esc_url(home_url('/')); ?>"
							class="search">
							<div>
								<input type="submit" value=""> <label class="sr-only"><?php _e( 'Search for', 'meris' );?>:</label>
								<input type="text" value="" name="s" id="s"
									placeholder="<?php _e( 'Type your keyword search', 'meris' );?>">
							</div>
						</form>
					</div>
					</div>
			</div>
				<div class="footer-secound-row">
			<div class="col-md-12 text-center">
				
					<div class="site-info">
					&copy; <?php echo date("Y"); ?> Adform <!--| The Fine Print | Web Design by <a href="http://www.fusioninteractivegroup.com/" target="_blank">FUSION Interactive</a>-->
				</div>
				</div>
			</div>
		</div>
	</footer>
</div>
</div>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/slick.min.js"  defer></script>
<script type="text/javascript"  defer>
jQuery(document).ready(function() {
	  jQuery('.bottombanner').slick({
		dots: false,
		infinite: true,
		speed: 300,
		slidesToShow: 5,
		slidesToScroll: 2,
		autoplay: true,
		autoplaySpeed: 5000,
		adaptiveHeight:true,
		responsive: [
		{
		  breakpoint: 1024,
		  settings: {
			slidesToShow: 4,
			slidesToScroll: 2,
			infinite: true,
			dots: true
		  }
		},
		{
		  breakpoint: 600,
		  settings: {
			slidesToShow: 2,
			slidesToScroll: 2
		  }
		},
		{
		  breakpoint: 480,
		  settings: {
			slidesToShow: 2,
			slidesToScroll: 2
		  }
		}

		]
		});
								
	jQuery(".various").fancybox({
		maxWidth	: 800,
		maxHeight	: 600,
		fitToView	: false,
		width		: '100%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});
});
</script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.fancybox.js?v=2.1.5"  defer></script>
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/jquery.fancybox.css?v=2.1.5" media="screen" />
<script type="text/javascript"  defer>
  var topRange      = 200,  // measure from the top of the viewport to X pixels down
  edgeMargin    = 20,   // margin above the top or margin from the end of the page
  animationTime = 1200, // time in milliseconds
  contentTop = [];

  jQuery(document).ready(function(){ 
  // Stop animated scroll if the user does something
  jQuery('html,body').bind('scroll mousedown DOMMouseScroll mousewheel keyup', function(e){
    if ( e.which > 0 || e.type == 'mousedown' || e.type == 'mousewheel' ){
      jQuery('html,body').stop();
    }
  })

  // Set up content an array of locations
  jQuery('#sidemenu').find('a').each(function(){
    contentTop.push( jQuery( jQuery(this).attr('href') ).offset().top );
  })

  // Animate menu scroll to content
  jQuery('#sidemenu').find('a').click(function(){
      var sel = this,
      newTop = Math.min( contentTop[ jQuery('#sidemenu a').index( jQuery(this) ) ], jQuery(document).height() - jQuery(window).height() ); // get content top or top position if at the document bottom
      jQuery('html,body').stop().animate({ 'scrollTop' : newTop }, animationTime, function(){
      window.location.hash = jQuery(sel).attr('href');
    });
    return false;
  })

  // adjust side menu
  jQuery(window).scroll(function(){
    var winTop = jQuery(window).scrollTop(),
    bodyHt = jQuery(document).height(),
    vpHt = jQuery(window).height() + edgeMargin;  // viewport height + margin
    jQuery.each( contentTop, function(i,loc){
      if ( ( loc > winTop - edgeMargin && ( loc < winTop + topRange || ( winTop + vpHt ) >= bodyHt ) ) ){
        jQuery('#sidemenu li')
        .removeClass('selected')
        .eq(i).addClass('selected');
      }
    })
  })
})
</script>

<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.newsTicker.js"  defer></script>
<script type="text/javascript"  defer>
    jQuery(document).ready(function() {
          
            var nt_example1 = jQuery('#nt-example1').newsTicker({
                row_height: 110,
                max_rows: 2,
                speed: 400,
                duration: 4000,
                prevButton: jQuery('#nt-example1-prev'),
                nextButton: jQuery('#nt-example1-next')
            });
        });
</script>
<script type="text/javascript" >
  jQuery(document).ready(function() {
    jQuery('#contact_form img').on("contextmenu",function(e){      
        return false;
    });      
  });
</script>
<script type="text/javascript">
  //Show/hide sub menu on down arrow click
 jQuery(document).ready(function () {
 	jQuery('#menu li').each(function (e){
    	
 		jQuery(this).prepend('<span class="downarrw"></span>');
		jQuery("#menu li").each(function () {
			var hasSubmenu = jQuery(this).find(".sub-menu").length;
			if(hasSubmenu == 0){
				jQuery(this).find('.downarrw').remove();
			} 
		});
    	
		jQuery(this).find('span.downarrw').click(function () {
	    	jQuery(this).parent().addClass('active').siblings().removeClass('active');
	    	if(jQuery(this).parent().hasClass('active')){
	    		//show current sub menu
	    		jQuery(this).parent().find("ul.sub-menu").slideDown('slow');
	    	}
			//hide other submenu 
	       	jQuery(this).parent().siblings().find("ul.sub-menu").hide();	
		});
  	});
 });   
</script>
<script type="text/javascript">	
	function resizer(container_val,video_val,ratio_val) {				
		//var width = parseInt(window.getComputedStyle(container)['width'], 9.5);
		var width = parseInt(window.getComputedStyle(container_val, null).getPropertyValue("width"), 9.5);
		var height = (width * ratio_val);
		
		video_val.style.width = width + 'px';
		video_val.style.height = height + 'px';
		video_val.style.marginTop = '-0.278%'; //~732px wide, the video border is about 24px thick (24/732)
		
		container_val.style.height = (height * 1.00) + 'px'; //0.88 was the magic number that you needed to shrink the height of the outer container with.
	}		
			
	jQuery( ".video_cls" ).each(function() {
		var class_id = jQuery(this).attr('id');			  
		var container = document.getElementById(class_id);				  

		if( container != null )
		{					
			var video_id = jQuery(this).children().attr('id');
			
			var video = document.getElementById(video_id);
			var ratio = 9/16;
			window.addEventListener('resize', resizer, false);			
			resizer(container,video,ratio);			
		}	  
	});
</script>
<?php wp_footer();?>
</body>
</html>