
app.scoreCollection = new MF.Collection({
	url: 'scores',
	route : 'scores/',
	model : Score
});

app.composerCollection = new MF.Collection({
	url: 'composers',
	route: 'composers/',
	model : Composer
});
app.composerCollection.fetch({async:false});

app.lyricistCollection = new MF.Collection({
	url: 'lyricists',
	route: 'lyricists/',
	model : Lyricist
});
app.lyricistCollection.fetch({async:false});

app.styleCollection = new MF.Collection({
	url: 'styles',
	route: 'styles/',
	model : Style
});
app.styleCollection.fetch({async:false});

app.choirTypeCollection = new MF.Collection({
	url: 'choirTypes',
	route: 'choirTypes/',
	model : ChoirType
});
app.choirTypeCollection.fetch({async:false});

app.languageCollection = new MF.Collection({
	url: 'languages',
	route: 'languages/',
	model : Language
});
app.languageCollection.fetch({async:false});

app.cupboardCollection = new MF.Collection({
	url: 'cupboards',
	route: 'cupboards/',
	model : Cupboard
});
app.cupboardCollection.fetch({async:false});

app.boxCollection = new MF.Collection({
	url: 'boxes',
	route: 'boxes/',
	model : Box
});
app.boxCollection.fetch({async:false});
