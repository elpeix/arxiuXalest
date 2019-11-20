function getAutocompleteElements(type){
    var app = window.app;
	var collection;
	switch (type){
		case 'composer':
			collection = app.composerCollection;
			break;
		case 'lyricist':
			collection = app.lyricistCollection;
			break;
		case 'choirType':
			collection = app.choirTypeCollection;
			break;
		case 'style':
			collection = app.styleCollection;
			break;
		case 'language':
			collection = app.languageCollection;
			break;
		case 'cupboard':
			collection = app.cupboardCollection;
			break;
		case 'box':
			collection = app.boxCollection;
			break;
		default:
			collection = {status: 'error'};
			break;
	}
	var arrElements = [];
	for(var i = 0; i < collection.count; i++){
		var el = collection[window.settings.items_name][i].content;
		var obj = {
			value: el.name,
			label: el.name,
			iden : el.id
		};
		arrElements.push(obj);
	}
	return arrElements;
}

function filterToStr(filter, reg){
	var arr = [];
	if (!reg) reg = '&';

	for (var i = 0; i < filter.length; i++) {
		var el = filter[i];

		if (el.sch == 'tag') continue;

		if ($.isArray(el)) {
			arr.push(filterToStr(el, '|'));
			continue;
		}
		var sch = (el.type == 'like' && el.sch != 'score')? (el.sch + '__') : '';
		var value = (el.type == 'like'? '__icontains=' : '=') + el.val;

		if (!el.val && el.type == 'equal')
			value = ' IS NULL';
		
		arr.push(sch + el.field + value);
	}
	return arr.join(reg);
}

function filterTagToStr(filter){
	var arr = [];
	for (var i = 0; i < filter.length; i++) {
		var el = filter[i];
		if (el.sch == 'tag')
			arr.push(el.val);
	}
	return arr.join(",");
}

function prepareContentForm(data){
	var content = [];
	for (var k in data.schema){
		if (!data.schema[k].isPublic) continue;
		var obj = {
			type: data.schema[k].type,
			nom: data.schema[k].tr,
			seccio: k,
			iden: 0
		};
		if (data.content)
			obj.valor = data.content[k];
		content.push(obj);
	}
	return content;
}
