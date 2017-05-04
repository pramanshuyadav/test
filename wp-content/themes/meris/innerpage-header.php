<div class="col-md-8 col-sm-8">&nbsp;</div>
<div class="col-md-4 col-sm-12">
	<div class="right-nav">
	<div class="home-icn">
		<a href="<?php echo site_url();?>"><img src="<?php echo site_url();?>/wp-content/themes/meris/images/home_icon_vpi_blue.png" height="20"></a>	
	</div>
	<div class="home-icn">
		<a href="<?php echo site_url();?>/company/contact-us/"><img src="<?php echo site_url();?>/wp-content/themes/meris/images/email-icon-orange.png"></a>
	</div>
	<div class="home-icn">
		<a onclick="window.open('https://c1.websitealive.com/6892/visitor/window/?code_id=6229&amp;dl='+escape(document.location.href),'wsa_6892_0','height=200,width=200')" href="javascript:void(0)">
			<img src="<?php echo site_url();?>/wp-content/themes/meris/images/chat-icon-orange.png">
		</a>
	</div>
	<div class="home-icn">
		<a href="<?php echo site_url();?>/company/contact-us/"><img src="<?php echo site_url();?>/wp-content/themes/meris/images/phone-icon-orange.png"></a>
	</div>
	<button class="site-nav-toggle">
		<span class="sr-only"><?php _e( 'Toggle navigation', 'meris' );?></span>
		<i class="fa fa-bars fa-2x"></i>
	</button>
	<nav class="site-nav" role="navigation">
		<?php
		wp_nav_menu ( array (
				'theme_location' => 'menu',
				'menu' => 'main',
				'depth' => 0,
				'fallback_cb' => false,
				'container' => '',
				'container_class' => 'main-menu',
				'menu_id' => 'menu',
				'menu_class' => 'main-nav',
				'link_before' => '<span>',
				'link_after' => '</span>',
				'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>' 
		) );
		?>
	</nav>
	</div>
</div>
