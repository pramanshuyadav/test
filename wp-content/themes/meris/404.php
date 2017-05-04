<?php
/**
 * The Template for displaying 404 Page not Found .
 *
 * @since meris 1.0.0
 */

get_header(); 
$pageColor = $cfs->get('page_main_color');
$colorStyle = $pageColor == ''?"":"style='color:$pageColor'";

if($pageColor != ''){
	$submenuColor = substr($pageColor,1);
	$submenuColorClass = "class-$submenuColor";
}
?>
<div class="inner-page">
<?php
wp_nav_menu ( array (
		'theme_location' => 'leftsidebar',
		'menu' => 'leftsidebar',
		'depth' => 0,
		'fallback_cb' => false,
		'container' => '',
		'container_class' => '' 
) );
?>
</div>
<div class="container-fluid">
	<div id="post-<?php the_ID(); ?>" class="type-page">
		<div class="blog-list">
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						
						<div class="">
						<div class="inner-page1">
							<div class="">
						<?php get_template_part( "innerpage", "header" ); ?>
						 <div class="col-md-12">
									<div class="row">
										<div class="col-md-8 col-sm-7">
								 		<?php
											$currentPageId = $post->ID;
											$parents = get_post_ancestors ( $post->ID );
											$pageParentId = ($parents) ? $parents [count ( $parents ) - 1] : $post->ID;
											$parent = get_post ( $pageParentId );
											$mobileBackgroundImage = get_field('heading_background_image_for_small_devices',$pageParentId);
											$mobileBackgroundImageColor = get_field('heading_background_image_color_for_small_devices',$pageParentId);
											
											
											?>
						 				<div class="page-main-heading">
												<h1 <?php echo $colorStyle; ?>><?php echo $parent-> post_title; ?></h1>
												<h2>Oh no! Just what we all dread the most!</h2>
											</div>
										</div>
										<div class="col-md-4 col-sm-5 col-xs-7">
									 		<?php if ( meris_options_array('logo')!="") { ?>
												<div class="logo-div innerpage-logo">
												<a href="<?php echo esc_url(home_url('/')); ?>"> <img
													src="<?php echo esc_url(meris_options_array('logo')); ?>"
													class="site-logo" alt="<?php bloginfo('name'); ?>" />
												</a>
											</div>
										     <?php } ?>
									 </div>
									</div>
								</div>
								<div class="mobile-text col-xs-12"><h2>Oh no! Just what we all dread the most!</h2></div>				
							<div class="col-md-12 gallery-padding inner-page-padding">
								<div class="main-content">
									<div class="row">
										<div class="col-md-8 col-sm-12 col-xs-12 not-found">
											<?php   wp_reset_query(); 
											$page_404_content = meris_options_array('page_404_content');
											//echo $page_404_content ; 
											?>
											<img src="<?php echo site_url();?>/wp-content/themes/meris/images/not-found.png">
											<p>The page you are looking for is gone, either that or it doesn't exist!<br> Here are some options to help get you back on track.</p>

											<ul class="col-md-12 col-sm-12 col-xs-12 no-padding">
												<li class="blue"><a href="<?php echo site_url();?>">Go Home</a></li>
												<li class="orange"><a href="<?php echo site_url();?>/creative-lounge/">Creative Lounge</a></li>
												<li class="green"><a href="<?php echo site_url();?>/company/contact-us/">Contact Us</a></li>
												<li class="s-blue"><a href="#">Report an Error</a></li>
											</ul>
										</div>
									</div>
									
								</div>
							</div>
							</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>