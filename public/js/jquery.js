$(document).ready(function(){

    /*test*/
    $("body").hover(function(){
    	$("body").animate({color: 'blue'}, 3000);
        $("#flashMessage").animate({display: 'none'}, 3000);
    });
	
	/*APPARITION DU FOND DE FLEURS*/
	$("body").hover(function(){
		$("#divtitle").animate({opacity: '1'}, 3000);
	});
	
	/*APPARITION DE MON NOM AU MILIEU DES FLEURS*/
	$("body").hover(function(){
		$("#title").animate({opacity: '1'}, 3000);
	});
	
	/*Rabbatement du div de contenu principal sous le cadre rouge de titre*/
	$(".roses").mouseenter(function(){
		$(".roses").animate({height: '250px'}, 1500);
	});

    /*APPARITION DU DIV DE CONTENU PRINCIPAL*/
	
	/*première partie*/
	$("#1").hover(function(){
		$("#1").animate({opacity: '1'});
	});
	
	/*deuxième partie*/
	$("#2").hover(function(){
		$("#2").animate({opacity: '1'});
	});
	
	/*troisième partie*/
	$("#3").hover(function(){
		$("#3").animate({opacity: '1'});
	});
	
	
	/*ANIMATION DU DIV DE CONTENU PRINCIPAL*/
	
	/*Quand on passe sur le premier paragraphe, la taille du titre augmente*/
	$("#1").mouseenter(function(){
		$("h2").animate({fontSize: '50px'});
	});
	
	/*Quand on quitte le premier paragraphe, la taille du titre diminue*/
	$("#1").mouseleave(function(){
		$("h2").animate({fontSize : '28px'});
	});
	
	
	/*Quand on passe sur le deuxième paragraphe, la taille du titre augmente*/
	$("#2").mouseenter(function(){
		$("#h3p1").animate({fontSize: '30px'});
	});

	/*Quand on quitte le deuxième paragraphe, la taille du titre diminue*/
	$("#2").mouseleave(function(){
		$("#h3p1").animate({fontSize : '22px'});
	});
	
	
	/*Quand on passe sur le troisième paragraphe, la taille du titre augmente*/
	$("#3").mouseenter(function(){
		$("#h3p2").animate({fontSize: '30px'});
	});
	
	/*Quand on quitte le troisième paragraphe, la taille du titre diminue*/
	$("#3").mouseleave(function(){
		$("#h3p2").animate({fontSize : '22px'});
	});
	
	
	/*Quand on passe sur le quatrième paragraphe, la taille du titre augmente*/
	$("#4").mouseenter(function(){
		$("#h3p3").animate({fontSize: '30px'});
	});
	
	/*Quand on quitte le quatrième paragraphe, la taille du titre diminue*/
	$("#4").mouseleave(function(){
		$("#h3p3").animate({fontSize : '22px'});
	});
	
	
	/*Quand on passe sur le cinquième paragraphe, la taille du titre augmente*/
	$("#5").mouseenter(function(){
		$("#h3p4").animate({fontSize: '30px'});
	});
	
	/*Quand on quitte le cinquième paragraphe, la taille du titre diminue*/
	$("#5").mouseleave(function(){
		$("#h3p4").animate({fontSize : '22px'});
	});
	
	
	
	/*ANIMATIONS DU FOOTER*/
	
	/*Quand on passe sur le footer, le 'CONTACTEZ-MOI !' grandit*/
	$("#footer").mouseenter(function(){
		$("h2").animate({fontSize: '50px'});
		$("#footer").animate({height: '600px'});
	});
	
	/*Quand on quitte le footer, le 'CONTACTEZ-MOI !' revient à sa taille normale*/
	$("#footer").mouseleave(function(){
		$("h2").animate({fontSize: '28px'}, 1000);
		$("#footer").animate({height: '500px'}, 1000);
	});
	
	
	
	/*ANIMATION DE LA BARRETTE DE FLEURS AVANT LE FOOTER*/
	
	$("#separation").hover(function(){
		$("#separation").animate({width: "50%"}, 700);
		$("#separation").animate({paddingLeft: "50%"}, 600);
		$("#separation").animate({paddingLeft: "16%"}, 600);
		$("#separation").animate({width: "80%"}, 700);
	});
});