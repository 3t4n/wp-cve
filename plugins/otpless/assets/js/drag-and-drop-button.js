var button = document.getElementById('drag-and-drop-button');
var isDragging = false;
var currentX;
var currentY;
var initialX;
var initialY;
var xOffset = 0;
var yOffset = 0;

button?.addEventListener("mousedown", dragStart);
button?.addEventListener("mouseup", dragEnd);
button?.addEventListener("mousemove", drag);

function dragStart(e) {
  initialX = e.clientX - xOffset;
  initialY = e.clientY - yOffset;
  
  if (e.target === button) {
    isDragging = true;
  }
}

function dragEnd(e) {
  initialX = currentX;
  initialY = currentY;
  
  isDragging = false;
}

function drag(e) {
  if (isDragging) {
    e.preventDefault();
    currentX = e.clientX - initialX;
    currentY = e.clientY - initialY;
    
    xOffset = currentX;
    yOffset = currentY;
    
    setTranslate(currentX, currentY, button);
  }
}

function setTranslate(xPos, yPos, el) {
  el.style.transform = "translate3d(" + xPos + "px, " + yPos + "px, 0)";
}

