/**
 * Plugin mainMenu
 * Data Name: mainmenu
 * Function Name: MainMenu
 * 
 * @author Francesc Requesens
 * @version 0.1 2013-10-15
 *
 */

;(function($) {
  
	$.fn.mainMenu = function(options) {
    	
    	// default options
		var defaults = {
			data : {},
			typeElement : 'li'
		};

		// method calling
		if( typeof options == 'string') {
			var args = Array.prototype.slice.call(arguments, 1);
			var res;
			this.each(function() {
				var udata = $.data(this, 'mainmenu');
				if(udata && $.isFunction(udata[options])) {
					var r = udata[options].apply(udata, args);
					if(res === undefined)
						res = r;
					if(options == 'destroy')
						$.removeData(this, 'mainmenu');
				}
			});
			if(res !== undefined)
				return res;
			return this;
		}
		options = $.extend(defaults, options || {});
	    
		this.each(function(i, _element) {
			var element = $(_element);
			var udata = new MainMenu(element, options);

			element.data('mainmenu', udata);
			udata.init();
		});
		return this;
	};
  
	function MainMenu(element, options) {
		var t = this;

		// exports
		t.options = options;
		t.init = init;
		t.destroy = destroy;
		t.active = active;
		t.deactive = deactive;
		
		// imports
		var typeElement = options.typeElement;
		var data = options.data;

		// locals
		var $menuItems = {};
		var theme = 'u-menu';

		// public methods
		function init() {

			//Clean
			clean();
			
			//Generate Menu
			for (var k in data){
				$menuItems[k] = $('<' + typeElement + ' />',{
					'class' : theme + '-item',
				}).appendTo(element);
				
				$('<a />',{
					'class' : theme + '-item-link',
					href : data[k].link,
					text : data[k].text
				}).appendTo($menuItems[k]);
			}
		}

		function destroy() {
			element.remove();
		}

		function clean() {
			element.html('');
		}

		function deactive(){
			element.find(typeElement).removeClass('active');
		}
		
		function active(k){
			deactive();
			if ($menuItems.hasOwnProperty(k))
				$menuItems[k].addClass('active');
		}
	}
})(jQuery);