<?php
/**
 * Template Name: Home page template
 *
 */
get_header ();
$cur_post_id = $post->ID;
?>

<link rel='stylesheet' href='<?php echo get_template_directory_uri(); ?>/css/slick.css' type='text/css' media='all' />
<link rel='stylesheet' href='<?php echo get_template_directory_uri(); ?>/css/slick-theme.css' type='text/css' media='all' />
<div class="container-fluid">
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="blog-list">
			<div class="row">
				<div class="col-md-4 col-sm-4">

					<div class="menu-bg-change">
						<button class="site-nav-toggle">
							<span class="sr-only"><?php _e( 'Toggle navigation', 'meris' );?></span>
							<i class="fa fa-bars fa-2x"></i>
						</button>
						<div class="home-icn index-icn">
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
						<nav class="site-nav col-lg-11 col-sm-10" role="navigation">
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


					<div class="logo-div">
							<a href="<?php echo esc_url(home_url('/')); ?>"> <img
								src="<?php echo esc_url(meris_options_array('logo')); ?>"
								class="site-logo" alt="<?php bloginfo('name'); ?>" />
							</a>
						</div>

					<div class="left-side">
				<?php if ( meris_options_array('logo')!="") { ?>
				
		        <?php } ?>
						<div class="content-div">
							<?php if(get_field('left_section_title') != ''){ ?>
							<h3><?php echo get_field('left_section_title'); ?></h3>
							<?php } ?>
							<?php echo get_field('left_section_detailed_content'); ?>
							
							
						</div>

						<div class="video-section">
						<h3><a href="<?php echo site_url();?>/company/real-adform-experiences/">Testimonials</a></h3>
						 	<ul id="nt-example1">
						 	<?php
							   $args = array(
					   				'post_type' => 'pra_testimonials',
							      	'post_status' => 'publish',							      								      	
							   	);
							   $slider_posts = new WP_Query($args);
								if($slider_posts->have_posts()) : while($slider_posts->have_posts()) : $slider_posts->the_post() ?>
						       	<li>
						       		<div class="test-content"><?php the_content();?></div><div class="test-title"><a href="<?php echo site_url();?>/company/real-adform-experiences/"><?php the_title();?></a></div>
						       </li>
							   <?php endwhile; endif; ?>			                   
		                	</ul>
						</div>
						
						<!--<div class="video-section">
								<?php
									if(get_field('left_section_video_poster_image',$cur_post_id) != ''){
										?>
											<a class="various fancybox.iframe" href="<?php echo get_field('video_url',$cur_post_id); ?>"><img src="<?php echo get_field('left_section_video_poster_image',$cur_post_id); ?>" alt="" /></a>
										<?php 										
									
									} 
								?>
						</div>
						<div class="video-section">
							<ul class="homesidebarlinks">
							<?php
								if(get_field('email_id') != ''){
									?>
										<li><a href="mailto:<?php echo get_field('email_id'); ?>"><?php echo get_field('email_us_text'); ?></a></li>
									<?php 										
								}

								if(get_field('quick_track_link') != ''){
									?>
										<li><a href="<?php echo get_field('quick_track_link'); ?>" target="_blank"><?php echo get_field('quick_track_text'); ?></a></li>
									<?php 										
								}  
							?>
							</ul>
						</div>	-->
					</div>
				</div>
				
				<script type="text/javascript">
					jQuery(document).ready(function(){
						if(jQuery(window).width() > 1024){
							jQuery('.child_submenu').hide();
							
							
							jQuery('.commen-hover-div').hover(function () {	
													
							if (jQuery(this).find('.child_submenu').length > 0) {
								jQuery(this).find('.child_submenu').stop().slideDown('slow');
								jQuery(this).find('.child_submenu li a').css("background","none");
								//jQuery(this).find(' > a').animate({ marginTop: '-50px'});

								jQuery(this).find('li:first > a').css("margin-top","-50px");
								jQuery(this).find('li:first > a#company-title').css("margin-left","-32px");
								jQuery(this).find('li:first > a#branding-title').css("margin-left","-21px");
								jQuery(this).find('#creative-louge-span').css("margin-right","145px");
								jQuery(this).find('#customer-span').css("margin-right","106px");
							}

							},function () {
								if (jQuery(this).find('.child_submenu').length > 0) {
									jQuery(this).find('.child_submenu').stop().slideUp('slow');
									jQuery(this).find('.child_submenu li a').css("background","none");
									
									//jQuery(this).find(' > a').animate({ marginTop: '0px'});

									jQuery(this).find('li:first > a').css("margin-top","0px");
									jQuery(this).find('li:first > a').css("margin-left","0px");
								}
							});
							
							
							/*
							jQuery('.hover_title').hover(function () {	
													
							if (jQuery(this).find('.child_submenu').length > 0) {
								jQuery(this).find('.child_submenu').stop().slideDown('slow');
								jQuery(this).find('.child_submenu li a').css("background","none");
								//jQuery(this).find(' > a').animate({ marginTop: '-50px'});

								jQuery(this).find(' > a').css("margin-top","-50px");
								jQuery(this).find(' > a#company-title').css("margin-left","-32px");
								jQuery(this).find(' > a#branding-title').css("margin-left","-21px");
								jQuery(this).find(' > a#creative-title > span').css("margin-left","15px");
								jQuery(this).find(' > a#customer-title > span').css("margin-right","106px");
							}

							},function () {
								if (jQuery(this).find('.child_submenu').length > 0) {
									jQuery(this).find('.child_submenu').stop().slideUp('slow');
									jQuery(this).find('.child_submenu li a').css("background","none");
									
									//jQuery(this).find(' > a').animate({ marginTop: '0px'});

									jQuery(this).find(' > a').css("margin-top","0px");
									jQuery(this).find(' > a').css("margin-left","0px");
								}
							});*/
						}
						jQuery(window).resize(function(){
							if(jQuery(window).width() > 1024){
								jQuery('.child_submenu').hide();
								jQuery('.commen-hover-div').hover(function () {	
													
								if (jQuery(this).find('.child_submenu').length > 0) {
									jQuery(this).find('.child_submenu').stop().slideDown('slow');
									jQuery(this).find('.child_submenu li a').css("background","none");
									//jQuery(this).find(' > a').animate({ marginTop: '-50px'});

									jQuery(this).find('li:first > a').css("margin-top","-50px");
									jQuery(this).find('li:first > a#company-title').css("margin-left","-32px");
									jQuery(this).find('li:first > a#branding-title').css("margin-left","-21px");
									jQuery(this).find('#creative-louge-span').css("margin-right","145px");
									jQuery(this).find('#customer-span').css("margin-right","106px");
								}

								},function () {
									if (jQuery(this).find('.child_submenu').length > 0) {
										jQuery(this).find('.child_submenu').stop().slideUp('slow');
										jQuery(this).find('.child_submenu li a').css("background","none");
										
										//jQuery(this).find(' > a').animate({ marginTop: '0px'});

										jQuery(this).find('li:first > a').css("margin-top","0px");
										jQuery(this).find('li:first > a').css("margin-left","0px");
									}
								});
							}else{
								jQuery('.child_submenu').hide();
								jQuery('.commen-hover-div').hover(function (e) {
								  jQuery('.child_submenu').hide();
								  jQuery(this).find('li:first > a').stop();
								 
								},function(){
									jQuery('.child_submenu').hide();
									jQuery(this).find('li:first > a').stop();
								
								});
							}
						});
					});
				</script>
				<div class="col-md-8 col-sm-8 padding-none">
					<div class="homepage-right-section">
						<div class="company-div commen-hover-div">
						<span class="righ-bg-image"><img src="<?php echo get_template_directory_uri();?>/images/company-icon.png" alt="Company" height="60" width="60"/></span>
							<h4>
								
								<li class="hover_title"><a href="<?php echo get_permalink(6); ?>" id="company-title">Company</a>
								
									<?php
									$args = array(
									    'post_type'      => 'page',
									    'posts_per_page' => -1,
									    'post_parent'    => 6,
									    'order'          => 'ASC',
									    'orderby'        => 'menu_order'
									 );
									$parent = new WP_Query( $args );

									if ( $parent->have_posts() ) : while ( $parent->have_posts() ) : $parent->the_post(); 
									$menulist[] = array('link'=> get_the_permalink(),'title'=> get_the_title());
									/*
									?>
								        <li class="home-company-<?php echo $post->ID?>">
								            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
								        </li>
								    <?php 
									*/

								    endwhile; endif; wp_reset_query(); 
								    echo '<span><ul class="child_submenu company-first">';
									$totallinks = round(count($menulist)/2);
									for($i = 0 ;$i<$totallinks; $i++){								    
								    ?>
								    	<li class="home-company-<?php echo $post->ID?>">
								            <a href="<?php echo $menulist[$i]['link']; ?>" title="<?php echo $menulist[$i]['title']; ?>"><?php echo $menulist[$i]['title']; ?></a>
								        </li>
							     	<?php
									}
									echo '</ul>';

									echo '<ul class="child_submenu company-second">';
									for($i = $totallinks ;$i<count($menulist); $i++){
										?>
								        <li>
								            <a href="<?php echo $menulist[$i]['link']; ?>" title="<?php echo $menulist[$i]['title']; ?>"><?php echo $menulist[$i]['title']; ?></a>
								        </li>
								    <?php
									}
									echo '</ul></span>';
									?>
							    </ul>
							    </li>
							</h4>
						</div>
						<div class="capabilities-div commen-hover-div">
							<span class="righ-bg-image"><img src="<?php echo get_template_directory_uri();?>/images/capabilities-icon.png"  height="60" width="60"/></span>
							<h4>
								<li class="hover_title"><a href="<?php echo get_permalink(8); ?>"  id="capabilities-title">Capabilities</a>
								<!--<ul class="child_submenu">
									<?php/*
									$args = array(
									    'post_type'      => 'page',
									    'posts_per_page' => -1,
									    'post_parent'    => 8,
									    'order'          => 'ASC',
									    'orderby'        => 'menu_order'
									 );
									$parent = new WP_Query( $args );

									if ( $parent->have_posts() ) : while ( $parent->have_posts() ) : $parent->the_post(); ?>
								        <li>
								            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
								        </li>
								    <?php endwhile; endif; wp_reset_query(); */?>
							    </ul>-->
							    </li>
							</h4>
						</div>
						<div class="branding-div commen-hover-div">
						<span class="righ-bg-image"><img src="<?php echo get_template_directory_uri();?>/images/branding-icon.png"  height="60" width="60" /></span>
							<h4>
								<li class=""><a href="<?php echo get_permalink(29); ?>"  id="branding-title">Branding Tools</a>
								
									<?php
									$args = array(
									    'post_type'      => 'page',
									    'posts_per_page' => -1,
									    'post_parent'    => 29,
									    'order'          => 'ASC',
									    'orderby'        => 'menu_order'
									 );
									$parent = new WP_Query( $args );

									if ( $parent->have_posts() ) : while ( $parent->have_posts() ) : $parent->the_post(); 
									$menulist1[] = array('link'=> get_the_permalink(),'title'=> get_the_title());
									/*
									?>
								        <li class="home-tools-<?php echo $post->ID?>">
								            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
								        </li>
								    <?php 
									*/
								    endwhile; endif; wp_reset_query(); 

								    echo '<span><ul class="child_submenu tools-first">';
									$totallinks1 = round(count($menulist1)/2);
									for($i = 0 ;$i<$totallinks1; $i++){
										?>
								        <li>
								            <a href="<?php echo $menulist1[$i]['link']; ?>" title="<?php echo $menulist1[$i]['title']; ?>"><?php echo $menulist1[$i]['title']; ?></a>
								        </li>
								    <?php
									}
									echo '</ul>';
									echo '<ul class="child_submenu tools-second">';
									for($i = $totallinks1 ;$i<count($menulist1); $i++){
										?>
								        <li>
								            <a href="<?php echo $menulist1[$i]['link']; ?>" title="<?php echo $menulist1[$i]['title']; ?>"><?php echo $menulist1[$i]['title']; ?></a>
								        </li>
								    <?php
									}
									echo '</ul></span>';
								    ?>
							    
							    </li>
							</h4>
						</div>
						<div class="lounge-div commen-hover-div">
						<span class="righ-bg-image"><img src="<?php echo get_template_directory_uri();?>/images/creative-lounge.png"  height="52" width="58" /></span>
						<h4>
							<li class="hover_title"><a href="<?php echo get_permalink(32); ?>"  id="creative-title">Creative Lounge</a>
							
								<?php
								$args = array(
								    'post_type'      => 'page',
								    'posts_per_page' => -1,
								    'post_parent'    => 32,
								    'order'          => 'ASC',
								    'orderby'        => 'menu_order'
								 );
								$parent = new WP_Query( $args );

								if ( $parent->have_posts() ) : while ( $parent->have_posts() ) : $parent->the_post(); 
								$menulist2[] = array('link'=> get_the_permalink(),'title'=> get_the_title());

								/*
								?>
							        <li class="home-lounge-<?php echo $post->ID?>">
							            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
							        </li>
							    <?php */ 

							    endwhile; endif; wp_reset_query(); 

							    echo '<span id="creative-louge-span"><ul class="child_submenu lounge-first">';
								$totallinks2 = round(count($menulist2)/2);
								for($i = 0 ;$i<$totallinks2; $i++){
									?>
							        <li>
							            <a href="<?php echo $menulist2[$i]['link']; ?>" title="<?php echo $menulist2[$i]['title']; ?>"><?php echo $menulist2[$i]['title']; ?></a>
							        </li>
							    <?php
								}
								echo '</ul>';
								echo '<ul class="child_submenu lounge-second">';
								for($i = $totallinks2 ;$i<count($menulist2); $i++){
									?>
							        <li>
							            <a href="<?php echo $menulist2[$i]['link']; ?>" title="<?php echo $menulist2[$i]['title']; ?>"><?php echo $menulist2[$i]['title']; ?></a>
							        </li>
							    <?php
								}
								echo '</ul></span>';
							    ?>
						  
						    </li>
						</h4>
						
						<!--<ul class="subpageslist">
							<?php wp_list_pages('title_li=&child_of=32'); ?>
						</ul>-->
											
						</div>
						<div class="customer-div commen-hover-div">
							<span class="righ-bg-image"><img src="<?php echo get_template_directory_uri();?>/images/customer.png" height="60" width="60" /></span>
							<h4>
								<li class="hover_title"><a href="<?php echo get_permalink(44); ?>" id="customer-title">Customer Care</a>
								
									<?php
									$args = array(
										'post_type'      => 'page',
										'posts_per_page' => -1,
										'post_parent'    => 44,
										'order'          => 'ASC',
										'orderby'        => 'menu_order'
									 );
									$parent = new WP_Query( $args );

									if ( $parent->have_posts() ) : while ( $parent->have_posts() ) : $parent->the_post(); 
									$menulist3[] = array('link'=> get_the_permalink(),'title'=> get_the_title());
									/*
									?>
										<li class="home-care-<?php echo $post->ID?>">
											<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
										</li>
									<?php */

									endwhile; endif; wp_reset_query(); 

									echo '<span id="customer-span"><ul class="child_submenu care-first">';
									$totallinks3 = round(count($menulist3)/2);
									for($i = 0 ;$i<$totallinks3; $i++){
										?>
								        <li>
								            <a href="<?php echo $menulist3[$i]['link']; ?>" title="<?php echo $menulist3[$i]['title']; ?>"><?php echo $menulist3[$i]['title']; ?></a>
								        </li>
								    <?php
									}
									echo '</ul>';
									echo '<ul class="child_submenu care-second">';
									for($i = $totallinks3 ;$i<count($menulist3); $i++){
										?>
								        <li>
								            <a href="<?php echo $menulist3[$i]['link']; ?>" title="<?php echo $menulist3[$i]['title']; ?>"><?php echo $menulist3[$i]['title']; ?></a>
								        </li>
								    <?php
									}
									echo '</ul></span>';
									?>
								
								</li>
							</h4>
						</div>
					</div>
				
				<?php
				// $multipleImages = $cfs->get('banners_loop');
				// foreach($multipleImages as $img){
				// echo "<div class='right-images'><img src='".$img['image']."' alt='' title='' class='img-responsive' /></div>";
				// }
				?>
			</div>
			<div id="mobile-only-content">
				
					<div class="col-md-4"></div>
				
			</div>
				<div class="partner-div text-center">
					<div class="col-md-12">
						<?php /* ?><div class="show-banner-desktop">
							<div class='col-md-1 col-sm-1'>&nbsp;</div>
							<?php
							
							$multipleImages1 = $cfs->get ( 'partners_logos' );
							foreach ( $multipleImages1 as $img ) {
								echo "<div class='col-md-2 col-sm-2'><img src='" . $img ['partner_image'] . "' alt='' title='' class='img-responsive' /></div>";
							}
							?>
							<div class='col-md-1 col-sm-1'>&nbsp;</div>
						</div><?php */ ?>
						<div class="show-banner-mobile">
							<div class="bottombanner">
							<?php
							/*
							$multipleImages1 = $cfs->get ( 'partners_logos' );
							foreach ( $multipleImages1 as $img ) {
								echo "<div class='partner-mobile'><a href='".site_url()."/company/clients/'><img src='" . $img ['partner_image'] . "' alt='' title='' class='img-responsive grayscale' /></a></div>";
							}*/

							$args = array(
								'post_status' => 'publish',
							    'post_type' => 'partner-logos',
							    'orderby' => 'title',
								'order'   => 'ASC',
							    //'paged' => $paged,
								//'posts_per_page' => 30,   
							);

							$wp_query = new WP_Query();
							$wp_query->query($args);
							if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();
							$imgPath = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID, 'full'));											
							?>
								
								<div class='partner-mobile'>
									<a href="<?php echo site_url();?>/company/clients">
										
										

										<?php the_post_thumbnail('post-thumbnail', array( 'class'	=> "img-responsive grayscale")); ?>								
									</a>
								</div>

							<?php endwhile; endif; ?>	
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>
