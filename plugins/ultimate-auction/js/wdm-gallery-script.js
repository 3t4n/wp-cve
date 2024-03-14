document.addEventListener("DOMContentLoaded", function() {

if (sliderJsVars.Totalimages > 0) {

var slides = [];
 for (let i = 0; i <= sliderJsVars.Totalarray; i++) {

 slidesvar = "{ type: '" + sliderJsVars.Auctionimages[i]['type'] + "', src: '" + sliderJsVars.Auctionimages[i]['src'] + "' }";
 slidesvar = { type:  sliderJsVars.Auctionimages[i]['type'] , src:  sliderJsVars.Auctionimages[i]['src'] , href: sliderJsVars.Auctionimages[i]['href'] };
 slides.push(slidesvar);
 
 }

  
  // Add this line inside the "DOMContentLoaded" event listener
const closeIcon = document.querySelector(".close-btn");

closeIcon.addEventListener("click", () => {
  closePopup();
});


// Add these lines inside the "DOMContentLoaded" event listener
const prevButton = document.querySelector(".slider .prev-btn");
const nextButton = document.querySelector(".slider .next-btn");

prevButton.addEventListener("click", () => {
  changeSlide(-1); // Change to previous slide
});

nextButton.addEventListener("click", () => {
  changeSlide(1); // Change to next slide
});
  
  
// popup next prev btn
const prevButtonPopup = document.querySelector("#popup .prev-btn");
const nextButtonPopup = document.querySelector("#popup .next-btn");

prevButtonPopup.addEventListener("click", () => {
  changePopupSlide(-1); // Change to previous slide
});

nextButtonPopup.addEventListener("click", () => {
  changePopupSlide(1); // Change to next slide
});
  
  
  
/*
const slides = [
{ type: 'image', src: 'http://localhost/auctiondemo/wp-content/uploads/2023/08/pexels-anthony-📷📹🙂-132474.jpg' },
{ type: 'image', src: 'http://localhost/auctiondemo/wp-content/uploads/2023/08/pexels-pixabay-158063.jpg' },
{ type: 'image', src: 'http://localhost/auctiondemo/wp-content/uploads/2023/08/pexels-stefan-stefancik-96920.jpg' },
{ type: 'image', src: 'http://localhost/auctiondemo/wp-content/uploads/2023/08/pexels-tabitha-mort-432360.jpg' }
];
*/


let currentSlideIndex = 0;
let popupSlideIndex = 0;

const currentSlideContainer = document.getElementById("currentSlideContainer");

const popupContent = document.getElementById("popupContent");
const popupElement = document.getElementById("popup");

function showSlide(index) {


  currentSlideIndex = index;
  //alert(currentSlideIndex);
  const slide = slides[index];

  if (slide.type === 'image') {

    currentSlideContainer.innerHTML = `<img src="${slide.src}" alt="Image">`;
  } else if (slide.type === 'youtube') {

    currentSlideContainer.innerHTML = `<a href="${slide.href}"><img src="${slide.src}" alt="Image" ></a>`;

  } else if (slide.type === 'video') {

    currentSlideContainer.innerHTML = `<video src="${slide.src}" controls></video>`;
  } 
}

// Handle click event on the current slide
// document.querySelector(".thumbnails video").addEventListener("click", (e) => {
//   console.log(e)
// });



document.querySelectorAll('.thumbnails video').forEach(videoElement => {
  videoElement.controls = false;
});

// Handle click events on thumbnails
document.querySelectorAll(".thumbnail").forEach((thumbnail, index) => {
  thumbnail.addEventListener("click", () => {
    showSlide(index);
  });
});

function changeSlide(delta) {
  let newIndex = currentSlideIndex + delta;
  if (newIndex < 0) {
    newIndex = slides.length - 1;
  } else if (newIndex >= slides.length) {
    newIndex = 0;
  }
  showSlide(newIndex);
}

function openPopup(index) {
  popupSlideIndex = index;
  const slide = slides[index];
  if (slide.type === 'image') {
    popupContent.innerHTML = `<img src="${slide.src}" alt="Image">`;
  } else if (slide.type === 'youtube') {
    popupContent.innerHTML = `<a href="${slide.href}"><img src="${slide.src}" alt="Image"></a>`;
  } else if (slide.type === 'video') {
    popupContent.innerHTML = `<video src="${slide.src}" controls></video>`;
  }
  popupElement.style.display = "block";
}

function closePopup() {
  popupElement.style.display = "none";
}

function changePopupSlide(delta) {
  let newIndex = popupSlideIndex + delta;
  if (newIndex < 0) {
    newIndex = slides.length - 1;
  } else if (newIndex >= slides.length) {
    newIndex = 0;
  }
  popupSlideIndex = newIndex;
  const slide = slides[newIndex];
  if (slide.type === 'image') {
    popupContent.innerHTML = `<img src="${slide.src}" alt="Image">`;
  } else if (slide.type === 'youtube') {
    popupContent.innerHTML = `<a href="${slide.href}"><img src="${slide.src}" alt="Image"></a>`;
  } else if (slide.type === 'video') {
    popupContent.innerHTML = `<video src="${slide.src}" controls></video>`;
  }
}

// Show the first slide on page load
showSlide(0);

// Handle click events on thumbnails
const thumbnails = document.querySelectorAll(".thumbnail");

thumbnails.forEach((thumbnail, index) => {
  thumbnail.addEventListener("click", () => {
    showSlide(index);
  });
});

// Handle click event on the current slide
currentSlideContainer.addEventListener("click", () => {
  openPopup(currentSlideIndex);
});

}

});