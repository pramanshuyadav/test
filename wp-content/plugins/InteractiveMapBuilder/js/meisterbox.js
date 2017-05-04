/**
 * jQuery Plugin to obtain touch gestures from iPhone, iPod Touch and iPad, should also work with Android mobile phones (not tested yet!)
 * Common usage: wipe images (left and right to show the previous or next image)
 * 
 * @author Andreas Waltl, netCU Internetagentur (http://www.netcu.de)
 * @version 1.1.1 (9th December 2010) - fix bug (older IE's had problems)
 * @version 1.1 (1st September 2010) - support wipe up and wipe down
 * @version 1.0 (15th July 2010)
 */
(function($){$.fn.touchwipe=function(settings){var config={min_move_x:20,min_move_y:20,wipeLeft:function(){},wipeRight:function(){},wipeUp:function(){},wipeDown:function(){},preventDefaultEvents:true};if(settings)$.extend(config,settings);this.each(function(){var startX;var startY;var isMoving=false;function cancelTouch(){this.removeEventListener('touchmove',onTouchMove);startX=null;isMoving=false}function onTouchMove(e){if(config.preventDefaultEvents){e.preventDefault()}if(isMoving){var x=e.touches[0].pageX;var y=e.touches[0].pageY;var dx=startX-x;var dy=startY-y;if(Math.abs(dx)>=config.min_move_x){cancelTouch();if(dx>0){config.wipeLeft()}else{config.wipeRight()}}else if(Math.abs(dy)>=config.min_move_y){cancelTouch();if(dy>0){config.wipeDown()}else{config.wipeUp()}}}}function onTouchStart(e){if(e.touches.length==1){startX=e.touches[0].pageX;startY=e.touches[0].pageY;isMoving=true;this.addEventListener('touchmove',onTouchMove,false)}}if('ontouchstart'in document.documentElement){this.addEventListener('touchstart',onTouchStart,false)}});return this}})(jQuery);

/*! Copyright (c) 2013 Brandon Aaron (http://brandon.aaron.sh)
* Licensed under the MIT License (LICENSE.txt).
*
* Version: 3.1.6
*
* Requires: jQuery 1.2.2+
*/

