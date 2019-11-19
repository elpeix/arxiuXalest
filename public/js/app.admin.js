app.isAdmin = true;

$(document).ready(function(){
	creamenuAdmin($('#menuAdmin'));
});

function creamenuAdmin(element){
	//Menu
	$('<li>').append($('<a />',{
		text : 'Nova partitura',
		href : '#scores/new'
	})).appendTo(element);

	$('<li>').append($('<a />',{
		text : 'Nou compositor',
		href : '#composers/new'
	})).appendTo(element);

	$('<li>').append($('<a />',{
		text : 'Nova formació',
		href : '#formations/new'
	})).appendTo(element);

	$('<li>').append($('<a />',{
		text : 'Nova editorial',
		href : '#publishers/new'
	})).appendTo(element);
	
	$('<li>').append($('<a />',{
		text : 'Nou arxivador',
		href : '#filings/new'
	})).appendTo(element);

	//Esdeveniments Menu
	var $btn = $('#menuAdminBtn');
	$btn.click(function(e){
	 	e.stopPropagation();
	 	if ($btn.hasClass('sel')){
	 		element.hide();
	 		$btn.removeClass('sel');
	 	}
	 	else {
	 		element.slideDown(70);
	 		$btn.addClass('sel');
	 	}
	});
	$(document).click(function(){
		element.hide();
		$btn.removeClass('sel');
	});
};

function createElement(type, data){
	app.content.formulari({
		type: type,
		edit: false,
		data : {
			content : prepareContentForm(data)
		},
		callback : function(type, message, name, newReg){
			if (type == 'scores') {
				data.add({
					async : false,
					data : message,
					callback : function (rData){
						if (rData.status > 299) {
							MF.alert('Hi ha hagut un error a l\'afegir un registre', 'error');
							return;
						}
						
						MF.alert('Registre afegit');
						if (newReg) createElement(type, data)
						else routie(type);
					}
				});
			}
			else {
				addElement(type, message, name, function(rData){
					if (rData.status > 299) {
						MF.alert('Hi ha hagut un error a l\'afegir un registre', 'error');
						return;
					}

					MF.alert(rData.status == 100? 'Registre duplicat' : 'Registre afegit');
					routie(type);
				});
			}
		},
		cancel : function(){
			routie(type);
		}
	});
}

function editElement(type, data){
	app.content.formulari({
		type: type,
		edit: true,
		iden : data.id,
		data : {
			content : prepareContentForm(data)
		},
		callback : function(type, iden, message){
			data.update({
				data: message, 
				callback: function(rData){
					routie(type);
				}
			});
		},
		cancel : function(){
			window.history.back();
		}
	});
}

function addElement(type, message, name, callback){
	var collection = app[type + 'Collection'];
	var result = false;

	callback = callback || function(data){
		if (data.status > 299) return;

		if (data.hasOwnProperty("content"))
			result = data.content;
		else
			result = data;
	};

	if (name && name !== "")
		var isDuplicate = collection.isDuplicate('name', name);

	if (isDuplicate) {
		result = {id: isDuplicate, name: name, status: 100};
		callback(result);
	}
	else
		collection.create({
			async: false,
			data: message,
			callback: callback
		});

	return result;
}

function prepareContentForm(data){
	var content = [];
	for (var k in data.schema){
		if (!data.schema[k].isPublic) continue;
		var obj = {
			type: data.schema[k].type,
			name: data.schema[k].tr,
			seccio: k,
			iden: 0
		};
		if (data.content) 
			obj.valor = data.content[k];

		content.push(obj);
	}
	return content;
}