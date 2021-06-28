$(document).ready(function(){

    /*test*/
    $("body").hover(function(){
    	$("body").animate({color: 'blue'}, 3000);
        $("#flashMessage").animate({display: 'none'}, 3000);
    });

});