<?php
/*
Plugin Name: OS BXSlider
Plugin URI: http://offshorent.com/blog/extensions/os-bxslider
Description: Creates a responsive slider using bxslider. WordPress plugin develop by Offshorent Softwares Pvt Ltd.
Version: 2.4
Author: Jinesh.P.V, Offshorent Softwares Pvt Ltd.
Author URI: http://offshorent.com/
License: GPL2
/*  Copyright 2015-2016  OS BxSlider - Offshorent Softwares Pvt Ltd  ( email: jinesh@offshorent.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'osBxSlider' ) ) :
    
    /**
    * Main osBxSlider Class
    *
    * @class osBxSlider
    * @version	2.4
    */

	    final class osBxSlider {
	    
		/**
		* @var string
		* @since	2.4
		*/
		 
		public $version = '2.4';

		/**
		* @var osBxSlider The single instance of the class
		* @since 2.4
		*/
		 
		protected static $_instance = null;

		/**
		* Main osBxSlider Instance
		*
		* Ensures only one instance of osBxSlider is loaded or can be loaded.
		*
		* @since 2.4
		* @static
		* @see OSBX()
		* @return osBxSlider - Main instance
		*/
		 
		public static function init_instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
		}

		/**
		* Cloning is forbidden.
		*
		* @since 2.4
		*/

		public function __clone() {
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'os-bxslider' ), '2.4' );
		}

		/**
		* Unserializing instances of this class is forbidden.
		*
		* @since 2.4
		*/
		 
		public function __wakeup() {
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'os-bxslider' ), '2.4' );
		}
	        
		/**
		* Get the plugin url.
		*
		* @since 2.4
		*/

		public function plugin_url() {
            return untrailingslashit( plugins_url( '/', __FILE__ ) );
		}

		/**
		* Get the plugin path.
		*
		* @since 2.4
		*/

		public function plugin_path() {
            return untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

		/**
		* Get Ajax URL.
		*
		* @since 2.4
		*/

		public function ajax_url() {
            return admin_url( 'admin-ajax.php', 'relative' );
		}
	        
		/**
		* osBxSlider Constructor.
		* @access public
		* @return osBxSlider
		* @since 2.4
		*/
		 
		public function __construct() {
			
            register_activation_hook( __FILE__, array( &$this, 'os_bxslider_install' ) );

            // Define constants
            self::os_bxslider_constants();

            // Include required files
            self::os_bxslider_admin_includes();

            // Action Hooks
            add_action( 'init', array( $this, 'os_bxslider_init' ), 0 );
            add_action( 'admin_init', array( $this, 'os_bxslider_admin_init' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'os_bxslider_frontend_styles_scrips' ) );

            // Shortcode Hooks
            add_shortcode( 'os-bxslider', array( $this, 'os_bxslider_shortcode' ) );

            //Filter Hook
            add_filter( 'widget_text', 'do_shortcode', 11 );
		}
	        
		/**
		* Install osBxSlider
		* @since 2.4
		*/
		 
		public function os_bxslider_install (){
			
            // Flush rules after install
            flush_rewrite_rules();

            // Redirect to welcome screen
            set_transient( '_OSBX_activation_redirect', 1, 60 * 60 );
		}
	        
		/**
		* Define osBxSlider Constants
		* @since 2.4
		*/
		 
		private function os_bxslider_constants() {
			
			define( 'OSBX_PLUGIN_FILE', __FILE__ );
			define( 'OSBX_PLUGIN_BASENAME', plugin_basename( dirname( __FILE__ ) ) );
			define( 'OSBX_PLUGIN_URL', plugins_url() . '/' . OSBX_PLUGIN_BASENAME );
			define( 'OSBX_VERSION', $this->version );
			define( 'OSBX_TEXT_DOMAIN', 'os-bxslider' );
			define( 'OSBX_PERMALINK_STRUCTURE', get_option( 'permalink_struture' ) ? '&' : '?' );
			
		}
	        
		/**
		* includes admin defaults files
		*
		* @since 2.4
		*/
		 
		private function os_bxslider_admin_includes() {
			
            include_once( 'includes/admin/os-bxslider-post-types.php' );
		}
	        
		/**
		* Init osBxSlider when WordPress Initialises.
		* @since 2.4
		*/
		 
		public function os_bxslider_init() {
	            
            self::os_bxslider_do_output_buffer();
		}
	        
		/**
		* Clean all output buffers
		*
		* @since  2.4
		*/
		 
		public function os_bxslider_do_output_buffer() {
	            
            ob_start( array( &$this, "os_bxslider_do_output_buffer_callback" ) );
		}

		/**
		* Callback function
		*
		* @since  2.4
		*/
		 
		public function os_bxslider_do_output_buffer_callback( $buffer ){
            return $buffer;
		}
		
		/**
		* Clean all output buffers
		*
		* @since  2.4
		*/
		 
		public function os_bxslider_flush_ob_end(){
            ob_end_flush();
		}
	        
		/**
		* Admin init osBxSlider when WordPress Initialises.
		* @since  2.4
		*/
		 
		public function os_bxslider_admin_init() {
				
            self::os_bxslider_admin_styles_scrips();
		}
	        
		/**
		* Admin side style and javascript hook for osBxSlider
		*
		* @since  2.4
		*/
		 
		public function os_bxslider_admin_styles_scrips() {
            
            wp_enqueue_script( 'jquery-ui-sortable' );            
            wp_enqueue_style( 'os-admin-style', plugins_url( 'css/admin/style-min.css', __FILE__ ) );
            wp_enqueue_script( 'os-custom-min', plugins_url( 'js/admin/custom-min.js', __FILE__ ), array(), '2.4', true );
		}

		/**
		* Frontend style and javascript hook for osBxSlider
		*
		* @since  2.4
		*/
		 
		public function os_bxslider_frontend_styles_scrips() {
	                      
            wp_enqueue_style( 'os-bxslider-style', plugins_url( 'plugins/bxslider/jquery.bxslider.css', __FILE__ ) );
            wp_enqueue_style( 'os-frontend-style', plugins_url( 'css/style.css', __FILE__ ) );
            wp_enqueue_script( 'os-bxslider-min', plugins_url( 'plugins/bxslider/jquery.bxslider.min.js', __FILE__ ), array(), '2.4', true );
            wp_enqueue_script( 'os-frontend-script', plugins_url( 'js/frontend-min.js', __FILE__ ), array(), '2.4', true );         
		} 

		/**
		* Add Custom style for each slider
		*
		* @since  2.4
		*/		 

		public function os_bxslider_custom_css( $slider_id ) {

			$post_type = new osBxSliderPostTypes();
			$slider_custom_meta = $post_type->os_bxslider_return_slider_custom_meta( $slider_id );

			$bgcolor = $slider_custom_meta['design']['bgcolor'];
			$pager_type = $slider_custom_meta['design']['pager-type'];			
			$pager_color = $slider_custom_meta['design']['pager-color'];
			$pager_hover_color = $slider_custom_meta['design']['pager-hover-color'];
			$next_button = $slider_custom_meta['design']['next-button'];
			$prev_button = $slider_custom_meta['design']['prev-button'];

			if( !empty( $prev_button ) && !empty( $next_button ) ) {
				list( $width, $height ) = getimagesize ( $next_button );
			}	
			
			$custom_css  = 	"<style>";

			if( !empty( $bgcolor ) ) {
				$custom_css .= ".os_slider_wrapper .bx-wrapper .bx-viewport{ 
									background:{$bgcolor};border: 5px solid {$bgcolor};
								}";
			}

			if( !empty( $pager_color ) ) {
				$custom_css  .=  ".os_slider_wrapper .bx-wrapper .bx-pager.bx-default-pager a {";
				if( $pager_type == 'square' ) {
					$custom_css .= "border-radius: 0; background: {$pager_color};border: none;";	
				} else { 
					$custom_css .= "border-radius: 5px; background: {$pager_color};border: none;";
				}
				$custom_css .= "}";
			}	
			
			if( !empty( $pager_hover_color ) ) {
				$custom_css .= ".os_slider_wrapper .bx-wrapper .bx-pager.bx-default-pager a:hover,
								.os_slider_wrapper .bx-wrapper .bx-pager.bx-default-pager a.active {
									background: {$pager_hover_color};
								}";
			}

			if( !empty( $prev_button ) && !empty( $next_button ) ) {
				$custom_css .= ".os_slider_wrapper .bx-wrapper .bx-controls-direction a {
									width: {$width}px;
									height : {$height}px;
									border: none;
								}";
			}

			if( !empty( $prev_button ) && !empty( $next_button ) ) {
				$custom_css .= ".os_slider_wrapper .bx-wrapper .bx-prev {
									background : url({$prev_button}) no-repeat scroll left top;
								}";
			}

			if( !empty( $prev_button ) && !empty( $next_button ) ) {				
				$custom_css .= ".os_slider_wrapper .bx-wrapper .bx-next {
									background : url({$next_button}) no-repeat scroll left top;
								}";
			}
			$custom_css .= "</style>";

			echo $custom_css;
		}

		/**
		* Shortcode function for os-bxslider
		*
		* @since  2.4
		*/
		 
		public function os_bxslider_shortcode( $atts ) {

			ob_start();

			// Extract os-bxslider shortcode

			$atts = shortcode_atts(
					array(
						'id' => '2'
					), $atts );
			$slider_id = $atts['id'];

			$general_options = $pager_options = $slider_type_options = $controls_options = $slider_options = $auto_options = '';

			$post_type = new osBxSliderPostTypes();
			self::os_bxslider_custom_css( $slider_id );
			$slider_custom_meta = $post_type->os_bxslider_return_slider_custom_meta( $slider_id ); 
			$slides = $slider_custom_meta['slides']; 
			$slider_type = $slider_custom_meta['slider_type'];

			// General 
			$mode = $slider_custom_meta['general']['slider-mode'];
			$speed = $slider_custom_meta['general']['slider-speed'];
			$margin = $slider_custom_meta['general']['slide-margin'];
			$start_slide = $slider_custom_meta['general']['start-slide'];
			$random_start = $slider_custom_meta['general']['random-start'];
			$infinite_loop = $slider_custom_meta['general']['infinite-loop'];
			$hide_control_on_end = $slider_custom_meta['general']['hide-control-on-end'];
			$adaptive_height = $slider_custom_meta['general']['adaptive-height'];
			$responsive = $slider_custom_meta['general']['responsive'];	

			// Pager 
			$pager = $slider_custom_meta['pager']['pager'];
			$type = $slider_custom_meta['pager']['type'];
			$short_separator = $slider_custom_meta['pager']['short-separator'];

			// Controls 
			$controls = $slider_custom_meta['controls']['controls'];
			$next_text = $slider_custom_meta['controls']['next-text'];
			$prev_text = $slider_custom_meta['controls']['prev-text'];
			$auto_controls = $slider_custom_meta['controls']['auto-controls'];
			$start_text = $slider_custom_meta['controls']['start-text'];
			$stop_text = $slider_custom_meta['controls']['stop-text'];

			// Auto 
			$auto = $slider_custom_meta['auto']['auto'];
			$pause = $slider_custom_meta['auto']['pause'];
			$auto_start = $slider_custom_meta['auto']['auto-controls'];
			$auto_direction = $slider_custom_meta['auto']['auto-direction'];
			$auto_hover = $slider_custom_meta['auto']['auto-hover'];
			$auto_delay = $slider_custom_meta['auto']['auto-delay'];

			// Carousel 
			$min_slides = $slider_custom_meta['carousal']['min-slides'];
			$max_slides = $slider_custom_meta['carousal']['max-slides'];
			$move_slides = $slider_custom_meta['carousal']['move-slides'];
			$slide_width = $slider_custom_meta['carousal']['slide-width'];


			// GENERAL ARRAY
		    $general_options = 	array(		    							
		    							'mode' =>  $mode,
										'infiniteLoop' =>  $infinite_loop,
										'hideControlOnEnd' =>  $hide_control_on_end,
										'speed' =>  $speed,
										'slideMargin' =>  $margin,
										'startSlide' =>  $start_slide,
										'randomStart' =>  $random_start,
										'adaptiveHeight' =>  $adaptive_height,
										'responsive' =>  $responsive,
										'sliderType' => $slider_type
									);	

			// PAGER ARRAY
			if( $pager == 'true' ) {
				$pager_options = array(
										'pager' =>  $pager,
										'pagerType' =>  $type,
										'pagerShortSeparator' => $short_separator
									);
			} else {
				$pager_options = array(
										'pager' =>  '',
										'pagerType' =>  '',
										'pagerShortSeparator' => ''	
									);										
			}

			// CONTROLS ARRAY
			$controls_options = array(	
										'controls' =>  $controls,
										'nextText' =>  $next_text,
										'prevText' =>  $prev_text,
										'autoControls' =>  $auto_controls,
										'startText' =>  $start_text,
										'stopText' =>  $stop_text
									);

			// AUTO ARRAY
			$auto_options = array(
									'auto' =>  $auto,
									'pause' =>  $pause,
									'autoStart' =>  $auto_start,
									'autoDirection' =>  $auto_direction,
									'autoHover' =>  $auto_hover,
									'autoDelay' =>  $auto_delay
								);


			if( $slider_type == 'slider_caption' ) {
		    	$slider_type_options = 	array( 'captions' => 'true' );
		    } elseif( $slider_type == 'carousel' ) {
				$slider_type_options = array(
						'minSlides' =>  $min_slides,
						'maxSlides' =>  $max_slides,
						'moveSlides' =>  $move_slides,
						'slideWidth' =>  $slide_width
					);	    			
		    } elseif( $slider_type == 'ticker' ) {
		    	$slider_type_options = array(
						'ticker' => 'true',
						'minSlides' =>  $min_slides,
						'maxSlides' =>  $max_slides,
						'moveSlides' =>  $move_slides,
						'slideWidth' =>  $slide_width
					);
		    } else {
		    	$slider_type_options = array();		  
		    }	

		    $slider_options = array_merge( $general_options, $pager_options, $controls_options, $auto_options, $slider_type_options );
		    	
			wp_localize_script( 'os-frontend-script', 'osbx', $slider_options );
			?>
			<div class="os_slider_wrapper">
				<ul class="os_slider">
					<?php 
					foreach ( $slides as $slideObj ) {
			            $attachment_id = isset( $slideObj['attachment_id'] ) ? $slideObj['attachment_id'] : '';
			            $link = isset( $slideObj['link'] ) ? $slideObj['link'] : '';
			            $link_target = isset( $slideObj['link_target'] ) ? $slideObj['link_target'] : '';
			            $caption = isset( $slideObj['caption'] ) ? $slideObj['caption'] : '';
			            $slider_image_data = wp_get_attachment_image_src( esc_attr( $attachment_id ), 'full' );	
			            $target	= ( $link_target == '_blank' ) ? '_blank' : '_self';

			            if( !empty( $attachment_id ) ) {
							?>
							<li>
								<?php if( esc_attr( $link ) ) { ?>
									<a href="<?php echo esc_attr( $link ); ?>" target="<?php echo esc_attr( $target ); ?>">
								<?php } ?>
								<img src="<?php echo esc_attr( $slider_image_data[0] ); ?>" title="<?php echo esc_attr( $caption ); ?>" />
								<?php if( esc_attr( $link ) ) { ?>
									</a>
								<?php } ?>
							</li>						
						<?php
						}
					}
					?>					
				</ul>
				<?php
				if( $slider_type == 'thumbnail' ) {
				?>	
					<div id="osbx-thumbnail" style="text-align: center;">
						<?php 
						$i = 0;
						foreach ( $slides as $slideObj ) {
							$attachment_id = isset( $slideObj['attachment_id'] ) ? $slideObj['attachment_id'] : '';
							$slider_thumb_data = wp_get_attachment_image_src( esc_attr( $attachment_id ), 'thumbnail' );
							if( !empty( $attachment_id ) ) {
								?>
								<a data-slide-index="<?php echo esc_attr( $i );?>" href="javascript:;">
									<img src="<?php echo esc_attr( $slider_thumb_data[0] ); ?>" width="75" />
								</a>
								<?php 
							}
						$i++;
						} ?>
					</div>
				<?php
				}
				?>
			</div>
			<?php
			return ob_get_clean();
		}	
	}   
endif;

/**
 * Returns the main instance of osBxSlider to prevent the need to use globals.
 *
 * @since  2.4
 * @return osBxSlider
 */
 
return new osBxSlider;