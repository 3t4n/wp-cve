import "./index.scss";

import WisdmLoader from "../commons/loader/index.js";
import React, { Component } from "react";
import { __ } from "@wordpress/i18n";

class StudentProfile extends Component {
  constructor(props) {
    super(props);
    let error = null;
    let avatar = this.getUserType() ? "www.gravatar.com/avatar/789047b2eb7fd33f3fb6858358dcc5d8?s=150&r=g&d=mm" : wisdm_learndash_reports_front_end_script_student_table.avatar_url;
    console.log(avatar);
    let username = this.getUserType() ? "No Name" : wisdm_learndash_reports_front_end_script_student_table.current_user.data.display_name;
    if (this.getUserType()) {
      error = {
        message: __(
          "Sorry you are not allowed to access this block, please check if you have proper access permissions",
          "learndash-reports-by-wisdmlabs"
        ),
      };
    }
    this.state = {
      isLoaded: true,
      userImage : avatar,
      userName : username,
      error: error,
    };
  }

  /**
   * Based on the current user roles aray this function desides wether a user is a group
   * leader or an Administrator and returns the same.
   */
  getUserType() {
    if(wisdm_learndash_reports_front_end_script_student_table.current_user.ID == 0){
      return true;
    }
    return false;
  }

  componentDidMount() {
   
  }

  componentDidUpdate() {
  }

  render() {
    let body = <div></div>;

    if (this.state.error) {
      // error
      body = '';
  } else if (!this.state.isLoaded) {
      // yet loading
      body = <WisdmLoader />;
    } else {
      body = (
        <div className="user-info-section">
          <div className="thumbnail">
            <img
              alt=""
              src={this.state.userImage}
              srcSet={this.state.userImage}
              className="avatar avatar-96 photo"
              height="96"
              width="96"
              loading="lazy"
              decoding="async"
            />
          </div>
          <div className="information">
            <div className="label clabel">
              <span>Student Name</span>
            </div>
            <div className="name">
              <span>{this.state.userName}</span>
            </div>
          </div>
        </div>
      );
    }

    return body;
  }
}

export default StudentProfile;

document.addEventListener("DOMContentLoaded", function (event) {
  let elem = document.getElementsByClassName(
    "wisdm-learndash-reports-student-profile front"
  );
  if (elem.length > 0) {
    ReactDOM.render(React.createElement(StudentProfile), elem[0]);
  }
});
