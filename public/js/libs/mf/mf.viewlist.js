/**
 * Class Name: MF.ViewList
 * 
 * @author Francesc Requesens i Roca
 * @version 0.1 2013-10-15
 *
 */
MF.ViewList = function(element, options){
	var t = this;
	
	t.init 			= init;
	t.destroy		= destroy;
	
	var defaults = {
		page: 1,
		maxResults: 4,
		orderBy: 'name asc',
		language : {
			list_empty : 'No hi ha cap element',
			delete_confirm : 'Vols eliminar de l\'element?',
			delete_error : 'No s\'ha pogut eliminar l\'element',
			delete_success : 'L\'element ha estat eliminat',
			search_error : 'Hi ha hagut un error al fer la cerca'
		}
	};
	options = $.extend(defaults, options || {});
	
	// imports
	var type		= options.type;
	var page		= options.page;
	var maxResults 	= options.maxResults;
	var orderBy		= options.orderBy;
	
	var viewConfig  = options.viewConfig; 
	var language    = options.language; 
	var collection  = viewConfig.collection;
	var app = window.app;
		
	//Local
	var isAdmin		= app.isAdmin;
	var items_name  = window.settings.items_name;
	
	//Public methods
	function init(){
		app.header.text(viewConfig.title);
		element.removeClass('empty').html('');
		var $preCont = $('<div />',{
			'class' : 'llista-pre-cont'
		}).appendTo(element);
		var table = $('<table class="llista-cont" />');
		$preCont.append(table);
		table.append(_header());
		
		app.load.show();
		collection.fetch({
			params : _generateParams(page),
			success : function (rData){
				app.load.hide();
				var numItems = rData.count;
				if (numItems){
					var $list = $('<tbody />').appendTo(table);

					$preCont.MFPage({
						numItems: rData.count,
						page: page,
						next: rData.next,
						previous: rData.previous,
						maxResults: maxResults,
						callback: function(p){
                            page = p;
							consulta($list, p);
						}
					});
					
					var maxLength = maxResults;
					if (rData[items_name].length < maxResults) {
						maxLength = rData[items_name].length;
					}
					for (var i = 0; i < maxLength; i++) {
						$list.append(_paint(rData[items_name][i], i));
					}
				} else {
					element.addClass('empty').text(language.list_empty);
				}
			},
			error : function(rData){
				app.load.hide();
				element.addClass('empty').text(language.search_error);
			}
		});
	}
	function destroy(){
		element.html('');
	}
	function consulta($list, page){
		if (!$list){
			$list = element.find('tbody');
			if (!$list) return false;
		}
		var numItems = collection.count;
		
		if (numItems){
			$list.html('');
			var firstItem = (page - 1) * maxResults;
			var maxLength = maxResults;
			if (collection.count < maxResults) {
				maxLength = collection.count;
			}
			if (collection.count - firstItem < maxLength) {
				maxLength = collection.count - firstItem;
			}
			for (var i = 0; i < maxLength; i++){
				$list.append(_paint(collection[window.settings.items_name][i + firstItem], i + firstItem));
			}
		} else {
			element.addClass('empty').text(language.list_empty);
		}
	}
	
	//Private methods
	function _generateParams(page){
		var arrParams = [];
		if (orderBy) arrParams.push('orderBy=' + orderBy);
		return arrParams.join('&');
	}
	function _header(){
		var $thead = $('<thead class="llista-head" />');
		var $line = $('<tr class="llista-line-head" />').appendTo($thead);
		var arrOrder = orderBy.split(' ');
		var schema = collection.model.prototype.schema;
		for (var k in schema){
			if (!schema[k].isPublic) continue;
			var $field  = $('<th />',{
				'class' : 'llista-' + k + (arrOrder[0] == k? ' ' + arrOrder[1].toLowerCase() : ''),
				text: schema[k].tr,
                click: function(){
                    _order($(this), $(this).data('fieldName'));
                }
			}).appendTo($line).data('fieldName', k);
		}
		if (isAdmin && !viewConfig.preventEdit){
			$('<th class="llista-edit" title="Edita" />').appendTo($line);
			$('<th class="llista-edit" title="Elimina" />').appendTo($line);
		}
		return $thead;
	}
	function _order(el, str){
		var theadLine = el.parent();
		if(!el.hasClass('asc')){
			theadLine.find('th').removeClass('asc desc')
                     .find('span').removeClass('ui-icon-triangle-1-n').removeClass('ui-icon-triangle-1-s');
			orderBy = str + ' asc';
			el.addClass('asc');
            el.find('span').addClass('ui-icon-triangle-1-s');
		}
		else{
			theadLine.find('th').removeClass('asc desc')
                     .find('span').removeClass('ui-icon-triangle-1-n').removeClass('ui-icon-triangle-1-s');
			orderBy = str + ' desc';
			el.addClass('desc');
            el.find('span').addClass('ui-icon-triangle-1-n');
		}
		init();
	}
	function _paint(data, num){
		var dataEl = data.content;
		var $line = $('<tr />',{
			'class' : 'llista-line',
			click : function(){
				app.filter = [{
            		sch : viewConfig.type,
            		field:  viewConfig.filterParam,
            		type : 'equal',
            		val : dataEl.id
            	}];
				routie('scores');
			}
		});
		
		var firstField = true;
		for (var k in data.schema){
			if (!data.schema[k].isPublic) continue;
			var typeItem = data.schema[k].type; 
			var $field  = $('<td />',{
				'class' : 'llista-' + k
			}).appendTo($line);
			
			
			switch (typeItem){
				case 'object':
				case 'sel':
					if (!dataEl[k]) continue;
					$field.text(dataEl[k].name? dataEl[k].name : '-');
					break;
				case 'check':
					if (dataEl[k] == 1)
						$field.text('Sí');
					else
						$field.text('-');
					break;
				default:
					$field.text(dataEl[k]);
					break;
			}
			
			if (firstField) {
				$('<span />',{
					'class' :' ui-icon ui-icon-search'
				}).appendTo($field);
				$field.addClass('link');
				firstField = false;
			}
		}
		
		if (isAdmin && !viewConfig.preventEdit){
			var $edit = $('<td class="llista-edit"><span class="ui-icon ui-icon-pencil" title="Edita"></span></td>').appendTo($line);
			var $delete = $('<td class="llista-edit"><span class="ui-icon ui-icon-trash" title="Elimina"></span></td>').appendTo($line);

			$edit.on('click', function(e){
				e.stopPropagation();
				routie(collection.route + data.id + "/edit");
			});
			
			$delete.click(function(e){
				e.stopPropagation();
				MF.confirm(language.delete_confirm, 'Elimina', function(r){
					if(!r) return false;
					collection.remove(data.id, function(rData){
						if(rData.status > 299)
							MF.alert(language.delete_error + '\nAssegura\'t que l\'element que vols eliminar no s\'estigui utilitzant.', 'error');
						else {
							MF.alert(language.delete_success,'success',dataEl.nom);
							$line.remove();
						}
					});
				});
			});
		}
		return $line;
	}
};
