<?php 
/* 
Template Name: White Paper
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
										<div class="col-md-9 col-sm-9">
								 		<?php
											$currentPageId = $post->ID;
											$parents = get_post_ancestors ( $post->ID );
											$pageParentId = ($parents) ? $parents [count ( $parents ) - 1] : $post->ID;
											$parent = get_post ( $pageParentId );
											$mobileBackgroundImage = get_field('heading_background_image_for_small_devices',$pageParentId);
											$mobileBackgroundImageColor = get_field('heading_background_image_color_for_small_devices',$pageParentId);
											
											$args = array(
											'post_type' => 'page',
											'post_status' => 'publish',
											'post_parent' => $pageParentId, // any parent
											'numberposts' => -1,
											'orderby'=> 'menu_order',
											);
											
											$attachments = get_posts($args);
											
											if ($attachments) {
												foreach ($attachments as $post) {
													$subpagesDropdown[$post->ID]['id'] = $post->ID;
													$subpagesDropdown[$post->ID]['link'] = get_permalink();
													$subpagesDropdown[$post->ID]['title'] = get_the_title();
												}
											}
											?>
						 				<div class="page-main-heading">
												<h1 <?php echo $colorStyle; ?>><?php echo $parent-> post_title; ?></h1>
											</div>
										</div>
										<div class="col-md-3 col-sm-3 col-xs-7">
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
							<div class="col-md-12 gallery-padding">
								<div class="submenu-pages <?php echo $submenuColorClass; ?>" >
									<?php   wp_list_pages("title_li=&child_of=$pageParentId&sort_column=menu_order"); ?>
								</div>
								
								<div class="mobile-banner-image mobile-gellary-title" style="background-image:url('<?php echo $mobileBackgroundImage; ?>');background-color:<?php echo $mobileBackgroundImageColor; ?>"><h1><?php echo $parent-> post_title; ?></h1></div>
								<div class="submenu-pages-dropdown">
									<select id="changepage">
									<option value="">Select</option>
										<?php
											foreach($subpagesDropdown as $k=>$v){
												?>
													<option <?php echo $v['id']== $currentPageId? 'selected':''; ?> value="<?php echo $v['link'] ; ?>" ><?php echo $v['title']; ?></option>
												<?php
											}
										?>
									</select>
								</div>
							</div>
							<div class="col-md-12 ">
								<div class="main-content orange-color">
									<div class="col-md-12">

										<?php
											$paged = get_query_var('paged') ? get_query_var('paged') : 1;
											$args = array(
												'post_status' => 'publish',
											    'post_type' => 'post',
											    'category_name' => 'white-papers',
											    'paged' => $paged,
												'posts_per_page' => 2,   
											);

											$wp_query = new WP_Query();
											$wp_query->query($args);
											if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();
										?>

										<div class="col-md-7 col-sm-12 col-xs-12 blog-pandding">
											<h3 class="orange-color"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
											<?php 
												$content = get_the_content();
												$content = apply_filters('the_content', $content );
												echo substr($content,0,450);
												if( $content != '' ){ ?>
													<a href="<?php echo the_permalink();?>">...Read More</a>
												<?php }
											?>
										</div>
										<div class="col-md-5 col-sm-12 col-xs-12 blog-pandding">
											<?php echo get_field('right_section_slider_1');?>
										</div>

										<?php endwhile; endif;?>

										<div class="col-md-12 col-sm-12 col-xs-12">
											<?php 												
												wp_pagenavi( array( 'query' => $wp_query ) );
												wp_reset_postdata();
											?>
										</div>
									</div>

									<div class="col-md-5">

										<?php  //wp_reset_query();?>
																				
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
