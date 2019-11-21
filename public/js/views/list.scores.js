/**
 * Scores list
 * @autor: Francesc Requesens i Roca
 * @date: 2013-10-15
 * 
 */
;(function($){
	$.fn.listScores = function(options){
		var defaults = {
			page: 1,
			maxResults: 20,
			orderBy: 'scores.name asc',
			filter: app.filter,
			views : {
				name: '',
				composer: '',
				century: '',
				style: '',
				language: '',
				choirType : 'hide',
				lyricist: 'hide',
				cupboard: 'hide',
				box : ''
			}
		};
		
		// method calling
		if (typeof options == 'string') {
			var args = Array.prototype.slice.call(arguments, 1);
			var res;
			this.each(function() {
				var list = $.data(this, 'list');
				if (list && $.isFunction(list[options])) {
					var r = list[options].apply(list, args);
					if (res === undefined)
						res = r;
					if (options == 'destroy')
						$.removeData(this, 'list');
				}
			});
			if (res !== undefined) {
				return res;
			}
			return this;
		}
		
		options = $.extend(defaults, options || {});
		
		this.each(function(i, _element) {
			var element = $(_element);
			var list = new Llista(element, options);
			
			element.data('list', list);
			list.init();
		});
		
		return this;
	};
	
	function Llista(element, options){
		var t = this;
	
		// exports
		t.options 		= options;
		t.init 			= init;
		t.destroy 		= destroy;
		
		// imports
		var page		= options.page;
		var maxResults 	= options.maxResults;
		var views		= options.views;
		var orderBy		= options.orderBy;
		var app         = window.app;
		app.filter		= options.filter;
		
		// locals
		var isAdmin		= app.isAdmin;
		var list;
		var collection  = app.scoreCollection;
		var items_name  = window.settings.items_name;
		var $conTable;
		var $table;
		
		//Public methods
		function init(){
			clean();

			//Header
			app.header.text('Partitures');
			
			//Menu Views
			element.append(_menuViews());
			
			var $preCont = $('<div />',{
				'class' : 'llista-pre-cont'
			}).appendTo(element);
			
			//Table
			$conTable = $('<div class="llista-table" />');
			$table = $('<table class="llista-cont" />');
			
			//Filter Bar
			if (app.filter.length || app.filterTag.length){
				$preCont.append(_filterBar());
				$conTable.addClass('wfilter');
			} else{
				//Add filter bar
				$('<div />',{
					text : 'Mostra barra de filtres',
					'class' : 'llista-filtre-show',
					click: function(){
						$preCont.prepend(_filterBar());
						$conTable.addClass('wfilter');
						$(this).remove();
					}
				}).prependTo(element);
			}
			
			$preCont.append($conTable.append($table));
			
			//Header
			$table.append(_header());

			load();
		}
		function load() {
			$table.find('tbody').remove();
			//Search 
			app.load.show();
			collection.fetch({
				params : _generateParams(page),
				success : function (rData){
					app.load.hide();
					var numItems = rData.count;
					if (numItems){

						//Crea llista
						var $list = $('<tbody />').appendTo($table);
						
						//Pagina si és necessari
						$conTable.upage({
							numItems: rData.count,
							page: page,
							maxResults: maxResults,
							callback: function(page){
								consulta($list, _generateParams(page));
							}
						});
							
						//Pinta linies
						for (var i = 0; i < rData[items_name].length; i++) {
							$list.append(_paint(rData[items_name][i], i));
						}
					}
				}
			});
		}
		function clean(){
			element.removeClass('empty').html('');
		}
		function destroy(){
			element.remove();
		}
		function consulta($list, params){
			if (!$list){
				$list = element.find('tbody');
				if (!$list) return false;
			}
			app.load.show();
			collection.fetch({
				params : params,
				success : function (rData){
					app.load.hide();
					//Crea llista
					$list.html('');
					for (var i = 0; i < rData[items_name].length; i++)
						$list.append(_paint(rData[items_name][i], i));
				}
			});	
			return true;
		}
		
		//Private methods
		function _menuViews(){
			var menuviewCont = $('<div />',{
				'class' : 'llista-menu-views-cont'
			});
			var menuView = $('<div />',{
				text: 'Columnes visibles',
				'class' : 'llista-menu-views',
				click : function(e){
					e.stopPropagation();
					if ($(this).hasClass('sel'))
						$(this).removeClass('sel');
					else
						$(this).addClass('sel');
				}
			}).append('<span class="ui-icon ui-icon-triangle-1-s" />').append(menuviewCont);
			
			var schema = collection.model.prototype.schema;
			for (var k in schema){
				if (!schema[k].isPublic) continue;
				var $field  = $('<div />',{
					'class' : 'llista-menu-views-el ' + views[k],
					text: schema[k].tr,
	                click: function(e){
	                	e.stopPropagation();
	                	var $this = $(this);
	                	var k = $this.data('fieldName');
	                	
						if ($this.hasClass('hide')){
							views[k] = '';
							element.find('.llista-' + k).removeClass('hide');
							$this.removeClass('hide');
						}
						else{
							views[k] = 'hide';
							element.find('.llista-' + k).addClass('hide');
							$this.addClass('hide');
						}
						if (localStorage) {
							options.views = localStorage.setItem('scoreColViews', JSON.stringify(views));
						}
	                }
				}).appendTo(menuviewCont).data('fieldName', k);
			}
			
			
			menuviewCont.find('> div').each(function(){
				$(this).prepend('<span class="ui-icon ui-icon-check" />')
					   .prepend('<span class="ui-icon ui-icon-bullet" />');
			});
			
			return menuView;
		}
		function _header(){
			var $thead = $('<thead class="llista-head" />');
			var $line = $('<tr class="llista-line-head" />').appendTo($thead);
            
			var arrOrder = orderBy.split(' ');
			var typeOrder = arrOrder[0].split('.');
			var schema = collection.model.prototype.schema;

			for (var k in schema){
				if (!schema[k].isPublic) continue;
				let $field  = $('<th />',{
					'class' : 'llista-' + k + ' ' + views[k] + (typeOrder[0] == k? ' ' + arrOrder[1].toLowerCase() : ''),
					text: schema[k].tr,
	                click: function(event){
	                	var k = $(event.target).data('fieldName');
	                    _order($(this), schema[k].orderField);
	                }
				}).appendTo($line).data('fieldName', k);
			}
			if (isAdmin){
				$('<th class="llista-edit" title="Edita" />').appendTo($line);
				$('<th class="llista-edit" title="Elimina" />').appendTo($line);
			}
			return $thead;
		}

		function _filterBar(){
			var bar = $('<div class="llista-filtre" />');
			var title = $('<div />',{
				text : 'Filtre',
				'class' : 'llista-filtre-title'
			}).appendTo(bar);
			var closeBar = $('<div />',{
					'class' : 'ui-icon ui-icon-circle-close',
					click: function(){
						app.filter = [];
						app.filterTag = [];
						init();
					}
			}).appendTo(title);
			var cont = $('<div />',{
				'class': 'llista-filtre-cont'
			}).appendTo(bar);

			// Paint filters
			cont.filter({
				data : app.filter,
				dataTag : app.filterTag,
				schema : collection.model.prototype.schema,
				load : function(data){
					app.filter = data;
					init();
				}
			});
			
			return bar;
		}
		function _order(el, str){
			var theadLine = el.parent();
			if(!el.hasClass('asc')){
				theadLine.find('th').removeClass('asc').removeClass('desc')
                         .find('span').removeClass('ui-icon-triangle-1-n').removeClass('ui-icon-triangle-1-s');
				orderBy = str + ' asc';
				el.addClass('asc');
                el.find('span').addClass('ui-icon-triangle-1-s');
			}
			else{
				theadLine.find('th').removeClass('asc desc').removeClass('desc')
                         .find('span').removeClass('ui-icon-triangle-1-n').removeClass('ui-icon-triangle-1-s');
				orderBy = str + ' desc';
				el.addClass('desc');
                el.find('span').addClass('ui-icon-triangle-1-n');
			}
			load();
		}
		function _generateParams(page){
			var arrParams = [];
			var filterParams = encodeURI(filterToStr(app.filter));
			var filterTags = encodeURI(filterTagToStr(app.filterTag));

			if (filterParams) arrParams.push('filter=' + filterParams);
			if (page) arrParams.push('page=' + page);
			if (maxResults) arrParams.push('perPage=' + maxResults);
			if (orderBy) arrParams.push('orderBy=' + orderBy);

			return arrParams.join('&');
		}
		function _paint(data, num){
			var dataEl = data.content;
			var $line = $('<tr />',{
				'class' : 'llista-line llista-line-link',
				click : function(e){
					e.stopPropagation();
					routie('scores/' + data.id);
				}
			}).data('identificador-partitura',data.id);
			
			var schema = collection.model.prototype.schema;
			for (var k in schema){
				if (!schema[k].isPublic) continue;
				var typeItem = schema[k].type; 
				var $field  = $('<td />',{
					'class' : 'llista-' + k + ' ' + views[k]
				}).appendTo($line).data('fieldName', k);
				
				switch (typeItem){
				    case 'collection':
				        var identifier = dataEl[k];
						if (!identifier) break;
						app[schema[k].collection].get({
							identifier: identifier.id,
							async: false,
							success: function(dataItem){
								var str = dataItem.content.name? dataItem.content.name : '-';
								$field.text(str).addClass('link').click(function(){
								var k = $(this).data('fieldName');
								var field = app[k + 'Collection'].model.prototype.basePath + ".id";
								app.filter.push({
									sch : k,
									field:  field,
									type : 'equal',
									val : dataItem.content.id
								});
								init();
								});
								$('<span class="ui-icon ui-icon-search" />').prependTo($field); 
							}
						});
				        break;
				        
					case 'object':
					case 'sel':
						$field.text(dataEl[k].name? dataEl[k].name : '-').addClass('link').click(function(){
							var k = $(this).data('fieldName');
		                	app.filter.push([{
		                		sch : k,
		                		field:  k +'Id',
		                		type : 'equal',
		                		val : dataEl[k].id
		                	}]);
							init();
						});
						$('<span class="ui-icon ui-icon-search" />').appendTo($field);
						break;
						
					case 'check':
						$field.text(dataEl[k] == 1 ? 'Sí' : '-');
						break;
						
					case 'tag':
						var tagsList = dataEl[k];
						for (var i=0; i < tagsList.length; i++) {
							app[schema[k].collection].get({
				        		identifier: tagsList[i],
				        		async: false,
				        		success: function(dataItem){
									$('<span />',{
										'class' : 'score-tag',
										text : dataItem.content.name? dataItem.content.name : '-',
										click : function(e){
											e.stopPropagation();
											app.filterTag.push({
						                		sch : 'tag',
						                		field:  'tag',
						                		type : 'equal',
						                		val : dataItem.content.id
						                	});
											init();
										}
									}).appendTo($field).data('tag', dataItem.content.id);
								}
							});
						}
						break;
					default:
						$field.text(dataEl[k]);
						break;
				}
			}
			
			if (isAdmin){
				var edit = $('<td class="llista-edit"><span class="ui-icon ui-icon-pencil" title="Edita"></span></td>');
				var elimina = $('<td class="llista-edit"><span class="ui-icon ui-icon-trash" title="Elimina"></span></td>');

				$line.append(edit)
					 .append(elimina);
					
				edit.on('click', function(e){
					e.stopPropagation();
					routie("scores/"+data.id+"/edit");
				});
				elimina.click(function(e){
					e.stopPropagation();
					MF.confirm('Vols eliminar del registre la partitura?', 'Elimina', function(r){
						if(!r) return false;
						data.remove(function(rData){
							if(rData.status > 299){
								MF.alert('No s\'ha pogut eliminar la partitura');
								return false;
							}
							MF.alert('La partirura '+dataEl.name+' ha estat eliminada');
							$line.remove();
						});
					});
				});
			}
			return $line;
		}
	}
})(jQuery);