(function (factory) {
    if ( typeof define === 'function' && define.amd ) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        // Node/CommonJS style for Browserify
        module.exports = factory;
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function ($) {

    var toFix = ['wheel', 'mousewheel', 'DOMMouseScroll', 'MozMousePixelScroll'],
        toBind = ( 'onwheel' in document || document.documentMode >= 9 ) ?
                    ['wheel'] : ['mousewheel', 'DomMouseScroll', 'MozMousePixelScroll'],
        slice = Array.prototype.slice,
        nullLowestDeltaTimeout, lowestDelta;

    if ( $.event.fixHooks ) {
        for ( var i = toFix.length; i; ) {
            $.event.fixHooks[ toFix[--i] ] = $.event.mouseHooks;
        }
    }

    $.event.special.mousewheel = {
        version: '3.1.6',

        setup: function() {
            if ( this.addEventListener ) {
                for ( var i = toBind.length; i; ) {
                    this.addEventListener( toBind[--i], handler, false );
                }
            } else {
                this.onmousewheel = handler;
            }
        },

        teardown: function() {
            if ( this.removeEventListener ) {
                for ( var i = toBind.length; i; ) {
                    this.removeEventListener( toBind[--i], handler, false );
                }
            } else {
                this.onmousewheel = null;
            }
        }
    };

    $.fn.extend({
        mousewheel: function(fn) {
            return fn ? this.bind('mousewheel', fn) : this.trigger('mousewheel');
        },

        unmousewheel: function(fn) {
            return this.unbind('mousewheel', fn);
        }
    });


    function handler(event) {
        var orgEvent = event || window.event,
            args = slice.call(arguments, 1),
            delta = 0,
            deltaX = 0,
            deltaY = 0,
            absDelta = 0;
        event = $.event.fix(orgEvent);
        event.type = 'mousewheel';

        // Old school scrollwheel delta
        if ( 'detail' in orgEvent ) { deltaY = orgEvent.detail * -1; }
        if ( 'wheelDelta' in orgEvent ) { deltaY = orgEvent.wheelDelta; }
        if ( 'wheelDeltaY' in orgEvent ) { deltaY = orgEvent.wheelDeltaY; }
        if ( 'wheelDeltaX' in orgEvent ) { deltaX = orgEvent.wheelDeltaX * -1; }

        // Firefox < 17 horizontal scrolling related to DOMMouseScroll event
        if ( 'axis' in orgEvent && orgEvent.axis === orgEvent.HORIZONTAL_AXIS ) {
            deltaX = deltaY * -1;
            deltaY = 0;
        }

        // Set delta to be deltaY or deltaX if deltaY is 0 for backwards compatabilitiy
        delta = deltaY === 0 ? deltaX : deltaY;

        // New school wheel delta (wheel event)
        if ( 'deltaY' in orgEvent ) {
            deltaY = orgEvent.deltaY * -1;
            delta = deltaY;
        }
        if ( 'deltaX' in orgEvent ) {
            deltaX = orgEvent.deltaX;
            if ( deltaY === 0 ) { delta = deltaX * -1; }
        }

        // No change actually happened, no reason to go any further
        if ( deltaY === 0 && deltaX === 0 ) { return; }

        // Store lowest absolute delta to normalize the delta values
        absDelta = Math.max( Math.abs(deltaY), Math.abs(deltaX) );
        if ( !lowestDelta || absDelta < lowestDelta ) {
            lowestDelta = absDelta;
        }

        // Get a whole, normalized value for the deltas
        delta = Math[ delta >= 1 ? 'floor' : 'ceil' ](delta / lowestDelta);
        deltaX = Math[ deltaX >= 1 ? 'floor' : 'ceil' ](deltaX / lowestDelta);
        deltaY = Math[ deltaY >= 1 ? 'floor' : 'ceil' ](deltaY / lowestDelta);

        // Add information to the event object
        event.deltaX = deltaX;
        event.deltaY = deltaY;
        event.deltaFactor = lowestDelta;

        // Add event and delta to the front of the arguments
        args.unshift(event, delta, deltaX, deltaY);

        // Clearout lowestDelta after sometime to better
        // handle multiple device types that give different
        // a different lowestDelta
        // Ex: trackpad = 3 and mouse wheel = 120
        if (nullLowestDeltaTimeout) { clearTimeout(nullLowestDeltaTimeout); }
        nullLowestDeltaTimeout = setTimeout(nullLowestDelta, 200);

        return ($.event.dispatch || $.event.handle).apply(this, args);
    }

    function nullLowestDelta() {
        lowestDelta = null;
    }

}));

