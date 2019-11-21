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

		var options = {};
		if (localStorage && localStorage.hasOwnProperty('scoreColViews')) {
			options.views = JSON.parse(localStorage.getItem('scoreColViews'));
		}
		app.content.listScores(options);
	},
	
	create: function(){
		app.menu.mainMenu('active', 'scores');
		app.header.text('Afegeix partitura');
		createElement('score', new Score(), 'scores');
	},

	get: function(id){
		app.menu.mainMenu('active', 'scores');
		app.content.viewScore({
			identifier:id
		});
	},

	edit: function(id){
		app.menu.mainMenu('active', 'scores');
		app.scoreCollection.get({
			identifier: id,
			success: function(data) {
				app.header.text('Edita la partitura: ' + data.content.name);
				editElement('score', data, 'scores/' + id);
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
		createElement('composer', new Composer(), 'composers');
	},
	edit: function(id) {
		app.menu.mainMenu('active', 'composers');
		app.composerCollection.get({
			identifier: id,
			success: function(data) {
				app.header.text('Edita el compositor: ' + data.content.name);
				editElement('composer', data, 'composers');
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
		createElement('choirType', new ChoirType(), 'choirTypes');
	},
	edit: function(id) {
		app.menu.mainMenu('active', 'choirTypes');
		app.choirTypeCollection.get({
			identifier: id,
			success: function(data) {
				app.header.text('Edita el conjunt de veus: ' + data.content.name);
				editElement('choirType', data, 'choirTypes');
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
		createElement('cupboard', new Cupboard(), 'cupboards');
	},
	edit: function(id) {
		app.menu.mainMenu('active', 'cupboards');
		app.cupboardCollection.get({
			identifier: id,
			success: function(data) {
				app.header.text('Edita l\'armari: ' + data.content.name);
				editElement('cupboard', data, 'cupboards');
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
		createElement('box', new Box(), 'boxes');
	},
	edit: function(id) {
		app.menu.mainMenu('active', 'boxes');
		app.boxCollection.get({
			identifier: id,
			success: function(data) {
				app.header.text('Edita la caixa: ' + data.content.name);
				editElement('box', data, 'boxes');
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
		createElement('language', new Language(), 'languages');
	},
	edit: function(id) {
		app.menu.mainMenu('active', 'languages');
		app.languageCollection.get({
			identifier: id,
			success: function(data) {
				app.header.text('Edita l\'idioma: ' + data.content.name);
				editElement('language', data, 'languages');
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
		createElement('lyricist', new Lyricist(), 'lyricists');
	},
	edit: function(id) {
		app.menu.mainMenu('active', 'lyricists');
		app.lyricistCollection.get({
			identifier: id,
			success: function(data) {
				app.header.text('Edita el lletrista: ' + data.content.name);
				editElement('lyricist', data, 'lyricists');
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
		createElement('style', new Style(), 'styles');
	},
	edit: function(id) {
		app.menu.mainMenu('active', 'styles');
		app.styleCollection.get({
			identifier: id,
			success: function(data) {
				app.header.text('Edita l\'estil: ' + data.content.name);
				editElement('style', data, 'styles');
			}
		});
	}
});