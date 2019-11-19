/**
 * Plugin filter
 * Data Name: filter
 * Function Name: Filter
 * 
 * @author Francesc Requesens
 * @version 0.1 2013-10-17
 *
 */

;(function($) {
  
	$.fn.filter = function(options) {
    	
    	// default options
		var defaults = {
			data : {},
			schema : {},
			load : function(){},
			error : function(){}
		};
    
		// method calling
		if( typeof options == 'string') {
			var args = Array.prototype.slice.call(arguments, 1);
			var res;
			this.each(function() {
				var udata = $.data(this, 'filter');
				if(udata && $.isFunction(udata[options])) {
					var r = udata[options].apply(udata, args);
					if(res === undefined)
						res = r;
					if(options == 'destroy')
						$.removeData(this, 'filter');
				}
			});
			if(res !== undefined)
				return res;
			return this;
		}
		options = $.extend(defaults, options || {});
	    
		this.each(function(i, _element) {
			var element = $(_element);
			var udata = new Filter(element, options);

			element.data('filter', udata);
			udata.init();
		});
		return this;
	};
  
	function Filter(element, options) {
		var t = this;

		// exports
		t.options = options;
		t.init = init;
		t.destroy = destroy;
		
		// imports
		var data = options.data;
		var dataTag = options.dataTag;
		var schema = options.schema;
		var load = options.load;
		var error = options.error;
		var app = window.app;

		// locals

		// public methods
		function init() {
			clean();

			var tagContainer = $('<div />').appendTo(element);
			var filterContainer = $('<div />').appendTo(element);

			paintTagFilter(tagContainer);
			paintFilter(filterContainer);
			_add(filterContainer, data, 0);
		}

		function destroy() {
			element.remove();
		}

		function clean() {
			element.html('');
		}

		function paintTagFilter($container) {

			for (var i=0; i < dataTag.length; i++) {
				var $tagItem = $('<div />',{
					'class' : 'llista-filtre-cont-el'
				}).appendTo($container).data('index', i);

				var dataTagSch = dataTag[i].sch;
				$tagItem.data('field', dataTagSch);
				_paintTitle(dataTagSch).appendTo($tagItem);
				_paintValue(dataTag[i]).appendTo($tagItem);

				//Remove
				_paintClose(function($this){
					var $tagItem = $this.parent();
					var arrTmp = [];
					var ind = $tagItem.data('index');
					dataTag.splice(ind, 1);
					
					$tagItem.remove();
					init();
					if (load && typeof load === 'function') load(data);
				}).prependTo($tagItem);
			}

		}
		
		function paintFilter($container, dataFilter, index){
			if (!index) index = 0;
			if (!dataFilter) dataFilter = data;
			
			//Clean container
			$container.html('');
			
			for (var i=0; i < dataFilter.length; i++) {
				var $filterItem = $('<div />',{
					'class' : 'llista-filtre-cont-el'
				}).appendTo($container).data('index', i);
				
				var isfilter = true;
				if ($.isArray(dataFilter[i])){
					paintFilter($filterItem, dataFilter[i], 1);
					isfilter = false;
				}
				
				if (isfilter){
					var dataFilterSch = dataFilter[i].sch;
					if (dataFilter[i].sch == 'score')
						dataFilterSch = dataFilter[i].field;
					
					$filterItem.data('field', dataFilterSch);
					_paintTitle(dataFilterSch).appendTo($filterItem);
					_paintValue(dataFilter[i]).appendTo($filterItem);
				}
				
				//Remove
				_paintClose(function($this){
					var $filterItem = $this.parent();
					var arrTmp = [];
					var ind = $filterItem.data('index');
					dataFilter.splice(ind, 1);
					_cleanFilter();
					
					$filterItem.remove();
					init();
					if (load && typeof load === 'function') load(data);
				}).prependTo($filterItem);

				//Add
				if (index < 1)
					_add($filterItem, dataFilter[i], 1);
			}
		}

		// private methods
		function _add($filterItem, dataFilter, index){
			var $add = $('<div />',{
				'class' : 'llista-filtre-cont-add',
				text: 'Afegeix condició',
				click : function(){
					_addCondition($(this), dataFilter, index);
				}
			}).appendTo($filterItem);
			
			$('<span />',{
				'class' : 'ui-icon ui-icon-circle-plus'
			}).appendTo($add);
		}
		function _addCondition($add, dataFilter, index){
			$add.html('Per quin camp vols cercar?')
				.off('click')
				.addClass('llista-filtre-cont-el')
				.removeClass('llista-filtre-cont-add');
				
			var $select = $('<select />',{
				'class' : 'llista-filtre-cont-add-select',
				click : function(e){
					e.stopPropagation();
				},
				change : function(){
					_onSelectCondition($(this).val(), $add, dataFilter, index);
				}
			}).appendTo($add);
			
			$('<option />',{
				'class' : 'llista-filtre-cont-add-opt',
				value : '',
				text : 'Escull el camp'
			}).appendTo($select);
			
			for (var k in schema) {
				if (!schema[k].isPublic || schema[k].type == 'tag') continue;
				$('<option />',{
					'class' : 'llista-filtre-cont-add-opt',
					value : k ,
					text : schema[k].tr 
				}).appendTo($select);
			}
			
			//Close
			_paintClose(function($this){
				init();
			}).prependTo($add);
		}
		function _onSelectCondition(k, $add, dataFilter, index){
			$add.html('');
			
			// Title
			_paintTitle(k).appendTo($add);
			
			// Value
			$('<input />',{
				type : 'text',
				'class' : 'llista-filtre-cont-add-input',
				keypress : function(e){
					if(e.keyCode == 13){
						var $this = $(this);
						var obj;
						
						if (!$this.val()) return false;
						switch(schema[k].type) {
							case 'object':
							case 'collection':
							case 'sel':
								obj = {
									sch : k,
									field : 'name',
									type : 'like',
									val : $this.val()
								};
								break;
							default : 
								obj = {
									sch : 'score',
									field : k,
									type : 'like',
									val : $this.val()
								};
								break;
						}

						if (index === 0) dataFilter.push([obj]);
						else dataFilter.push(obj);

						_cleanFilter();
						init();
						if (load && typeof load === 'function') load(data);
					}
					if (e.keyCode == 27) init();
				}
			}).appendTo($add).focus();
			
			// Close
			_paintClose(function($this){
				init();
			}).prependTo($add);
		}
		function _paintTitle(k){
			var title = '';
			if (k == 'score') title = 'Nom';
			else if (schema[k]) title = schema[k].tr;
			return $('<span />',{
				'class' : 'llista-filre-cont-el-title',
				text : title + ': '
			}).data('k', k);
		}
		function _paintValue(dataFilter){
			var $value = $('<span />',{
				'class' : 'llista-filre-cont-el-title',
				text : dataFilter.val? dataFilter.val : '-'
			});
			if (dataFilter.val && dataFilter.type == 'equal' && dataFilter.val != '0'){
				app[dataFilter.sch + 'Collection'].get({
					identifier: dataFilter.val,
					success: function(rData){
						$value.text(rData.content.name);
					}
				});
			}
			return $value;
		}
		function _paintClose(callback){
			return $('<div />',{
				'class' : 'ui-icon ui-icon-circle-close',
				click : function(){
					if (callback && typeof callback === 'function')
						callback($(this));
				}
			});
		}
		
		function _cleanFilter(){
			for (var i=0; i < data.length; i++) {
				if (!data[i].length) data.splice(i, 1);
				if (!$.isArray(data[i])){
					var tmpObj = $.extend({}, data[i]);
					if ($.isEmptyObject(tmpObj)) data.splice(i, 1);
					else data[i] = [tmpObj];
				}
			}
		}
	}

})(jQuery);
