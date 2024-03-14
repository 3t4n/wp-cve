jQuery(document).ready(function($){
    
   	var styletag = $('#dojodigital_live_css');
   	var startingStyles = styletag.html();
   	
   	var toggle = $('#wp-admin-bar-dojodigital_live_css-toggle');
   
   	toggle.find('a').on('click', function(e){
   		e.preventDefault();
   		openEditor();
   		$(this).blur();
   	});
	
	var hideButton = $('<a href="#" class="lct-hide"><span>Hide</span></a>').on('click', function(e){
		e.preventDefault();
		closeEditor();
	});
	
	var saveButton = $('<a href="#" id="lct-save">Saved</a>').on('click', function(e){
		e.preventDefault();
		saveStyles($(this));
	});
   		
	var wpadminbar = $('#wpadminbar');
	
	var container = $('<div id="lct-container"><div id="lct-editor">' + startingStyles + '</div></div>').css({
		'z-index': maximumZ,
		top: wpadminbar.height() + 10 + 'px',
		left: '10px',
		width: '350px',
		height: '250px'
	});
		
	var handle = $('<div class="lct-handle lct-has-icon">CSS</div>');
	
	handle.on('dblclick', function(){
		container.css({
			width: '350px',
			height: '250px',
		});
		resizeEditor();
	});
	
	handle.append(saveButton);
	
	handle.append(hideButton);
	
	container.prepend(handle);
	
	var footer = $('<div id="lct-footer" />');
	
	var screensize = $('<div class="lct-has-icon" id="lct-screensize">' + $(window).width() + ' x ' + $(window).height() + '</div>');
	
	$(window).bind('beforeunload', function(){
		if( stylesChanged() ){
			return 'The CSS changes you made will be lost if you navigate away from this page.';
		}
	});
	
	container.append(footer.append(screensize));
	
	$('body').prepend(container);
	
	container.bind("mouseover", function(){
       $('body').on({
           'mousewheel': function(e) {
           e.preventDefault();
           e.stopPropagation();
           }
       });
    });
    container.bind("mouseleave",function(){
          $('body').unbind("mousewheel");
    });
	
	
	editor = ace.edit("lct-editor");
    editor.setTheme("ace/theme/monokai");
    editor.getSession().setMode("ace/mode/css");
    editor.getSession().setUseWrapMode(true);
    
	editor.on('change', function(){
		styletag.text(editor.getValue());
		
		if(stylesChanged()){
			saveButton.addClass('active').text('Save & Publish');
		} else {
			saveButton.removeClass('active').text('Saved');
		}
		
	});
    	
	var lctEditor = $('#lct-editor');
	
    $(lctEditor).keydown(function(e) {
	    if (!( String.fromCharCode(e.which).toLowerCase() == 's' && e.ctrlKey) && !(e.which == 19)) return true;
	    saveStyles(saveButton);
	    e.preventDefault();
	    return false;
	});   	
	
	resizeEditor();
	
	container.draggable({
		
		handle:'.lct-handle',
		containment:'window',
		stop: function(e,ui){
			checkBounds();
		}
	
	}).resizable({
		minWidth:250,
		minHeight:150,
		containment: 'parent',
		resize: function(e, ui){
			if( $(window).scrollTop() + $(window).height() < e.pageY - 10){
				$(this).resizable('widget').trigger('mouseup');
			}
		},
        start: function(e, ui) {
            container.css({'position':'absolute', top: ui.position.top + $(window).scrollTop() + 'px'});
        },
        stop: function(e, ui) {
            container.css({'position':'fixed', top: ui.position.top + 'px'});
            if(container.width() < 100){
            	container.css('width','100px');
            }
            
            checkBounds();
        }
   	});

   	$(window).resize(function(){ checkBounds(); });
   	
   	$(window).scroll(function(){ checkBounds(); });
   	
   		
   	/** FUNCTIONS **/
   		
   	function saveStyles( $this ){
   		
   		if( !$this.hasClass('active') ) return false;
		
		var savingIcon = $(' <span class="dashicons dashicons-update"></span>');
		
		$this.removeClass('active').html('Saving ').append(savingIcon);
		var rotate = 0;
		var rotator = setInterval(function(){ rotate += 20; savingIcon.rotate(rotate); }, 50);
		
		$.post( ajaxurl, {
			"action" : 'frontend_save',
	        "styles" :  styletag.html()
		    },
		    function( response ) {
		    	if( 1 != response ){
		    		console.log( 'Unable to update options.' );
		    	} else {
		    		$this.text('Saved');
		    		clearInterval(rotator);
		    		startingStyles = styletag.html();
		    	}
		    	
		    }
		);
		
   	} 
	
	function stylesChanged(){
		return ( styletag.html() != startingStyles );
	}
	
	function maximumZ(){
		var maxZ = 0;
		$("body > *").each(function() {
			var thisZ = parseInt( $(this).css("z-index") );
			maxZ = (thisZ >= maxZ) ? thisZ : maxZ;
		});
		
		return maxZ + 1;
	}
	
	function resizeEditor(){
		
		lctEditor.css({
			width:container.width() + 'px', 
			height:container.height() - handle.height() - 30 + 'px'
		});
		
	    editor.resize();
	}
	
	function closeEditor(){
		toggle.fadeIn('fast');
		container.fadeOut('fast');
	}
	
	function openEditor(){
		toggle.fadeOut('fast');
		container.fadeIn('fast');
	}
	
	
	function checkBounds(){
		
		var win = $(window);
    
	    var viewport = {
	        top : win.scrollTop(),
	        left : win.scrollLeft(),
	        right : win.scrollLeft() + win.width(),
	        bottom : win.scrollTop() + win.height()
	    };

    	var bounds = container.offset();
    	bounds.right = bounds.left + container.outerWidth();
    	bounds.bottom = bounds.top + container.outerHeight();
    	
    	if(container.width() > win.width()){
    		container.css('width', win.width() + 'px');
    	}
    	
    	if(container.height() > win.height() ){
    		container.css('height', win.height() + 'px');
    	}
    
    	if(bounds.left < viewport.left ){
    		container.css('left', '0');
    	}
    
    	if(bounds.right > viewport.right ){
    		container.css('left', win.width() - container.outerWidth() + 'px');
    	}
    
    	if(bounds.top < viewport.top ){
    		container.css('top', '0');
    	}
    
    	if(bounds.bottom > viewport.bottom ){
    		container.css('top', win.height() - container.outerHeight() + 'px');
    	}
    	    	
    	screensize.text(win.width() + ' x ' + win.height());
    	
    	resizeEditor();
    
	}
	
	function rotateSaver(){
		$('#lct-saver').rotate(3);
	}
	
	jQuery.fn.rotate = function(degrees) {
    
	    $(this).css({'-webkit-transform' : 'rotate('+ degrees +'deg)',
	                 '-moz-transform' : 'rotate('+ degrees +'deg)',
	                 '-ms-transform' : 'rotate('+ degrees +'deg)',
	                 'transform' : 'rotate('+ degrees +'deg)'});
	};

});


