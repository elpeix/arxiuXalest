var listElements = {
	listComposers : {
		collection : app.composerCollection,
		type :'composer',
		title : 'Compositors',
		filterParam : 'composers.id',
	},
	listCupboards : {
		collection : app.cupboardCollection,
		type :'cupboard',
		title : 'Armaris',
		filterParam : 'cupboards.id',
	},
	listBoxes : {
		collection : app.boxCollection,
		type :'box',
		title : 'Caixes',
		filterParam : 'boxes.id',
	},
	listChoirTypes : {
		collection : app.choirTypeCollection,
		type :'choirType',
		title : 'Veus',
		filterParam : 'choirTypes.id',
	},
	listLanguages : {
		collection : app.languageCollection,
		type :'language',
		title : 'Idiomes',
		filterParam : 'languages.id',
	},
	listLyricists : {
		collection : app.lyricistCollection,
		type :'lyricist',
		title : 'Lletristes',
		filterParam : 'lyricists.id',
	},
	listStyles : {
		collection : app.styleCollection,
		type :'style',
		title : 'Estils',
		filterParam : 'styles.id',
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
						filterParam : element.filterParam,
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
							if (res === undefined) res = r;
							if (options == 'destroy') $.removeData(this, key);
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