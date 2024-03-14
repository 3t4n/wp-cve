var config = {
  "particles": {
	"number": {
	  "value": 80 * rnPbwpData.particle_density,
	  "density": {
		"enable": true,
		"value_area": 800
	  }
	},
	"color": {
	  "value": rnPbwpData.dot_color
	},
	"shape": {
	  "type": "circle",
	  "stroke": {
		"width": 0,
		"color": "#000000"
	  },
	  "polygon": {
		"nb_sides": 5
	  },
	},
	"opacity": {
	  "value": 0.5,
	  "random": false,
	  "anim": {
		"enable": false,
		"speed": 1,
		"opacity_min": 0.1,
		"sync": false
	  }
	},
	"size": {
	  "value": 5,
	  "random": true,
	  "anim": {
		"enable": false,
		"speed": 40,
		"size_min": 0.1,
		"sync": false
	  }
	},
	"line_linked": {
	  "enable": true,
	  "distance": 150,
	  "color": rnPbwpData.dot_color,
	  "opacity": 0.4,
	  "width": 1
	},
	"move": {
	  "enable": true,
	  "speed": 6,
	  "direction": "none",
	  "random": false,
	  "straight": false,
	  "out_mode": "out",
	  "attract": {
		"enable": false,
		"rotateX": 600,
		"rotateY": 1200
	  }
	}
  },
  "interactivity": {
	"detect_on": "canvas",
	"events": {
	  "onhover": {
		"enable": true,
		"mode": "repulse"
	  },
	  "onclick": {
		"enable": true,
		"mode": "push"
	  },
	  "resize": true
	},
	"modes": {
	  "grab": {
		"distance": 400,
		"line_linked": {
		  "opacity": 1
		}
	  },
	  "bubble": {
		"distance": 400,
		"size": 40,
		"duration": 2,
		"opacity": 8,
		"speed": 3
	  },
	  "repulse": {
		"distance": 100
	  },
	  "push": {
		"particles_nb": 4
	  },
	  "remove": {
		"particles_nb": 2
	  }
	}
  },
  "retina_detect": true
}

if ( rnPbwpData.custom_json ) {
	config = rnPbwpData.custom_json
}

var css_matches = document.querySelectorAll( rnPbwpData.css_target )

if ( css_matches.length ) {
	document.querySelector('head').innerHTML += " \
		<style type='text/css' name='rn-pbwp-css-target-css'> \
		div.rn-pbwp-div { \
			background-color:"+rnPbwpData.bg_color+"; \
			position: relative; \
		} \
		div.rn-pbwp-text { \
			display: block; \
			width: 100%; \
			text-align: center;\
			color: white; \
			font-weight: bold; \
			font-size: 44px; \
			letter-spacing: 1px; \
			position: absolute; \
			top: 43%; \
			transform: translateY(-43%); \
			padding: 0 8px; \
		} \
	" + rnPbwpData.custom_css + "</style>"

	for ( i = 0; i < css_matches.length; i++ ) {

		var newDiv = document.createElement('div')
		newDiv.id = 'rn-pbwp-div-' + i
		newDiv.className = 'rn-pbwp-div'
		
		if ( rnPbwpData.text ) {
			newDiv.innerHTML += '<div class="rn-pbwp-text">'+rnPbwpData.text+'</div>'
		}
		
		css_matches[i].appendChild( newDiv )
		particlesJS( 'rn-pbwp-div-' + i, config )
		
	}

}