<?php
/**
 * The Template for displaying single post.
 *
 * @since meris 1.0.0
 */

get_header(); 

$category = get_the_category($post->ID);
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

	<?php if (have_posts()) :?>
	<?php	while ( have_posts() ) : the_post();
    $meris_sidebar          = get_post_meta( $post->ID, '_meris_layout', true );
	$meris_sidebar      = $meris_sidebar==""?"right":$meris_sidebar;
	$column_class_one   = ""; 
	$column_class_two   = ""; 
	$column_class_three = ""; 
	switch($meris_sidebar){
		case "left":
		$column_class_one   = "col-md-9 col-md-push-3"; 
		$column_class_two   = "col-md-3 col-md-pull-9"; 
		$column_class_three = ""; 
		break;
		case "right":
		$column_class_one   = "col-md-9"; 
		$column_class_two   = ""; 
		$column_class_three = "col-md-3";
		break;
		case "both":
		$column_class_one   = "col-md-6 col-md-push-3"; 
		$column_class_two   = "col-md-3 col-md-pull-6"; 
		$column_class_three = "col-md-3"; 
		break;
		case "none":
		$column_class_one   = "col-md-12"; 
		$column_class_two   = ""; 
		$column_class_three = "";
		break;
		default:
		$column_class_one   = "col-md-9"; 
		$column_class_two   = ""; 
		$column_class_three = "col-md-3";
		break;
		
	}
	?>
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
								 			$parent = get_post (32);									

											$args = array(
											'post_type' => 'page',
											'post_status' => 'publish',
											'post_parent' => 32, // any parent
											'numberposts' => -1,
											'orderby'=> 'menu_order',
											);
											
											$attachments = get_posts($args);																					
											?>
						 				<div class="page-main-heading">
												<h1 style="color:#fc4f00;"><?php echo $parent->post_title; ?></h1>
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

								<div class="col-md-12">
									<div class="row">
									<div class="submenu-pages single-post-page" >
										<?php   wp_list_pages("title_li=&child_of=32&sort_column=menu_order"); ?>
									</div>

										<section class="text-center" role="main">											
											<article class="post-entry text-left">
												<div class="<?php if($category[0]->category_nicename == 'blog-news'){ ?>col-md-12<?php } else { ?>col-md-8<?php } ?> col-sm-12 col-xs-12">																																		
													<div class="entry-main">
														<div class="entry-header">
															<h3><?php the_title();?></h3>
				                                        <div class="entry-meta">

				                                        <div class="entry-date">
															<div class="day"><?php echo get_the_time('d');?></div>
															<div class="month"><?php echo get_the_time("M Y"); ?></div>
														</div>

														<div class="entry-author"><i class="fa fa-user"></i><?php echo get_the_author_link();?></div> 
														<div class="entry-category"><i class="fa fa-file-o"></i><?php the_category(', '); ?></div>
														<div class="entry-comments"><i class="fa fa-comments"></i><?php  comments_popup_link( __('No comments yet','meris'), __('1 comment','meris'), __('% comments','meris'), 'comments-link', __('No comment','meris'));?></div>
					                                    <?php edit_post_link( __('Edit','meris'), '<div class="entry-edit"><i class="fa fa-pencil"></i>', '</div>', get_the_ID() ); ?> 					                                    
														</div>
					                                
														</div>
														<div class="entry-content">
														<?php 
															the_content();

														if($category[0]->category_nicename == 'blog-news')
														{ 
															echo the_post_thumbnail(array(394, 214)); 
														}
														else
														{ 
															echo get_field('right_section_slider_1');
													 	}
														?>
														</div>
					                                    <?php  wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'meris' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );?>
													</div>
												</div>
												<div class="col-md-4 col-sm-12 col-xs-12">
													<?php
													if($category[0]->category_nicename != 'blog-news' && $category[0]->category_nicename != 'case-studies')
													{ ?>	
														<h4>Fill the form to download</h4>
													<?php } ?>

													<?php
													if($category[0]->category_nicename == 'white-papers')
													{													
														echo do_shortcode('[email-download download_id="2" contact_form_id="449"]');
													}

													if($category[0]->category_nicename == 'standard-product-presentations')
													{													
														echo do_shortcode('[email-download download_id="3" contact_form_id="426"]');
													}

													if($category[0]->category_nicename == 'case-studies')
													{	?>													
														<div class="case">
															<div class="casecontact">
															<?php
																//echo do_shortcode('[email-download download_id="1" contact_form_id="451"]');
																echo do_shortcode('[contact-form-7 id="451" title="Case Studies"]');
															?>
															</div>
														</div>
														<div id="case-study-pdf" style="display:none;">
															<?php $field = get_field('pdf_url', $post->ID); 
															if($field != ''){
															?>
															<div style="display: inline;" id="wpm_download_2">
																<a href="<?php echo $field; ?>" target="_blank" class="icon-button download-icon">
																	<span class="et-icon">
																		<span>Download Casestudy</span>
																	</span>
																</a>
																<br>
															</div>			
															<?php } ?>									
														</div>
													<?php
													}
													?>
												</div>												
											</article>
							
											<?php
											if($category[0]->category_nicename != 'blog-news')
											{ ?>	
												<div class="comments-area text-left col-md-8 col-sm-12 col-xs-12">
											<?php } else {?>
												<div class="comments-area text-left col-md-12 col-sm-12 col-xs-12">
											<?php } ?>

	                       					<?php
												echo '<div class="comment-wrapper">';
												comments_template(); 
												echo '</div>';
											?>                             
	                        				</div>
										</section>
									</div>
								</div>
							</div>
						</div>                   

					 	<?php if($meris_sidebar == "left" || $meris_sidebar == "both"){?>
							<div class="<?php echo $column_class_two;?>">
								<aside class="blog-side left text-left">
									<div class="widget-area">
											<?php meris_get_sidebar("post_left_sidebar");?>
									</div>
								</aside>
							</div>
	                     <?php }?>
	                    <?php if($meris_sidebar == "right" || $meris_sidebar == "both"){?>
							<div class="<?php echo $column_class_three;?>">
								<aside class="blog-side right text-left">
									<div class="widget-area">
								<?php meris_get_sidebar("post_right_sidebar");?>
									</div>
								</aside>
							</div>
	                    <?php }?>
					</div>			
				</div>
			</div>
		</div>
	</div>
<?php endwhile;?>
<?php endif;?>
</div>
</div>
<?php get_footer(); ?>