var listElements = {
	listComposers : {
		collection : app.composerCollection,
		type :'composer',
		title : 'Compositors',
		filter_param : 'composer=',
	},
	listCupboards : {
		collection : app.cupboardCollection,
		type :'cupboard',
		title : 'Armaris',
		filter_param : 'cupboard=',
	},
	listBoxes : {
		collection : app.boxCollection,
		type :'box',
		title : 'Caixes',
		filter_param : 'box=',
	},
	listChoirTypes : {
		collection : app.choirTypeCollection,
		type :'choirType',
		title : 'Veus',
		filter_param : 'choirType=',
	},
	listLanguages : {
		collection : app.languageCollection,
		type :'language',
		title : 'Idiomes',
		filter_param : 'language=',
	},
	listLyricists : {
		collection : app.lyricistCollection,
		type :'lyricist',
		title : 'Lletristes',
		filter_param : 'lyricist=',
	},
	listStyles : {
		collection : app.styleCollection,
		type :'style',
		title : 'Estils',
		filter_param : 'style=',
	}
}

for (const key in listElements) {
	if (listElements.hasOwnProperty(key)) {
		const element = listElements[key];
		(function($){
			$.fn[key] = function(options){
				var defaults = {
					viewConfig : {
						collection : element.collection,
						type :element.type,
						title : element.title,
						filter_param : element.filter_param,
					},
					page: 1,
					maxResults: 20,
					orderBy: 'name asc'
				};

				// method calling
				if (typeof options == 'string') {
					var args = Array.prototype.slice.call(arguments, 1);
					var res;
					this.each(function() {
						var list = $.data(this, key);
						if (list && $.isFunction(list[options])) {
							var r = list[options].apply(list, args);
							if (res === undefined)
								res = r;
							if (options == 'destroy')
								$.removeData(this, key);
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
					var list = new MF.ViewList(element, options);
					element.data(key, list); 
					list.init();
				});
				
				return this;
			};
		})(jQuery);
	}
}