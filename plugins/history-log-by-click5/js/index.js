function click5_sitemap_notification(type, msg, timeout = 3500) {
  let curElement = document.getElementById('click5_history_log_notification');
  if (curElement) {
    curElement.remove();
    setTimeout(() => {
      let notificationElement = document.createElement('div');
      notificationElement.setAttribute('id', 'click5_history_log_notification');
      notificationElement.className = type;
      notificationElement.innerHTML = '<span>' + msg + '</span>';

      document.querySelector('body').appendChild(notificationElement);
      notificationElement.style.opacity = '1';
      setTimeout(() => {
        notificationElement.opacity = '0';
        setTimeout(() => {
          notificationElement.remove();
        }, 300);
      }, timeout);
    }, 500);
  } else {
    let notificationElement = document.createElement('div');
    notificationElement.setAttribute('id', 'click5_history_log_notification');
    notificationElement.className = type;
    notificationElement.innerHTML = '<span>' + msg + '</span>';

    document.querySelector('body').appendChild(notificationElement);
    notificationElement.style.opacity = '1';
    setTimeout(() => {
      notificationElement.opacity = '0';
      setTimeout(() => {
        notificationElement.remove();
      }, 300);
    }, timeout);
  }
}

function postRequestJSON(url, object, callback) {
    const authenticationObj = {
      token: document.querySelector('#verification_token').value,
      user: document.querySelector('#user_identificator').value
    };
  
    var xhr = new XMLHttpRequest();
    xhr.open('POST', url);
    xhr.setRequestHeader('token', authenticationObj.token);
    xhr.setRequestHeader('user', authenticationObj.user);
    xhr.setRequestHeader('Content-type', 'application/json;charset=UTF-8');
    xhr.onload = function () {
      if (xhr.status === 200) {
        let resObject = [];
        try {
          resObject = JSON.parse(xhr.responseText)
          if (resObject) {
            if (resObject.notification) {
              //click5_sitemap_notification(resObject.type, resObject.message, 3500);
              return;
            }
          }
        } catch (e) {
  
        }
        callback(resObject);
      }
      else {
      }
    };
    xhr.send(JSON.stringify(object));
}


function setPluginSupport(data) {
  //console.log(data);
    postRequestJSON(c5resturl.wpjson + 'click5_history_log/API/support_plugin', {name: data.attributes.id.value, user: data.attributes.user.value, track: data.checked}, (data) => {
      
      if(data[1]) {
        click5_sitemap_notification('success', data[0] + ' tracking has been enabled', 1500);
      } else {
        click5_sitemap_notification('success', data[0] + ' tracking has been disabled', 1500);
      }
    });
}

function setModuleSupport(data) {
  //console.log(data);
  postRequestJSON(c5resturl.wpjson + 'click5_history_log/API/support_module', {id: data.attributes.id.value, name: data.dataset.name, user: data.attributes.user.value, track: data.checked}, (data) => {
    
    if(data[1]) {
      click5_sitemap_notification('success', data[0] + ' module tracking has been enabled', 1500);
    } else {
      click5_sitemap_notification('success', data[0] + ' module tracking has been disabled', 1500);
    }
  });
}

function closeModal(){
    var modal = document.getElementById("c5ConfirmModal");
    var span = document.getElementsByClassName("c5close")[0];
    span.onclick = function() {
    modal.style.display = "none";
    }
    modal.style.display = "none";
}

function btnClick(){
    var modal = document.getElementById("c5ConfirmModal");
    modal.style.display = "block";
}

function addEmailInput(){
  let appendElement = '<div class="alert_email_input_box"><input type="text" class="click5_history_log_alert_email" name="click5_history_log_alert_email[]" onchange="validateEmail()"/><span class="add_email_input" onclick="addEmailInput()">+</span><span class="user_email_check"></span></div>';
  const emailContainer = jQuery(".alert_email_inputs");
  emailContainer.append(appendElement);
}

function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

function validateEmail(){
  jQuery(".click5_history_log_alert_email").each(function(index,event){
    let emailCheckElement = jQuery(event).parent().find('.user_email_check');
    if(isEmail(event.value)){
      emailCheckElement.removeClass("invalid");
      emailCheckElement.addClass("valid");
      emailCheckElement.html("&#10004;");
    }else{
      emailCheckElement.removeClass("valid");
      emailCheckElement.addClass("invalid");
      emailCheckElement.html("&#10005;");
    }
  });

  if(jQuery(".user_email_check").hasClass('invalid')){
    jQuery('input[name=save_button]').attr("disabled",true);
  }else{
    jQuery('input[name=save_button]').removeAttr("disabled");
  }



}


jQuery(document).ready(function() {
    jQuery('[name="save_button_op"]').click(function(e) {
        
        if(jQuery('[name="confirmDelete"]').val()!='delete' && jQuery('[name="confirmDelete"]').val()!='DELETE')
        {
            console.log(  jQuery('[name="confirmDelete"]').val());
            jQuery('[name="confirm-delete-message"]').css( "display", "block" );
            e.preventDefault();
        }

       
    });
    jQuery("#confirmDelete").keypress(function(event) {
        //console.log(event.keyCode);
        if (event.keyCode === 13) {
            if(jQuery('[name="confirmDelete"]').val()!='delete' && jQuery('[name="confirmDelete"]').val()!='DELETE')
            {
                //console.log(  jQuery('[name="confirmDelete"]').val());
                jQuery('[name="confirm-delete-message"]').css( "display", "block" );
                event.preventDefault();
            }
        }
    });

    if(jQuery(".alert_email_inputs").length){
        jQuery(".click5_history_log_alert_email").each((index,event) => {
          validateEmail();
        });
    }
});
