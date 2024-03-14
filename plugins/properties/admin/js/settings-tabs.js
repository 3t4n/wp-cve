jQuery(document).ready(function($){
	var tabsContainer = document.querySelector('.settings-sections-container');
	var nav = document.createElement('ul');
	nav.className = 'settings-tabs';
	$('.properties_plugin-settings-page .settings-section h2').each(function(){
		var link = document.createElement('li');
		link.className = 'settings-tab';
		link.setAttribute( 'data-tab', this.parentNode.getAttribute('id') );
		link.appendChild(document.createTextNode($(this).text()));
		nav.appendChild(link);
		$(this.parentNode).fadeOut();
	});
	$('.properties_plugin-settings-page .settings-section').first().fadeIn(400,function(){
		$(this).toggleClass('active-tab');
		$('.settings-tab').first().toggleClass('active-tab');
	});
	tabsContainer.parentNode.insertBefore(nav,tabsContainer);
	$('.settings-tab').on('click',function(e){
		e.preventDefault();
		if(!$(this).hasClass('active-tab')) {
			$('.settings-tab.active-tab').each(function(){
				var targetId = this.getAttribute('data-tab');
				$(this).toggleClass('active-tab');
				$(document.getElementById(targetId)).fadeOut(400,function(){
					$(this).toggleClass('active-tab');
				});
			});
			var targetId = this.getAttribute('data-tab');
			$(this).toggleClass('active-tab');
			$(document.getElementById(targetId)).fadeIn(400,function(){
				$(this).toggleClass('active-tab');
			});
		}
	});
});