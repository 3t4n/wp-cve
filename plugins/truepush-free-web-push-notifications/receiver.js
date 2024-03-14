jQuery(document).ready(notice_active);

var tpStart = false;
var tpStatus= undefined;
var intial_post = undefined;
var tpCount =0;
function wpeditor(){
  var editorWp = "";
  if (!wp || !wp.data || !wp.data.select("core/editor")) {
    return false;
  } else {
     editorWp = wp.data.select("core/editor");
     return editorWp;
  }
 
}
function notice_active() {
  var editorWp = wpeditor();
  if(!editorWp)
  {
    return;
  }else{
    wp.data.subscribe(() => {
      const post = editorWp.getCurrentPost();
      if(post && post !== {}){
    
        if (!intial_post) {
          intial_post = post.modified;	
        }
        const modified = post.modified;
        const send_tp_notif = jQuery("[name=send_truepush_notification]").attr(
          "checked"
        );
        tpStatus = post.status;
        if (!tpStart && (modified !== intial_post) && send_tp_notif && (tpStatus=== "publish") ) {
          setTimeout(get_tpdata, 5000); 
          tpStart = true;
        }
      }
      
    });
  }
  const get_tpdata = () => {
    const data = {
      action: "truepush_published_data",
      post_id: ajax_object_tp.post_id
    };
    jQuery.get(ajax_object_tp.ajax_url, data, function(response) {
      response = JSON.parse(response);
      // console.log("response ",response);
      const { truepush_response } = response;
      if(truepush_response.length>0 && JSON.parse(truepush_response).status_code)
      {
        if (JSON.parse(truepush_response).status_code >= 400) {
          errorMsg("Truepush :: there was a " + JSON.parse(truepush_response).status_code + " error sending your notification: " + JSON.parse(truepush_response).message);
          resetValues();
          return;
        }
        var notice_text="";
        if (tpStatus === "publish") {
          // console.log("tpStatus ",tpStatus);
          notice_text = "Truepush ::  Your Truepush campaign created successfully. You can monitor this campaign in https://app.truepush.com.";
          // var notice_text = "Truepush ::  "+JSON.parse(truepush_response).message;
        } 
       
        wp.data
        .dispatch("core/notices")
        .createNotice(
          "info",
          notice_text ,
          {
              id:'truepush-notice',
              isDismissible: true
          }
        );
        resetValues();
        return;
      }
      else{
        if(tpCount<9){
          setTimeout(get_tpdata, 5000); 
          tpCount++;
        }
        else{
          resetValues();
          return;
        }
        
        
      }
    });
  }
  const errorMsg = error => {
      wp.data.dispatch("core/notices").createNotice("error", error, {
          isDismissible: true,
          id:'truepush-error'
      });
  };

  const resetValues = () => {
       
    tpStart = false;
    intial_post = undefined;
    tpStatus= undefined;
    tpCount =0;
  }
};
window.Truepush = notice_active();