<?php
	// The Open link template
	$click_action_name = "Open link";
	$click_action_desc = 'Opens a link, when a marker, region or text is clicked. The url is stored in the click value for each marker or region.';
	
	$click_tab_1 = 'http://en.wikipedia.org/wiki/Germany';
	$click_tab_2 = 'http://en.wikipedia.org/wiki/Spain';
	$click_tab_3 = 'http://en.wikipedia.org/wiki/Italy';
	
	$html = '<div id="{cssid}">
  %%map%%
</div>';
	$javascript = 'window.location = click_value;';
	$css = '#{cssid} {
 
}';

	$action = new Click_Action(-1, $click_action_name, $click_action_desc, $click_tab_1, $click_tab_2, $click_tab_3, $html, $javascript, $css);
	$action->save();

	
	// The Open link in a new window template
	$click_action_name = "Open link in a new window";
	$click_action_desc = 'Opens a link in a new window, when a marker, region or text is clicked. The url is stored in the click value for each marker or region.';
	
	$click_tab_1 = 'http://en.wikipedia.org/wiki/Germany';
	$click_tab_2 = 'http://en.wikipedia.org/wiki/Spain';
	$click_tab_3 = 'http://en.wikipedia.org/wiki/Italy';
	
	$html = '<div id="{cssid}">
  %%map%%
</div>';
	$javascript = 'window.open(click_value);';
	$css = '#{cssid} {
 
}';

	$action = new Click_Action(-1, $click_action_name, $click_action_desc, $click_tab_1, $click_tab_2, $click_tab_3, $html, $javascript, $css);
	$action->save();

	
	// The Show html above map template
	$click_action_name = "Show html (above map)";
	$click_action_desc = 'This template allows to show HTML below the map. The click value can contain any valid HTML.';
	
	$click_tab_1 = '<strong class="germany">This is Germany.</strong><br><small>This could be more html.</small>';
	$click_tab_2 = '<strong class="italy">This is Spain.</strong><br><small>This could be more html.</small>';
	$click_tab_3 = '<strong class="spain">This is Spain.</strong><br><small>This could be more html.</small>';
	
	$html = '<div id="{cssid}">
  <div class="value">
    Click on the map!
  </div>
  %%map%%
</div>';
	$javascript = 'jQuery("#" + cssid + " .value").html(click_value);';
	$css = '#{cssid} {
 
}';

	$action = new Click_Action(-1, $click_action_name, $click_action_desc, $click_tab_1, $click_tab_2, $click_tab_3, $html, $javascript, $css);
	$action->save();
	
	
	// The Show link template
	$click_action_name = "Show link";
	$click_action_desc = 'Displays a link below the map. The click value expects to have the url in the first line. A second new line can have a caption for the link. Otherwise, the link url will be displayed.

Format of the click value:
Line 1: url
Line 2: caption (if needed, otherwise the url will be displayed)';
	
	$click_tab_1 = 'http://en.wikipedia.org/wiki/Germany
Germany';
	$click_tab_2 = 'http://en.wikipedia.org/wiki/Spain
Spain';
	$click_tab_3 = 'http://en.wikipedia.org/wiki/Italy';
	
	$html = '<div id="{cssid}">
  %%map%%
  <p class="container">
    <span class="default">Click on the map!</span>
  	<a href="" class="map_link"></a>  
  </p>
  
</div>';
	$javascript = 'jQuery("#" + cssid + " .default").hide();
var data = click_value.split("\n");
if(data.length==1) {
  jQuery("#" + cssid + " .map_link").attr({href: data[0], target: "_blank"}).text(data[0]).show();
} else {
  jQuery("#" + cssid + " .map_link").attr({ href: data[0], target: "_blank"}).text(data[1]).show();
}';
	$css = '#{cssid} .map_link {
 display: none;
}';

	$action = new Click_Action(-1, $click_action_name, $click_action_desc, $click_tab_1, $click_tab_2, $click_tab_3, $html, $javascript, $css);
	$action->save();
	
	
	// The Storage example template
	$click_action_name = "Storage example";
	$click_action_desc = 'Shows how the storage for a click value works.';
	
	$click_tab_1 = 'Germany';
	$click_tab_2 = 'Spain';
	$click_tab_3 = 'Italy';
	
	$html = '<div id="{cssid}">
  %%map%%
  <div class="value">
    Click on the map!
  </div>
</div>';
	$javascript = 'jQuery("#" + cssid + " .value").text("You clicked " + (++storage) + " times on " + click_value + ".");';
	$css = '#{cssid} .value {
 color: blue;
}';

	$action = new Click_Action(-1, $click_action_name, $click_action_desc, $click_tab_1, $click_tab_2, $click_tab_3, $html, $javascript, $css);
	$action->save();

?>