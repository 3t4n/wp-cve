window.onload = function(){
    document.querySelector('[data-slug="gdpr-compliant-recaptcha-for-all-forms"] .deactivate a').addEventListener('click', function(event){
      event.preventDefault();
      var urlRedirect = document.querySelector('[data-slug="gdpr-compliant-recaptcha-for-all-forms"] .deactivate a').getAttribute('href');
  
      // create form container
      var formContainer = document.createElement('div');
      formContainer.style.backgroundColor = 'yellow';
      formContainer.setAttribute('id', 'deactivation-form-container');
      formContainer.innerHTML = `
        <h2>Problems with the plugin? Give us feedback and we'll fix it within days.</h2>
        <form id="deactivation-form">
          <textarea name="grp_reason_long" id="grp_reason_long" placeholder="Request for help, additional information, hints" maxlength="500" cols="80" rows="5"></textarea><br>
          <input type="submit" value="Deactivate" id="gdpr_submit" />
          <button id="cancel-deactivation">Cancel</button>
        </form>
        <style>
        #deactivation-form-container {
          position: fixed;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          background-color: #fff;
          padding: 20px;
          border-radius: 5px;
          box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3);
          z-index: 9999;
        }
      </style>
      `;

      // append form container to body
      document.body.appendChild(formContainer);

      // handle form submission
      var deactivationForm = document.querySelector('#deactivation-form');
      deactivationForm.addEventListener('submit', function(event) {
        event.preventDefault();
        let textarea = document.querySelector('textarea[name="grp_reason_long"]');
        if(textarea.value.trim() !== ''){
          let formData = new FormData();
          formData.append('grp_reason_long', textarea.value);
          const xhr = new XMLHttpRequest();
          xhr.open('POST', 'https://programmiere.de/GDPRCompliantRecaptcha/update.php', true);
          xhr.onload = () => {
            if (xhr.status === 200) {
              const response = JSON.parse(xhr.responseText);
            } else {
              console.log("Error: " + xhr.statusText);
            }
          };
          xhr.onerror = () => {
            console.log("Error: " + xhr.statusText);
          };
          xhr.send(formData);
        }

        // deactivate the plugin after form submission
        window.location.href = urlRedirect;
      });
  
      // handle cancel button click
      var cancelButton = document.querySelector('#cancel-deactivation');
      cancelButton.addEventListener('click', function(event) {
          event.preventDefault();
          document.body.removeChild(formContainer);
      });
  
      // show form container
      formContainer.style.display = 'block';
    }, {capture: true});

}