<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Post types
 *
 * Registers post types and taxonomies
 *
 * @class       osBxSliderPostTypes
 * @version     2.3
 * @category            Class
 * @author      Jinesh.P.V, Offshorent Softwares Pvt Ltd.
 */
 
if ( ! class_exists( 'osBxSliderPostTypes' ) ) :
    
    class osBxSliderPostTypes { 
        
        /**
         * Constructor
         */

        public function __construct() { 

            add_action( 'init', array( &$this, 'register_os_bxslider_post_types' ) );
            add_filter( 'manage_edit-os_bxslider_columns', array( &$this, 'os_bxslider_edit_columns' ), 10, 2 );
            add_action( 'manage_os_bxslider_posts_custom_column', array( &$this, 'os_bxslider_custom_column' ), 10, 2 );
            add_action( 'save_post', array( &$this, 'os_bxslider_save_slider_values' ) );
        }

        /**
         * Register os_bxslider post types.
         */

        public static function register_os_bxslider_post_types() {
            
            self::os_bxslider_includes();

            if ( post_type_exists( 'os_bxslider' ) )
                return;

            $label              =   'OS BXSlider';
            $labels = array(
                'name'                  =>  _x( $label, 'post type general name' ),
                'singular_name'        =>   _x( $label, 'post type singular name' ),
                'add_new'               =>  _x( 'Add New', OSBX_TEXT_DOMAIN ),
                'add_new_item'          =>  __( 'Add New OS BXSlider', OSBX_TEXT_DOMAIN ),
                'edit_item'             =>  __( 'Edit OS BXSlider', OSBX_TEXT_DOMAIN),
                'new_item'              =>  __( 'New OS BXSlider' , OSBX_TEXT_DOMAIN ),
                'view_item'             =>  __( 'View OS BXSlider', OSBX_TEXT_DOMAIN ),
                'search_items'          =>  __( 'Search ' . $label ),
                'not_found'             =>  __( 'Nothing found' ),
                'not_found_in_trash'    =>  __( 'Nothing found in Trash' ),
                'parent_item_colon'     =>  ''
            );

            register_post_type( 'os_bxslider', 
                apply_filters( 'os_bxslider_register_post_types',
                    array(
                            'labels'                 => $labels,
                            'public'                 => true,
                            'publicly_queryable'     => true,
                            'show_ui'                => true,
                            'exclude_from_search'    => true,
                            'query_var'              => true,
                            'has_archive'            => false,
                            'hierarchical'           => true,
                            'menu_position'          => 20,
                            'show_in_nav_menus'      => true,
                            'supports'               => array( 'title' )
                        )
                )
            );                              
        }
        
        /**
         * Includes the metabox classes and views
         */
        
        public static function os_bxslider_includes() {
            
            include_once( 'meta-boxes/class.os-bxslider.design.php' );
            include_once( 'meta-boxes/class.os-bxslider.slide.php' );
            include_once( 'meta-boxes/class.os-bxslider.type.php' );
            include_once( 'meta-boxes/class.os-bxslider.general.php' );
            include_once( 'meta-boxes/class.os-bxslider.pager.php' );
            include_once( 'meta-boxes/class.os-bxslider.controls.php' );
            include_once( 'meta-boxes/class.os-bxslider.auto.php' );
            include_once( 'meta-boxes/class.os-bxslider.carousel.php' );
            include_once( 'meta-boxes/class.os-bxslider.shortcode.php' );
        }
        
        /**
         * os_bxslider slider edit columns.
         */

        public function os_bxslider_edit_columns() {

            $columns = array(
                'cb'                          =>    '<input type="checkbox" />',
                'title'                       =>    'Title',
                'osbxslider-type'             =>    'Slider Type',
                'osbxslider-shortcode'        =>    'Shortcode',
                'osbxslider-count'            =>    'Image Count',
                'date'                        =>    'Date'
            );

            return $columns;
        }

        /**
         * display os_bxslider slider custom columns.
         */

        public function os_bxslider_custom_column( $column, $post_id ) {

            $slider_custom = self::os_bxslider_return_slider_custom_meta( $post_id );
            $slider_type = isset( $slider_custom['slider_type'] ) ? $slider_custom['slider_type'] : '';
            $slides = isset( $slider_custom['slides'] ) ? $slider_custom['slides'] : '';
            $image_count = absint( count( $slides ) ) - 1;

            switch ( $column ) {
                case 'osbxslider-type':                    
                    if ( ! empty( $slider_type ) )
                        echo ucwords( $slider_type );
                    break;
                case 'osbxslider-shortcode':
                    if ( !empty( $slider_type ) )
                        echo self::os_bxslider_shortcode_creator( $post_id, $slider_type );
                    break;
                case 'osbxslider-count':
                    echo '<span class="slidde_count">' . $image_count . '</span>';
                    break;
            }
        }
        
        /**
        * storing meta fields function for os_bxslider_save_slider_values.
        */

        public function os_bxslider_save_slider_values( $post_id ) {

            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
                return;

            if ( ! empty( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
                if ( ! current_user_can( 'edit_page', $post_id ) )
                    return;
            } else {
                if ( ! current_user_can( 'edit_post', $post_id ) )
                return;
            }

            if ( ! empty( $_POST['osbx'] ) ) {                
                update_post_meta( $post_id, 'osbx_slider_custom_meta', $_POST['osbx'] );
            }
        }
       
       /**
        * return slider custom meta values.
        */

        public function os_bxslider_return_slider_custom_meta( $post_id ) {

            return $slider_custom_meta = get_post_meta( $post_id, 'osbx_slider_custom_meta', true );
        }

       /**
        * os_bxslider shortcode creation
        */

        public function os_bxslider_shortcode_creator( $post_id, $slider_type ) {
            $shortcode = '[os-bxslider id="' . $post_id . '"]';
            return '<input type="text" readonly="readonly" id="shortcode_' . $post_id . '" class="shortcode" value="' . esc_attr( $shortcode ) . 
            '">';
        }
    }
endif;

/**
 * Returns the main instance of osBxSliderPostTypes to prevent the need to use globals.
 *
 * @since  2.3
 * @return osBxSliderPostTypes
 */
 
return new osBxSliderPostTypes();
?>