'use strict';

$(document).ready(function(){
    $('form').on('submit', function(event){
        event.preventDefault();
        $("#errorMessage").html("&nbsp;");
        var data = {};
        $(this).serializeArray().forEach(element => {
            data[element.name] = element.value;
        });
        var request = $.post("/login", data);
        request.fail(function(response){
            $("#errorMessage").text(response.responseJSON.error.description);
        });
        request.done(function(response){
            window.location = "";
        });
    });
});