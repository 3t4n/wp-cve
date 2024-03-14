<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
   exit();
}
$WpForms_gs_tools_service = new WPforms_Gsheet_Connector_Init();
?>
<div class="system-statuswc">
   <div class="info-container">
    <h2 class="systemifo">System Info</h2>
      <button onclick="copySystemInfo()" class="copy">Copy System Info to Clipboard</button>
      <?php echo $WpForms_gs_tools_service->get_wpforms_system_info(); ?>
   </div>
</div>
<div class="system-Error">
    <div class="error-container">
        <h2 class="systemerror">Error Log</h2>
        <p>If you have <a href="https://www.gsheetconnector.com/how-to-enable-debugging-in-wordpress" target="_blank">WP_DEBUG_LOG</a> enabled, errors are stored in a log file. Here you can find the last 100 lines in reversed order so that you or the GSheetConnector support team can view it easily. The file cannot be edited here.</p>
        <button onclick="copyErrorLog()" class="copy">Copy Error Log to Clipboard</button>
         <button class="clear-content-logs-wp">Clear</button>
         <span class="clear-loading-sign-logs-wp">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        <div class="clear-content-logs-msg-wp"></div>
        <input type="hidden" name="gs-ajax-nonce" id="gs-ajax-nonce"
                    value="<?php echo wp_create_nonce('gs-ajax-nonce'); ?>" />
        
        <div class="copy-message" style="display: none;">Copied</div> <!-- Add a hidden div for the copy message -->
        <?php echo $WpForms_gs_tools_service->display_error_log(); ?>
    </div>
</div>
<style>
.info-button .dashicons {
    font-size: 21px; /* Adjust the size as needed */
    vertical-align: middle; /* Align the icon vertically with the button text */
    margin-left: 8px; /* Add space between the icon and button text */
}
  
.systemerror{
/*  color: #0073e6;*/
  font-size: 20px;
  margin-left: -2px;
  color: #242628;
  position: relative;
  z-index: 1;
}
/* Style for the "Clear" button */
.clear-content-logs-wp {
  margin: 1rem 0;
  display: inline-flex;
  align-items: center;
  margin: 0.5rem 0 1rem;
  font-size: 14px;
  line-height: 38px;
  height: auto;
  min-height: 30px;
  padding: 0 20px;
  color: #6b7278;
  border: 1px solid #7f868d;
  border-radius: 3px;
  background: #f8f9fa;
  -webkit-box-shadow: none;
  box-shadow: none;
  margin-left: -2px;
}

.clear-content-logs-wp:hover {
  color: #069de3;
  border-color: #069de3;
  background: #f8f9fa;;
}

/* Style for the paragraph text */
.error-container p {
  font-size: 16px; /* Adjust the font size as needed */
  margin: 10px 0; /* Add margin for spacing */
  color: #333; /* Text color */
  line-height: 1.5; /* Line height for readability */
}

/* Style for the link within the paragraph */
.error-container a {
    color: #007BFF; /* Link color (blue) */
    text-decoration: underline; /* Underline the link */
}

.error-container a:hover {
    text-decoration: none; /* Remove underline on hover */
}

/* Style for the "Copied" message */
.copy-message {
    display: none;
    background-color: #4CAF50; /* Green background color */
    color: #fff; /* White text color */
    font-size: 14px;
    padding: 10px 15px;
    border-radius: 5px;
    position: absolute;
    top: 50%; /* Position it vertically centered */
    left: 50%; /* Position it horizontally centered */
    transform: translate(-50%, -50%); /* Center it precisely */
    z-index: 999; /* Ensure it appears above other elements */
    opacity: 0.9; /* Adjust the opacity as needed */
    transition: opacity 0.3s ease;
}

.copy-message.show {
    display: block;
}
.errorlog {
    cursor: default;
    font-family: monospace;
    border: 1px solid #ccc;
    padding: 10px;
    background-color: #32344b;
    width: 100%;
    height: 400px; /* Set a fixed height for the "screen" */  
    border-color: #32344b;
    color: azure;
}
.systemifo{
/*  color: #0073e6;*/
  font-size: 20px;
  margin-left: -2px;
  color: #242628;
  position: relative;
  z-index: 1;
}
.system-Error {
  position: relative;
  background-color: #fff;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  border: 1px solid #b5bfc9;
  margin: 20px auto;
  width: 100%;
  box-sizing: border-box; /* Add box-sizing property */
  overflow: hidden; /* or overflow: auto; depending on your content */
}
.system-statuswc {
  position: relative;
  background-color: #fff;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  border: 1px solid #b5bfc9;
  margin: 20px auto;
  width: 100%;
  box-sizing: border-box; /* Add box-sizing property */
  overflow: hidden; /* or overflow: auto; depending on your content */
}


.info-button {
  background-color: white;
  color: #2c3338;
  padding: 13px 17px;
  cursor: pointer;
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  font-weight: 400;
  flex-grow: 1;
  font-size: 14px;
  margin: 0;
  border: 1px solid #c5c5c5;
  border-radius: 3px;
  background: #f8f9fa;
  box-sizing: border-box; /* Add box-sizing property */
  overflow: hidden; /* or overflow: auto; depending on your content */
}

.info-button:hover {
    background-color: white;
}

.info-button span {
    font-size: 16px;
    margin-left: 26px;
}

.info-content {
    display: none;
    background-color: #fff;
    border: 1px solid #ccc;
    padding: 20px;
    width: 100%; /* Make the content width 100% to match the card width */

}
.info-content tr:nth-child(even) {
    background-color: #ffffff; /* Light background color for even rows */
}
  
.info-content tr:nth-child(odd) {
    background-color: #f5f5f5; /* Dark background color for odd rows */
}

.info-content h3 {
    color: #0073e6;
}

