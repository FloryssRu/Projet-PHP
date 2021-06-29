$("#vertical").hide(0);

$(document).ready(function(){

    $("#close").click(function(){
    	$("#flashMessage").animate({opacity: '0', margin: '0', height: '0', padding: '0'}, 500);
    });

    $("#menu").click(function(){
    	$("#vertical").toggle(0);
    });

});