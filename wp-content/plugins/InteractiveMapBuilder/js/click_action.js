
	/**
	 *  Handles all interactions with the import box.
	 *  
	 *  @class Import_Box
	 *  @author Fabian Vellguth
	 */
	Import_Box = (function() {
		var $=jQuery;
		
		/**
		 *	Handles all interactions with the import box.
		 *
		 *  HTML:
		 
			<a class="link">...</a>
		 
			<div id="box">
		 
				<textarea>...(text)...</textarea>
				<div class="spinner"></div>
				<button>...</button>
		 
			</div>
			
		 * JS:
		 
		   var box = new Import_Box("#box", ".link"); 
		   
		 * @class Import_Box
		 * @param {String} box_selector A selector for jQuery to find the box.
		 * @param {String} link_selector A selector for jQuery for the link that should open the box.
		 * @constructor
		 * @extends EventTarget
		 */
		function Import_Box(box_selector, link_selector) {
			this.$box     = $(box_selector);
			this.$proceed = this.$box.find(".button_proceed");
			this.$data    = this.$box.find("textarea");
			this.$spinner = this.$box.find(".spinner");
			this.$links   = $(link_selector);
			this.$close   = "";
			this.click_listener = this;
			this.bind_events();
		}
		
		Import_Box.prototype = new EventTarget();
		
		/**
		 *  Binds a function that will be fired when the box opens.
		 *  
		 *  @event open
		 */
		Import_Box.prototype.open = function(func) {
			this.addListener("open", func);
		}
		
		/**
		 *  Binds a function that will be fired when the user clicks the import button.
		 *  
		 *  @event submit
		 */
		Import_Box.prototype.submit = function(func) {
			this.addListener("submit", func);
		}
		
		/**  
		 *  Resets the visual interface of the box. Is automatically called, when link_selector is clicked and 
		 *  right before the box opens.
		 *  
		 *  @method prepare
		 */
		Import_Box.prototype.prepare = function() {
			this.$data.val("");
			this.$proceed.removeClass("waiting").addClass("button_blue");
			this.hide_spinner();
		}
		
		/**
		 *  Click Event. Is supposed to be called internally to fire the an event. To connect a method see 
		 *  the method set_click.
		 *  
		 *  @method click
		 *  @param {String} name The event that occured.
		 */
		Import_Box.prototype.click = function(name) {
			console.log(name);
		}
		
		/**
		 *  Connects the click event to another object.
		 *  
		 *  @method set_click
		 *  @param {Object} s The object where f is connected to.
		 *  @param {Object} f The function that will be fired, when an event occurs.
		 */
		Import_Box.prototype.set_click = function(s, f) {
			this.click_listener = s;
			this.click          = f;
		}
		
		/**
		 *  Binds all events in the UI of the box.
		 *  
		 *  @method bind_events
		 */
		Import_Box.prototype.bind_events = function() {
			var _this = this;
			
			this.$links.click(function(e) {
				_this.prepare();
				_this.click.call(_this.click_listener, "open");
				_this.fire("open");
				e.preventDefault();
			});
			
			this.$proceed.click(function(e) {
				var __this = _this;
				$(this).blur();
				_this.click.call(_this.click_listener, "submit");
				_this.fire("submit");
				_this.show_spinner();
				_this.$spinner.show();
				e.preventDefault();
				return;
			});
		}
		
		/**
		 *  Returns the text from the textarea.
		 *  
		 *  @method get_text
		 *  @return {String} The text from the textarea.
		 */
		Import_Box.prototype.get_text = function() {
			return this.$data.val();
		}
		
		Import_Box.prototype.success_response = function(data) {
			this.hide_spinner();
			if(is_valid_transaction(data)) {
				set_message(data, "normal");
				update_data(data);
				current_action = data.id;
				current_mode   = "import";
				page.change_page(data.id, "show", true);
			} else {
				set_message(data, "error");
				page.change_page(current_action, "show", true)
			}
		}
		
		Import_Box.prototype.error_response = function() {
			this.hide_spinner();
		}
		
		/**
		 *  Hides spinner.
		 *  
		 *  @method hide_spinner
		 */
		Import_Box.prototype.hide_spinner = function() {
			this.$spinner.css({ visibility: "hidden", opacity: 0, width: 0});
		}
		
		/**
		 *  Show spinner.
		 *  
		 *  @method show_spinner
		 */
		Import_Box.prototype.show_spinner = function() {
			this.$spinner.css("visibility", "visible").css({opacity: 0.75, width: "24px"});
			this.$proceed.removeClass("button_blue").addClass("waiting");
		}
		
		return Import_Box;
	})();
	
	/**
	 *	Handler for Interactions with the left menu.
     *	 
	 * @class Menu_Handler
	 */
	Menu_Handler = (function() {
		var $=jQuery;
		
		/**
		 *	Handler for Interactions with the left menu.
		 *
		 *	HTML:
	 
		 <div class="elements">
		 
			<div id="name_input_container">
			   <input type="text" name="action_name" />
			</div>
	 
			<div class="element linked"> 
				<a actionid="2"></a>
			</div>
			
			<div class="element highlighted arrow_right"> 
				<a actionid="6"></a>
			</div>
			
		 </div>
	  
	     *  JS:
	     
		 var menu = new Menu_Handler(".elements", "#name_input_container");
		 
		 *  @constructor
		 *  @class Menu_Handler
		 *  @param {string} elements_selector
		 *  @param {string} field_selector
		 *  @param {string} url
		 */
		function Menu_Handler(elements_selector, field_selector, url) {
			/**
			 *  The container for all menu elements.
			 *
			 *  @property $elements
			 *  @type Object
			 */
			this.$elements = $(elements_selector);
			
			/**
			 *  The menu element for new entry.
			 *
			 *  @property $field
			 *  @type Object
			 */
			this.$field = $(field_selector);
			
			/**
			 *  The CSS class that will be set when an element is highlighted.
			 *  
			 *  @property highlight_css
			 *  @type String
			 *  @default "highlighted arrow_right"
			 */
			this.highlight_css  = "highlighted arrow_right";
			
			/**
			 *  The CSS class that will be set when an element is dehighlighted.
			 *  
			 *  @property base_css
			 *  @type String
			 *  @default "linked"
			 */
			this.base_css       = "linked";
			this.action_attr    = "actionid";
			this.click_listener = this;
			this.url            = url;
			
			this.bind_events();
		}
		
		Menu_Handler.prototype = new EventTarget();
		
		/**
		 *  Binds all events for the menu.
		 *  
		 *  @method bind_events
		 */
		Menu_Handler.prototype.bind_events = function() {
			var _this = this;
			
			this.$elements.find("a").click(function(e) {
				_this.clicked_id = $(this).attr(_this.action_attr);
				_this.fire("click");
				e.preventDefault();
			});
			this.$elements.find(".nolink").dblclick(function(e) {
				_this.clicked_id = $(this).parents(".element").find("a").attr(_this.action_attr);
				_this.fire("dblclick");
				e.preventDefault();
			});
		}
		
		/**
		 *  Dehighlights all elements.
		 *  
		 *  @method dehighlight
		 */
		Menu_Handler.prototype.dehighlight = function() {
			this.$elements.find(".element").removeClass(this.highlight_css).addClass(this.base_css);
		}
		
		/**
		 *  Highlights the entry with the given action id.
		 *  
		 *  This happens by adding the classes from the highlight_css property to the .element-parent of the corresponding link.
		 *  
		 *  @param {String|int} action_id
		 *  @method highlight
		 */
		Menu_Handler.prototype.highlight = function(action_id) {
			this.dehighlight();
			this.$elements.find(".element [" + this.action_attr + "=" + action_id + "]").parents(".element").addClass(this.highlight_css).removeClass(this.base_css);
			this.$elements.find(".element a").blur();
		}
		
		/**
		 *  Shows the new field entry for the given action.
		 *  
		 *  @param {string|int} action_id
		 *  @method show_field
		 */
		Menu_Handler.prototype.show_field = function(action_id) {
			if(action_id=="-1") {
				this.$elements.prepend(this.$field);
				var style = window.getComputedStyle(this.$field[0]); 
				style.maxHeight; // ..? --> Needs to be called to see CSS Transitions
			} else {
				this.highlight(action_id);
				this.$elements.find("a[" + this.action_attr + "=" + action_id + "]").parents(".element").after(this.$field);
			}
		}
		
		/**
		 *  Updates the menu.
		 *  
		 *  @param {object} data
		 *     @param {object} data.click_actions
		 *     @param {array} data.order
		 *     @param {int} data.id
		 *  @method update_data
		 */
		Menu_Handler.prototype.update_data = function(data) {
			var actions = data.click_actions;
			var order   = data.order;
			var id      = data.id;
			
			jQuery("#action_count").text(order.length);
			this.clean_list(actions);
			this.update_entries(actions);
			this.check_entry_order(order);
			this.add_new_entries(actions, order, id);
		}
		
		Menu_Handler.prototype.clean_list = function(actions) {
			var _this    = this;
			var _actions = actions;
			this.$elements.find(".element").each(function() {
				var element = jQuery(this);
				var link    = element.find(".link a");
				
				var actionid = link.attr("actionid");
				if(typeof actionid === "undefined" || !_this.actionid_exists(_actions, actionid)) {
					element.remove();
				}
			});
		}
		
		Menu_Handler.prototype.actionid_exists = function(actions, actionid) {
			return typeof actions[actionid] !== "undefined";
		}
		
		Menu_Handler.prototype.update_entries = function(click_actions) {
			var _this = this;
			jQuery.each(click_actions, function(id, obj) {
				var link = _this.$elements.find("[actionid="+id+"]");
				if(link.length>0) {
					var element = link.parents(".element");
					var name    = obj["name_esc"];
					element.find(".link a").html(name);
					element.find(".nolink").html(name);
				}
			});
			
		}

		Menu_Handler.prototype.check_entry_order = function(order) {
			var _this = this;
			jQuery.each(order, function(k,v) {
				var link = _this.$elements.find("[actionid="+v+"]");
				if(link.length>0) {
					var element = link.parents(".element");
					_this.$field.before(element);
				}
			});
		}
		Menu_Handler.prototype.add_new_entries = function(actions, order, selected) {
			var _this = this;
			jQuery.each(order, function(k,v) {
				var __this = _this;
				if(v!=-1) {
					var link = _this.$elements.find("[actionid="+v+"]");
					if(link.length>0) {
						//var element = link.parents(".element");
						//jQuery(".elements .field").before(element);
					} else {
						var element = jQuery(".clone_element").clone();
						element.removeClass("clone_element");
						if(v==selected) {
							element.addClass("highlighted");
						} else {
							element.addClass("linked");
						}
						element
							.find("a")
								.html(actions[v]["name_esc"])
								.attr("actionid", v)
								.attr("href", _this.url + "&action=" + v + "&mode=show")
								.click(function(e) {
									var id = $(this).attr(_this.action_attr);
									__this.click.call(__this.click_listener, id, 1);
									e.preventDefault();
								});
						
						element
							.find(".nolink")
								.html(actions[v]["name_esc"]);
						
						// Insert in menu
						if(k==0) {
							_this.$elements.prepend(element);
						} else {
							var link = _this.$elements.find("[actionid="+order[k-1]+"]");
							if(link.length>0) {
								var before = link.parents(".element");
								
								if(v!=selected)
									element.css("display", "none");
								before.after(element);
								if(v!=selected)
									element.slideDown();
							}
						}
					}
				}
			});
		}
		return Menu_Handler;
	})();
	
	/**
	 *  Handles all interactions with the map preview.
	 *  
	 *  @class Preview_Handler
	 */
	Preview_Handler = (function() {
		var $iframe, $loader, url;
		var $=jQuery;
		
		/**
		 *	Handles all interactions with the map preview.
		 *
		 *  HTML:
		 
			<iframe id="map">...</iframe>
			<div class="loader">...</div>
			
		 * JS:
		 
		   var preview = new Preview_Handler("http://www.sitewithmaps.de/?action_id=", "#map", ".loader"); 
		   
		 *  @param {string} url An uncomplete url with a missing map variable at the end. For more details look
		 *  at the example or inside the load method.
		 *  @param {string} iframe_selector
		 *  @param {string} loader_selector
		 *  @constructor
		 *  @class Preview_Handler
		 */
		function Preview_Handler(url, iframe_selector, loader_selector) {
			this.url         = url;
			this.$iframe     = jQuery(iframe_selector);
			this.$loader     = jQuery(loader_selector);
			this.bind();
		}
		
		Preview_Handler.prototype = new EventTarget();
		
		/**
		 *  Loads the map for the given id.
		 *  
		 *  It's important to understand that this will <b>replace</b> the old iframe by a new one. By doing
		 *  this, the new url won't be added to the browser's history. To prevent movement of the content, the
		 *  <b>height</b> of the old and new one are set equal. Furthermore, <b>name</b> and <b>id</b> attribute 
		 *  are the same.
		 *  
		 *  @param {string|int} action_id The id that will be loaded together with the url from the constructor.
		 *  @method load
		 */
		Preview_Handler.prototype.load = function(action_id) {
			var _this  = this;
			var url    = this.url + action_id;
			var parent = this.$iframe.parent();
			
			// Configure new iframe
			var new_iframe = jQuery('<iframe />', {
				name:        this.$iframe.attr("name"),
				id:          this.$iframe.attr("id"),
				frameborder: '0',
				src:         url
			}).load(function() {
				_this.hide_loader();
			}).css("height", this.$iframe.css("height"));

			// Remove old iframe and append it
			this.$iframe.remove();
			parent.append(new_iframe);
			this.$iframe = new_iframe;
			
			// Show loader
			this.show_loader();
		}
		
		/**
		 *  Bind events.
		 *  
		 *  The Preview reacts to the following events:
		 *  - Preview Size
		 *  
		 *  @method bind
		 */
		Preview_Handler.prototype.bind = function() {
			var _this = this;
			this.$iframe.load(function() {
				_this.hide_loader();
			});
			
			$("#preview_width .value").click(function() {
				var width = $(this).attr("value");
				_this.set_width(width);
				$("#resize_box").css("width", width+"%");
				/*$(".map_info .right .value").removeClass("selected");
				$(this).addClass("selected");*/
			});
		}
		
		/**
		 *  Showes the loader.
		 *  
		 *  @method show_loader
		 */
		Preview_Handler.prototype.show_loader = function() {
			this.$loader.stop().fadeIn(200);
		}
		
		/**
		 *  Hides the loader.
		 *  
		 *  @method hide_loader
		 */
		Preview_Handler.prototype.hide_loader = function() {
			this.$loader.stop().fadeOut(600);
		}
		
		/**
		 *  Returns the iframe.
		 *  
		 *  @method get_iframe
		 */
		Preview_Handler.prototype.get_iframe = function() {
			return this.$iframe;
		}
		
		/**
		 *  Sets the width of the map in percent of the max width.
		 *  
		 *  @param {number} percent Number between 0 and 1.
		 *  @method set_width
		 */
		Preview_Handler.prototype.set_width = function(percent) {
		
		}
		
		return Preview_Handler;
	})();
	
	/**
	 *  Handles all interactions with the form.
	 *  
	 *  @class Page_Form
	 */
	Page_Form = (function() {
		var $form, $elements, names;
		
		/**
		 *  Handles all interactions with the form.
		 *  
		 *  HTML:
		   
		<form id="example">
		  <input name="url"     type="text" />
		  <input name="message" type="text" />
		  <input name="submit"  type="submit" value="send" />
		</form>
		   
		 *  JS:
		   
		var form = new Page_Form("#example");
		var obj1 = { my_url: "ftp://",  my_mess: "Hello world"};
		var obj2 = { my_url: "http://", my_mess: "Hello world"};
		 
		form.add_element("my_url",  "url");
		form.add_element("my_mess", "message");
		 
		form.fill(obj1);
		form.has_new_data(obj2); // => false
		form.get_data();         // => {url: "ftp://", message: "Hello world", submit: "send"}
		   
		 *  @class Page_Form
		 *  @constructor
		 *  @param {string} selector A selector for jQuery to find the form.
		 */
		function Page_Form(selector) {
			this.$elements = {};
			this.names     = [];
			this.$form     = jQuery(selector);
		}
		
		/**
		 *  Adds or sets the alias for a form element.
		 *  
		 *  @param {string} alias The name of the property in the action object that is used 
		 *  as a parameter in the other methods.
		 *  @param {string} name The name of the form element. 
		 *  @method add_element
		 */
		Page_Form.prototype.add_element = function(alias, name) {
			var element = this.$form.find("[name=" + name + "]");
			if(element) {
				if(typeof this.$elements[alias]=="undefined") {
					this.names.push(alias);
				}
				this.$elements[alias] = element;
			} else {
				return false;
			}
			return true;
		}
		
		/**
		 *	Checks for each property in action if there is a difference between the corresponding form element
		 *  and the given action array.
		 *
		 *	@param {array} action The property names must be the same as the corresponding element alias.
		 *  @return {boolean} true, if there is something new, otherwise false.
		 *  @method has_new_data
		 */
		Page_Form.prototype.has_new_data = function(action) {
			for(i=0; i<this.names.length; i++) {
				var name     = this.names[i];
				var $element = this.$elements[name];
				if(action[name]==$element.val()) {
					continue;
				} else {
					return true;
				}
			}
			return false;
		}
		
		/**
		 *	Fills the form elements with the given action values.
		 *
		 *	@param {Object} action action[alias]=value The names must be the same as the corresponding element name.
		 *  @method fill
		 */
		Page_Form.prototype.fill = function(action) {
			for(i=0; i<this.names.length; i++) {
				name     = this.names[i];
				$element = this.$elements[name];
				$element.val(action[name]);
			}
		}
		
		/**
		 *	Returns the values of each form element in an array.
		 *
		 *	@return array name=>value
		 *  @method get_data
		 */
		Page_Form.prototype.get_data = function() {
			var data = {};
			var a    = this.$form.serializeArray();
			jQuery.each(a, function() {
			   if (data[this.name]) {
				   if (!data[this.name].push) {
					   data[this.name] = [data[this.name]];
				   }
				   data[this.name].push(this.value || '');
			   } else {
				   data[this.name] = this.value || '';
			   }
			});
			return data;
		}
		return Page_Form;
	})();
	
	/**
	 *  Class for the Click Action Page.
	 *
	 *  @class Click_Action_Page
	 */
	Click_Action_Page = (function() {
		var $page, modes, form, url;
		//var current_mode, current_action;
		var actions, preview, menu;
		var $tab1, $tab2, $tab3, $description, $export, $message;
		var $=jQuery;
		
		/**
		 *  The current page is determined by the <b>mode</b> and the <b>id of an action</b>.
		 *  The following modes are possible:
		 *  - show (default)
		 *  - edit
		 *  - new
		 *  - delete
		 *  - import
		 *  - export
		 *  
		 *  The visibility of mode-specific DOM elements and the transition is all realized by adding/changing
		 *  a second CSS class for div#click_actions_page. 
		 *
		 *  The Click_Action_Page-Class has to cover the following events:
		 *  - Import Box (uses {{#crossLink "Import_Box"}}{{/crossLink}})
		 *     - Import: Sends a request to the server.
		 *        - Success: Show the imported action and set mode to "show".
		 *        - Failure: Return to "show" mode and show the error.
		 *     - Close/Click on the background: Closes the box and returns to "show" mode.
		 *  - Delete Box
		 *     - Delete: Send a request to the server.
		 *        - Success: Return to "show" mode and select from the left menu the action on top (or
		 *          below or ...)
		 *        - Failure: Return to "show" mode and show the error.
		 *     - Close/Click on the background: Closes the box and returns to "show" mode.
		 *  - Export Box
		 *     - Close/Click on the background: Closes the box and returns to "show" mode.
		 *  - Top Menu
		 *     - Add New: Set mode to "new".
		 *     - Import: Open Import Box.
		 *  - Left menu
		 *     - Click: Change the selected action and sets the mode to "show".
		 *  - Preview
		 *  - Action Sub Menu
		 *     - Delete Link: Open Delete Box
		 *     - Export: Open Export Box
		 *     - Copy: Send a request to the server.
		 *        - Success: Show the copied action and set mode to "show".
		 *        - Failure: Return to "show" mode and show the error.
		 *     - Edit: Set mode to "edit"
		 *  - Form
		 *     - Save: Sends a request to the server.
		 *     - New: Sends a request to the server.
		 *  - Browser History
		 *     - Contains for each entry in the history the mode and id. This will be set whenever the user
		 *       moves forward or backward in history.
		 *  
		 *  Expected HTML structure:
		 
		 <div id="click_actions_page" class="page">
		    ...
		    <div id="message">...</div>
		    ...
		    <div id="s_description">...</div>
		    ...
			<textarea id="export_text">...</textarea>
			...
		    <div id="tab1">
				<p>...</p>
			</div>
			<div id="tab2">
				<p>...</p>
			</div>
			<div id="tab3">
				<p>...</p>
			</div>
			...
		 </div>
		 
		 *  @param {string} base_mode Mode in the beginning.
		 *  @param {string} base_action Action in the beginning.
		 *  @param {Object} actions actions[id]=action
		 *  @param {Object} preview_obj
		 *  @param {Object} menu_obj
		 *  @param {Object} i_box
		 *  @constructor
		 *  @class Click_Action_Page
		 */
		function Click_Action_Page(base_mode, base_action, actions, preview_obj, menu_obj, i_box, url) {
			/**
			 *  Contains the current mode. Should only be modified with the change_page method.
			 *
			 *  @property current_mode
			 *  @type String|int
			 */
			this.current_mode   = base_mode;
			
			/**
			 *  Contains the current action. Should only be modified with the change_page method.
			 *
			 *  @property current_action
			 *  @type String
			 */
			this.current_action = base_action;
			
			/**
			 *  A reference to the page DOM-element. This will be used to set the additional class for the current mode.
			 *  
			 *  @property $page
			 *  @type Object
			 *  @default jQuery("#click_actions_page")
			 */
			this.$page = jQuery("#click_actions_page");
			
			/**
			 *  The Form Object.
			 *  
			 *  @property form
			 *  @type Page_Form
			 */
			this.form = new Page_Form("#form");
			this.configure_form();
			
			this.modes          = ["edit_mode", "new_mode", "delete_mode", "import_mode", "export_mode", "welcome_mode"];
			this.actions        = actions;
			this.url            = url;//"";
			this.preview        = preview_obj;
			this.$tab1          = jQuery("#tab1 p");
			this.$tab2          = jQuery("#tab2 p");
			this.$tab3          = jQuery("#tab3 p");
			this.$description   = jQuery("#s_desciption");
			this.$export        = jQuery("#export_text");
			this.$message       = jQuery("#message");
			//this.menu           = menu_obj;
			this.ibox           = i_box;
			
			this.init();
		}
		
		Click_Action_Page.prototype = new EventTarget();
		
		Click_Action_Page.prototype.init = function() {
			this.bind_events();
		}
		
		/**
		 *  Binds all events for the click action page.
		 *  
		 *  @method bind_events
		 */
		Click_Action_Page.prototype.bind_events = function() {
			var _this = this;
			
			//this.preview.bind_events();
			/*this.menu.addListener("click", function() {
				_this.hide_message();
				_this.change_page(_this.menu.clicked_id, "show", true);
			});*/
			/*this.menu.addListener("dblclick", function() {
				_this.change_page(_this.menu.clicked_id, "edit", true);
			});*/
			this.ibox.set_click(this, this.open_import_click);
		}
		
		/**
		 *  Configures and adds the elements of the form.
		 *  
		 *  @method configure_form
		 */
		Click_Action_Page.prototype.configure_form = function() {
			this.form.add_element("id",          "id");
			this.form.add_element("name",        "name");
			this.form.add_element("description", "description");
			this.form.add_element("tab1",        "data_table_value_1");
			this.form.add_element("tab2",        "data_table_value_2");
			this.form.add_element("tab3",        "data_table_value_3");
			this.form.add_element("html",        "html");
			this.form.add_element("css",         "css");
			this.form.add_element("js",          "js");
		}
		
		/**
		 *  A method to receive the click event from the import box.
		 *  
		 *  @method open_import_click
		 */
		Click_Action_Page.prototype.open_import_click = function(name) {
			if(name=="open") {
				this.change_page(this.current_action, "import", true);
			} else if(name=="submit") {
				this.change_page(this.current_action, "import2", false);
			}
		}
		
		/**
		 *  Sets the class for the page element.
		 *  
		 *  @param {string} classes_to_add Classes that should be added, divided by a space.
		 *  @method set_class
		 */
		Click_Action_Page.prototype.set_class = function(classes_to_add) {
			this.$page.removeClass(this.modes.join(' ')).addClass(classes_to_add);
		}
		
		/**
		 *  Changes the page.
		 *  
		 *  <b style="color: red;">This is the most important and mainly used method in this object.</b>
		 *  
		 *  @param {string|int} action
		 *  @param {string} mode
		 *  @param {boolean} has_to_set_url If true, it will be added to the browsers history and will change
		 *  the url.
		 *  @method change_page
		 */
		Click_Action_Page.prototype.change_page = function(action, mode, has_to_set_url) {
			if(this.current_mode==mode && this.current_action==action) // Already on the right page?
				return;
			if(!this.there_is_unsaved_data_in_form(action,mode)) { // Changing the site without saving?
				if(has_to_set_url) {
					this.set_url(action, mode);
				}
				this.set_iframe(action);
				this.set_static_elements(action);
				this.set_form_elements(action, mode);
				this.set_page_design(action,mode);
				this.fire_events(action,mode);
				
				this.current_action = action;
				this.current_mode   = mode;
				
				this.page_ready();
				
				this.fire("change");
			}
		}
		
		Click_Action_Page.prototype.fire_events = function(action, mode) {
			if(this.current_mode != mode) {
				this.current_mode   = mode;
				this.fire("mode_change");
			}
			if(this.current_action != action) {
				this.current_action = action;
				this.fire("action_change");
			}
		}
		
		/**
		 *  Checks if the is unsaved data in the form.		
		 *
		 *  @param {string|int} action 
		 *  @param {string} mode 
		 */
		Click_Action_Page.prototype.there_is_unsaved_data_in_form = function(action,mode) {
			return false; // Don't use this in version 1.0
			if(action=="import" || action=="export")
				return false; // shows only the import box

			if(this.current_mode=="edit") {
				
			} else if(this.current_mode=="new") {
				var has_new_data = this.form.has_new_data(this.actions[this.current_action]);
				
				if(has_new_data) {
					jQuery(".messagebg").fadeIn();
					jQuery("#warning_box").fadeIn();
					return true;
				}
			}
			return false;
		}
		
		/**
		 *  Creates an url from the given action and mode and adds it to the browsers history.
		 *  
		 *  @param {string|int} action
		 *  @param {string} mode
		 *  @method set_url
		 */
		Click_Action_Page.prototype.set_url = function(action, mode) {
			url     = this.url + "&action=" + action + "&mode=" + mode;
			state   = {action: action, mode: mode};
			history.pushState(state, null, url);
		}
		
		/**
		 *  Loads the preview, depending on the selected mode.
		 *  
		 *  @param {string|int} action
		 *  @method set_iframe
		 */
		Click_Action_Page.prototype.set_iframe = function(action) {
			if(action!=this.current_action && action!="copy" && action!="import") {
				this.preview.load(action);
			}
		}
		
		/**
		 *  Sets the static elements: Sample Table, Description, Export
		 *  
		 *  @param {string|int} action
		 *  @method set_static_elements
		 */
		Click_Action_Page.prototype.set_static_elements = function(action) {
			this.set_table(action);
			this.set_description(action);
			this.set_export(action);
		}
		
		/**
		 *  Sets the static values in the Sample Table.
		 *  
		 *  @param {string|int} action
		 *  @method set_table
		 */
		Click_Action_Page.prototype.set_table = function(action) {	
			this.data_tab_setter(this.$tab1, this.actions[action]["tab1_esc"]);
			this.data_tab_setter(this.$tab2, this.actions[action]["tab2_esc"]);
			this.data_tab_setter(this.$tab3, this.actions[action]["tab3_esc"]);
		}
		
		/**
		 *  Decorator for the static sample table values.
		 *  
		 *  @param {Object} element The element where the text should be inserted.
		 *  @param {string} string The Text to insert.
		 *  @method data_tab_setter
		 */
		Click_Action_Page.prototype.data_tab_setter = function(element, string) {
			var notext = "- No data -";
			if(string!="" && string!=null)
				element.html(string).attr("title",string).removeClass("nodata");
			else
				element.html(notext).addClass("nodata");
		}
		
		/**
		 *  Sets the description for the given action.
		 *  
		 *  @param {string|int} action
		 *  @method set_description
		 */
		Click_Action_Page.prototype.set_description = function(action) {
			this.$description.html( this.actions[action]["description_esc"] );
		}
		
		/**
		 *  Sets the export data for the given action.
		 *  
		 *  @param {string|int} action
		 *  @method set_export
		 */
		Click_Action_Page.prototype.set_export = function(action) {
			this.$export.val( this.actions[action]["export"] );
		}
		
		/**
		 *  Sets the form elements for the given action, depending on the selected mode.
		 *  
		 *  Due to the transitions between new=>show and edit=>show, the form won't be set, if mode=="show".
		 *  Otherwise the new data would be visible before the transition has started.
		 *  
		 *  @param {string|int} action
		 *  @param {string} mode
		 *  @method set_form_elements
		 */
		Click_Action_Page.prototype.set_form_elements = function(action, mode) {
			if(mode!="show") {
				this.form.fill(this.actions[action]);
			}
		}
		
		/**
		 *  Sets the page design depending on the selected mode.
		 *  
		 *  @param {string|int} action
		 *  @param {string} mode
		 *  @method set_page_design
		 */
		Click_Action_Page.prototype.set_page_design = function(action,mode) {
			if(mode=="show") {
				this.set_class("");
				//this.menu.highlight(action);
				//this.fire("menu_highlight");
				if(action==-1) {
					this.set_class("welcome_mode");
				}
			} else if(mode=="new") {
				//this.menu.show_field(-1);
				this.set_class("new_mode");
				//this.menu.dehighlight();
				//this.fire("menu_new");
			} else if(mode=="edit") {
				//this.menu.show_field(action);
				this.set_class("edit_mode");
			} else if(mode=="delete") {
				this.set_class("delete_mode");
			} else if(mode=="import") {
				if(action==-1) {
					this.set_class("import_mode welcome_mode");
				} else {
					this.set_class("import_mode");
				}
			} else if(mode=="export") {
				this.set_class("export_mode");
			} else if(mode=="copy") {
				jQuery("#import_text").val("");
				jQuery(".head a").blur();
			}
		}
		
		/**
		 *  This method is the last method that gets called in change_page.
		 *  
		 *  Right now its doing the following:
		 *  - checks if the label has to be shown for the filled elements.
		 *  
		 *  @method page_ready
		 */
		Click_Action_Page.prototype.page_ready = function() {
			jQuery("label.layover").siblings("input, textarea")
				.each(function() {
					if(jQuery(this).val()=="")
						jQuery(this).siblings("label").show();
					else
						jQuery(this).siblings("label").hide();
				});
		}
		
		/**
		 *  Fades out the message box.
		 *  
		 *  @method fadeout_message
		 */
		Click_Action_Page.prototype.fadeout_message = function() {
			$("#ca-message").fadeOut();
		}
		
		/**
		 *  Checks if the returned transaction data object is valid.
		 *  
		 *  @param {object} data
		 *  @method is_valid_transaction
		 *  @return boolean
		 */
		Click_Action_Page.prototype.is_valid_transaction = function(data) {
			if(typeof data["id"] === "undefined" || typeof data["mode"] === "undefined")
				return false;
			return true;
		}
	
		/**
		 *  Does the transaction.
		 *  
		 *  @method do_transaction
		 */
		Click_Action_Page.prototype.do_transaction = function() {
			var _this = this;
			this.show_button_spinner();
			this.fadeout_message();
			jQuery.ajax({
				url: 		ajaxurl,
				data:		{
								'action':'imb_save_clickaction',
								'mode'  : this.current_mode,
								'form'  : this.form.get_data(),
							},
				dataType: 	'JSON',
				success: 	function(data) {
								_this.hide_button_spinner();
								if(_this.is_valid_transaction(data)) {
									_this.set_message(data, "normal");
									_this.actions = data.click_actions;
									_this.menu.update_data(data);
									_this.current_action = data.id;
									_this.current_mode   = "edit";
									_this.change_page(data.id, "show", true);
								} else {
									_this.set_message(data, "error");
								}
							},
				error:		function(e) {
								_this.hide_button_spinner();
							}
			});
		}
		
		Click_Action_Page.prototype.show_button_spinner = function() {
			jQuery("#main_spinner").fadeIn();
			/*if(this.current_mode=="new") {
				jQuery("#main_spinner").fadeIn();
			} else if(this.current_mode=="edit") {
				jQuery("#main_spinner").fadeIn();
			} else if(this.current_mode=="copy") {
				jQuery("#main_spinner").fadeIn();
			}*/
		}
		
		Click_Action_Page.prototype.hide_button_spinner = function() {
			jQuery(".spinner").hide();
		}
		
		Click_Action_Page.prototype.hide_message = function() {
			this.$message.css("visibility", "hidden");
		}
		Click_Action_Page.prototype.show_message = function(message, css_class, box_mode) {
			var div = this.$message;
			div.find("p").hide();
			
			var p = this.$message.find(css_class);
			if(message!=="") {
				p.html(message);
			}
			p.show();

			div.removeClass("updated error").addClass(box_mode).css("visibility", "visible");
			div.slideDown();
		}
		Click_Action_Page.prototype.set_message = function(data, type) {
			if(type=="normal")
				this.show_message(data.message, ".success", "updated");
			else if(type=="error")
				this.show_message(data.message, ".success", "error");
		}
		return Click_Action_Page;
	})();
	