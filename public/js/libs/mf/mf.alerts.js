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
		confirm: function(message, btnStr, callback) {
			$.mfAlert._hideConfirm();
			$.mfAlert._overlay('show');
			var cont = $('<div id="mfConfirm" class="mf-confirm" />');
			var text = $('<div class="mf-confirmText" />');
			var ok = $('<input type="button" value="'+btnStr+'" class="btn-primary" />');
			var cn = $('<input type="button" value="Cancel&middot;la" class="btn-cancel" />');

			cont.append(text)
				.append(ok)
				.append(cn)
				.appendTo($("body"));

			text.html(message.replace(/\n/g, '<br />'));
			ok.click( function() {
				ret(true);
			});
			cn.click( function() {
				ret(false);
			});
			ok.focus();
			cont.keypress( function(e) {
				//if( e.keyCode == 13 ) ret(true);
				if( e.keyCode == 27 ) ret(false);
			});
			function ret(r){
				$.mfAlert._hideConfirm();
				if(callback) callback(r);
			}
		},
		
		//Private methods
		_hideConfirm: function(){
			$("#mfConfirm").animate({
				opacity: 0
			},120,"swing",function(){$(this).remove();});
			$.mfAlert._overlay('hide');
		},
		_overlay: function(status){
			switch( status ) {
				case 'show':
					$.mfAlert._overlay('hide');
					$("body").append('<div class="mf-alertBlank" />');
				break;
				case 'hide':
					$(".mf-alertBlank").animate({
						opacity: 0
					},120,"swing",function(){$(this).remove();});
				break;
			}
		}
	};
	
	//Alert
	window.MF.alert = function(message, type, val){
		if (val) message = message.replace(/%s/, val);
		$.mfAlert.alert(message, type);
	};
	window.MF.confirm = function(message, btnStr, callback){
		if(typeof(arguments[1]) == 'function'){
			callback = btnStr;
			btnStr = 'Accepta';
		}
		$.mfAlert.confirm(message, btnStr, callback);
	};
})(jQuery);
