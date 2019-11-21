$(document).ready(function(){
	initApp();
});

function initApp(ev){
    var app = window.app;
	app.header = $('#containerHeader');
	app.content = $('#content');
	app.load = $('#load');
	app.filter = [];
	app.filterTag = [];
	app.menu = $('#menu');

	app.menu.mainMenu({
		data: {
			search : {link: '#', text : 'Cerca'},
			scores : {link: '#scores', text : 'Partitures'},
			composers : {link: '#composers', text : 'Compositors'},
			lyricists : {link: '#lyricists', text : 'Lletristes'},
			choirTypes : {link: '#choirTypes', text : 'Veus'},
			styles : {link: '#styles', text : 'Estils'},
			languages : {link: '#languages', text : 'Idiomes'},
			cupboards : {link: '#cupboards', text : 'Armaris'},
			boxes : {link: '#boxes', text : 'Caixes'}
		}
	});

	var urls = {
		'' : app.homeController.view,
		'scores' : app.scoreController.list,
		'scores/:id' : app.scoreController.get,
		'cupboards' : app.cupboardController.list,
		'cupboards/:id' : app.cupboardController.get,
		'boxes' : app.boxController.list,
		'boxes/:id' : app.boxController.get,
		'choirTypes' : app.choirTypeController.list,
		'choirTypes/:id' : app.choirTypeController.get,
		'languages' : app.languageController.list,
		'languages/:id' : app.languageController.get,
		'composers' : app.composerController.list,
		'composers/:id' : app.composerController.get,
		'lyricists' : app.lyricistController.list,
		'lyricists/:id' : app.lyricistController.get,
		'styles' : app.styleController.list,
		'styles/:id' : app.styleController.get,
		'*' : app.homeController.nops
	};

	if (app.isAdmin) {
		urls = $.extend(true, {
			'scores/new' : app.scoreController.create,
			'scores/:id/edit' : app.scoreController.edit,
			'composers/new' : app.composerController.create,
			'composers/:id/edit' : app.composerController.edit,
			'lyricists/new' : app.lyricistController.create,
			'lyricists/:id/edit' : app.lyricistController.edit,
			'choirTypes/new' : app.choirTypeController.create,
			'choirTypes/:id/edit' : app.choirTypeController.edit,	
			'styles/new' : app.styleController.create,
			'styles/:id/edit' : app.styleController.edit,	
			'languages/new' : app.languageController.create,
			'languages/:id/edit' : app.languageController.edit,
			'cupboards/new' : app.cupboardController.create,
			'cupboards/:id/edit' : app.cupboardController.edit,
			'boxes/new' : app.boxController.create,
			'boxes/:id/edit' : app.boxController.edit
		}, urls);
	}
	routie(urls);

	//Events
	$(document).click(function(){
		$('.llista-menu-views').removeClass('sel');
	});

	app.load.fadeOut();

	$('#logout').on('click', function(event){
		event.preventDefault();
		var request = $.post("/logout");
        request.always(function(response){
            window.location = "";
        });
	});
}
