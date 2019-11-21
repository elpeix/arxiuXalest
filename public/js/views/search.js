/**
 * Llista Partitures
 * @autor: Francesc Requesens
 * @date: 2011-09-02
 * 
 */
(function($){
	$.fn.search = function(options){
		var defaults = {};
		
		// method calling
		if (typeof options == 'string') {
			var args = Array.prototype.slice.call(arguments, 1);
			var res;
			this.each(function() {
				var udata = $.data(this, 'search');
				if (udata && $.isFunction(udata[options])) {
					var r = udata[options].apply(udata, args);
					if (res === undefined)
						res = r;
					if (options == 'destroy')
						$.removeData(this, 'search');
				}
			});
			if (res !== undefined)
				return res;
			return this;
		}
		
		options = $.extend(defaults, options || {});
		
		this.each(function(i, _element) {
			var element = $(_element);
			var udata = new Search(element, options);
			
			element.data('search', udata);
			udata.init();
		});
		
		return this;
	};
	
	function Search(element, options){
		var t = this;
	
		t.options 		= options;
		t.init 			= init;
		t.destroy 		= destroy;
		t.clean			= clean;
		
		//Public methods
		function init(){
			clean();
			
			var $input, $button;
			
			var $main = $('<div />',{
				'class' : 'search-main'
			}).appendTo(element);
			
			$('<h1 />',{
				'class' : 'search-title',
				text : 'Arxiu partitures'
			}).appendTo($main);
			
			var $form = $('<form />',{
				action : '#',
				'class' : 'cercador',
				submit : function(){
					var inputVal = $input.val();
					if (inputVal){
						if (app.advancedFilter) {
							app.filter = [[
								{
									sch : 'score',
									field: 'name',
									type : 'like',
									val : inputVal
								},
								{
									sch : 'composer',
									field: 'name',
									type : 'like',
									val : inputVal
								}
							]];
						} else {
							app.filter = [{
								sch : 'name',
								field: 'scores.name',
								type : '',
								val : inputVal
							}];
						}
						routie('scores');
					}
				}
			}).appendTo($main);
			
			$input = $('<input />',{
				'class' : 'search-text',
				type : 'text',
				placeholder: 'Partitures',
			}).appendTo($form);
			
			$button = $('<input />',{
				type: 'submit',
				'class' : 'search-button',
				value: 'Cerca'
			}).appendTo($form);

			$('<div />',{
				'class' : 'search-circle',
				'html': '<img src="css/images/logo.png" />'
			}).appendTo($main);
			
			$input.focus();
		}
		function destroy(){
			element.html('');
		}
		function clean(){
			element.html("");
		}
		function consulta(list){}		
	}
})(jQuery);