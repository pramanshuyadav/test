<?php
class Help_Tabs {
	protected $id = 0;
	
	public $tabs = array(
		// The assoc key represents the ID
		// It is NOT allowed to contain spaces
		 'EXAMPLE' => array(
		 	 'title'   => 'TEST ME!',
			 'content' => 'FOO'
		 )
	);

	static public function init() {
		$class = __CLASS__ ;
		new $class;
	}

	public function __construct() {
		
	}
	
	static public function create_managemaps_helptabs() {
		$class = __CLASS__ ;
		$me = new $class;
		
		$me->tabs = array();
		
		//-------- Quickstart
		ob_start();
		
		?>
		<p>When the plugin is activated for the first time, it will automatically create some sample maps. 
		When you click on "I Map Builder", the screen will probably look like the following picture. 
		From this page you can start to edit one of the examples or you could start to create your own first map.</p>
		<p style="text-align: center;">
			<img class="aligncenter size-medium wp-image-88" alt="click_actions" src="<?php echo plugins_url('../assets/images/_first-activation.png', __FILE__ ); ?>" />
		</p>
		<p>In both cases the Map Builder will open.</p>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["quickstart"] = array(
			"title" => "Quickstart",
			"content" => $content
		);
		
		//-------- Include
		ob_start();
		
		?>
		<p>The map can be included in your pages and posts by inserting the corresponding shortcode. You can find this shortcode
		for every map on the "All Maps" page. Find the map and copy the text from the "Shortcode" column. It should have the 
		following format:</p>
		<pre>[interactive_map id="1"]</pre>
		<p>Note that the id is different for each map.</p>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["include"] = array(
			"title" => "Include map in pages/posts",
			"content" => $content
		);
		
		
		//-------- Import
		ob_start();
		
		?>
		<p>Hit the button "Import" at the top of the "All Maps" page. A small window should open. The map can be importet in two ways:</p>
		<ol>
			<li><strong>File mode</strong>: Upload a file that contains the map. The file must be generated with this plugin.</li>
			<li><strong>Text mode</strong>: Paste text into the text field. This allows to copy maps from one place to another using the clipboard. 
			Note that the text has to be generated from the plugin.</li>
		</ol>
		<p>If you click on the "Import" button, the import data will be passed to the Map Builder. To complete the import process, click on "Add map".
		Note that you might have to select a click action, because this is not imported.</p>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["import"] = array(
			"title" => "Import a map",
			"content" => $content
		);
		
		
		//-------- Import both
		ob_start();
		
		?>
		<p>If you want to import a map, you have to be aware that only the map is imported and not the selected click action. If you want to import 
		both, we recommend to follow these steps:</p>
		<ol>
			<li>Import the click action.</li>
			<li>Import the map. Then select the imported click action in the Map Builder. Before saving you can already test in the preview, if 
			everthing works as supposed.</li>
		</ol>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["import_both"] = array(
			"title" => "Import map and click action together",
			"content" => $content
		);
		
		
		//-------- Export
		ob_start();
		
		?>
		<p>To export a map, navigate to the "All Maps" page, hit the "More" button and click on "Export". A small window should open where you have 
		to pick an export format. You have the following options:
		<ul>
			<li><strong>File</strong>: Use this to store a backup copy on your computer or to send this via email.</li>
			<li><strong>Text</strong>: Use this if you want to copy the map via the clipboard (copy and paste on the same computer). </li>
		</ul>
		Note that the click action won't be saved and needs to be selected during the import process.</p>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["export"] = array(
			"title" => "Export a map",
			"content" => $content
		);
		
		
		//-------- Delete
		ob_start();
		
		?>
		<p>To delete a map, navigate to the "All Maps" page, hit the "More" button and click on "Delete". A small window should open where you have 
		to confirm the removal.</p>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["delete"] = array(
			"title" => "Delete a map",
			"content" => $content
		);
		
		
		//-------- Delete
		ob_start();
		
