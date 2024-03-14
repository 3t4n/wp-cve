function jPopup(nom,valeur,contenu,duree,largeur,hauteur,opacite,bouton,disableclick,modeDebug){

	//no cookie >> popup
	if(!jQuery.cookie(nom) || modeDebug){
		jQuery('body').append('<div id="framePopUp" class="closepopup"></div><div id="inPopUp"></div>');
		jQuery('#inPopUp').css({'width':largeur+'px','height':hauteur+'px','marginTop':'-'+hauteur/2+'px','marginLeft':'-'+largeur/2+'px','zIndex':'999999'});
		jQuery('#inPopUp').append(contenu);
		jQuery('#framePopUp').css({'backgroundColor':options.bgcolor});
		// optionnal close button
		if(bouton){
			jQuery('#inPopUp').append('<a id="spmCloseButton" class="closepopup" href="#">&nbsp;</a>');
			jQuery('#inPopUp').css({'overflow':'visible'});
		}
		//disable close bind on background
		if(disableclick){
			jQuery('#framePopUp').removeClass('closepopup');
		}

		jQuery('#framePopUp').css({'opacity':opacite,'zIndex':'99999'});
		jQuery('#framePopUp').fadeIn('fast');
		jQuery('#inPopUp').show();
	}
	
	//cookie creation on closing
	jQuery('.closepopup').on('touchstart click',function (){
		jQuery.cookie(nom, valeur, { expires: duree,path: '/' });
		jQuery('#framePopUp').remove();
		jQuery('#inPopUp').remove();
		return false;
	});
}

jQuery(window).load(function(){
	if(jQuery(window).width()>options.threshold)
	jPopup(servername, 'popup', options.contenu, parseInt(options.cookie), parseInt(options.largeur), parseInt(options.hauteur),options.opacite,options.bouton,options.disableOutside,options.debug);
});