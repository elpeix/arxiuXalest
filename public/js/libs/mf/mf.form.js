/**
 * UAW Form
 * @autor: Francesc Requesens i Roca
 * @date: 2013-10-15
 * @version: 0.4 
 * 
 */
;(function($){
	$.fn.formulari = function(options){
		var defaults = {
			data : {},
			iden: 0,
			edit: false,
			callback : function(){},
			cancel : function(){}
		};
		// method calling
		if (typeof options == 'string') {
			var args = Array.prototype.slice.call(arguments, 1);
			var res;
			this.each(function() {
				var formulari = $.data(this, 'formulari');
				if (formulari && $.isFunction(formulari[options])) {
					var r = formulari[options].apply(formulari, args);
					if (res === undefined)
						res = r;
					if (options == 'destroy')
						$.removeData(this, 'formulari');
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
			var formulari = new Formulari(element, options);
			
			element.data('formulari', formulari);
			formulari.init();
		});
		
		return this;
	};
	
	function Formulari(element, options){
		var t = this;
	
		// exports
		t.options 	 = options;
		t.init 		 = init;
		t.destroy 	 = destroy;
		
		// imports
		var data 	 = options.data;
		var iden 	 = options.iden;
		var edit	 = options.edit;
		var type	 = options.type;
		var newReg   = options.newReg;
		var callback = options.callback;
		var cancel	 = options.cancel;
		
		// locals
		//empty
		
		//Public methods
		function init(){
			//Role
			//TODO

			//Control data
			if (!data.content.length)
				return false;
			
			//Clean element
			clean();
			
			//Create Container
			var $form = $('<form />', {
				id : "formulari",
				'class' : "fr"
			}).appendTo(element);

			//Paint Elements
			for (var i = 0; i < data.content.length; i++)
				$form.append(_paint(data.content[i], i));

			// Save
			$('<input />',{
				type : "submit",
				'class' :"fr-sv",
				value : "Desa",
				click : function(e){
					e.stopPropagation();
					e.preventDefault();
					_save();
				}
			}).appendTo($form);

			// Save and create new Regsiter
			if (!edit && newReg){
				var $saveAndNew = $('<input />',{
					type : "button",
					'class' : "fr-nr",
					value : "Desa i crea nou registre",
					click : function(e){
						e.stopPropagation();
						_save(true);
					}
				}).appendTo($form);

				$form.keypress(function(e){
					if(e.controlkey && e.which == 13)
						$saveAndNew.click();
				});
			}
			
			//Cancel
			$('<input />', {
				type : "button",
				'class' : "fr-cn",
				value : "Cancel·la",
				click : function(e){
					e.stopPropagation();
					cancel();
				}
			}).appendTo($form);
			
			$('#element_0').focus();
		}

		function clean(){
			element.html('');
		}

		function destroy(){
			routie('scores');
		}

		//Private methods
		function _paint(dataEl, num){
			var $field = $('<div />', {
				'class' : 'fr-element'
			});
			var $box;
			
			if (dataEl.type != 'check') {
				$('<label />',{
					'class' : "fr-label",
					'for': 'element_' + num,
					text : dataEl.name
				}).appendTo($field);
			}
						
			switch(dataEl.type){
				case 'text':
					$box = $('<textarea />',{
						id : 'element_' + num
					}).appendTo($field);
					
					if (edit){
						$box.text(dataEl.valor)
							.data('iden', dataEl.iden)
							.data('valor',dataEl.valor);
					}
					break;

				case 'string':
					$box = $('<input />',{
						type : 'text',
						id : 'element_' + num
					}).appendTo($field);
					
					if (edit){
						$box.val(dataEl.valor)
							.data('iden', dataEl.iden)
							.data('valor',dataEl.valor);
					}
					break;

				case 'check':
					var $check = $('<input />',{
						'class' : 'fr-checkbox',
						type : 'checkbox',
						id : 'element_' + num
					}).appendTo($field);
					
					$('<label />',{
						'class' : "fr-label fr-label-checkbox",
						'for': 'element_' + num,
						text : dataEl.name
					}).appendTo($field);
					
					if (edit){
						$check.prop('checked', (dataEl.valor==1? true : false))
							  .data('iden', dataEl.iden)
							  .data('valor',dataEl.valor);
					}
					break;

				case 'tag':
					$box = $('<div />',{
						id : 'element_' + num
					}).appendTo($field).inputTags();
					if (edit){
						$box.data('tagsValuePrevious', dataEl.valor);
						for (var i=0; i < dataEl.valor.length; i++) 
							app[dataEl.seccio + 'Collection'].get({
								identifier : dataEl.valor[i],
								async: false,
								success: function(dataItem) {
									$box.inputTags('addTag',dataItem.content.name);
								}
							});
					}
					break;

				case 'object':
				case 'collection':
				case 'sel':
					$box = $('<input />',{
						type : 'text',
						id : 'element_' + num
					}).appendTo($field);
					if (edit){
					    if (dataEl.type == 'collection'){
					    	if (dataEl.valor.id)
	                            app[dataEl.seccio + 'Collection'].get({
	                            	identifier: dataEl.valor.id,
	                            	async: false,
	                            	success: function(dataItem){
	                            		$box.val(dataItem.content.name? dataItem.content.name : '-');
	                            	}
	                            });
					    } 
					    else
					        $box.val(dataEl.valor.name);
					}
					
					//Autocomplete
					$box.autocomplete({
						minLength: 1,
						source : getAutocompleteElements(dataEl.seccio),
						select : function(event, ui){
							$box.data('iden',ui.item.iden)
								.data('valor',ui.item.value);
						}
					});
					break;
			}
			
			return $field;
		}

		function _save(newReg){
			var ret = false;
			var objMessage = {};
			var elVal;

			if (edit) objMessage.id = iden;
			
			for (var i = 0; i < data.content.length; i++){
				var dataEl = data.content[i];
				var el = $('#element_' + i);
				var valor = '';
				
				switch(dataEl.type){
					case 'text':
					case 'string':
						valor = elVal = el.val();
						break;
					
					case 'check':
						valor = elVal = el.prop('checked')? 1 : 0;
						break;
						
					case 'sel':
						elVal = el.val();
						if (elVal == el.data('valor'))
							valor = {id : el.data('iden')};
						break;
	
					case 'tag':
						var tagList = el.inputTags('getData');
						if (!tagList.length) continue;

						var valor = [];
						for (var j = 0; j < tagList.length; j++)
							valor.push(_getResult(dataEl, tagList[j]).id);
						break;
						
					case 'collection':
					    elVal = el.val();
					    if (!elVal) break;

                        if (elVal == el.data('valor'))
                            valor = el.data('iden');
                        else
                        	valor = _getResult(dataEl, elVal).id;
                        break;
                        
					case 'object':
						elVal = el.val();
						if (elVal == el.data('valor'))
							valor = {id : el.data('iden')};
						else
							valor = {id : _getResult(dataEl, elVal).id};
						break;
				}
				objMessage[dataEl.seccio] = valor;
			}

			if(type == 'scores') elVal = false;
			if (edit) callback(type, iden, objMessage);
			else callback(type, objMessage, elVal, newReg);
		}

		function _getResult(dataEl, elVal) {
		    return addElement(dataEl.seccio, {name: elVal}, elVal);
		}
	}
})(jQuery);