jQuery(function() {
	$=jQuery;
	$.event.special.tap = {
		// Abort tap if touch moves further than 10 pixels in any direction
		distanceThreshold: 10,
		// Abort tap if touch lasts longer than half a second
		timeThreshold: 500,
		setup: function() {
			var self = this,
			$self = $(self);

			// Bind touch start
			$self.on('touchstart', function(startEvent) {
				// Save the target element of the start event
				var target = startEvent.target,
				touchStart = startEvent.originalEvent.touches[0],
				startX = touchStart.pageX,
				startY = touchStart.pageY,
				threshold = $.event.special.tap.distanceThreshold,
				timeout;

				function removeTapHandler() {
					clearTimeout(timeout);
					$self.off('touchmove', moveHandler).off('touchend', tapHandler);
				};

				function tapHandler(endEvent) {
					removeTapHandler();

					// When the touch end event fires, check if the target of the
					// touch end is the same as the target of the start, and if
					// so, fire a click.
					if (target == endEvent.target) {
						$.event.simulate('tap', self, endEvent);
					}
				};

				// Remove tap and move handlers if the touch moves too far
				function moveHandler(moveEvent) {
					var touchMove = moveEvent.originalEvent.touches[0],
					moveX = touchMove.pageX,
					moveY = touchMove.pageY;

					if (Math.abs(moveX - startX) > threshold ||
						Math.abs(moveY - startY) > threshold) {
						removeTapHandler();
					}
				};

				// Remove the tap and move handlers if the timeout expires
				timeout = setTimeout(removeTapHandler, $.event.special.tap.timeThreshold);

				// When a touch starts, bind a touch end and touch move handler
				$self.on('touchmove', moveHandler).on('touchend', tapHandler);
			});
		}
	};
});

			jQuery(function() {
				jQuery("#meisterbox_overlay").click(function() {
					jQuery(this).fadeOut();
				});
			
			});
			
			Meisterbox = (function() {
				var $=jQuery, data, current, hooked=false;
				var $all, $box, $overlay, $left, $right, $bottom, $slides, $circles;
				
				function Meisterbox(data) {
				  this.data = data;
				  this.init();
				}

				Meisterbox.prototype.init = function() {
					this.$slides  = [];
					this.$circles = [];
					this.current  = 0;
					this.scrollright = 0;
					this.scrollleft  = 0;
					this.create_elements();
					this.bind_methods();
					this.prepare();
				};
				
				Meisterbox.prototype.prepare = function() {
					$.each(this.$slides, function(i, v) {
						v.hide().css({ marginLeft:  -18, marginTop: -18, opacity: 0.3});
					});
					this.$slides[this.current].show();
					this.$circles[this.current].addClass("selected");
				}
				
				Meisterbox.prototype.show_slide = function(i) {
					var pos = i;
					if(pos>=this.data.length)
						pos = 0;
					if(pos<0)
						pos = this.data.length-1;
					
					this.load_slide(pos);
					this.resize_slide(pos);
					this.$slides[pos].show();
					this.$bottom.find(".meister_circle").removeClass("selected");
					this.$circles[pos].addClass("selected");
					this.current = pos;
					$.each(this.$slides, function(i, v) {
						if(pos!=i)
							v.hide();
					});
				}
				
				Meisterbox.prototype.load_slide = function(i) {
					var _i         = i;
					var _this      = this;
					var src        = this.data[i]["src"];
					var $slide     = this.$slides[i];
					var $img       = $slide.find("img");
					
					if($img.attr("status")!=="notloaded")
						return;
						
					loader         = new Image();
					loader.onload  = function() {
						$slide.css("visibility", "hidden");
						$img.attr("src", src)
							.attr("status", "loaded")
							.attr("o_width", loader.width)
							.attr("o_height", loader.height);
						
						_this.resize_slide(_i);
					}
					
					loader.src = src;
				}
				
				Meisterbox.prototype.resize_slide = function(i) {
					var max_width  = $(document).width() - 128 - 128 - 30;
					var max_height = $(document).height() - this.$bottom.height()*2 - 30-52;
					if(document!==top.document) {
						max_width  = $(top).width() - 128 - 128 - 30;
						max_height = $(top).height() - this.$bottom.height()*2 - 30-52;
					}
					//console.log(document===top.document);
					var $slide     = this.$slides[i];
					var $img       = $slide.find("img");
					
					if($img.attr("status")==="notloaded")
						return;
						
					var image_width  = parseInt($img.attr("o_width"),10);
					var image_height = parseInt($img.attr("o_height"),10);
						
						
					if ((image_width > max_width) || (image_height> max_height)) {
						if ((image_width / max_width) > (image_height / max_height)) {
							image_height = parseInt(image_height / (image_width / max_width), 10);
							image_width  = max_width;
						} else {
							image_width  = parseInt(image_width / (image_height / max_height), 10);
							image_height = max_height;
						}
					}
					$slide.stop().css({ marginLeft:  -(image_width+22)/2, marginTop: -(image_height+22)/2, opacity: 1.0}).stop().css("visibility", "visible");
					$img.height(image_height)
						.width(image_width);
					
				}
				
				Meisterbox.prototype.create_elements = function() {
					var _this = this;
					this.create_slides();
					this.$all     = $("<div>");
					this.$box     = $("<div>").addClass("meisterbox");
					$.each(this.$slides, function(i, v) {
						_this.$box.append(v);
					});
					this.$overlay = $("<div>").addClass("meisterbox_overlay");
					this.$box.append(this.$overlay);
					this.$left    = $("<div>").addClass("meisterbox_left");
					this.$right   = $("<div>").addClass("meisterbox_right");
					this.$bottom  = $("<div>").addClass("meisterbox_bottom");
					$.each(this.$circles, function(i, v) {
						_this.$bottom.append(v);
					});
					this.$all.append(this.$box)
					     	 .append(this.$left)
						     .append(this.$right)
						     .append(this.$bottom);
				};
				
				Meisterbox.prototype.create_slides = function() {
					var _this = this;
					$.each(this.data, function(i, v) {
						var type=v["type"], src=v["src"], caption=v["caption"];
						
						$slide = $("<div>").addClass("meisterbox_content").attr("slide", i);
						$img   = $("<img>").attr("status", "notloaded");//.attr("src", "../images/ajax-loader.gif")
						$slide.append($img);
						if(caption!="") {
							$div   = $("<div>").addClass("meisterbox_caption").hide();
							$p     = $("<p>").text(v["caption"]);
							$slide.append($div.append($p));
						}
						$circle = $("<span>").addClass("meister_circle");
						_this.$slides.push($slide);
						_this.$circles.push($circle);
					});
				}
				
				Meisterbox.prototype.enable = function(element) {
					if(!this.hooked)
						$("body", top.document).append(this.$all);
					this.$all.fadeIn();
					this.load_slide(this.current);
					this.enable_keyboard();
					this.enable_resize();
				}
				
				Meisterbox.prototype.enable_keyboard = function() {
					var _this = this;
					$(top.document).bind("keyup", function(e) {
						var code = e.keyCode;
						if(code==39)
							_this.show_slide(_this.current+1);
						else if(code==37)
							_this.show_slide(_this.current-1);
						else if(code===27)
							_this.disable();
						//console.log(e);
					});
				}
				
				Meisterbox.prototype.enable_resize = function() {
					var _this = this;
					$(top).bind("resize", function(e) {
						//console.log(e);
						_this.resize_slide(_this.current);
					});
					_this.resize_slide(_this.current);
				}
				Meisterbox.prototype.disable_resize = function() {
					$(top).unbind("resize");
				}
				Meisterbox.prototype.disable_keyboard = function() {
					$(document).unbind("keyup");
				}
				
				Meisterbox.prototype.disable = function(element) {
					this.$all.fadeOut();
					this.disable_keyboard();
					this.disable_resize();
				}
				
				Meisterbox.prototype.bind_methods = function(element) {
					var _this = this;
					this.$overlay.click(function() {
						_this.disable();
					});
					this.$left.bind("tap click", function(e) {
						_this.show_slide(_this.current-1);
						e.preventDefault();
					});

					this.$right.on("tap click", function(e) {
						_this.show_slide(_this.current+1);
						e.preventDefault();
					});

					$.each(this.$slides, function(i, v) {
						var __this = _this;
						v.mouseenter(function() {
							jQuery(this).find(".meisterbox_caption").fadeIn();
						});
						v.mouseleave(function() {
							jQuery(this).find(".meisterbox_caption").fadeOut();
						});
					});
					this.$all.touchwipe({
							wipeLeft: function() { _this.show_slide(_this.current+1); },
							wipeRight: function() { _this.show_slide(_this.current-1); },
							min_move_x: 40,
							min_move_y: 40,
						});
					$.each(this.$circles, function(i, v) {
						var __this = _this;
						v.click(function() {
							__this.show_slide(i);
						});
					});
					this.$all.bind('touchmove', function(event) { event.preventDefault() });
					this.$all.on('mousewheel', function(event) {
						if(event.deltaY==-1) {
							if(_this.scrollright>=1) {
								_this.show_slide(_this.current+1);
								_this.scrollright=0;
							} else {
								_this.scrollright++;
							}
							_this.scrollleft=0;
						} else if(event.deltaY==1) {
							if(_this.scrollleft>=1) {
								_this.show_slide(_this.current-1);
								_this.scrollleft=0;
							} else {
								_this.scrollleft++;
							}
							_this.scrollright=0;
							
						}
						event.preventDefault();
						//console.log(event.deltaX, event.deltaY, event.deltaFactor);
					});
				}
				
				Meisterbox.prototype.set_background_color = function(color) {
					this.$overlay.css({backgroundColor: color});
					return this;
				}
				
				Meisterbox.prototype.set_background_opacity = function(op) {
					this.$overlay.css({opacity: op});
					return this;
				}
				
				return Meisterbox;
				
			})();