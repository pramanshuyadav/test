<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Post types
 *
 * Creating metabox for slider type
 *
 * @class 		osBxSliderMetaboxTypes
 * @version		2.3
 * @category            Class
 * @author 		Jinesh.P.V, Offshorent Softwares Pvt Ltd.
 */
 
if ( ! class_exists( 'osBxSliderMetaboxTypes' ) ) :

    class osBxSliderMetaboxTypes { 

        /**
         * Constructor
         */

        public function __construct() { 

            add_action( 'add_meta_boxes_os_bxslider', array( &$this, 'os_bxslider_type_meta_box' ), 10, 1 );
        }		

        /**
         * callback function for os_bxslider_type_meta_boxe.
         */

        public function os_bxslider_type_meta_box() {
            add_meta_box( 	
                            'display_os_bxslider_type_meta_box',
                            'Slider Type',
                            array( &$this, 'display_os_bxslider_type_meta_box' ),
                            'os_bxslider',
                            'normal', 
                            'high'
                        );
        }

        /**
         * display function for display_os_bxslider_type_meta_box.
         */

        public function display_os_bxslider_type_meta_box() {

            $post_id = get_the_ID();					

            wp_nonce_field( 'os_bxslider', 'os_bxslider' );
            include_once( 'views/os-bxslider.type.php' );
        }
    }
endif;

/**
 * Returns the main instance of osBxSliderMetaboxTypes to prevent the need to use globals.
 *
 * @since  2.3
 * @return osBxSliderMetaboxTypes
 */
 
return new osBxSliderMetaboxTypes();
?>