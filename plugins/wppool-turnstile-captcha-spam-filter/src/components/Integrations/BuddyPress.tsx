import React, { FC, useState } from "react";
import Title from "./../../core/Title";

import {
  toBoolean,
  isBuddyPressLoaded,
  isBuddyPressInstalled,
  getNonce,
  getAjaxNonce,
} from "./../../Helpers";

import Icons from "../../icons";
import BuddyPressLogo from "../../images/buddy_press_integration.png";

type Props = {
  store: any;
  setStore: any;
};

const BuddyPress: FC<Props> = ({ store, setStore }) => {
  const [installed, setInstall] = useState<boolean>(isBuddyPressInstalled());
  const [loaded, setLoad] = useState<boolean>(isBuddyPressLoaded());
  const [loader, setLoader] = useState<boolean>(false);

  const handleStatus = (e): void => {
    setStore({
      ...store,
      integrations: {
        ...store.integrations,
        [e.target.name]: e.target.checked,
      },
    });
  };

  const handlePluginInstall = (e) => {
    e.preventDefault();

    setLoader(true);

    wp.ajax.send("wp_ajax_install_plugin", {
      data: {
        _ajax_nonce: getAjaxNonce(),
        slug: "buddypress",
      },
      success() {
        setLoader(false);
        setInstall(true);
      },
      error() {
        setInstall(false);
      },
    });
  };

  const handlePluginActive = (e) => {
    e.preventDefault();

    setLoader(true);

    wp.ajax.send("active_plugin", {
      data: {
        nonce: getNonce(),
        slug: "buddypress",
      },
      success() {
        setLoader(false);
        setLoad(true);
      },
      error() {
        setLoad(true);
        setLoader(false);
      },
    });
  };

  return (
    <>
      <div className="integration_media">
        <img src={BuddyPressLogo} alt="WordPress Logo" />
      </div>
      <div className="integration_content">
        <div className="left_content">
          <Title>
            <h3>BuddyPress</h3>
          </Title>
          {!installed && (
            <div className="active_install_wrapper">
              <div className="info_message">
                <span>{Icons.info_icon}</span>
                <span>Please install BuddyPress first!</span>
              </div>
            </div>
          )}

          {installed && !loaded && (
            <div className="active_install_wrapper">
              <div className="info_message">
                <span>{Icons.info_icon}</span>
                <span>Please activate BuddyPress first!</span>
              </div>
            </div>
          )}

          {installed &&
            loaded &&
            toBoolean(
              store?.integrations?.budypress &&
                store.integrations.budypress.toString()
            ) && <p className="status">Turnstile is enabled</p>}
        </div>

        <div className="right_content">
          {installed && loaded && (
            <label className="switch" htmlFor="buddypress">
              <input
                type="checkbox"
                name="buddypress"
                id="buddypress"
                onClick={(e) => handleStatus(e)}
                checked={toBoolean(
                  store?.integrations?.buddypress &&
                    store?.integrations?.buddypress.toString()
                )}
              />
              <span className="slider round"></span>
            </label>
          )}

          {!installed && (
            <div>
              {loader ? (
                <svg width="25" height="25" viewBox="0 0 100 100">
                  <g transform="translate(50,50)">
                    <g transform="scale(1)">
                      <circle cx="0" cy="0" r="50" fill="#687c93"></circle>
                      <circle
                        cx="0"
                        cy="-26"
                        r="12"
                        fill="#ffffff"
                        transform="rotate(161.634)"
                      >
                        <animateTransform
                          attributeName="transform"
                          type="rotate"
                          calcMode="linear"
                          values="0 0 0;360 0 0"
                          keyTimes="0;1"
                          dur="1s"
                          begin="0s"
                          repeatCount="indefinite"
                        ></animateTransform>
                      </circle>
                    </g>
                  </g>
                </svg>
              ) : (
                <button
                  className="ect-install-button"
                  onClick={(e) => handlePluginInstall(e)}
                >
                  Install
                </button>
              )}
            </div>
          )}

          {installed && !loaded && (
            <div>
              {loader ? (
                <svg width="25" height="25" viewBox="0 0 100 100">
                  <g transform="translate(50,50)">
                    <g transform="scale(1)">
                      <circle cx="0" cy="0" r="50" fill="#687c93"></circle>
                      <circle
                        cx="0"
                        cy="-26"
                        r="12"
                        fill="#ffffff"
                        transform="rotate(161.634)"
                      >
                        <animateTransform
                          attributeName="transform"
                          type="rotate"
                          calcMode="linear"
                          values="0 0 0;360 0 0"
                          keyTimes="0;1"
                          dur="1s"
                          begin="0s"
                          repeatCount="indefinite"
                        ></animateTransform>
                      </circle>
                    </g>
                  </g>
                </svg>
              ) : (
                <button
                  className="ect-active-button"
                  onClick={(e) => handlePluginActive(e)}
                >
                  Activate
                </button>
              )}
            </div>
          )}
        </div>
      </div>
    </>
  );
};

export default BuddyPress;
