/**
 * page resultats
 * @autor: Francesc Requesens
 * @date: 2013-10-13
 * 
 */

(function($){
	
	$.fn.upage = function(options){

		var defaults = {
			numItems: 0,
			page: 1,
			maxResults: 20,
			callback: function(){}
		};

		// method calling
		if (typeof options == 'string') {
			var args = Array.prototype.slice.call(arguments, 1);
			var res;
			this.each(function() {
				var llista = $.data(this, 'upage');
				if (llista && $.isFunction(llista[options])) {
					var r = llista[options].apply(llista, args);
					if (res === undefined)
						res = r;
					if (options == 'destroy')
						$.removeData(this, 'upage');
				}
			});
			if (res !== undefined)
				return res;
			return this;
		}
		
		options = $.extend(defaults, options || {});
		
		this.each(function(i, _element) {
			var element = $(_element);
			var llista = new UPage(element, options);
			
			element.data('upage', llista); // TODO: look into memory leak implications
			llista.init();
		});
		
		return this;
	};
	
	function UPage(element, options){
		var t = this;
	
		// exports
		t.options 		= options;
		t.init 			= init;
		t.clean			= clean;
		t.destroy 		= destroy;
		
		// imports
		var numItems 	= options.numItems;
		var maxResults	= options.maxResults;
		var next        = options.next;
		var previous    = options.previous;
		var page 		= options.page;
		var callback	= options.callback;
		
		//Public methods
		function init(){
			//Pagina si és necessari
			if (maxResults < numItems) {
			
				var lastPage = numItems / maxResults;
				if (numItems % maxResults)
					lastPage = parseInt(lastPage) + 1;

				var $wrapPage = $('<div />',{
					'class' : 'llista-wrap-pagina'
				}).appendTo(element);
				
				var $page = $('<div />',{
					'class' : 'llista-pagina'
				}).appendTo($wrapPage);

				// Begin
				$('<div />',{
					'class' : 'llista-pagina-principi ui-icon ui-icon-arrowthickstop-1-w',
					click : function() {
						page = 1;
						jumpToPage($pageText, page, lastPage);
					}
				}).appendTo($page);

				// Previous
				$('<div />',{
					'class' : 'llista-pagina-ant ui-icon ui-icon-arrowthick-1-w',
					click : function(){
						if (page > 1){
							page = page - 1;
							jumpToPage($pageText, page, lastPage);
						}
					}
				}).appendTo($page);

				// Text page
				var $pageText = $('<div />',{
					'class' : 'llista-pagina-text',
					text : page + ' / ' + lastPage
				}).appendTo($page);

				// Next
				$('<div />',{
					'class' : 'llista-pagina-seg ui-icon ui-icon-arrowthick-1-e',
					click : function(){
						if (page < lastPage){
							page = page + 1;
							jumpToPage($pageText, page, lastPage);
						}
					}
				}).appendTo($page);

				// Last
				$('<div />',{
					'class' : 'llista-pagina-final ui-icon ui-icon-arrowthickstop-1-e',
					click : function(){
						page = lastPage;
						jumpToPage($pageText, page, lastPage);
					}
				}).appendTo($page);
			}
		}
		function clean(){element.html('');}
		function destroy(){element.remove();}
		function jumpToPage($pageText, page, lastPage){
			$pageText.text(page + ' / ' + lastPage);
			callback(page);
		}
	}
})(jQuery);