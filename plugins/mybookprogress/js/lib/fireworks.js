(function () {
	var requestAnimationFrame = ( function() {
		return window.requestAnimationFrame ||
			window.webkitRequestAnimationFrame ||
			window.mozRequestAnimationFrame ||
			function( callback ) {
				window.setTimeout( callback, 1000 / 60 );
			};
	})();

	// get a random number within a range
	function random( min, max ) {
		return Math.random() * ( max - min ) + min;
	}

	// calculate the distance between two points
	function calculateDistance( p1x, p1y, p2x, p2y ) {
		var xDistance = p1x - p2x,
			yDistance = p1y - p2y;
		return Math.sqrt( Math.pow( xDistance, 2 ) + Math.pow( yDistance, 2 ) );
	}

	// create firework
	function Firework( parent, sx, sy, tx, ty ) {
		// actual coordinates
		this.x = sx;
		this.y = sy;
		// starting coordinates
		this.sx = sx;
		this.sy = sy;
		// target coordinates
		this.tx = tx;
		this.ty = ty;
		// parent values
		this.parent = parent;
		this.hue = parent.hue;
		// distance from starting point to target
		this.distanceToTarget = calculateDistance( sx, sy, tx, ty );
		this.distanceTraveled = 0;
		// track the past coordinates of each firework to create a trail effect, increase the coordinate count to create more prominent trails
		this.coordinates = [];
		this.coordinateCount = 3;
		// populate initial coordinate collection with the current coordinates
		while( this.coordinateCount-- ) {
			this.coordinates.push( [ this.x, this.y ] );
		}
		this.angle = Math.atan2( ty - sy, tx - sx );
		this.speed = 2;
		this.acceleration = 1.05;
		this.brightness = random( 50, 70 );
		// circle target indicator radius
		this.targetRadius = 1;
	}

	// update firework
	Firework.prototype.update = function( index ) {
		// remove last item in coordinates array
		this.coordinates.pop();
		// add current coordinates to the start of the array
		this.coordinates.unshift( [ this.x, this.y ] );

		// cycle the circle target indicator radius
		if( this.targetRadius < 8 ) {
			this.targetRadius += 0.3;
		} else {
			this.targetRadius = 1;
		}

		// speed up the firework
		this.speed *= this.acceleration;

		// get the current velocities based on angle and speed
		var vx = Math.cos( this.angle ) * this.speed,
				vy = Math.sin( this.angle ) * this.speed;
		// how far will the firework have traveled with velocities applied?
		this.distanceTraveled = calculateDistance( this.sx, this.sy, this.x + vx, this.y + vy );

		// if the distance traveled, including velocities, is greater than the initial distance to the target, then the target has been reached
		if( this.distanceTraveled >= this.distanceToTarget ) {
			for(var i = 30; i >= 0; i--) {
				this.parent.particles.push(new Particle(this, this.tx, this.ty));
			}
			// remove the firework, use the index passed into the update function to determine which to remove
			return false;
		} else {
			// target not reached, keep traveling
			this.x += vx;
			this.y += vy;
		}

		return true;
	}

	// draw firework
	Firework.prototype.draw = function(ctx) {
		ctx.beginPath();
		// move to the last tracked coordinate in the set, then draw a line to the current x and y
		ctx.moveTo( this.coordinates[ this.coordinates.length - 1][ 0 ], this.coordinates[ this.coordinates.length - 1][ 1 ] );
		ctx.lineTo( this.x, this.y );
		ctx.strokeStyle = 'hsl(' + this.hue + ', 100%, ' + this.brightness + '%)';
		ctx.stroke();
	}

	// create particle
	function Particle( parent, x, y ) {
		this.x = x;
		this.y = y;
		// track the past coordinates of each particle to create a trail effect, increase the coordinate count to create more prominent trails
		this.coordinates = [];
		this.coordinateCount = 5;
		while( this.coordinateCount-- ) {
			this.coordinates.push( [ this.x, this.y ] );
		}
		// set a random angle in all possible directions, in radians
		this.angle = random( 0, Math.PI * 2 );
		this.speed = random( 1, 10 );
		// friction will slow the particle down
		this.friction = 0.95;
		// gravity will be applied and pull the particle down
		this.gravity = 1;
		// set the hue to a random number +-20 of the overall hue variable
		this.hue = random( parent.hue - 20, parent.hue + 20 );
		this.brightness = random( 50, 80 );
		this.alpha = 1;
		// set how fast the particle fades out
		this.decay = random( 0.015, 0.03 );
	}

	// update particle
	Particle.prototype.update = function( index ) {
		// remove last item in coordinates array
		this.coordinates.pop();
		// add current coordinates to the start of the array
		this.coordinates.unshift( [ this.x, this.y ] );
		// slow down the particle
		this.speed *= this.friction;
		// apply velocity
		this.x += Math.cos(this.angle) * this.speed;
		this.y += Math.sin(this.angle) * this.speed + this.gravity;
		// fade out the particle
		this.alpha -= this.decay;

		return this.alpha > this.decay;
	}

	// draw particle
	Particle.prototype.draw = function(ctx) {
		ctx.beginPath();
		// move to the last tracked coordinates in the set, then draw a line to the current x and y
		ctx.moveTo(this.coordinates[this.coordinates.length - 1][0], this.coordinates[ this.coordinates.length - 1][1]);
		ctx.lineTo(this.x, this.y);
		ctx.strokeStyle = 'hsla(' + this.hue + ', 100%, ' + this.brightness + '%, ' + this.alpha + ')';
		ctx.stroke();
	}

	function Fireworks(options) {
		this.element = options.element;
		this.ctx = options.element[0].getContext('2d');

		this.fireworks = [];
		this.particles = [];
		this.hue = 120;

		// this will time the auto launches of fireworks, one launch per 80 loop ticks
		this.launch_rate = (options.launch_rate && options.launch_rate.min && options.launch_rate.max) ? options.launch_rate : {min: options.launch_rate || 1, max: options.launch_rate || 1};
		this.timerTotal = 0;
		this.timerTick = 0;

		this.stopped = false;
		this.destroyed = false;
		this.loop = this.loop.bind(this);
		this.loop();
	}

	Fireworks.prototype.loop = function() {
		if(!this.destroyed) {
			requestAnimationFrame(this.loop);
		} else {
			return this.ctx.clearRect(0, 0, this.element.width(), this.element.height());
		}

		if(this.element[0].width != this.element.width() || this.element[0].height != this.element.height()) {
			this.element[0].width = this.element.width();
			this.element[0].height = this.element.height();
		}

		var cw = this.element.width();
		var ch = this.element.height();

		// increase the hue to get different colored fireworks over time
		this.hue += 0.5;

		// normally, clearRect() would be used to clear the canvas
		// we want to create a trailing effect though
		// setting the composite operation to destination-out will allow us to clear the canvas at a specific opacity, rather than wiping it entirely
		this.ctx.globalCompositeOperation = 'destination-out';
		// decrease the alpha property to create more prominent trails
		this.ctx.fillStyle = 'rgba(0, 0, 0, 0.5)';
		this.ctx.fillRect(0, 0, cw, ch);
		// change the composite operation back to our main mode
		// lighter creates bright highlight points as the fireworks and particles overlap each other
		this.ctx.globalCompositeOperation = 'lighter';

		// loop over each firework, draw it, update it
		for(var i = this.fireworks.length - 1; i >= 0; i--) {
			this.fireworks[i].draw(this.ctx);
			if(!this.fireworks[i].update(i)) {
				this.fireworks.splice(i, 1);
			}
		}

		// loop over each particle, draw it, update it
		for(var i = this.particles.length - 1; i >= 0; i--) {
			this.particles[i].draw(this.ctx);
			if(!this.particles[i].update(i)) {
				this.particles.splice(i, 1);
			}
		}

		if(!this.stopped) {
			// launch fireworks automatically to random coordinates, when the mouse isn't down
			if(this.timerTick >= this.timerTotal) {
				// start the firework at the bottom middle of the screen, then set the random target coordinates, the random y coordinates will be set within the range of the top half of the screen
				this.fireworks.push(new Firework(this, random(0, cw), random(ch, ch*1.5), random(0, cw), random(0, ch)));
				this.timerTick = 0;
				this.timerTotal = random(60/this.launch_rate.max, 60/this.launch_rate.min);
			} else {
				this.timerTick++;
			}
		}
	}

	Fireworks.prototype.destroy = function() {
		this.destroyed = true;
	}

	Fireworks.prototype.stop = function() {
		this.stopped = true;
	}

	jQuery.fn.fireworks = function(options) {
		return this.each(function(i, el) {
			el = jQuery(el);
			if(options === 'destroy') {
				if(el.data('mbp-fireworks')) {
					el.data('mbp-fireworks').destroy();
					el.removeData('mbp-fireworks');
				}
			} else if(options === 'stop') {
				if(el.data('mbp-fireworks')) {
					el.data('mbp-fireworks').stop();
				}
			} else {
				el.data('mbp-fireworks', new Fireworks(jQuery.extend({}, options, {element: el})));
			}
		});
	}
})();