var main_fab = document.getElementById('main-fab');
var imageContact = document.getElementById('imageContact');
var innerFabs = document.getElementsByClassName('inner-fabs')[0];

// Open the FAM
main_fab.addEventListener('click', function () {
  innerFabs.classList.toggle('show');
});

imageContact.addEventListener('click', function () {
  innerFabs.classList.toggle('show');
});


// Close the FAM
document.addEventListener('click', function (e) {
  switch (e.target.id) {
    case "main-fab":
    case "activity-fab":
    case "challenges-fab":
    case "chat-fab":
    case "fab-add-icon":
      break;
      
    default:
      innerFabs.classList.remove('show');
      break;
  }
});

var chat_close_btn = document.getElementById('chat-close-button');


