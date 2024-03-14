/**
 * 
 */
jQuery(document).ready(function() {
	// cookie-ecl for counter
	jQuery('#counter-ecl-cookie-js').each(function() {
		jQuery(this).data('cookieecl', new ecl_cookie(this));
		jQuery(this).data('cookieecl').writeCookie();	
	});
	
	// message-ecl
	jQuery('#counter-ecl-dg-js').each(function() {
		jQuery(this).data('messageecl', new ecl_message(this));
		jQuery(this).data('messageecl').showEffects();
	});
	
    // counter-ecl
	jQuery('.counter-ecl-js').each(function() {
		jQuery(this).data('counterecl', new ecl_cont_analog(this));
		
		jQuery(this).data('counterecl').start();
	});
	
});

function ecl_cookie(layer) {
	this.layer = layer;
	this.expire = 365;
	this.domain = '';
	this.path ='/';
	this.secure = '0';
	this.name ='cookieecl';
	this.value = 'ok';
	
	var param = jQuery(this.layer).data();
    for (var key in param) {
    	this[key] = param[key];
    } 
    
    this.writeCookie = function() {
      var expire = parseInt(this.expire);
      var secure = this.secure == '1' ? ';secure' : '';
      var fecha = new Date();
      
      fecha.setTime(fecha.getTime() + (24 * 60 * 60 * 1000 * expire));
      
      document.cookie = this.name + '=' + this.value + ';expires=' + fecha.toUTCString() + ';domain=' + this.domain +
                        ';path=' + this.path + secure; 
      
   }
}

function ecl_message(layer) {

	this.layer = layer;
	this.effects = 'none';
	this.id = '#counter-ecl-dg-js';
	this.idbutton = '#counter-ecl-dg-btn-js';
	this.hide = '0';
	
	this.effects = jQuery(this.layer).data('effects');
	this.hide = jQuery(this.layer).data('hide');
	
	this.showEffects = function() {
		var thiso = this;
		
		switch (this.effects) {
		case 'none':
			jQuery(thiso.id).show(0);
			jQuery(thiso.idbutton).click(function() {
				jQuery(thiso.id).hide(0);
				thiso.validateCookie();
			});
			
			if (this.hide == '1')
			  jQuery(document).click(function() {
				  jQuery(thiso.id).hide(0);
			  });
			break;
		case 'hide':
			jQuery(thiso.id).show(1000);
			jQuery(thiso.idbutton).click(function() {
				jQuery(thiso.id).hide(1000);
				thiso.validateCookie();
			});
			
			if (this.hide == '1')
			  jQuery(document).click(function() {
				  jQuery(thiso.id).hide(1000);
			  });
			break;
		case 'fade':
		    jQuery(thiso.id).fadeIn(1000);
		    jQuery(thiso.idbutton).click(function() {
		    	jQuery(thiso.id).fadeOut(1000);
		    	thiso.validateCookie();
		    }); 
		    
		    if (this.hide == '1')
		      jQuery(document).click(function() {
		    	  jQuery(thiso.id).fadeOut(1000);
		      });
		    break;
		case 'slide':
		    jQuery(thiso.id).slideDown(1000);
		    jQuery(thiso.idbutton).click(function() {
		    	jQuery(thiso.id).slideUp(1000);
		    	thiso.validateCookie();
		    }); 
		    
		    if (this.hide == '1')
		      jQuery(document).click(function() {
		    	  jQuery(thiso.id).slideUp(1000);
		      });
		    break;
		}
	}
	
	this.validateCookie = function() {
		jQuery('#counter-ecl-cookie-message-js').each(function() {
			jQuery(this).data('cookieecl', new ecl_cookie(this));
			jQuery(this).data('cookieecl').writeCookie();	
		});
	}
}

