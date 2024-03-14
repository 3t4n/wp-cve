import React, { FC, useState } from "react";

import Title from "../../core/Title";
import Icons from "../../icons";
import BbPressImage from "../../images/bbpress.svg";

import {
  toBoolean,
  isBbPressInstalled,
  isBbPressLoaded,
  getNonce,
  getAjaxNonce,
} from "../../Helpers";

type Props = {
  handleSingleIntegration: any;
  store: any;
  setStore: any;
};

const BbPress: FC<Props> = ({ handleSingleIntegration, store, setStore }) => {
  const [installed, setInstall] = useState<boolean>(isBbPressInstalled());
  const [loaded, setLoad] = useState<boolean>(isBbPressLoaded());
  const [loader, setLoader] = useState<boolean>(false);
  const [collapse, setCollapse] = useState<boolean>(
    toBoolean(localStorage.getItem("ect_bbpress_integration_collapse"))
  );

  const handleCollapse = (value) => {
    setCollapse(value);
    localStorage.setItem("ect_bbpress_integration_collapse", value);
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

  const handlePluginInstall = (e) => {
    e.preventDefault();

    setLoader(true);

    wp.ajax.send("wp_ajax_install_plugin", {
      data: {
        _ajax_nonce: getAjaxNonce(),
        slug: "bbpress",
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
        slug: "bbpress",
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
        <img src={BbPressImage} alt="BbPress Logo" />
      </div>
      <div className="integration_content">
        <div className="left_content">
          <Title>
            <h3>bbPress</h3>
            <span>New</span>
          </Title>
          {!installed && (
            <div className="active_install_wrapper">
              <div className="info_message">
                <span>{Icons.info_icon}</span>
                <span>Please install BbPress first!</span>
              </div>
            </div>
          )}

          {installed && !loaded && (
            <div className="active_install_wrapper">
              <div className="info_message">
                <span>{Icons.info_icon}</span>
                <span>Please activate BbPress first!</span>
              </div>
            </div>
          )}

          {installed &&
            loaded &&
            toBoolean(
              store?.integrations?.bbpress &&
                store.integrations.bbpress.toString()
            ) && <p className="status">Turnstile is enabled for</p>}
          {collapse ? (
            <>
              {!installed || !loaded ? (
                <>
                  <ul className="ect-list-items">
                    <li>BbPress Topic</li>
                    <li>BbPress Reply</li>
                  </ul>
                </>
              ) : (
                <>
                  <div
                    className={`integration-item ${
                      store?.integrations?.bbpress &&
                      store.integrations.bbpress.toString()
                        ? ""
                        : "disable_item"
                    }`}
                  >
                    <label htmlFor="bbpress_topic">
                      <input
                        type="checkbox"
                        name="bbpress_topic"
                        id="bbpress_topic"
                        disabled={
                          !toBoolean(
                            store?.integrations?.bbpress &&
                              store?.integrations?.bbpress.toString()
                          )
                        }
                        checked={store?.fields?.bbpress?.includes(
                          "bbpress_topic"
                        )}
                        onClick={(e) => handleSingleIntegration(e)}
                      />{" "}
                      <span className="checkbox_checkmark"></span>
                      <span className="checkbox_label">BbPress Topic</span>
                    </label>
                  </div>
                  <div
                    className={`integration-item ${
                      store?.integrations?.bbpress &&
                      store.integrations.bbpress.toString()
                        ? ""
                        : "disable_item"
                    }`}
                  >
                    {" "}
                    <label htmlFor="bbpress_reply">
                      <input
                        type="checkbox"
                        name="bbpress_reply"
                        id="bbpress_reply"
                        checked={store?.fields?.bbpress?.includes(
                          "bbpress_reply"
                        )}
                        disabled={
                          !toBoolean(
                            store?.integrations?.bbpress &&
                              store?.integrations?.bbpress.toString()
                          )
                        }
                        onClick={(e) => handleSingleIntegration(e)}
                      />{" "}
                      <span className="checkbox_checkmark"></span>
                      <span className="checkbox_label">BbPress Reply</span>
                    </label>
                  </div>
                </>
              )}
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

        <div className="right_content">
          {installed && loaded && (
            <label className="switch" htmlFor="bbpress">
              <input
                type="checkbox"
                name="bbpress"
                id="bbpress"
                onClick={(e) => handleStatus(e)}
                checked={toBoolean(
                  store?.integrations?.bbpress &&
                    store?.integrations?.bbpress.toString()
                )}
              />
              <span className="slider round"></span>
            </label>
          )}

          {!installed && (
            <div>
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
            </div>
          )}

          {installed && !loaded && (
            <div>
              <div onClick={(e) => handlePluginActive(e)}>
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
                  <button className="ect-active-button">Activate</button>
                )}
              </div>
            </div>
          )}
        </div>
      </div>
    </>
  );
};

export default BbPress;
