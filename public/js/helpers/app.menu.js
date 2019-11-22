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
		var theme = 'mf-menu';
		const menuBtn = $('.header .menu-btn')[0];
		// public methods
		function init() {			
			var arr = [];
			for (var k in data) arr.push(k);
			for (let index = arr.length - 1; index >= 0; index--) {
				const k = arr[index];
				$menuItems[k] = $('<' + typeElement + ' />',{
					'class' : theme + '-item',
				}).prependTo(element);

				$('<a />',{
					'class' : theme + '-item-link',
					href : data[k].link,
					text : data[k].text
				}).on('click', function(event) {menuBtn.checked = false;}).appendTo($menuItems[k]);
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