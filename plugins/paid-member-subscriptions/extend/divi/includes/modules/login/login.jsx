// External Dependencies
import React from "react";
import AjaxComponent from "./../base/AjaxComponent/AjaxComponent";

// Internal Dependencies
import "./style.css";

class Login extends AjaxComponent {
  static slug = "pms_login";

  _shouldReload(oldProps, newProps) {
    return (
      oldProps.register_url        !== newProps.register_url        ||
      oldProps.lostpassword_url    !== newProps.lostpassword_url    ||
      oldProps.redirect_url        !== newProps.redirect_url        ||
      oldProps.logout_redirect_url !== newProps.logout_redirect_url
    );
  }

  _reloadFormData(props) {
    var formData = new FormData();

    formData.append("action"              , "pms_divi_extension_ajax");
    formData.append("form_type"           , "l");
    formData.append("register_url"        , props.register_url);
    formData.append("lostpassword_url"    , props.lostpassword_url);
    formData.append("redirect_url"        , props.redirect_url);
    formData.append("logout_redirect_url" , props.logout_redirect_url);

    return formData;
  }

  render() {
    return super.render();
  }

  _render() {
    return (
      <div
        className="pms-form-container"
        dangerouslySetInnerHTML={{ __html: this.state.result }}
      />
    );
  }
}

export default Login;
