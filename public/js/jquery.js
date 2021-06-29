$("#vertical").hide(0);

$(document).ready(function(){

    $("#close").click(function(){
    	$("#flashMessage").animate({opacity: '0', margin: '0', height: '0', padding: '0'}, 500);
    });

    /*$(".buttonRed").mouseenter(function(){
        $(".buttonRed").animate({color: 'blue'}, 500);
    });

    $(".buttonRed").mouseleave(function(){
        $(".buttonRed").animate({border: 'none'}, 500);
    });*/

    $("#menu").click(function(){
    	$("#vertical").toggle(0);
    });

    /*$("#menu").toggle(
        function(){$("#vertical").show(0);},
        function(){$("#vertical").hide(0);},
    );*/

});