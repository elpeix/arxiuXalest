(function($){
	'use strict';

	if (!window.hasOwnProperty('MF')) return;

	$.mfAlert = {
		// Public methods
		alert: function(message, type) {
			if(!type) type = 'alert';
			$("#mfAlert").remove();
			var alertVar = $('<div id="mfAlert" class="mf-alert" />');
			$("body").append(alertVar);
			if (!message) alertVar.fadeOut();
			else {
				alertVar.removeClass()
						.addClass(type)
						.append(message)
						.show();
				window.setTimeout(function(){
					alertVar.fadeOut();
				},2000 + message.length * 50); // Message length defines the alert duration.
			}			
		},
		bigAlert: function(message, callback) {
			var cont = this._createBox(message);
			window.setTimeout(function(){
				$.mfAlert._hideBox();
				if(callback && typeof callback === 'function') callback();
			},2000 + message.length * 50); // Message length defines the alert duration.	
		},
		confirm: function(message, btnStr, callback) {
			var cont = this._createBox(message);
			var ok = $('<input type="button" value="'+btnStr+'" class="btn-primary" />').appendTo(cont);
			var cn = $('<input type="button" value="Cancel&middot;la" class="btn-cancel" />').appendTo(cont);
			ok.on('click', function() {ret(true);});
			cn.on('click', function() {ret(false);});
			cont.keypress( function(e) {
				if( e.keyCode == 27 ) ret(false);
			});
			ok.focus();
			function ret(r){
				$.mfAlert._hideBox();
				if(callback) callback(r);
			}
		},
		
		//Private methods
		_createBox: function(message) {
			$.mfAlert._hideBox();
			$.mfAlert._overlay('show');
			var cont = $('<div class="mf-box" />');
			var text = $('<div class="mf-box-text" />').appendTo(cont);
			text.html(message.replace(/\n/g, '<br />'));
			cont.appendTo($("body"));
			return cont;
		},

		_hideBox: function(){
			$(".mf-box").animate({
				opacity: 0
			},120,"swing",function(){$(this).remove();});
			$.mfAlert._overlay('hide');
		},
		_overlay: function(status){
			if (status == "show") {
				$.mfAlert._overlay('hide');
				var overlay = $('<div class="mf-overlay" />').appendTo($('body'));
				overlay.on('click', $.mfAlert._hideBox);
				return;
			}
			$(".mf-overlay").animate({opacity: 0},120,"swing",function(){$(this).remove();});
		}
	};
	
	//Alert
	window.MF.alert = function(message, type, val){
		if (val) message = message.replace(/%s/, val);
		$.mfAlert.alert(message, type);
	};
	window.MF.bigAlert = function(message,callback){
		$.mfAlert.bigAlert(message, callback);
	};
	window.MF.confirm = function(message, btnStr, callback){
		if(typeof(arguments[1]) == 'function'){
			callback = btnStr;
			btnStr = 'Accepta';
		}
		$.mfAlert.confirm(message, btnStr, callback);
	};
})(jQuery);
