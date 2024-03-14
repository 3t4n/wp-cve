# slidesbg.js 
slidesbg.js is a simple, configurable and multi-purpose jQuery plugin used for generating a background slideshow from an array of images you specify.

## Features
- Fully responsive and highly customizable
- Auto rotation at a given interval
- Keyboard interaction
- Fullscreen mode

## How to use
###1. Started
Use jQuery 1.7+ and include the `slidesbg.min.js`
```html
<script src="jquery-1.7.1.min.js"></script>
<script src="slidesbg.min.js"></script>
<link rel="stylesheet" src="slidesbg.css">
```

###2. HTML
Just give `id/class` in your HTML element.
```html
<header id="myheader">
</header>
```

###3. Call the Plugin
Put the following code and slider is ready!
```javascript
var slides = [
  "image1.jpg",
  "image2.jpg "
];

$("#myheader").slidesbg({
  dataSlide: slides
});
```

##Demo
No Demo! Just try it yourself.

##License
The MIT License (MIT)
