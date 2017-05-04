<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Post types
 *
 * Creating metabox for design
 *
 * @class 		osBxSliderMetaboxDesign
 * @version		2.3
 * @category    Class
 * @author 		Jinesh.P.V, Offshorent Softwares Pvt Ltd.
 */
 
if ( ! class_exists( 'osBxSliderMetaboxDesign' ) ) :

    class osBxSliderMetaboxDesign { 

        /**
         * Constructor
         */

        public function __construct() { 

            add_action( 'add_meta_boxes_os_bxslider', array( &$this, 'os_bxslider_design_meta_box' ), 10, 1 );
        }		

        /**
         * callback function for os_bxslider_design_meta_boxe.
         */

        public function os_bxslider_design_meta_box() {
            add_meta_box( 	
                            'display_os_bxslider_design_meta_box',
                            'Design',
                            array( &$this, 'display_os_bxslider_design_meta_box' ),
                            'os_bxslider',
                            'normal', 
                            'low'
                        );
        }

        /**
         * display function for display_os_bxslider_design_meta_box.
         */

        public function display_os_bxslider_design_meta_box() {

            $post_id = get_the_ID();					

            wp_nonce_field( 'os_bxslider', 'os_bxslider' );
            include_once( 'views/os-bxslider.design.php' );
        }
    }
endif;

/**
 * Returns the main instance of osBxSliderMetaboxDesign to prevent the need to use globals.
 *
 * @since  2.3
 * @return osBxSliderMetaboxDesign
 */
 
return new osBxSliderMetaboxDesign();
?>