		?>
		<p>To copy a map, navigate to the "All Maps" page, hit the "More" button and click on "Create a copy". The copy should be created and the table 
		will update automatically. The copied map will be marked with a "new" label.</p>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["copy"] = array(
			"title" => "Copy a map",
			"content" => $content
		);
		
		
		$me->add_tabs();
	}
	
	static public function create_mapbuilder_helptabs() {
		$class = __CLASS__ ;
		$me = new $class;
		
		$me->tabs = array();
		
		//-------- The Map Builder
		ob_start();
		
		?>
		<p>The Map Builder is the most important tool of this plugin. It is used to create or edit maps. The Map Builder page can 
		be divided into three sections:</p>
		<ol>
			<li><strong>Map Options</strong> <small>(the right side)</small></li>
			<li><strong>Map Preview</strong> <small>(the container showing the map)</small></li>
			<li><strong>Map Template</strong> <small>(the first box below the preview)</small></li>
			<li><strong>Map Elements</strong> <small>(the table below the "Map Template" box)</small></li>
		</ol>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["builder"] = array(
			"title" => "The Map Builder",
			"content" => $content
		);
		
		//-------- 1. Map Options
		ob_start();
		
		?>
		<p>All available map options are located on the right side. The following picture gives an overview:</p>
		<p style="text-align: center;">
			<img class="aligncenter size-medium wp-image-88" alt="click_actions" src="<?php echo plugins_url('../assets/images/_map_options2.png', __FILE__ ); ?>" />
		</p>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["options"] = array(
			"title" => "1. Map Options",
			"content" => $content
		);
		
		//-------- 1.1 Region
		ob_start();
		
		?>
		<p style="margin-bottom: 5px;">
			The region specifies the displayed area of the map. It allows to focus the map on the 
			</p>
			<ul style="margin-bottom: 5px;">
				<li><strong>world</strong>, </li>
				<li>a <strong>continent</strong>, </li>
				<li>a <strong>sub-continent</strong>, </li>
				<li>a <strong>country</strong> or </li>
				<li>a <strong>province</strong>. </li>
			</ul>
			<p>The default value is the world map. To focus the map on a smaller area, select a continent from the select field. 
			After selecting a continent, a new field should appear, showing a list of subcontinents. The selection of a subcontitent allows 
			you to select a country.
			<br/>
			Note that provinces are only available for the United States.</p>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["region"] = array(
			"title" => " - Region",
			"content" => $content
		);
		
		//-------- 1.3 Resolution
		ob_start();
		
		?>
		<p style="text-align: center;">
			<img class="aligncenter size-medium wp-image-88" alt="click_actions" src="<?php echo plugins_url('../assets/images/borders.png', __FILE__ ); ?>" />
		</p>
		<p>This option specifies the resolution of the map borders. The following values are available:</p>
		<ul>
			<li><strong>Continent borders*</strong> This is supported for the world map and continent maps.</li>
			<li><strong>Subcontinent borders*</strong> This is supported for the world map and continent and subcontinent maps.</li>
			<li><strong>Country borders</strong> This is supported for all regions, except for US state regions.</li>
			<li><strong>Province borders</strong> This is supported only for country regions and US state regions. Not supported for 
			all countries; if available, the option is selectable.</li>
			<li><strong>Metro borders</strong> This is supported for the US country region and US state regions only.</li>
		</ul>
		<p>Note that the value might change automatically if you select another region. Also note that the values are disabled if they 
		are not available.</p>
		<p>* Note that the continent and subcontinent mode will still show each country. If one of these modes is selected the countries
		of a continent or subcontinent can be colored all at once.
		</p>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["resolution"] = array(
			"title" => " - Resolution",
			"content" => $content
		);
		
		//-------- 1.2 Display mode
		ob_start();
		
		?>
		<p style="text-align: center;">
			<img class="aligncenter size-medium wp-image-88" alt="click_actions" src="<?php echo plugins_url('../assets/images/_display_modes.png', __FILE__ ); ?>" />
		</p>
		<p>The display mode specifies the data that you want to display. If you want to highlight the shapes of countries 
		or provinces, then you have to select the <strong>region mode</strong>. For the United States, it is also possible 
		to highlight metro areas. In this case, it is required to set the resolution (in 1.3) to metro borders.
		<br/>
		If you want to create a map with markers, then you have two choices. The <strong>markers from text</strong> option 
		allows to set the location by passing a string (for example, "1600 Pennsylvania Ave" or "New York City"). When the 
		map is loaded, the string will be used to find the location of the marker. If you have many markers on the map, this 
		can cause the markers to appear after a delay.
		<br/>
		In the <strong>markers from coordinates</strong> mode, you can set the location by passing the latitude and longitude. 
		The main advantage is that the markers appear when the map is loaded. So it is recommended to pick this mode. Even if 
		you don't know the coodinates, the form of the data table allows you to search for the coordinates by clicking on 
		"Find coordinates".
		</p>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["displaymode"] = array(
			"title" => " - Display Mode",
			"content" => $content
		);
		
		//-------- 1.4 Click Action
		ob_start();
		
		?>
		<p>This option allows to select a click action. If you want nothing to happen when a marker or region is clicked, then 
		select "None".</p>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["clickaction"] = array(
			"title" => " - Click Action",
			"content" => $content
		);
		
		//-------- 1.5 Map Styling
		ob_start();
		
		?>
		<p>The following options are available:</p>
		<ul>
			<li><strong>background color</strong> The background color for the main area of the map.</li>
			<li><strong>dataless region color</strong> Colors to assign to regions with no associated data.</li>
			<li><strong>height</strong> The height of the map. The default height is 250 pixels, unless the <strong>width</strong> 
			option is specified and <strong>keep aspect ratio</strong> is selected - in which case the height is calculated 
			accordingly when the map is loaded.</li>
			<li><strong>width</strong> The width of the map. The default width is <strong>auto</strong>. In this case, the map will 
			automatically resize and fit to the content arround it.</li>
			<li><strong>keep aspect ratio</strong> If selected, the map will have its natural aspect ratio. If not, the map will 
			stretch.</li>
			<li><strong>region interactivity</strong> If selected, a region can be selected and can react to clicks. Note that this 
			option has to be selected to execute a click action.</li>
			<li><strong>border size</strong> Specifies a border size arround the map. Default is 0. Better results can often be 
			achieved by creating a dummy click action and specify the css there.</li>
			<li><strong>border color</strong> Specifies the border color.</li>
		</ul>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["mapstyling"] = array(
			"title" => " - Map Styling",
			"content" => $content
		);
		
		//-------- 1.6 Marker Styling
		ob_start();
		
		?>
		<p>If the map displays markers, the following options are available for the appearance:</p>
		<ul>
			<li><strong>Marker size</strong> This defines the size for all markers.</li>
			<li><strong>Marker opacity</strong> This specifies the opacity of all markers.</li>
		</ul>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["markerstyling"] = array(
			"title" => " - Marker Styling",
			"content" => $content
		);
		
		//-------- 1.7 Tooltip Styling
		ob_start();
		
		?>
		<p>The following options are available:</p>
		<ul>
			<li><strong>Show tooltips</strong> If selected, this will show tooltips.</li>
			<li><strong>Font color</strong> This specifies the font color for all tooltips.</li>
			<li><strong>Font name</strong> This specifies the font name for all tooltips. The default value is 
			<strong>&lt;global-font-name&gt;</strong>. In this case it will use the font from you WordPress theme.</li>
			<li><strong>Font size</strong> This specifies the font size for all tooltips. The defailt value is 
			<strong>&lt;global-font-size&gt;</strong>. In this case it will use the fontsize from you WordPress theme.</li>
		</ul>
		<p>Note that tooltips are only available for regions and markers from the data table. The tooltip title and text for a given 
		marker or region can be changed in the data table form.</p>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["tooltipstyling"] = array(
			"title" => " - Tooltip Styling",
			"content" => $content
		);
		
		//-------- 2. Map Data Table
		ob_start();
		
		?>
		<p>The data table contains all the markers or regions that you want to display on the map. The appearance of the data table might 
		change with the selected <strong>display mode</strong> (in 1.2).</p>
		<p style="text-align: center;">
			<img class="aligncenter size-medium wp-image-88" alt="click_actions" src="<?php echo plugins_url('../assets/images/_map_table.png', __FILE__ ); ?>" />
		</p>
		<p>To edit an entry from the table, you have to click on the row. A form should appear and should look like this:</p>
		<p style="text-align: center;">
			<img class="aligncenter size-medium wp-image-88" alt="click_actions" src="<?php echo plugins_url('../assets/images/_map_form.png', __FILE__ ); ?>" />
		</p>
		<p>You can edit the following values:</p>
		<ul>
			<li><strong>Location</strong> Sets location for the given region or marker. Depending on the selected 
			<strong>display mode</strong>, the field might look different. In the coordinate mode you will also find a link "Find 
			coordinates". A click shows a small text field which allows you to translate tha address or name of a location to coordinates. 
			To check the correctness of the found coordinates, click on the "Change" button of the form and observe where the marker appears 
			when the map is reloaded.</li>
			<li><strong>Color</strong> Sets a color for the region or marker.</li>
			<li><strong>Tooltip</strong> Sets the title and text for the tooltip. Note that the <strong>Show tooltips</strong> 
			option must be activated. Otherwise the tooltip will not be visible.</li>
			<li><strong>Click Value</strong> Sets the click value for the region or marker. The expected value depends on the selected 
			click action. To get more details, click on "All Click Actions" in the left menu and read the description of the click value. 
			A closer look at at the values from the sample data table might also help.</li>
		</ul>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["datatable"] = array(
			"title" => "2. Map Data Table",
			"content" => $content
		);
		
		//-------- 3. Map Preview
		ob_start();
		
		?>
		<p>The preview renders the map for the given data and options. It updates automatically when an option or the data table changes. 
		The following picture shows the preview area:</p>
		<p style="text-align: center;">
			<img class="aligncenter size-medium wp-image-88" alt="click_actions" src="<?php echo plugins_url('../assets/images/_preview_container.png', __FILE__ ); ?>" />
		</p>
		<p>The following options can be set for the preview area:</p>
		<ul>
			<li><strong>Preview click</strong> This defines what happens when you click on something in the preview. You have two options 
			that can independently be enabled or disabled: The first one controls the execution of the click action. The second one controls 
			the opening of the data table form. Note that the opening for regions only works if the <strong>region interactivity</strong> 
			is activated.</li>
			<li><strong>Preview width</strong> This simulates different page sizes. If the width in the Map Options is set to auto. Then the 
			map should automatically resize and center itself.</li>
			
		</ul>
		<p>Note also that these options only affect the preview. When the map is inserted somewhere later, the selected click action will 
		always be executed.	If you want to add a marker or region to the map, you can hit the button <strong>"Add marker"</strong> or 
		<strong>"Add region"</strong>. To edit a region or marker, it's often easier to click on the region or marker in the preview. Again, 
		this only works when the <strong>region interactivity</strong> from the Map Options (1.5) is activated.</p>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["mappreview"] = array(
			"title" => "3. Map Preview",
			"content" => $content
		);
		
		//-------- Include
		ob_start();
		
		?>
		<p>The map can be included in your pages and posts by inserting the corresponding shortcode. You can find this shortcode
		for every map on the "All Maps" page. Find the map and copy the text from the "Shortcode" column. It should have the 
		following format:</p>
		<pre>[interactive_map id="1"]</pre>
		<p>Note that the id is different for each map.</p>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["include"] = array(
			"title" => "Include map in pages/posts",
			"content" => $content
		);
		
		$me->add_tabs();
	}
	
	static public function create_clickaction_helptabs() {
		$class = __CLASS__ ;
		$me = new $class;
		
		$me->tabs = array();
		
		//-------- The Click Action page
		ob_start();
		
		?>
		<p>The map template page is divided into 3 areas:</p>
		<ul>
			<li><strong>List of Map Templates</strong> The list is found on the left side. A click on the name loads the example map and the 
			description.</li>
			<li><strong>An example map</strong> The map demonstrates what the template does. This map is the same for all templates: 
			It shows Germany, Spain and Italy. The only difference is the specified click value for each country. The data table is hidden by 
			default and can be displayed by clicking on "Show data table".</li>
			<li><strong>Description</strong> This is located below the map and should describe what template does and what click values 
			it is expecting.</li>
		</ul>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["clickaction"] = array(
			"title" => "The Click Action page",
			"content" => $content
		);
		
		//-------- 
		ob_start();
		
		?>
		<p>To edit a template, you find the link "Edit" on top of the map container. The page will then switch to editing mode:</p>
		<p style="text-align: center;">
			<img class="aligncenter size-medium wp-image-88" alt="click_actions" src="<?php echo plugins_url('../assets/images/_click_actions_edit.png', __FILE__ ); ?>" />
		</p>
		<p>Besides the name and the description, there are four important values to edit.</p>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["editing"] = array(
			"title" => "1. Editing",
			"content" => $content
		);
		
		//-------- 
		ob_start();
		
		?>
		<p>There are three predefined countries for the example map: Germany, Spain and Italy. For each country, the click value can be edited. 
		Note that these are the values that will be passed to the JavaScript when the region or marker is clicked.</p>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["datatable"] = array(
			"title" => " - Data Table",
			"content" => $content
		);
		
		//-------- 
		ob_start();
		
		?>
		<p>Whenever a region or marker is clicked, the JavaScript code will be executed. This allows to add special behavior to your map. This 
		can depend on the given click value. Besides the <strong>click value</strong>, there are also predefined variables for your JavaScript 
		code. A complete list can be found in the JavaScript box below.</p>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["javascript"] = array(
			"title" => " - JavaScript",
			"content" => $content
		);
		
		//-------- 
		ob_start();
		
		?>
		<p>You can add your own HTML to the template. This can be useful, if you want to display data somewhere around the map. There are two 
		placeholder available for the HTML:</p>
		<ol>
			<li>
				<strong>{cssid}:</strong> This will be replaced by an id when the map is rendered. For example, you could write:
<pre class="lang:xhtml decode:true crayon-selected">&lt;div id="{cssid}"&gt;

&lt;/div&gt;</pre>
				This ensures that you don't conflict with other maps. You can easily find the element from your JavaScript with the following code:
				<pre class="lang:js decode:true">jQuery("#" + cssid);</pre>
			</li>
			<li>
				<strong>%%map%%: </strong>The second placeholder is required to be inserted somewhere in your HTML. This will be replaced by the 
				generated HTML of the map. The default value of the HTML is the following:
<pre class="lang:xhtml decode:true">&lt;div id="{cssid}"&gt;
	%%map%%
&lt;/div&gt;</pre>
			</li>
		</ol>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["html"] = array(
			"title" => " - HTML",
			"content" => $content
		);
		
		//-------- 
		ob_start();
		
		?>
		<p>The CSS has one placeholder:</p>
		<ol>
			<li>
				<strong>{cssid}:</strong> This will also be replaced with the generated id. The default CSS is:
<pre class="lang:css decode:true">#{cssid} {

}</pre>
			</li>
		</ol>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["css"] = array(
			"title" => " - CSS",
			"content" => $content
		);
		
		//-------- 
		ob_start();
		
		?>
		<p>Before saving, you can test the changes by clicking on "Update Preview". This reloads the example with the new data. A message box will 
		appear, if there are errors in your JavaScript code.</p>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["debugging"] = array(
			"title" => " - Debugging",
			"content" => $content
		);
		
		//-------- 
		ob_start();
		
		?>
		<p>The <strong>Text format</strong> is the only import method available for map templates. To import a template, hit "Import" at 
		the top of the map template page. Insert the text and click on the "Import" button. The template should automatically be 
		inserted to the left menu.</p>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["import"] = array(
			"title" => "2. Import",
			"content" => $content
		);
		
		//-------- 
		ob_start();
		
		?>
		<p>To export a template, make sure that the map template is selected in the left menu. Then hit the "Export" button 
		above the map. A small window should open. Make sure that you copy the whole text from the textbox. Otherwise the import might fail, if 
		you use an incomplete text.</p>
		<?php
		
		$content = ob_get_clean();
		$me->tabs["export"] = array(
			"title" => "3. Export",
			"content" => $content
		);
		$me->add_tabs();

	}
	
	public function add_tabs() {
		foreach ( $this->tabs as $id => $data )	{
			get_current_screen()->add_help_tab( array(
				 'id'       => $id++
				,'title'    => $data['title']
				// Use the content only if you want to add something
				// static on every help tab. Example: Another title inside the tab
				,'content'  => '<h3>Documentation</h3>'
				,'callback' => array( $this, 'prepare' )
			) );
		}
		get_current_screen()->set_help_sidebar(
			'<h4>For more information:</h4><a href="http://www.meisterpixel.com/interactive-map-builder" target="_blank">Visit plugin site</a>'
		);
	}

	public function prepare( $screen, $tab ) {
	    printf( '<p>%s</p>', $tab['callback'][0]->tabs[ $tab['id'] ]['content']	);
	}

}

?>