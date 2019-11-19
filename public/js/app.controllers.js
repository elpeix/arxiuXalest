app.homeController = MF.Controller({
	view: function(){
		app.menu.mainMenu('active', 'search');
		app.header.text('');
		app.content.search();
	},
	nops: function() {
		app.header.text('No trobat');
		app.menu.mainMenu('deactive');
		app.content.html("");
	}
});

app.scoreController = MF.Controller({
	list: function(){
		app.menu.mainMenu('active', 'scores');
		app.content.listScores();
	},
	
	create: function(){
		app.menu.mainMenu('active', 'scores');
		app.header.text('Afegeix partitura');
		createElement('scores', new Score());
	},

	get: function(id){
		app.menu.mainMenu('active', 'scores');
		app.content.viewScore({
			identifier:id
		});
	},

	edit: function(id){
		app.menu.mainMenu('active', 'arxiu');
		app.scoreCollection.get({
			identifier: id,
			success: function(data) {
				app.header.text('Edita la partitura: ' + data.content.name);
				editElement('scores', data);
			}
		});
	}
});

app.composerController = MF.Controller({
	collection: app.composerCollection,

	get: function(id){
		// TODO
	},

	list: function(){
		app.menu.mainMenu('active', 'composers');
		app.content.listComposers();
	},

	create: function(){
		app.menu.mainMenu('active', 'composers');
		app.header.text('Nou compositor');
		createElement('composers', new Composer());
	},
	edit: function(id) {
		app.menu.mainMenu('active', 'composers');
		app.composerCollection.get({
			identifier: id,
			success: function(data) {
				app.header.text('Edita el compositor: ' + data.content.name);
				editElement('composers', data);
			}
		});
	}
});

app.choirTypeController = MF.Controller({
	collection: app.choirTypeCollection,

	get: function(id){
		// TODO
	},

	list: function(){
		app.menu.mainMenu('active', 'choirTypes');
		app.content.listChoirTypes();
	},

	create: function(){
		app.menu.mainMenu('active', 'choirTypes');
		app.header.text('Nou conjunt de veus');
		createElement('choirTypes', new ChoirType());
	},
	edit: function(id) {
		app.menu.mainMenu('active', 'choirTypes');
		app.choirTypeCollection.get({
			identifier: id,
			success: function(data) {
				app.header.text('Edita el conjunt de veus: ' + data.content.name);
				editElement('choirTypes', data);
			}
		});
	}
});

app.cupboardController = MF.Controller({
	collection: app.cupboardCollection,

	get: function(id){
		// TODO
	},

	list: function(){
		app.menu.mainMenu('active', 'cupboards');
		app.content.listCupboards();
	},

	create: function(){
		app.menu.mainMenu('active', 'cupboards');
		app.header.text('Nou armari');
		createElement('cupboards', new Cupboard());
	},
	edit: function(id) {
		app.menu.mainMenu('active', 'cupboards');
		app.cupboardCollection.get({
			identifier: id,
			success: function(data) {
				app.header.text('Edita l\'armari: ' + data.content.name);
				editElement('cupboards', data);
			}
		});
	}
});

app.boxController = MF.Controller({
	collection: app.boxCollection,

	get: function(id){
		// TODO
	},

	list: function(){
		app.menu.mainMenu('active', 'boxes');
		app.content.listBoxes();
	},

	create: function(){
		app.menu.mainMenu('active', 'boxes');
		app.header.text('Nova caixa');
		createElement('boxes', new Box());
	},
	edit: function(id) {
		app.menu.mainMenu('active', 'boxes');
		app.boxCollection.get({
			identifier: id,
			success: function(data) {
				app.header.text('Edita la caixa: ' + data.content.name);
				editElement('boxes', data);
			}
		});
	}
});

app.languageController = MF.Controller({
	collection: app.languageCollection,

	get: function(id){
		// TODO
	},

	list: function(){
		app.menu.mainMenu('active', 'languages');
		app.content.listLanguages();
	},

	create: function(){
		app.menu.mainMenu('active', 'languages');
		app.header.text('Nou idioma');
		createElement('languages', new Language());
	},
	edit: function(id) {
		app.menu.mainMenu('active', 'languages');
		app.languageCollection.get({
			identifier: id,
			success: function(data) {
				app.header.text('Edita l\'idioma: ' + data.content.name);
				editElement('languages', data);
			}
		});
	}
});

app.lyricistController = MF.Controller({
	collection: app.lyricistCollection,

	get: function(id){
		// TODO
	},

	list: function(){
		app.menu.mainMenu('active', 'lyricists');
		app.content.listLyricists();
	},

	create: function(){
		app.menu.mainMenu('active', 'lyricists');
		app.header.text('Nou lletrista');
		createElement('lyricists', new Lyricist());
	},
	edit: function(id) {
		app.menu.mainMenu('active', 'lyricists');
		app.lyricistCollection.get({
			identifier: id,
			success: function(data) {
				app.header.text('Edita el lletrista: ' + data.content.name);
				editElement('lyricists', data);
			}
		});
	}
});

app.styleController = MF.Controller({
	collection: app.styleCollection,

	get: function(id){
		// TODO
	},

	list: function(){
		app.menu.mainMenu('active', 'styles');
		app.content.listStyles();
	},

	create: function(){
		app.menu.mainMenu('active', 'styles');
		app.header.text('Nou estil');
		createElement('styles', new Style());
	},
	edit: function(id) {
		app.menu.mainMenu('active', 'styles');
		app.styleCollection.get({
			identifier: id,
			success: function(data) {
				app.header.text('Edita l\'estil: ' + data.content.name);
				editElement('styles', data);
			}
		});
	}
});