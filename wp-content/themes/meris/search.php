<?php
/**
 * The search results template file.
 *
 * @since meris 1.0.0
 */

get_header(); 
$pageColor = $cfs->get('page_main_color');
$colorStyle = $pageColor == ''?"":"style='color:$pageColor'";
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
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="blog-list">
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						
						<div class="col-md-12">
						<div class="inner-page1">
							<div class="row">
						<?php get_template_part( "innerpage", "header" ); ?>
						 <div class="col-md-12">
									<div class="row">
										<div class="col-md-9">
								 		<div class="page-main-heading">
												<h1 <?php echo $colorStyle; ?>>Search Result</h1>
											</div>
										</div>
										<div class="col-md-3 col-xs-7">
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
							
							<div class="col-md-12">
								<div class="main-content">
									<div class="row">
										<div class="col-md-12">
											<?php if (have_posts()) :?>
												<div class="blog-list-wrap">
												<?php while ( have_posts() ) : the_post(); 
													get_template_part("content","article");
												?>
												<?php endwhile;?>
												</div>
												<?php else:?>
												<div class="blog-list-wrap">
													<?php _e("No results found","meris");?>
													
												</div>

												<?php endif;?>
											<div class="list-pagition text-center">
												<?php if(function_exists("meris_native_pagenavi")){meris_native_pagenavi("echo",$wp_query);}?>
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
</div>

<?php get_footer(); ?>