var Score = MF.Model.extend({
    urlRoot: uriREST + 'scores',
    schema: {
        id: {isPublic: false, type: 'number', tr: 'Id'},
        name: {isPublic: true,  type: 'string', tr: 'Nom'},
        composer: {isPublic: true,  type: 'collection', tr: 'Compositor', collection: 'composerCollection'},
        lyricist: {isPublic: true,  type: 'collection', tr: 'Lletristes', collection: 'lyricistCollection'},
        century: {isPublic: true,  type: 'string', tr: 'Segle'},
        choirType: {isPublic: true,  type: 'collection', tr: 'Veus', collection: 'choirTypeCollection'},
        style: {isPublic: true,  type: 'collection', tr: 'Estil', collection: 'styleCollection'},
        language: {isPublic: true,  type: 'collection', tr: 'Idioma', collection: 'languageCollection'},
        cupboard: {isPublic: true,  type: 'collection', tr: 'Armari', collection: 'cupboardCollection'},
        box: {isPublic: true,  type: 'collection', tr: 'Caixa', collection: 'boxCollection'},

    }
});
var Composer = MF.Model.extend({
    urlRoot: uriREST + 'composers',
    schema: {
        id:   { isPublic: false, type: 'number', tr: 'Id'},
        name: { isPublic: true,    type: 'string', tr: 'Nom'},
    }
});
var Lyricist = MF.Model.extend({
    urlRoot: uriREST + 'lyrcists',
    schema: {
        id:   { isPublic: false, type: 'number', tr: 'Id'},
        name: { isPublic: true,    type: 'string', tr: 'Nom'},
    }
});
var ChoirType = MF.Model.extend({
    urlRoot: uriREST + 'choirTypes',
    schema: {
        id:   { isPublic: false, type: 'number', tr: 'Id'},
        name: { isPublic: true,    type: 'string', tr: 'Nom'},
    }
});
var Style = MF.Model.extend({
    urlRoot: uriREST + 'styles',
    schema: {
        id:   { isPublic: false, type: 'number', tr: 'Id'},
        name: { isPublic: true,    type: 'string', tr: 'Nom'},
    }
});
var Language = MF.Model.extend({
    urlRoot: uriREST + 'languages',
    schema: {
        id:   { isPublic: false, type: 'number', tr: 'Id'},
        name: { isPublic: true,    type: 'string', tr: 'Nom'},
    }
});
var Cupboard = MF.Model.extend({
    urlRoot: uriREST + 'cupboards',
    schema: {
        id:   { isPublic: false, type: 'number', tr: 'Id'},
        name: { isPublic: true,    type: 'string', tr: 'Nom'},
    }
});
var Box = MF.Model.extend({
    urlRoot: uriREST + 'boxes',
    schema: {
        id:   { isPublic: false, type: 'number', tr: 'Id'},
        name: { isPublic: true,    type: 'string', tr: 'Nom'},
    }
});
