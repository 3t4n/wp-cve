import React, { useState, FC, useRef } from "react";
import { Turnstile } from "@marsidev/react-turnstile";
import type { TurnstileInstance } from "@marsidev/react-turnstile";
import ReactTooltip from "react-tooltip";

import Row from "../core/Row";
import Column from "../core/Column";
import Title from "../core/Title";

import { getNonce, toBoolean, getValidationStatus } from "../Helpers";

import Icons from "../icons";
import VideoCover from "../images/video_cover.png";
import SiteKeyTooltip from "../images/tooltip/sitekey_infotip.png";
import SecretKeyToolTIp from "../images/tooltip/secretkey_infotip.png";

type Props = {
  settings: {
    secret_key: string;
    site_key: string;
  };
  store: {
    settings: {
      secret_key: string;
      site_key: string;
    };
  };
  siteKey: string;
  setSiteKey: any;
  secretKey: string;
  setSecretKey: any;
  validation: boolean;
  setValidation: any;
};

const Dashboard: FC<Props> = ({
  siteKey,
  setSiteKey,
  secretKey,
  setSecretKey,
  validation,
  setValidation,
}) => {
  const [status, setStatus] = useState<string>();
  const [token, setToken] = useState<string | null>();
  const [saveChanges, setSaveChanges] = useState(false);
  const [showTheVideo, setShowTheVideo] = useState(false);
  const [displaySaveButton, setDisplaySaveButton] = useState<boolean>(true);

  const siteKeyRef = useRef<React.Ref<HTMLInputElement> | undefined>();
  const secretKeyRef = useRef<React.Ref<HTMLInputElement> | undefined>();
  const widgetRef = useRef<TurnstileInstance>(null);

  const handleShowVideo = () => {
    setShowTheVideo((prevState) => !prevState);
  };

  const saveSettings = (event): void => {
    event.preventDefault();

    if (siteKey === "") {
      siteKeyRef.current.style.borderColor = "red";
      siteKeyRef.current.className = "error";
    } else {
      siteKeyRef.current.style.borderColor = "";
      siteKeyRef.current.className = "";
    }

    if (secretKey === "") {
      secretKeyRef.current.style.borderColor = "red";
      secretKeyRef.current.className = "error";
    } else {
      secretKeyRef.current.style.borderColor = "";
      secretKeyRef.current.className = "";
    }

    if (siteKey === "" || secretKey === "") {
      return;
    }

    wp.ajax.send("save_settings", {
      data: {
        nonce: getNonce(),
        settings: JSON.stringify({
          site_key: siteKey,
          secret_key: secretKey,
        }),
      },
      success({ validated }) {
        setValidation(validated);
        setSaveChanges(false);

        if (!token || "invalid_sitekey" === token) {
          siteKeyRef.current.style.borderColor = "red";
          siteKeyRef.current.className = "error";
        } else {
          siteKeyRef.current.style.borderColor = "";
          siteKeyRef.current.className = "";
        }
      },
      error(err: any) {
        console.error(err);
      },
    });
  };

  const handleVerification = (): void => {
    if (!secretKey) {
      console.log(secretKey);

      return;
    }

    wp.ajax.send("verify_connection", {
      data: {
        nonce: getNonce(),
        secret_key: secretKey,
        token,
      },
      success({ validated }) {
        setValidation(validated);
        setSaveChanges(!validated);
      },
      error(err: any) {
        setValidation(false);
        setSaveChanges(true);
        setStatus("error");
      },
    });
  };

  const handleOnSuccess = (data: string) => {
    setToken(data);
    setDisplaySaveButton(false);

    siteKeyRef.current.style.borderColor = "";
    siteKeyRef.current.className = "";
  };

  const handleError = (data: string) => {
    setToken(data);
    setDisplaySaveButton(true);

    siteKeyRef.current.style.borderColor = "red";
    siteKeyRef.current.className = "error";
  };

  if (status === "error") {
    secretKeyRef.current.style.borderColor = "red";
    secretKeyRef.current.className = "error";
  }
  return (
    <div className="ect-dashboard">
      <Row customClass="custom_row_style">
        <Column sm="7" customClass="custom_column_style">
          <div className="ect-tab-content api-configuration">
            <div className="protection-status">
              {!validation && (!siteKey || (siteKey && !token)) && (
                <div className="zero-state-notice">
                  {Icons.key}{" "}
                  <span>
                    <a
                      target="_blank"
                      href="https://dash.cloudflare.com/?to=/:account/turnstile"
                      rel="noreferrer"
                      className="ect-click-here-btn"
                    >
                      Click here
                    </a>{" "}
                    to get your <strong>Site Key</strong> and{" "}
                    <strong>Secret Key</strong> and paste them below
                  </span>
                </div>
              )}

              {!saveChanges && !validation && siteKey && (
                <>
                  <Turnstile
                    ref={widgetRef}
                    siteKey={siteKey}
                    onError={(e) => handleError(e)}
                    onSuccess={(e) => handleOnSuccess(e)}
                    options={{
                      theme: "light",
                    }}
                  />
                </>
              )}

              {!validation && siteKey && secretKey && "error" === status && (
                <div className="connection-error-status">
                  <div className="icon">{Icons.error_icon}</div>
                  <div className="theMessage">
                    <h5>
                      Error! Your Secret Key is not match with the Cloudflare
                      Turnstile
                    </h5>
                    <p>
                      Please re-check and match the Secret Key from{" "}
                      {/*
											eslint-disable-next-line
											jsx-a11y/anchor-is-valid */}
                      <a
                        href="https://dash.cloudflare.com/?to=/:account/turnstile"
                        rel="noreferrer"
                        target="_blank"
                      >
                        here
                        <span className="link_icon">{Icons.link_icon}</span>
                      </a>
                    </p>
                  </div>
                </div>
              )}

              {!validation && token === "invalid_sitekey" && siteKey && (
                <div className="connection-error-status">
                  <div className="icon">{Icons.error_icon}</div>
                  <div className="theMessage">
                    <h5>
                      Error! Your Site Key is not match with the Cloudflare
                      Turnstile
                    </h5>
                    <p>
                      Please re-check and match the Site Key from{" "}
                      {/*
											eslint-disable-next-line
											jsx-a11y/anchor-is-valid */}
                      <a
                        href="https://dash.cloudflare.com/?to=/:account/turnstile"
                        rel="noreferrer"
                        target="_blank"
                      >
                        here
                        <span className="link_icon">{Icons.link_icon}</span>
                      </a>
                    </p>
                  </div>
                </div>
              )}

              {validation && siteKey && secretKey && (
                <div className="connection-success-status">
                  <div className="icon">{Icons.success_icon}</div>
                  <div className="theMessage">
                    <h5>
                      Success! Your website is connected to Cloudflare Turnstile
                    </h5>
                    <p>
                      You can get your Site Key and Secret Key always from{" "}
                      {/*
											eslint-disable-next-line
											jsx-a11y/anchor-is-valid */}
                      <a
                        href="https://dash.cloudflare.com/?to=/:account/turnstile"
                        rel="noreferrer"
                        target="_blank"
                      >
                        here
                        <span className="link_icon">{Icons.link_icon}</span>
                      </a>
                    </p>
                  </div>
                </div>
              )}
            </div>
            <div className="configuration-form">
              <div className="ect-form-control">
                <label htmlFor="site_key" className="ect-form-label">
                  Site Key{" "}
                  <span
                    className="tooltip_icon"
                    data-tip
                    data-for="site-key-tooltip"
                  >
                    {Icons.tooltip_icon}
                  </span>
                  <ReactTooltip id="site-key-tooltip" effect="solid">
                    <div className="tooltip-content">
                      Copy your Site Key from CloudFlare Dashboard and paste it
                      below
                    </div>
                    <img
                      className="tooltip-image"
                      src={SiteKeyTooltip}
                      alt="Image"
                    />
                  </ReactTooltip>
                </label>
                <input
                  className={status === "error" ? "invalid-credentials" : ""}
                  type="text"
                  name="site_key"
                  id="site_key"
                  onChange={(e) => {
                    setSiteKey(e.target.value);
                    setSaveChanges(true);
                    setStatus("init");
                    setDisplaySaveButton(true);
                    setToken(null);
                  }}
                  value={siteKey}
                  ref={siteKeyRef}
                />
              </div>
              <div className="ect-form-control">
                <label htmlFor="secret_key" className="ect-form-label">
                  Secret Key
                  <span
                    className="tooltip_icon"
                    data-tip
                    data-for="secret-key-tooltip"
                  >
                    {Icons.tooltip_icon}
                  </span>
                </label>
                <ReactTooltip id="secret-key-tooltip" effect="solid">
                  <div className="tooltip-content">
                    Copy your Secret Key from CloudFlare Dashboard and paste it
                    below
                  </div>
                  <img
                    className="tooltip-image"
                    src={SecretKeyToolTIp}
                    alt="Image"
                  />
                </ReactTooltip>
                <input
                  className={status === "error" ? "invalid-credentials" : ""}
                  type="text"
                  name="secret_key"
                  id="secret_key"
                  onChange={(e) => {
                    setSecretKey(e.target.value);
                    setSaveChanges(true);
                    setStatus("init");
                    setDisplaySaveButton(true);
                    setToken(null);
                  }}
                  value={secretKey}
                  ref={secretKeyRef}
                />
              </div>
              <div className="ect-form-control">
                {(!validation || saveChanges) && displaySaveButton ? (
                  <input
                    type="submit"
                    value="Connect"
                    onClick={saveSettings}
                    className={`button-save${
                      !siteKey || !secretKey ? ` no-credentials` : ""
                    }`}
                  />
                ) : null}
                {!validation &&
                  token &&
                  "invalid_sitekey" != token &&
                  siteKey && (
                    <input
                      type="submit"
                      value={"Confirm connection"}
                      onClick={handleVerification}
                      className="button-connect"
                    />
                  )}
                {validation && !saveChanges && (
                  <input
                    type="submit"
                    value={"Connected"}
                    disabled={validation}
                    className="button-connected"
                  />
                )}
              </div>
            </div>
          </div>
          <ReactTooltip
            place="right"
            type="success"
            effect="solid"
            border={true}
            borderColor="#BFBFBF"
            backgroundColor="white"
            textColor="#616161"
            className="customECTClass"
          />
        </Column>
        <Column sm="5" customClass="custom_column_style">
          <div className="intro-wrap">
            <div className="intro-head">
              <Title>
                <h3>
                  How to get Site Key and Secret Key?{" "}
                  <span className="intro-vide-duration">01:53</span>
                </h3>
              </Title>
            </div>
            <div className="intro-video-wrapper">
              {showTheVideo ? (
                <iframe
                  width="100%"
                  height="315"
                  src="https://www.youtube.com/embed/2jU2LhkiQQU"
                  title="YouTube video player"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                  allowFullScreen
                ></iframe>
              ) : (
                <div className="video_cover" onClick={handleShowVideo}>
                  <img src={VideoCover} alt="Video Coder" />
                </div>
              )}
            </div>
          </div>
        </Column>
      </Row>
    </div>
  );
};

export default Dashboard;
