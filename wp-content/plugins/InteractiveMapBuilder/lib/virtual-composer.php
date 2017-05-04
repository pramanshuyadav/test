<?php
class VCExtendAddonClass_for_InteractiveMapBuilder {
    function __construct() {
        // We safely integrate with VC with this hook
        add_action( 'init', array( $this, 'integrateWithVC' ) );
    }
 
    public function integrateWithVC() {
        // Check if Visual Composer is installed
        if ( ! defined( 'WPB_VC_VERSION' ) ) {
            // Display notice that Visual Compser is required
            // add_action('admin_notices', array( $this, 'showVcVersionNotice' ));
            return;
        }
 
		$map_objects = Interactive_Map::get_maps(null, null, "time_changed", "desc");
		$maps        = array();
		
		foreach ($map_objects as $map) {
			$maps[$map->get_name() . "   [interactive_map id=". $map->get_id() . "]"] = $map->get_id();
		}

		$plugin_url = admin_url("admin.php?page=interactive_map_builder");

        vc_map( array(
            "name"        => __("Interactive Map Builder", 'vc_extend'),
            "description" => __("Insert a map", 'vc_extend'),
            "base"        => "interactive_map",
            "class"       => "",
            "icon"        => plugins_url('../images/map_logo.png', __FILE__),
            "category"    => __('Content', 'js_composer'),
            "params"      => array(
                array(
                  "admin_label" => true,
                  "type"        => "dropdown",
                  "holder"      => "hidden",
                  "class"       => "",
                  "heading"     => __("Map to display", 'vc_extend'),
                  "param_name"  => "id",
                  "value"       => $maps,
                  "description" => __("Choose one of your created maps. <br> <a href='".$plugin_url."' target='_blank'>Click here to go to the All Maps page</a>", 'vc_extend')
              )
            ),
            
        ) );
    }
}
new VCExtendAddonClass_for_InteractiveMapBuilder();
?>
