/**
 * Plugin inputTags
 * Data Name: inputtags
 * Function Name: InputTags
 * 
 * @author Francesc Requesens
 * @version 0.1 2013-10-30
 */

;(function($) {
	$.fn.inputTags = function(options) {
    	
    	// default options
		var defaults = {
			maxTags : null,
			autosync : false, // true | false
			theme : 'u-inputtags',
			dataName : 'tags'
		};
    
		// method calling
		if( typeof options == 'string') {
			var args = Array.prototype.slice.call(arguments, 1);
			var res;
			this.each(function() {
				var udata = $.data(this, 'inputtags');
				if(udata && $.isFunction(udata[options])) {
					var r = udata[options].apply(udata, args);
					if(res === undefined)
						res = r;
					if(options == 'destroy')
						$.removeData(this, 'inputtags');
				}
			});
			if(res !== undefined)
				return res;
			return this;
		}
		options = $.extend(defaults, options || {});
	    
		this.each(function(i, _element) {
			var element = $(_element);
			var udata = new InputTags(element, options);

			element.data('inputtags', udata);
			udata.init();
		});
		return this;
	};
  
	function InputTags(element, options) {
		var t = this;

		// exports
		t.options = options;
		t.init = init;
		t.destroy = destroy;
		t.getData = getData;
		t.addTag = addTag;
		
		// imports
		var maxTags = options.maxTags;
		var autosync = options.autosync;
		var theme = options.theme;
		var dataName = options.dataName;

		// locals
		var $input;
		var data = [];

		// public methods
		function init() {

			//Clean
			clean();
			
			//Add Class
			element.addClass(theme);
			
			//Create input
			$input = $('<input />',{
				type : 'text',
				'class' : theme + '-input',
				focusin : function(){
					element.addClass('onfocus');
				},
				focusout : function(){
					element.removeClass('onfocus');
					if ($input.val() && data_add($input.val()))
						generateTag($input.val()).insertBefore($input);
					$input.val('');
					
				},
				keydown : function(e){
					e.stopPropagation();
					var charCode = (e.which) ? e.which : event.keyCode;
					switch (charCode) {
						case 13:  // (enter) Generate tag
							e.preventDefault();
						case 32:  // (space)
						case 188: // (comma)
							if ($input.val() && data_add($input.val())) {
								generateTag($input.val()).insertBefore($input);
								$input.val('').focus();
							}
							break;
							
						case 8:   // (backspace) if input val is empty, remove pregious tag
							if ($input.val() === ''){
								var $tag = $input.prev();
								
								data_delete($tag.data('tagName'));
								$tag.remove();
							}
							break;
							
						case 27:  // (escape) Cancel
							$input.val('');
							break;
					}
				},
				keyup : function(e){
					var charCode = (e.which) ? e.which : event.keyCode;
					switch (charCode) {
						case 13:  // (enter) Generate tag
						case 32:  // (space)
						case 188: // (comma)
							//if ($input.val().length == 1)
								$input.val('').focus();
							break;
					}
				}
			}).appendTo(element);
			element.click(function(){
				$input.focus();
			});
			$input.autocomplete({
				minLength: 1,
				source : getAutocompleteElements('tags')
			});
		}

		function clean() {
			element.removeClass(theme)
				   .html('');
			data_init();
		}
		function destroy() {
			element.remove();
		}
		function getData(){
			return element.data(dataName);
		}
		function addTag(text){
			if (data_add(text))
				generateTag(text).insertBefore($input);
		}

		function generateTag(text){
			var $tag = $('<div />',{
				'class' : theme + '-tag'
			}).data('tagName', text);
			
			$('<span />',{
				'class' : theme + '-tag-name',
				text : text
			}).appendTo($tag);
			
			$('<span />',{
				'class' : theme + '-tag-remove',
				text : 'x',
				click : function(){
					$tag.remove();
					data_delete(text);
				}
			}).appendTo($tag);
			return $tag;
		}
		
		// private methods			
		function data_init(){
			data = [];
			element.data(dataName, data);
		}
		function data_destroy(){
			data = [];
			element.removeData(dataName);
		}
		function data_add(str){
			var exists = false;
			for (var i=0; i < data.length; i++) {
				if (str == data[i]) {
					exists = true;
					break;
				}
			}
			if (!exists) data.push(str);
			data_sync();
			return !exists;
		}
		function data_delete(str){
			var exists = false;
			for (var i=0; i < data.length; i++) {
				if (str == data[i]) {
					exists = true;
					data.splice(i,1);
					break;
				}
			}
			data_sync();
			return exists;
		}
		function data_sync(){
			element.data(dataName, data);
		}
	}
})(jQuery);