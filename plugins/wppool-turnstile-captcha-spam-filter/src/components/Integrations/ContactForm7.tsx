import React, { FC, useState } from "react";
import Title from "./../../core/Title";
import ReactTooltip from "react-tooltip";

import {
  toBoolean,
  isCF7Loaded,
  isCF7Installed,
  getAjaxNonce,
  getNonce,
} from "./../../Helpers";

import Icons from "../../icons";
import CForm from "../../images/cf_integration.png";

type Props = {
  store: any;
  setStore: any;
};

const ContactForm7: FC<Props> = ({ store, setStore }) => {
  const [installed, setInstall] = useState<boolean>(isCF7Installed());
  const [loaded, setLoad] = useState<boolean>(isCF7Loaded());
  const [loader, setLoader] = useState<boolean>(false);
  const [copy, setCopy] = useState<string>("[easy_cloudflare_turnstile]");
  const [showTooltip, setShowTooltip] = useState(false);

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
        slug: "contact-form-7",
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
        slug: "contact-form-7",
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

  const handleCopy = () => {
    const el = document.createElement("textarea");
    el.value = copy;
    document.body.appendChild(el);
    el.select();
    document.execCommand("copy");
    document.body.removeChild(el);

    setShowTooltip(true);
    setTimeout(() => {
      setShowTooltip(false);
    }, 800);
  };

  return (
    <>
      <div className="integration_media">
        <img src={CForm} alt="Contact Form Logo" />
      </div>
      <div className="integration_content">
        <div className="left_content">
          <Title>
            <h3>Contact Form 7</h3>
          </Title>
          {!installed && (
            <div className="active_install_wrapper">
              <div className="info_message">
                <span>{Icons.info_icon}</span>
                <span>Please install Contact Form 7 first!</span>
              </div>
            </div>
          )}

          {installed && !loaded && (
            <div className="active_install_wrapper">
              <div className="info_message">
                <span>{Icons.info_icon}</span>
                <span>Please activate Contact Form 7 first!</span>
              </div>
            </div>
          )}

          {installed &&
            loaded &&
            toBoolean(
              store?.integrations?.cf7 && store.integrations.cf7.toString()
            ) && (
              <label htmlFor="cf7_shortcode" className="ect-form-label">
                <p className="status">Turnstile is enabled</p>
                <p className="shortcode-text">
                  Shortcode{" "}
                  <span
                    className="tooltip_icon"
                    data-tip="To add Turnstile to individual Contact Form 7 forms easily, add this shortcode (in the form editor)."
                    data-for="cf7_shortcode"
                  >
                    {Icons.tooltip_icon}
                    <ReactTooltip
                      id="cf7_shortcode"
                      effect="solid"
                      place="right"
                      class="tooltip-container"
                    />
                  </span>
                </p>
                <p className="short-code">
                  <code>[easy_cloudflare_turnstile]</code>{" "}
                  <span onClick={handleCopy}>
                    {Icons.copy}
                    {showTooltip && <span className="tooltip">Copied</span>}
                  </span>
                </p>
              </label>
            )}
        </div>

        <div className="right_content">
          {installed && loaded && (
            <label className="switch" htmlFor="cf7">
              <input
                type="checkbox"
                name="cf7"
                id="cf7"
                onClick={(e) => handleStatus(e)}
                checked={toBoolean(
                  store?.integrations?.cf7 &&
                    store?.integrations?.cf7.toString()
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

export default ContactForm7;
