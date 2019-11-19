/**
 * MF Controllers
 */
(function(){
    'use strict';

    var controllerResources = {
        parent : function(method){
            if (!method) return;
            var args = Array.prototype.slice.call(arguments, 1);
            var obj = this.constructor.__super__;

            if(obj && $.isFunction(obj[method]))
                obj[method].apply(this, args);
        },
        destroy: function(){
            self = {};
        }
    };

    /**
     * Basic Controller
     */
    MF.BasicController = function(){};
    $.extend(true, MF.BasicController.prototype, controllerResources, {
        view: function(){},
    });
    
    MF.BasicController.extend = MF.extend;

    /**
     * Collection Controller
     */
	MF.CollectionController = function(collection){
		if (!collection || typeof collection !== 'object') {
			this.destroy();
			throw "Invalid collection";
		}
		
		this.collection = collection;
	};

	$.extend(true, MF.CollectionController.prototype, controllerResources, {
		get: function(id){
			console.log('getSUPER');
			console.log(this);
		},
		list: function(){},
		create: function(){},
		edit: function(){},
	});

	MF.CollectionController.extend = MF.extend;

	MF.Controller = function(props){
		if (!props.collection || typeof props.collection !== 'object')
			return new (MF.BasicController.extend(props))();

		return new (MF.CollectionController.extend(props))(props.collection);
	}

}).call(this);
