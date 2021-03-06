function MFConnection(externalUrl){
	t = this;
	
	t.init 			= init;
	t.destroy		= destroy;
	t.abort			= abort;
	t.setUrl		= setUrl;
	t.setDataType	= setDataType;
	t.getData		= getData;
	t.getText		= getText;
	t.post			= post;
	t.postFormData	= postFormData;
	t.putFormData	= putFormData;
	t.put			= put;
	t.remove		= remove;
	t.checkStatus 	= checkStatus;
	
	//Local
	var url 		= ''; 
	var dataType	= '';  //xml, text, json
	var request;
	var data;
	
	//Ajax setup
	var token = $('meta[name="csrf-token"]').attr('content');
	$.ajaxSetup({
		headers: {
			Accept : "application/json",
			'X-CSRFToken': token
		},
		contentType: "application/json",
		dataType: "application/json"
	});

	//Public Methods
	function init(async, callback){
		if (externalUrl) setUrl(externalUrl);
		if (!arguments.length)
			get(false);
		else{
			if (typeof(arguments[0]) == 'function'){
				callback = async;
				async = true;
			}
			get(async, callback);
		}
	}
	function destroy(){
		data = {};
	}
	function abort(){
		request.abort();
	}
	function setUrl(externalUrl){
		url = externalUrl;
	}
	function setDataType(externalDataType){
		dataType = externalDataType;
		$.ajaxSetup({
			headers: {
				'X-CSRFToken': token,
				Accept : dataType
			},
			contentType: dataType,
			dataType: dataType
		});
	}
	function getData(){
		return data;
	}
	function get(async, callback){
		if (!arguments.length) async = true;
		else{
			if (typeof(arguments[0]) == 'function'){
				callback = async;
				async = true;
			}
		}
		request = $.ajax({
			type: 'GET',
			async: async,
			url: _getRESTUrl(),
			error: function(rData){data = _result(rData); if (callback) callback(data);},
			success: function(rData){data = _result(rData); if (callback) callback(data);}
		});
	}
	function getText(async, callback){
		if (!arguments.length) async = true;
		else{
			if (typeof(arguments[0]) == 'function'){
				callback = async;
				async = true;
			}
		}		
		var handleStateChange = function () {
			switch (xhr.readyState) {
				case 0: // UNINITIALIZED
		      	case 1: // LOADING
		      	case 2: // LOADED
		      	case 3: // INTERACTIVE
		      		break;
		      	case 4: // COMPLETED
		      		if (callback)
		      			callback({
		      				status : xhr.status, 
		      				responseText : xhr.responseText
		      			});
		      		break;
		      	default: 
		      		if (callback)
		      			callback({
		      				status: 500, 
		      				responseText : 'Error'
		      			});
		      		break;
		   }
		};
		
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = handleStateChange;
		xhr.open('GET', _getRESTUrl(), async);
		xhr.send(null);
		
	}
	function post(message, async, callback){
		if (!arguments.length) return false;
		else if (arguments.length == 1) async = true;
		else if (arguments.length == 2){
			if (typeof(arguments[1]) == 'function'){
				callback = async;
				async = true;
			}
		}
		request = $.ajax({
			type: 'POST',
			async: async,
			data: message,
			url: _getRESTUrl(),
			error: function(rData){if (callback)callback(_result(rData));},
			success: function(rData){if (callback)callback(_result(rData));}
		});
	}
	function putFormData(message, async, callback){
		_sendFormData('PUT', message, async, callback);
	}
	function postFormData(message, async, callback){
		_sendFormData('POST', message, async, callback);
	}
	function _sendFormData(type, message, async, callback){
		if (!arguments.length) return false;
		else if (arguments.length == 2) async = false;
		else if (arguments.length == 3){
			if (typeof(arguments[2]) == 'function'){
				callback = async;
				async = false;
			}
		}
		
		//Create FormData
		var formData = new FormData();
		for (var item in message) {
			formData.append(item, message[item]);
		}
		var handleStateChange = function () {
			switch (xhr.readyState) {
				case 0: // UNINITIALIZED
		      	case 1: // LOADING
		      	case 2: // LOADED
		      	case 3: // INTERACTIVE
		      		break;
		      	case 4: // COMPLETED
		      		if (callback){
		      			var rData = {};
						if (xhr.status > 299) {
							rData.status = xhr.status;
							rData.responseText = xhr.responseText;
						}
						else {
							rData = $.parseJSON(xhr.responseText);
						}
						callback(_result(rData));
		      		}
		      		break;
		      	default: 
		      		if (callback){
		      			callback({
		      				status: 500, 
		      				responseText : 'Error'
		      			});
		      			callback(_result(rData));
		      		}
		      		break;
		   }
		};
		
		//XHMLHttpRequest
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = handleStateChange;
		xhr.open(type, _getRESTUrl(), async);
		xhr.setRequestHeader('Accept', 'application/json');
		xhr.setRequestHeader('X-CSRFToken', token);
		xhr.setRequestHeader('contentType', 'application/json');
		xhr.send(formData);
	}
	function put(message, async, callback){
		if (!arguments.length) return false;
		else if (arguments.length == 1) async = true;
		else if (arguments.length == 2){
			if (typeof(arguments[1]) == 'function'){
				callback = async;
				async = true;
			}
		}	
		var method_ = 'PUT';
		var url_ = _getRESTUrl();
		if (window.APP_PSEUDO_PUT) {
			method_ = 'POST';
			url_ = url + '?method=PUT';
		}
		request = $.ajax({
			type: method_,
			async: async,
			data: message,
			url: url_,
			error : function(rData){if (callback)callback(_result(rData));},
			success : function(rData){if (callback)callback(_result(rData));}
		});
	}
	function remove(message, async, callback){
		if (!arguments.length) return false;
		else if (arguments.length == 1) {
			async = true;
			if (typeof(arguments[0]) == 'function'){
				callback = message;
				message = '';
			}
		}
		else if (arguments.length == 2){
			if (typeof(arguments[1]) == 'function'){
				callback = async;
				async = true;
			}
		}
		request = $.ajax({
			type	: 'DELETE',
			async 	: async,
			data	: message,
			url		: _getRESTUrl(),
			error 	: function(rData){if (callback)callback(_result(rData));},
			success : function(rData){if (callback)callback(_result(rData));}
		});
	}
	function checkStatus(rData){
		if (rData.status > 299){
			var responseText = parseResponseText(rData.responseText);
			MF.alert(responseText.message? responseText.message : responseText.code, 'error');
			return false;
		}
		return true;
	}
	
	//Private Methods
	function _getRESTUrl(){
		if (!url) setUrl(externalUrl);
		return uriREST + url;
	}

	function _result(rData){
		var dataTmp;
		if (dataType == 'xml')
			dataTmp = $(rData);
		else if (dataType == 'text')
			dataTmp = rData;
		else {
			try {
				dataTmp = _dataJSON(rData);
			}
			catch (err){
				dataTmp = err;
			}
		}
		return dataTmp;
	}
	
	function _dataJSON(rData){
		var dataTmp;
		switch (rData.status){
			case 0:
			case 503:
				if (rData.status == 503 || (rData.readyState == 0 && rData.statusText == 'error')) {
					var $infoText = $('#u_infoText');
					if (!($infoText.length && $infoText.text() == uln.CONNECTION_ERROR))
						MF.alert('Error de connexió', 'error');
				}
				throw rData;
				break;
			case 200:
			case 201:
			case 202:
			case 203:
				dataTmp = $.parseJSON(rData.responseText);
				break;
			case 204:
				dataTmp = {status : 204};
				break;
			case 401:
				MF.bigAlert('No et trobes conectat. Redirigint a la pantalla de login', function() {
					window.location = "";
				});
				dataTmp = {status : 401};
				break;
			default:
				//Error
				dataTmp = $.parseJSON(rData.responseText);
				break;
		}
		return dataTmp;
	}
}
