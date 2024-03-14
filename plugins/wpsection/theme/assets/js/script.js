


/*
document.addEventListener('DOMContentLoaded', function () {
            var modal = document.getElementById('custom-modal');
            var closeModalBtn = document.getElementById('closeModalBtn');

            // Show the modal after 2 seconds
            setTimeout(function () {
                modal.style.display = 'block';
            }, 2000);

            // Close the modal when the close button is clicked
            closeModalBtn.addEventListener('click', function () {
                modal.style.display = 'none';
            });

            // Close the modal if the user clicks outside of it
            window.addEventListener('click', function (event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            });
        });

*/
/*
  jQuery(document).ready(function($)
  {
    document.addEventListener("DOMContentLoaded", function () {
      var modal = document.getElementById("custom-modal");
      var closeModalBtn = document.getElementById("closeModalBtn");

      // Show the modal after 2 seconds
      setTimeout(function () {
        modal.style.display = "block";
      }, 2000);

      // Close the modal when the close button is clicked
      closeModalBtn.addEventListener("click", function () {
        modal.style.display = "none";
      });

      // Close the modal if the user clicks outside of it or after 3000 milliseconds
      var timeoutId = setTimeout(function () {
        modal.style.display = "none";
      }, 3000);

      window.addEventListener("click", function (event) {
        if (event.target == modal) {
          modal.style.display = "none";
          clearTimeout(timeoutId); // Clear the timeout if the modal is closed by clicking outside
        }
      });
    });
  });
  
  */