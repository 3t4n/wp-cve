import React, { FC, useState } from "react";

import Title from "../../core/Title";
import { toBoolean } from "./../../Helpers";

import Icons from "../../icons";
import WPIntgration from "../../images/wp_integration.png";

type Props = {
  handleSingleIntegration: any;
  store: any;
  setStore: any;
};

const WordPress: FC<Props> = ({ handleSingleIntegration, store, setStore }) => {
  const [collapse, setCollapse] = useState<boolean>(
    toBoolean(localStorage.getItem("ect_wp_integration_collapse") || true)
  );

  const handleCollapse = (value) => {
    setCollapse(value);
    localStorage.setItem("ect_wp_integration_collapse", value);
  };

  const handleStatus = (e): void => {
    setStore({
      ...store,
      integrations: {
        ...store.integrations,
        [e.target.name]: e.target.checked,
      },
    });

    if (!collapse && e.target.checked) {
      setCollapse(true);
    }
  };

  return (
    <>
      <div className="integration_media">
        <img src={WPIntgration} alt="WordPress Logo" />
      </div>
      <div className="integration_content">
        <div className="left_content">
          <Title>
            <h3>Default WordPress Forms</h3>
          </Title>
          {toBoolean(
            store?.integrations?.wordpress &&
              store.integrations.wordpress.toString()
          ) && (
            <p className="status">Turnstile is enabled for</p>
          )}
          {collapse ? (
            <>
              <div
                className={`integration-item ${
                  store?.integrations?.wordpress &&
                  store.integrations.wordpress.toString()
                    ? ""
                    : "disable_item"
                }`}
              >
                <label htmlFor="wordpress_login">
                  <input
                    type="checkbox"
                    name="wordpress_login"
                    id="wordpress_login"
                    disabled={
                      !toBoolean(
                        store?.integrations?.wordpress &&
                          store.integrations.wordpress.toString()
                      )
                    }
                    checked={store?.fields?.wordpress?.includes(
                      "wordpress_login"
                    )}
                    onClick={(e) => handleSingleIntegration(e)}
                  />{" "}
                  <span className="checkbox_checkmark"></span>
                  <span className="checkbox_label">WordPress Login</span>
                </label>
              </div>
              <div
                className={`integration-item ${
                  store?.integrations?.wordpress &&
                  store.integrations.wordpress.toString()
                    ? ""
                    : "disable_item"
                }`}
              >
                <label htmlFor="wordpress_register">
                  <input
                    type="checkbox"
                    name="wordpress_register"
                    id="wordpress_register"
                    checked={store?.fields?.wordpress?.includes(
                      "wordpress_register"
                    )}
                    disabled={
                      !toBoolean(
                        store?.integrations?.wordpress &&
                          store.integrations.wordpress.toString()
                      )
                    }
                    onClick={(e) => handleSingleIntegration(e)}
                  />{" "}
                  <span className="checkbox_checkmark"></span>
                  <span className="checkbox_label">WodPress Register</span>
                </label>
              </div>
              <div
                className={`integration-item ${
                  store?.integrations?.wordpress &&
                  store.integrations.wordpress.toString()
                    ? ""
                    : "disable_item"
                }`}
              >
                <label htmlFor="wordpress_reset_password">
                  <input
                    type="checkbox"
                    name="wordpress_reset_password"
                    id="wordpress_reset_password"
                    checked={store?.fields?.wordpress?.includes(
                      "wordpress_reset_password"
                    )}
                    disabled={
                      !toBoolean(
                        store?.integrations?.wordpress &&
                          store.integrations.wordpress.toString()
                      )
                    }
                    onClick={(e) => handleSingleIntegration(e)}
                  />
                  <span className="checkbox_checkmark"></span>
                  <span className="checkbox_label">
                    WordPress Reset Password
                  </span>
                </label>
              </div>
              <div
                className={`integration-item ${
                  store?.integrations?.wordpress &&
                  store.integrations.wordpress.toString()
                    ? ""
                    : "disable_item"
                }`}
              >
                <label htmlFor="wordpress_comment">
                  <input
                    type="checkbox"
                    name="wordpress_comment"
                    id="wordpress_comment"
                    checked={store?.fields?.wordpress?.includes(
                      "wordpress_comment"
                    )}
                    onClick={(e) => handleSingleIntegration(e)}
                    disabled={
                      !toBoolean(
                        store?.integrations?.wordpress &&
                          store.integrations.wordpress.toString()
                      )
                    }
                  />
                  <span className="checkbox_checkmark"></span>
                  <span className="checkbox_label">WordPress Comment</span>
                </label>
              </div>
            </>
          ) : null}
          {collapse ? (
            <button
              className="btn_arrow_up"
              onClick={() => handleCollapse(false)}
            >
              Hide Details {Icons.arrow_up}
            </button>
          ) : (
            <button
              className="btn_arrow_down"
              onClick={() => handleCollapse(true)}
            >
              View Details {Icons.arrow_down}
            </button>
          )}
        </div>

        <div className="right_conent">
          <label className="switch" htmlFor="wordpress">
            <input
              type="checkbox"
              name="wordpress"
              id="wordpress"
              onClick={(e) => handleStatus(e)}
              checked={toBoolean(
                store?.integrations?.wordpress &&
                  store?.integrations?.wordpress.toString()
              )}
            />
            <span className="slider round"></span>
          </label>
        </div>
      </div>
    </>
  );
};

export default WordPress;
