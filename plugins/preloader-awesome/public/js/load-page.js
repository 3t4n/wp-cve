(function(){
	var pageWrap = document.getElementById('ta-pageload'),
		pages = pageWrap.querySelector('div.container-pageload'),
		loaderTA = document.getElementById('loader');

	loaderTA.style.visibility = "visible";
	function id(v){ return document.getElementById(v); }
	function loadbar() {
		var ovrl = id("loader"),
				prog = id("progress"),
				stat = id("progstat"),
				img = document.images,
				c = 0,
				tot = img.length;
		if(tot == 0) return doneLoading();

		function imgLoaded(){
			c += 1;
			var perc = ((100/tot*c) << 0) +"%";
			prog.style.width = perc;
			stat.innerHTML = ""+ perc;
			if(c===tot) return doneLoading();
		}
		function doneLoading(){
			ovrl.style.opacity = 0;
			setTimeout(function(){
				ovrl.style.display = "none";
				loaderTA.style.visibility = "hidden";
				classie.removeClass(pages, 'show');
				classie.addClass(pages, 'show');
			}, 1200);
		}
		for(var i=0; i<tot; i++) {
			var tImg     = new Image();
			tImg.onload  = imgLoaded;
			tImg.onerror = imgLoaded;
			tImg.src     = img[i].src;
		}
	}
	document.addEventListener('DOMContentLoaded', loadbar, false);
}());