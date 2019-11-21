var Score = MF.Model.extend({
    urlRoot: uriREST + 'scores',
    schema: {
        id: {isPublic: false, type: 'number', tr: 'Id'},
        name: {isPublic: true,  type: 'string', tr: 'Nom', orderField: 'scores.name'},
        composer: {isPublic: true,  type: 'collection', tr: 'Compositor', collection: 'composerCollection', orderField: 'composers.name'},
        lyricist: {isPublic: true,  type: 'collection', tr: 'Lletristes', collection: 'lyricistCollection', orderField: 'lyricists.name'},
        century: {isPublic: true,  type: 'string', tr: 'Segle', orderField: 'scores.century'},
        choirType: {isPublic: true,  type: 'collection', tr: 'Veus', collection: 'choirTypeCollection', orderField: 'choirTypes.name'},
        style: {isPublic: true,  type: 'collection', tr: 'Estil', collection: 'styleCollection', orderField: 'styles.name'},
        language: {isPublic: true,  type: 'collection', tr: 'Idioma', collection: 'languageCollection', orderField: 'languages.name'},
        cupboard: {isPublic: true,  type: 'collection', tr: 'Armari', collection: 'cupboardCollection', orderField: 'cupboards.name'},
        box: {isPublic: true,  type: 'collection', tr: 'Caixa', collection: 'boxCollection', orderField: 'boxes.name'}
    }
});
var Composer = MF.Model.extend({
    urlRoot: uriREST + 'composers',
    schema: {
        id: { isPublic: false, type: 'number', tr: 'Id'},
        name: { isPublic: true, type: 'string', tr: 'Nom'},
    }
});
var Lyricist = MF.Model.extend({
    urlRoot: uriREST + 'lyricists',
    schema: {
        id: { isPublic: false, type: 'number', tr: 'Id'},
        name: { isPublic: true, type: 'string', tr: 'Nom'},
    }
});
var ChoirType = MF.Model.extend({
    urlRoot: uriREST + 'choirTypes',
    schema: {
        id: { isPublic: false, type: 'number', tr: 'Id'},
        name: { isPublic: true, type: 'string', tr: 'Nom'},
    }
});
var Style = MF.Model.extend({
    urlRoot: uriREST + 'styles',
    schema: {
        id: { isPublic: false, type: 'number', tr: 'Id'},
        name: { isPublic: true, type: 'string', tr: 'Nom'},
    }
});
var Language = MF.Model.extend({
    urlRoot: uriREST + 'languages',
    schema: {
        id: { isPublic: false, type: 'number', tr: 'Id'},
        name: { isPublic: true, type: 'string', tr: 'Nom'},
    }
});
var Cupboard = MF.Model.extend({
    urlRoot: uriREST + 'cupboards',
    schema: {
        id: { isPublic: false, type: 'number', tr: 'Id'},
        name: { isPublic: true, type: 'string', tr: 'Nom'},
    }
});
var Box = MF.Model.extend({
    urlRoot: uriREST + 'boxes',
    schema: {
        id: { isPublic: false, type: 'number', tr: 'Id'},
        name: { isPublic: true, type: 'string', tr: 'Nom'},
    }
});