function ecl_cont_analog(layer) {

	this.layer = layer;
    this.size = 'h3';
    this.id = 'widget-ecl';
    this.classcolor = '';
    this.classalign = 'text-right';
    this.cont = '0';
    this.contold = '0';
    this.type = 'text';
    this.textspan = '';
    this.display3d = '0';
    this.separatenumbers = '0';
    this.effects = 'none';
    this.tempo = 250;
    this.topfix = '0px';
    
    this.changeDigit = [];
    
    this._3d = [
               		"ecl-layer-3-highlight-1 ecl-layer-3-highlight",
               		"ecl-layer-3-highlight-2 ecl-layer-3-highlight",
               		"ecl-layer-3-highlight-3 ecl-layer-3-highlight",
               		"ecl-layer-3-highlight-4 ecl-layer-3-sidehighlight",
               		"ecl-layer-3-highlight-5 ecl-layer-3-sidelowlight",
               		"ecl-layer-3-highlight-6 ecl-layer-3-lowlight",
               		"ecl-layer-3-highlight-7 ecl-layer-3-lowlight",
               		"ecl-layer-3-highlight-8 ecl-layer-3-lowlight"
               	];
    
    
    var param = jQuery(this.layer).data();
    for (var key in param) {
    	this[key] = param[key];
    } 
    
    this.tempo = parseInt(this.tempo);
    
    this.start = function () {
    	
        this.drawLayers(this.layer);
        
        if (this.cont != this.contold && this.effects != 'none') {
        	this.animation();
	    }
        
    }

	this.drawLayers = function (layer) {
		var layer_0 = layer;
		var layer_root = document.getElementsByTagName('BODY');
		var layer_1 = document.createElement('div');
		var layer_hidden = document.createElement('div');
				
		layer_1.className = 'ecl-layer-1 ' + this.classalign;
		layer_1.setAttribute('id', this.id + '-s');
		
		if (this.id.indexOf('shortcode') == -1) {
		  layer_root[0].appendChild(layer_hidden);
		}
		else {
		  layer_0.appendChild(layer_hidden);
		}
		
		layer_0.appendChild(layer_1);
		
		this.drawcont(layer_1, layer_hidden);
		
		//clean hidden
		if (this.id.indexOf('shortcode') == -1) {
		  layer_root[0].removeChild(layer_hidden);
		} else {
		  layer_0.appendChild(layer_hidden);	
		}
	
	}
	
	this.drawcont = function (layer, layerhidden) {
		var layer_1 = layer;
		var cont = String(this.cont);
		var contold = String(this.contold);
		var layer_2, layer_digit_0, layer_digit_1, layer_hidden_0, layer_hidden_1;
		var cont_long, contold_long;
		var maxHeightDigit = 0, maxWidthDigit = 0;
		var newcontold = '', newcont = '';
		var addHeight = 0, addWidth = 0;
		var addHeight3d = 0, addWidth3d = 0;
		var fixTop = String(parseInt(this.topfix)) + 'px';
				
		cont_long = cont.length;
		contold_long = contold.length;
		
		if (cont_long > contold_long) {
			contold = this.repeatStr(' ', cont_long - contold_long) + contold;
		}
		
		if (this.separatenumbers == '0') {
			cont += ' ';
			contold += ' ';
			for (var d = 0; d < cont_long + 1; d++) {
			  newcontold += this.charEquiv(contold, d);	
			  newcont += this.charEquiv(cont, d);
			}
			contold = newcontold;
			cont = newcont;
			cont_long = contold_long = 1;
		}
		
		if (cont != contold && this.effects == 'none') {
			contold = cont;
		}
		
		for (var x = 0; x < cont_long; x++) {
			layer_2 = document.createElement('div');
			layer_2.className = 'ecl-layer-2';
		    
			layer_digit_0 = document.createElement(this.size);
			layer_digit_0.className = 'ecl-layer-digit';
			layer_digit_0.setAttribute('id', this.id + '-digito-' + String(x) + '-0');
			
			
			layer_digit_1 = document.createElement(this.size);
			layer_digit_1.className = 'ecl-layer-digit';
			layer_digit_1.setAttribute('id', this.id + '-digito-' + String(x) + '-1');
			
			// hidden
			layer_hidden_0 = document.createElement(this.size);
			layer_hidden_0.className = 'ecl-layer-hidden-text';
			
			
			layer_hidden_1 = document.createElement(this.size);
			layer_hidden_1.className = 'ecl-layer-hidden-text';
						
			switch (this.type) {
			   case 'text':
				   layer_digit_0.className += ' ' + this.classcolor;
				   layer_digit_0.style.backgroundColor = 'inherit';
				   if (this.separatenumbers == '0') {
					 layer_digit_0.innerHTML = contold; 
					 addWidth = 10;
				   } else {
				     layer_digit_0.innerHTML = this.charEquiv(contold, x);
				     addWidth = 1;
				   }
				   
				   if (this.display3d == '1') {
			         addHeight3d = 1;
			         if (this.separatenumbers == '0') {
				    	 addWidth3d = 3;
				     } else {
				    	 addWidth3d = 2; 
				     }
			       }
				   
				   layer_digit_1.className += ' ' + this.classcolor;
				   layer_digit_1.style.backgroundColor = 'inherit';
				   if (this.separatenumbers == '0') {
					 layer_digit_1.innerHTML = cont;
				   } else {
				     layer_digit_1.innerHTML = this.charEquiv(cont, x);
				   }
				   
				   break;
			   case 'analog':
				   layer_digit_0.className += ' ' + this.classcolor;
				   layer_digit_0.style.backgroundColor = 'black';
				   if (this.separatenumbers == '0') {
				     layer_digit_0.innerHTML = contold; 
				     addWidth = 10;
				   } else {
				     layer_digit_0.innerHTML = this.charEquiv(contold, x);
				     addWidth = 1;
				   }
				   
				   if (this.display3d == '1') {
				     addHeight3d = 1;
				     if (this.separatenumbers == '0') {
				    	 addWidth3d = 3;
				     } else {
				    	 addWidth3d = 2; 
				     }
				    	 
				   }
				   				   				   
				   layer_digit_1.className += ' ' + this.classcolor;
				   layer_digit_1.style.backgroundColor = 'black';
				   if (this.separatenumbers == '0') {
				     layer_digit_1.innerHTML = cont;  
				   } else {
					 layer_digit_1.innerHTML = this.charEquiv(cont, x);
				   } 
				   
				   break;
			   case 'label':
				   layer_digit_0.style.backgroundColor = 'inherit';
				   if (this.separatenumbers == '0') {
					 layer_digit_0.innerHTML = '<span class="label ' + this.textspan + '">' + contold + '</span>';  
					 addWidth = 10;
					 addHeight = 5;
				   } else {
				     layer_digit_0.innerHTML = '<span class="label ' + this.textspan + '">' + this.charEquiv(contold, x) + '</span>';
				     addWidth = 3;
				     addHeight = 5;
				   }
				   
				   layer_digit_1.style.backgroundColor = 'inherit';
				   if (this.separatenumbers == '0') {
					 layer_digit_1.innerHTML = '<span class="label ' + this.textspan + '">' + cont + '</span>';  
				   } else {
				     layer_digit_1.innerHTML = '<span class="label ' + this.textspan + '">' + this.charEquiv(cont, x) + '</span>';
				   }
				   
				   break;
			   case 'badge':
				   layer_digit_0.style.backgroundColor = 'inherit';
				   if (this.separatenumbers == '0') {
					 layer_digit_0.innerHTML = '<span class="badge">' + contold + '</span>';  
					 addWidth = 11;
					 addHeight = 1;
				   } else {
				     layer_digit_0.innerHTML = '<span class="badge">' + this.charEquiv(contold, x) + '</span>';
				     addWidth = 3;
				     addHeight = 1;
				   }
				   
				   if (this.display3d == '1') {
				         addHeight3d = 1;
				         if (this.separatenumbers == '1')
				           addWidth3d = 1;
				   }
				   
				   layer_digit_1.style.backgroundColor = 'inherit';
				   if (this.separatenumbers == '0') {
					 layer_digit_1.innerHTML = '<span class="badge">' + cont + '</span>';  
				   } else {
				     layer_digit_1.innerHTML = '<span class="badge">' + this.charEquiv(cont, x) + '</span>';
				   }
				   
				   break;
			}
			
			
			
			layer_hidden_1.innerHTML = layer_digit_1.innerHTML;
			layerhidden.appendChild(layer_hidden_1);
			
			layer_hidden_0.innerHTML = layer_digit_0.innerHTML;
			layerhidden.appendChild(layer_hidden_0);
		
			// width and height
			if (layer_hidden_0.offsetWidth > layer_hidden_1.offsetWidth) {
				maxWidthDigit = layer_hidden_0.offsetWidth + addWidth + addWidth3d; 
			} else {
				maxWidthDigit = layer_hidden_1.offsetWidth + addWidth + addWidth3d; 
			}
			
			if (layer_hidden_0.offsetHeight > layer_hidden_1.offsetHeight) {
				maxHeightDigit = layer_hidden_0.offsetHeight + addHeight + addHeight3d; 
			} else {
				maxHeightDigit = layer_hidden_1.offsetHeight + addHeight + addHeight3d; 
			}
			
			
			layer_2.style.width = String(maxWidthDigit) + 'px';
			layer_2.style.height = String(maxHeightDigit)  + 'px';
			
			layer_digit_0.style.width = String(maxWidthDigit)  + 'px';
			layer_digit_0.style.height = String(maxHeightDigit)  + 'px';
			layer_digit_0.style.top = fixTop;
			
			layer_digit_1.style.width = String(maxWidthDigit)   + 'px';
			layer_digit_1.style.height = String(maxHeightDigit)  + 'px';
			layer_digit_1.style.top = String(maxHeightDigit + parseInt(fixTop)) + 'px';
			
			
			
			
			layer_2.appendChild(layer_digit_1);
			layer_2.appendChild(layer_digit_0);
			layer_1.appendChild(layer_2);
			
			if (layer_digit_0.innerHTML != layer_digit_1.innerHTML && this.effects != 'none') {
				this.changeDigit[this.changeDigit.length] = {layer0: layer_digit_0,
				                                             layer1: layer_digit_1,
				                                             top: parseInt(fixTop),
				                                             height: maxHeightDigit,
				                                             desp: 0,
				                                             timer: 0};
			} 
			
			// clean hidden
			layerhidden.removeChild(layer_hidden_0);
			layerhidden.removeChild(layer_hidden_1);
			
			// 3D Effects
			if (this.display3d == '1') {
				this.effects3D(layer_2);  
			}
			
		}
		
	}
	
	this.animation = function() {
	    
	   
		switch (this.effects) {
	     case 'mov': 
	    	 this.move(this.changeDigit.length - 1);
	    	 break;
	     case 'hide':
	    	 this.hide();
	    	 break;
	     case 'fade':
	    	 this.fade();
	    	 break;
	     case 'slide':
	    	 this.slide();
	    	 break;
	   }
	
	}
	
	this.hide = function () {
		var thiso = this;
		jQuery('#' + thiso.id + '-s').delay(thiso.tempo * 2).hide(thiso.tempo, "linear", function() { thiso.newCounter(); }).delay(thiso.tempo * 2).show(thiso.tempo, "linear");
	}
	
	this.fade = function () {
		var thiso = this;
		jQuery('#' + thiso.id + '-s').delay(thiso.tempo * 2).fadeOut(thiso.tempo, "linear", function() { thiso.newCounter(); }).delay(thiso.tempo * 2).fadeIn(thiso.tempo, "linear");
	}
	
	this.slide = function () {
		var thiso = this;
		jQuery('#' + thiso.id + '-s').delay(thiso.tempo * 2).slideUp(thiso.tempo, "linear", function() { thiso.newCounter(); }).delay(thiso.tempo * 2).slideDown(thiso.tempo, "linear");
	}
	
	this.newCounter = function() {
	  for (var x = this.changeDigit.length - 1; x > - 1; x--) {
		  this.showDigit1(x);
	  }	
	
	}
	
	this.showDigit1 = function(digit) {
		var digito0 = this.changeDigit[digit].layer0;
		var digito1 = this.changeDigit[digit].layer1; 
		
		
		digito0.style.top = String((this.changeDigit[digit].height * -1) + this.changeDigit[digit].top)  + 'px';
		digito1.style.top = String(this.changeDigit[digit].top)  + 'px';
		
		this.changeDigit[digit].desp = this.changeDigit[digit].height;
	
	}
	
	this.move = function(digit) {
		var thiso = this;
		 
		this.changeDigit[digit].timer = window.setInterval(function() { thiso.reloj(digit); } , thiso.tempo);
	
	}
	
	this.reloj = function (digit) {
		
		var digito0 = this.changeDigit[digit].layer0;
		var digito1 = this.changeDigit[digit].layer1; 
		
		
		digito0.style.top = String(this.changeDigit[digit].top - this.changeDigit[digit].desp)  + 'px';
		digito1.style.top = String(this.changeDigit[digit].top + this.changeDigit[digit].height - this.changeDigit[digit].desp) + 'px';
		
		this.changeDigit[digit].desp += 2;
		
		if (digit > 0 && this.changeDigit.length > 1) {
			var digito0c = this.changeDigit[digit - 1].layer0;
			var digito1c = this.changeDigit[digit - 1].layer1; 
			
			digito0c.style.top = String(this.changeDigit[digit - 1].top - this.changeDigit[digit - 1].desp)  + 'px';
			digito1c.style.top = String(this.changeDigit[digit - 1].top + this.changeDigit[digit - 1].height - this.changeDigit[digit - 1].desp) + 'px';
			
			this.changeDigit[digit - 1].desp += 1;	
		}
		
		if (this.changeDigit[digit].desp > this.changeDigit[digit].height) {
			
			window.clearInterval(this.changeDigit[digit].timer);
			this.showDigit1(digit);
			digit--;
			if (digit > -1) {
				this.move(digit);
			}
		}
	}
	
	this.repeatStr = function(cad, num) {
		var nueva = '';
		for (var x = 0; x < num; x++)
			nueva += cad;
		return nueva;
	}
	
	this.charEquiv = function(cad, num) {
		switch (cad.charAt(num)) {
		  case ' ':
			return '&nbsp;';
		  case ',':
			return ',';
		  default:
			return cad.charAt(num);  
		}
		
	}
	
	this.effects3D = function(layer) {
		var layer_3d;
		
		for (var y = 0; y < this._3d.length; y++) {
	    	layer_3d = document.createElement('div');
	    	layer_3d.className = this._3d[y];
	    	layer_3d.innerHTML = "<p></p>";
	    	
	    	layer.appendChild(layer_3d);
	      }	
	}
	
}
