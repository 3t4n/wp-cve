import React, { FC, useState} from "react";
import {
  toBoolean,
  isForminatorFormsInstalled,
  isForminatorFormsLoaded,
  getNonce,
  getAjaxNonce,
} from "./../../Helpers";
import Title from "./../../core/Title";

import Icons from "../../icons";
import ForminatorImage from "../../images/forminator.png";

type Props = {
  store: any;
  setStore: any;
};

const Forminator: FC<Props> = ({ store, setStore }) => {
  const [installed, setInstall] = useState<boolean>(
    isForminatorFormsInstalled()
  );
  const [loaded, setLoad] = useState<boolean>(isForminatorFormsLoaded());
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
        slug: "forminator",
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
        slug: "forminator",
      },
      success() {
        setLoader(false);
        setLoad(true);
      },
      error(err) {
        console.log(err);
        setLoad(true);
        setLoader(false);
      },
    });
  };

  return (
    <>
      <div className="integration_media">
        <img src={ForminatorImage} alt="Forminator Forms Logo" />
      </div>
      <div className="integration_content">
        <div className="left_content">
          <Title>
            <h3>Forminator Forms</h3>
            <span>New</span>
          </Title>
          {!installed && (
            <div className="active_install_wrapper">
              <div className="info_message">
                <span>{Icons.info_icon}</span>
                <span>Please install Forminator Form first!</span>
              </div>
            </div>
          )}

          {installed && !loaded && (
            <div className="active_install_wrapper">
              <div className="info_message">
                <span>{Icons.info_icon}</span>
                <span>Please activate Forminator Form first!</span>
              </div>
            </div>
          )}

          {installed &&
            loaded &&
            toBoolean(
              store?.integrations?.forminator &&
                store.integrations.forminator.toString()
            ) && <p className="status">Turnstile is enabled</p>}
        </div>

        <div className="right_content">
          {installed && loaded && (
            <label className="switch" htmlFor="forminator">
              <input
                type="checkbox"
                name="forminator"
                id="forminator"
                onClick={(e) => handleStatus(e)}
                checked={toBoolean(
                  store?.integrations?.forminator &&
                    store?.integrations?.forminator.toString()
                )}
              />
              <span className="slider round"></span>
            </label>
          )}
          {!installed && (
            <div onClick={(e) => handlePluginInstall(e)}>
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
                <button className="ect-install-button">Install</button>
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
              ) : null}

              {installed && !loaded && !loader && (
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

export default Forminator;
