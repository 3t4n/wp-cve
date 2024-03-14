var BARGRAPH = {};

(function($) {
	var PLUGIN		= 'bargraph';
	
	function Bar(id) {
		this.id						= id;
		this.element 				= $('<div class="'+PLUGIN+'-bar" style="position: absolute;" id="BAR-'+this.id+'"><div style="width: 100%; height: 100%;" class="fill"><a class="bar-link" target="_blank" href="" style="width: 100%; height: 100%; position: absolute; top: 0; left: 0; display: none;"></a></div><div class="image"><img src="" style="width:100%;height:100%;overflow:hidden;" /><a class="bar-link" target="_blank" href="" style="width: 100%; height: 100%; position: absolute; top: 0; left: 0; display: none;"></a></div><div class="number">0</div></div>');
		this.showing				= false;
		this.value					= 0;
		this.display				= '';
		this.maxvalue				= 100;
		this.minvalue				= 0;
		this.orientation			= 'height';
		this.dimensions				= [0,0];
		this.siblings				= 10;
		this.padding				= 0;
		this.position				= 0;
		this.size					= 0;
		this.thickness				= 0;
		this.backgroundColor		= '';
		this.backgroundColorHover	= '';
		this.borderColor			= '';
		this.borderColorHover		= '';
		this.image					= '';
		this.modifier				= .8;
		this.link					= '';
		
		var _this					= this;
		
		this.val		= function() {
			if (typeof arguments[0] == 'undefined') return this.value;
			
			this.value = arguments[0];
			
			return this;
		}
		
		this.setMinMax		= function(min,max) {

			this.minvalue 	= min;
			this.maxvalue	= max;
			
		}
		
		this.calculate		= function() {
			/*
				Make sure the min/max accomodates the value
			*/
			var spread		= this.maxvalue - this.minvalue;
			if (spread == 0)	this.size = 50;
			else 				this.size = ((this.value - this.minvalue) / spread) * 50;
			
			var S			= this.size * this.modifier; // reduce 100% to 70% and reduce 10% to 7% and 0% will stay 0%!
			S				= S + 40;
			
			this.size		= S;
			this.thickness	= 100 / this.siblings;
			
		}
		
		this.setOrientation	= function(orientation) {
			this.orientation = orientation.toLowerCase();
		}
		this.setMobile		= function(b) {
			this.mobile = b;
		}
		this.setImage		= function(img) {
			this.image = img;
		}
		this.update			= function() {
			this.calculate();
			/*
				Apply the width/height for the value
			*/
			this.element.find('.fill').css({
				'width':'100%',
				'height':'100%',
				'border-color':this.borderColor,
				'background-color':this.backgroundColor,
				'border-style':'solid',
				'transition':'background .5s',
				'cursor':'pointer'
			}).hover(function() {
				$(this).css({'backgroundColor':_this.backgroundColorHover,'border-color':_this.borderColorHover});
			},function() {
				$(this).css({'backgroundColor':_this.backgroundColor,'border-color':_this.borderColor});
			});
			
			if (this.image !== '') {
				this.element.find('img').attr('src',this.image).show().css({'top':0,'position':'absolute','left':0});
			} else {
				this.element.find('img').hide();
			}
			if (this.link !== '') {
				this.element.find('.bar-link').attr('href',this.link).show();
			}
			
			if (this.mobile) {
				this.element.find('.number').hide();
			} else {
				this.element.find('.number').show();
			}
			this.element.find('.number').text(this.display);
			
			switch (this.orientation) {
				case 'width':
					this.element.css({
						'left':(this.thickness * this.position)+'%',
						'bottom':'1px',
						'top':'auto',
						'padding':'0 '+this.padding+'px',
						'height':this.size+'%',
						'width':this.thickness+'%'
					});
					
					this.element.find('.fill').css({
						'border-width':this.borderWidth+'px '+this.borderWidth+'px 0 '+this.borderWidth+'px',
						'border-radius':this.borderRadius+'px '+this.borderRadius+'px 0 0',
					});
					this.element.find('.image').css({'left':'0','right':'0','bottom':0,'height':this.dimensions[1]+'px','position':'relative','top':this.padding+'px'});
					
					var number = this.element.find('.number');
					this.element.find('.number').css({'width':'95%','left':'2.5%','text-align':'center','top':'-'+(number.height()+5)+'px','position':'absolute','font-size':15});
				break;
				case 'height':
					this.element.css({
						'top':(this.thickness * this.position)+'%',
						'left':'1px',
						'bottom':'auto',
						'padding':this.padding+'px 0',
						'height':this.thickness+'%',
						'width':this.size+'%'
					});
					
					this.element.find('.fill').css({
						'border-width':this.borderWidth+'px '+this.borderWidth+'px '+this.borderWidth+'px 0',
						'border-radius':'0 '+this.borderRadius+'px '+this.borderRadius+'px 0',
					});
					this.element.find('.image').css({'width':this.dimensions[0]+'px','position':'absolute','top':this.padding+'px','bottom':this.padding+'px','left':'-'+(this.dimensions[0] + this.padding)+'px'});
					
					var number = this.element.find('.number');
					this.element.find('.number').css({'height':'100%','top':0,'right':'-'+(number.width()+5)+'px','position':'absolute'});
					this.element.find('.number').css('lineHeight',this.element.find('.number').height()+'px');
				break;
			}
			
			/*
				Apply the position class
				
				To-Be-Removed
			*/
			var classList = this.element.get(0).className.split(/\s+/), toRemove = [];
			for (var i = 0; i < classList.length; i++) {
				if (classList[i].match(/position-\d+/)) {
					this.element.removeClass(classList[i]);
				}
			}
			this.element.addClass('position-'+this.position);
		}
		
		this.setRadius		= function(radius) {
			this.radius = radius;
		}
		
		this.setDimensions	= function(w,h) {
			this.dimensions = [w,h];
		}
		
		this.setPosition	= function(p) {
			this.position 	= p;
		}
		
		this.destroy		= function() {
			this.element.remove();
		}
		
		this.show			= function() {
			this.element.show();
		}
		
		this.hide			= function() {
			if (this.orientation == 'width') this.element.css({'height':0});
			else this.element.css({'width':0});
			this.element.hide();
		}
		
		this.appendTo		= function(el) {
			
			this.parent = el;
			
			this.element.appendTo(el);
			
		}
	}
	
	// Bargraph Object
	function Bargraph(element,options) {
		this.element	= element;
		this.graph		= $('<div class="bars"></div>');
		this.lines		= $('<div class="lines"></div>');
		this.graph.appendTo(this.element);
		this.lines.appendTo(this.element);
		this.data		= []; 
		this.style		= PLUGIN+'-style-'+((new Date()).getTime() + (Math.random * 30000));
		this.mobile		= false;
		_this			= this;
		
		/*
			Example Data
			this.data[0] = {
				'id'	: 'bank_1',
				'bar'	: new Bar()
			}
		*/
		
		this.settings	= $.extend({
			'bars'			: [],
			'min'			: 0,
			'max'			: 100,
			'quantity'		: 10, // number of bars to display at all times,
			'orientation'	: 'height',
			'padding'		: 5,
			'border-radius'	: 5,
			'order'			: 'asc'
		},options);
		
		this.methods	= {
			'update'		:true,
			'addbar'		:true,
			'removebar'		:true,
			'setbars'		:true,
			'editbar'		:true,
			'setorientation':true
		};
		
		this.getdatakey	= function(id) {
			
			var i;
			
			for (i in this.data) {
				if (this.data[i].id == id) return i;
			}
			
			return false;
			
		}
		
		this.getdata	= function(id) {
			
			var key = this.getdatakey(id);
			
			if (key) return this.data[key];
			
			return false;
			
		}
		
		this.getbar		= function(id) {
			
			var data = this.getdata(id);
			if (data) {
				if (typeof data.bar == 'object') {
					return data.bar;
				}
				
				return false;
			}
			return false;
			
		}

		this.addbar		= function(id,value) {
			var bar = new Bar(id);
			bar.siblings = this.settings.quantity;
			
			this.data.push({'id':id,'value':value,'bar':bar});
			
			this.update();
		}
		
		this.removebar	= function(id) {
			var key = this.getdatakey(id), bar = this.getbar(id);
			
			// Remove from DOM
			bar.remove();
			
			// Remove from array
			var $x = this.data.splice(key,1);
			
			// Was an element actually removed
			if ($x) {
				this.update();
				return true;
			}
			return false;
		}
		
		this.setbar	= function(id,value) {
			bar = this.getbar(id);

			if (bar) {
				bar.val(value);
				this.update();
				return true;
			}
			
			return false;
		}
		
		this.setbars = function(arr) {
			
			/*
				Hide all bars
			*/
			for (i in this.data) {
				this.data[i].hidden = true;
			}
			
			var id, data, bar, value;
			
			for (i in arr) {

				// Do we have the right values
				if ((typeof arr[i].id !== 'undefined') && (typeof arr[i].value !== 'undefined')) {
					
					id		= arr[i].id;
					value	= arr[i].value;
					
					/*
						Does this id exist already?
					*/
					if (key = this.getdatakey(id)) {
						data 	= this.getdata(id);
						
						if (this.getbar(id))	bar 	= this.getbar(id);
						else{
							data.bar = new Bar(id);
							bar = data.bar;
						}
						data.value = value;
						
						this.data[key] = data;
					}
					else { // add new if this id doesn't exist
						data	= {'id':id,'hidden':false,'bar':new Bar(id)}
						bar		= data.bar;
						
						/*
							Set new bar properties
						*/
						this.setProperties(bar);
						
						bar.appendTo(this.graph);
						
						this.data.push(data);
						key		= this.getdatakey(id);
					}
					
					this.data[key].hidden = false;
					
					if (typeof arr[i].image !== 'undefined') bar.setImage(arr[i].image);
					if (typeof arr[i].link !== 'undefined') bar.link = arr[i].link;
					bar.val(value);
					
					bar.display = arr[i].display || Math.floor(value);
					
					
					
				}
				
			}
			this.update();
		}
		
		this.setorientation	= function(orientation) {
			this.settings.orientation = orientation;
			this.update();
		}
		
		this.empty		= function() {
			this.data = [];
		}
		
		this.run		= function(method,arg) {
			
			if (typeof this.methods[method] == 'undefined') return false;
			
			// Run the function
			this[method](arg);
		}
		
		this.update		= function() {
			var ratio = '12:5', asp = ratio.split(':'), thickness, imageheight, imagewidth;
			
			var modifier = ((this.lines.height() - 30) / this.lines.height()).toFixed(2);
			/*
				Set orientation to the bars div
			*/
			this.graph.css({'padding-left':0,'padding-bottom':0});
			switch (this.settings.orientation) {
				case 'width':
					/*
						Set Mobile Mode < 720px
					*/
					if ($(this.element).width() < 720) this.mobile = true;
					else this.mobile = false;
					
					this.graph.css({'bottom':'35px','top':0,'left':0,'right':0});
					this.lines.css({'bottom':'35px','top':0,'left':0,'right':0,'border-bottom':'1px solid grey'});
					thickness = (this.lines.width() / this.settings.quantity) - (this.settings.padding * 2);
					
					imagewidth = Math.floor(thickness);
					imageheight = Math.floor((imagewidth / asp[0]) * asp[1]);
					
					this.graph.css({'bottom':(imageheight + this.settings.padding * 2)+'px'});
					this.lines.css({'bottom':(imageheight + this.settings.padding * 2)+'px'});
				break;
				case 'height':
				
					/*
						Set Mobile Mode < 400px
					*/
					if ($(this.element).height() < 400) this.mobile = true;
					else this.mobile = false;
					
					
					this.graph.css({'left':'35px','top':0,'bottom':'40px','right':0});
					this.lines.css({'bottom':'45px','top':0,'left':'35px','right':0,'border-left':'1px solid grey'});
					thickness = (this.lines.height() / this.settings.quantity) - (this.settings.padding * 2);
					
					imageheight = Math.floor(thickness);
					imagewidth = Math.floor((imageheight / asp[1]) * asp[0]);
					
					this.graph.css({'left':(imagewidth + this.settings.padding * 2)+'px'});
					this.lines.css({'left':(imagewidth + this.settings.padding * 2)+'px'});
				break;
			}
			
			/*
				Start off by adjusting all of the bars' min/max values to be even
			*/
			var min = -1, max = -1, val;
			
			// set the orientation
			// set the image dimensions
			// set mobile mode
			for (i in this.data) {
				
				// set the orientation
				this.data[i].bar.setOrientation(this.settings.orientation);
				
				// set image dimensions
				this.data[i].bar.setDimensions(imagewidth,imageheight);
				
				// set mobile mode
				this.data[i].bar.setMobile(this.mobile);
				
				// set the modifier
				this.data[i].bar.modifier = modifier;
				
			}
			
			/*
				Now sort the bars
			*/
			// sort the main array by value
			var order = this.settings.order;
			
			this.data.sort(function(a,b) {
				if (order == 'asc') return a.bar.val() - b.bar.val();
				else {
					return b.bar.val() - a.bar.val();
				}
			});
			
			// now apply proper positions to the bars
			var pos = 0;
			for (i in this.data) {
				if (this.data[i].hidden) {
					this.data[i].bar.hide();
				} else {
					this.data[i].bar.setPosition(pos);
					pos++;
					// Hide the bars that do not fall within the quantity of bars desired
					if (i > (this.settings.quantity - 1)) this.data[i].bar.hide();
					else {
						this.data[i].bar.show();
						
						val = this.data[i].bar.val();
					
						if (min == max && min == -1) {
							min = val;
							max = val;
						}
						
						if (val < min) min = val;
						if (val > max) max = val;
						
					}
				}
			}
			
			if (min == max && max == 0) max = 100;

			// Loop again to set the min/max
			for (i in this.data) {
				this.data[i].bar.setMinMax(min,max);
			}
			
			/*
				Play the update animations
			*/
			this.play();
		}
		
		this.play		= function() {
			for (i in this.data) {
				if (this.data[i].hidden == false) {
					this.data[i].bar.update();
				}
			}
		}
		
		this.setProperties	= function(bar) {
			bar.borderWidth			= this.settings['border-width'];
			bar.borderRadius		= this.settings['border-radius'];
			bar.borderColor			= this.settings['bar-border'];
			bar.borderColorHover	= this.settings['bar-border-hover'];
			
			bar.backgroundColor		= this.settings['bar-background'];
			bar.backgroundColorHover= this.settings['bar-background-hover'];
			bar.padding				= this.settings['padding'];
			bar.siblings			= this.settings.quantity;
		}
		
		/*
			Resize function
		*/
		this.resize		= function() {
			this.update();
		}
		
		/*
			Construct the bargraph
		*/
		this.setbars(this.settings.bars);
		this.setorientation(this.settings.orientation);
		
		// Set the resize handler
		$(window).resize(function() {
			_this.resize();
		});
	}
	
	$.fn.bargraph = function(options) {

		var args = arguments;
		
		// Loop through matched jquery elements
		return this.each(function() {
			var $this = $(this),
				data  = $this.data('plugin_' + PLUGIN);
			
			// Create new instance of Bargraph
			if (!data) {
				BARGRAPH = new Bargraph(this,options);
				$this.data('plugin_' + PLUGIN, BARGRAPH);
			}
			
			// Reference the bargraph plugin's public methods
			if (typeof options == 'string') {
				
				// Normalize the value of the string
				options = options.toLowerCase();
				
				// Add functionality for an argument to be passed
				var arg = false;
				if (typeof args[1] != 'undefined') arg = args[1];
				
				data.run(options,arg);
				
			}
		});
		
	}

}(jQuery));