.info-content table {
    width: 100%; /* Make the table width 100% to match the content width */
    border-collapse: collapse;
}

.info-content td {
    padding: 8px 0;
/*    border-bottom: 1px solid #ccc;*/
}

.info-content tr:last-child td {
    border-bottom: none;
}
.copy-success-message {
   position: fixed;
   top: 50%;
   left: 50%;
   transform: translate(-50%, -50%);
   padding: 10px;
   background-color: #4CAF50;
   color: #fff;
   font-weight: bold;
   border-radius: 4px;
   z-index: 9999;
}
.copy {
  margin: 1rem 0;
  display: inline-flex;
  align-items: center;
  margin: 0.5rem 0 1rem;
  font-size: 14px;
  line-height: 38px;
  height: auto;
  min-height: 30px;
  padding: 0 20px;
  color: #6b7278;
  border: 1px solid #7f868d;
  border-radius: 3px;
  background: #f8f9fa;
  -webkit-box-shadow: none;
  box-shadow: none;
  margin-left: -2px;
}

.copy:hover {
  color: #069de3;
  border-color: #069de3;
  background: #f8f9fa;;
}

.copy:focus {
  outline: none;
}
/* Media query for screens smaller than 768px */
@media (max-width: 768px) {
    .info-button .dashicons {
        font-size: 18px; /* Adjust the size for smaller screens */
    }

    .systemerror {
        font-size: 16px; /* Adjust the size for smaller screens */
    }

    /* Adjust other styles for smaller screens */
    .info-button {
        font-size: 12px;
    }
}

</style>

<script>
  function copySystemInfo() {
      const systemInfoContainer = document.querySelector('.info-container');
      const systemInfoElements = systemInfoContainer.querySelectorAll('.info-content h3, .info-content td');
      let systemInfoText = '';
      let currentRow = '';

      systemInfoElements.forEach((element) => {
          if (element.innerText) {
              const tagName = element.tagName.toLowerCase();
              if (tagName === 'h3') {
                  if (currentRow !== '') {
                      systemInfoText += currentRow + '\n\n'; // Add two newline characters to separate content sections
                  }
                  systemInfoText += `${element.innerText}\n`;
                  currentRow = '';
              } else if (tagName === 'td') {
                  currentRow += `${element.innerText}\t`; // Use tab as a separator
              }
          }
      });

      systemInfoText += currentRow; // Add the current row to the final text

      // Copy the formatted text to the clipboard
      navigator.clipboard.writeText(systemInfoText.trim())
          .then(() => {
              const messageElement = document.createElement('div');
              messageElement.textContent = 'System info copied!';
              messageElement.classList.add('copy-success-message');
              document.body.appendChild(messageElement);

              setTimeout(() => {
                  messageElement.remove();
              }, 3000);
          })
          .catch((error) => {
              console.error('Unable to copy system info:', error);
          });
  }




  jQuery(document).ready(function($) {
      $("#show-info-button").click(function() {
          $("#info-container").slideToggle();
      });
      $("#show-wordpress-info-button").click(function() {
          $("#wordpress-info-container").slideToggle();
      });
      $("#show-Drop-info-button").click(function() {
          $("#Drop-info-container").slideToggle();
      });
      $("#show-active-info-button").click(function() {
          $("#active-info-container").slideToggle();
      });
      $("#show-netplug-info-button").click(function() {
          $("#netplug-info-container").slideToggle();
      });
      $("#show-acplug-info-button").click(function() {
          $("#acplug-info-container").slideToggle();
      });
      $("#show-server-info-button").click(function() {
          $("#server-info-container").slideToggle();
      });
      $("#show-database-info-button").click(function() {
          $("#database-info-container").slideToggle();
      });
      $("#show-wrcons-info-button").click(function() {
          $("#wrcons-info-container").slideToggle();
      });
      $("#show-ftps-info-button").click(function() {
          $("#ftps-info-container").slideToggle();
      });
  });
  // JavaScript function to copy the error log to the clipboard
  function copyErrorLog() {
      // Select the textarea containing the error log
      var textarea = document.querySelector('.errorlog');
      // Select the message div
      var copyMessage = document.querySelector('.copy-message');

      // Check if the textarea and message div exist
      if (textarea && copyMessage) {
          // Select the text within the textarea
          textarea.select();

          try {
              // Attempt to copy the selected text to the clipboard
              document.execCommand('copy');
              // Display the "Copied" message
              copyMessage.style.display = 'block';

              // Hide the message after a few seconds (e.g., 3 seconds)
              setTimeout(function() {
                  copyMessage.style.display = 'none';
              }, 3000);
          } catch (err) {
              console.error('Unable to copy error log: ' + err);
              alert('Error log copy failed. Please copy it manually.');
          }

          // Deselect the text
          textarea.blur();
      } else {
          alert('Error log textarea or copy message not found.');
      }
  }

  // Add an event listener to call the copyErrorLog function when the button is clicked
  document.addEventListener('DOMContentLoaded', function() {
      var copyButton = document.querySelector('.copy');

      if (copyButton) {
          copyButton.addEventListener('click', function(event) {
              event.preventDefault();
              copyErrorLog();
          });
      }
  });

  // JavaScript function to clear the error log textarea
  function clearErrorLog() {
      var textarea = document.querySelector('.errorlog');

      if (textarea) {
          // Clear the textarea content
          textarea.value = '';
      }
  }

  // Add an event listener to call the clearErrorLog function when the "Clear" button is clicked
  document.addEventListener('DOMContentLoaded', function() {
      var clearButton = document.querySelector('.clear');

      if (clearButton) {
          clearButton.addEventListener('click', function(event) {
              event.preventDefault();
              clearErrorLog();
          });
      }
  });

</script>