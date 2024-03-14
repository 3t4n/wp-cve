/*
	Smartslider 1.0
	
	Author:
		Ralf Hortt, 'Horttcore' - http://www.horttcore.de

	License:
		MIT-style license
		
	Class: 
		Slider
		
	Description:
		Sliders for the World
		
	Requirements:
		Mootools 1.2 - Earlier Version should also work as its very simple 
	
	Usage:
		Add css class 'slider' for toggle element
		Target element ID in the 'href' attribute with "#"
		
	Options:
		Add css class 'slideShow' that the Element is opened onload
				
	Example:
		<a href="#lorem" class='slider'>Lorem Ipsum</a>
		<p id="lorem">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
*/
window.addEvent('domready', function(){
	var mySlides = [];
 	$$(".slider").each(function(slider,i){

		// get element to slide
		link = slider.getProperty('href');
		link = link.replace('#','');
 		mySlides[i] = new Fx.Slide(link);
		
		// set state
		state = slider.getProperty('class');
		if	(state.search(/slideShow/) == '-1')
			{mySlides[i].hide();}

		// inject onClick event
 		slider.addEvent('click', function(e){
			new Event(e).stop();
			mySlides[i].toggle();
			return false;
		});
 	});
 });