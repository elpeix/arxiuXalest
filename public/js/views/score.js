/**
 * Plugin viewScore
 * Data Name: viewscore
 * Function Name: ViewScore
 * 
 * @author Ubiquat Technologies
 * @version 0.1 2013-10-17
 *
 */

;(function($) {
	$.fn.viewScore = function(options) {
		var defaults = {
			identifier : 0
		};

		// method calling
		if( typeof options == 'string') {
			var args = Array.prototype.slice.call(arguments, 1);
			var res;
			this.each(function() {
				var udata = $.data(this, 'viewscore');
				if(udata && $.isFunction(udata[options])) {
					var r = udata[options].apply(udata, args);
					if(res === undefined)
						res = r;
					if(options == 'destroy')
						$.removeData(this, 'viewscore');
				}
			});
			if(res !== undefined)
				return res;
			return this;
		}
		options = $.extend(defaults, options || {});

		this.each(function(i, _element) {
			var element = $(_element);
			var udata = new ViewScore(element, options);

			element.data('viewscore', udata);
			udata.init();
		});
		return this;
	};

	function ViewScore(element, options) {
		var t = this;

		// exports
		t.options = options;
		t.init = init;
		t.destroy = destroy;
		
		// imports
		var identifier = options.identifier;

		// locals
		var objScore;
        var app = window.app;
        var isAdmin = app.isAdmin;

		// public methods
		function init() {
			app.load.show();

			clean();

			//Header
			app.header.text('Partitures >');
			
			if (!identifier) {
				routie('scores');
			}
			objScore = new Score({id : identifier});

			app.scoreCollection.get({
				identifier: identifier,
				success: function(rData){
					app.header.text(rData.content.name);
					app.load.hide();
					$('<span />',{text: ' > '}).prependTo(app.header);
					$('<a />',{href : '#scores', text : 'Arxiu'}).prependTo(app.header);
					var $cont = _paint(rData.content);
					_getButtons().appendTo($cont);
					var $preCont = $('<div />',{'class': 'llista-pre-cont'}).appendTo(element);
					$preCont.append($cont);
				}
			})
		}

		function destroy() {
			element.remove();
		}

		function clean() {
			element.html('');
		}

		// private methods
		function _paint(data){
			var $container = $('<div />',{
				'class' : 'score-detail'
			});
			
			for (var k in objScore.schema) {
				if (!objScore.schema[k].isPublic) continue;
				
				var $line = $('<div />',{
					'class' : 'score-detail-line'
				}).appendTo($container);
				
				// Title
				$('<div />',{
					'class' : 'score-detail-line-title',
					text : objScore.schema[k].tr
				}).appendTo($line);
				
				// Value
				var $value = $('<div />',{
					'class' : 'score-detail-line-text'
				}).appendTo($line);
				
				if (objScore.schema[k].type == 'object' || objScore.schema[k].type == 'sel')
					$value.text(data[k].name === null? '-' : data[k].name);
				
				switch(objScore.schema[k].type){
					case 'object':
					case 'sel':
						$value.text(data[k].name === null? '-' : data[k].name);
						break;
					case 'collection':
						if(!data[k]) break;

						app[objScore.schema[k].collection].get({
							identifier: data[k].id,
							async: false,
							success: function(dataItem){
								$value.text(dataItem.content.name? dataItem.content.name : '-');
							}
						});
						break;
					case 'tag':
						var tagsList = data[k];
						var schema = objScore.schema[k];
						for (var i=0; i < tagsList.length; i++) {
							app[schema.collection].get({
								identifier: tagsList[i],
								async: false,
								success: function(dataItem){
									$('<span />',{
										'class' : 'score-tag',
										text : dataItem.content.name,
										click : function(e){
											e.stopPropagation();
											app.filterTag.push({
						                		sch : 'tag',
						                		field:  'tag',
						                		type : 'equal',
						                		val : dataItem.content.id
						                	});
											routie('scores');
										}
									}).appendTo($value).data('tag', dataItem.content);
								}
							});
						}
						break;
					default:
						$value.text(data[k]);
						break;
				}
			}
			return $container;
		}

		function _getButtons() {
			var $buttons = $('<div class="score-detail-buttons" />');
			if (isAdmin) {
				$('<button />',{
					'class' : 'btn-remove trash',
					click : function(e){
						e.stopPropagation();
						MF.confirm('Vols eliminar del registre la partitura?', 'Elimina', function(r){
							if(!r) return false;
							rData.remove(rData.id, function(ret){
								if(ret.status > 299) MF.alert('No s\'ha pogut eliminar la partitura');
								else{
									MF.alert('La partirura '+rData.name+' ha estat eliminada');
									routie('scores');
								}
							});
						});
					}
				}).appendTo($buttons);
							
				$('<button />',{
					'class' : 'btn-primary',
					text : 'Edita',
					click : function(e){routie('scores/' + identifier + '/edit');}
				}).appendTo($buttons);
			}
			$('<button />',{
				'class' : 'btn-secondary go-back',
				text : 'Torna a l\'arxiu',
				click : function(e){routie('scores/');}
			}).appendTo($buttons);

			return $buttons;
		}
    
		// private function for debugging
		function debug($obj) {
			if (window.DEBUG && window.console && window.console.log)
				window.console.log($obj);
		}
	}

})(jQuery);
