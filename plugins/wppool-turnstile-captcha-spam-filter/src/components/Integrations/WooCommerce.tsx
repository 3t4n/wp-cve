import React, { FC, useState, useEffect } from "react";

import Title from "../../core/Title";
import Icons from "../../icons";
import ReactTooltip from "react-tooltip";
import WooIntegration from "../../images/woo_integration.png";

import {
  toBoolean,
  isWcLoaded,
  isWcInstalled,
  isWCECTPlacement,
  getNonce,
  getAjaxNonce,
} from "./../../Helpers";

type Props = {
  handleSingleIntegration: any;
  store: any;
  setStore: any;
};

const WooCommerce: FC<Props> = ({
  handleSingleIntegration,
  store,
  setStore,
}) => {
  const [installed, setInstall] = useState<boolean>(isWcInstalled());
  const [loaded, setLoad] = useState<boolean>(isWcLoaded());
  const [loader, setLoader] = useState<boolean>(false);
  const [placement, setPlacement] = useState<string>(isWCECTPlacement());
  const [collapse, setCollapse] = useState<boolean>(
    toBoolean(localStorage.getItem("ect_wc_integration_collapse"))
  );

  const handleCollapse = (value) => {
    setCollapse(value);
    localStorage.setItem("ect_wc_integration_collapse", value);
  };

  const handleSelectChange = (e) => {
    setPlacement(e.target.value);
    localStorage.setItem("wcPlacement", e.target.value);
    wp.ajax.send("ect_placement", {
      data: {
        nonce: getNonce(),
        selected_option: e.target.value,
        form: "woocommerce",
      },
      success(response) {
        console.log(response);
      },
      error(err) {
        console.error(err);
      },
    });
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
        slug: "woocommerce",
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
        slug: "woocommerce",
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

  useEffect(() => {
    const wcPlacement = localStorage.getItem("wcPlacement");
    if (wcPlacement) {
      setPlacement(wcPlacement);
    }
  }, [placement]);

  return (
    <>
      <div className="integration_media">
        <img src={WooIntegration} alt="Woo-Commerce Logo" />
      </div>
      <div className="integration_content">
        <div className="left_content">
          <Title>
            <h3>WooCommerce Forms</h3>
          </Title>
          {!installed && (
            <div className="active_install_wrapper">
              <div className="info_message">
                <span>{Icons.info_icon}</span>
                <span>Please install WooCommerce first!</span>
              </div>
            </div>
          )}

          {installed && !loaded && (
            <div className="active_install_wrapper">
              <div className="info_message">
                <span>{Icons.info_icon}</span>
                <span>Please activate WooCommerce first!</span>
              </div>
            </div>
          )}

          {installed &&
            loaded &&
            toBoolean(
              store?.integrations?.woocommerce &&
                store.integrations.woocommerce.toString()
            ) && <p className="status">Turnstile is enabled for</p>}
          {collapse ? (
            <>
              {!installed || !loaded ? (
                <>
                  <ul className="ect-list-items">
                    <li>My Account Login</li>
                    <li>WooCommerce Lost/Reset Password</li>
                  </ul>
                </>
              ) : (
                <>
                  <div
                    className={`integration-item ${
                      store?.integrations?.woocommerce &&
                      store.integrations.woocommerce.toString()
                        ? ""
                        : "disable_item"
                    }`}
                  >
                    <label htmlFor="my_account_login">
                      <input
                        type="checkbox"
                        name="my_account_login"
                        id="my_account_login"
                        disabled={
                          !toBoolean(
                            store?.integrations?.woocommerce &&
                              store?.integrations?.woocommerce.toString()
                          )
                        }
                        checked={store?.fields?.woocommerce?.includes(
                          "my_account_login"
                        )}
                        onClick={(e) => handleSingleIntegration(e)}
                      />{" "}
                      <span className="checkbox_checkmark"></span>
                      <span className="checkbox_label">My Account Login</span>
                    </label>
                  </div>
                  <div
                    className={`integration-item ${
                      store?.integrations?.woocommerce &&
                      store.integrations.woocommerce.toString()
                        ? ""
                        : "disable_item"
                    }`}
                  >
                    <label htmlFor="wc_lost_password">
                      <input
                        type="checkbox"
                        name="wc_lost_password"
                        id="wc_lost_password"
                        checked={store?.fields?.woocommerce?.includes(
                          "wc_lost_password"
                        )}
                        disabled={
                          !toBoolean(
                            store?.integrations?.woocommerce &&
                              store?.integrations?.woocommerce.toString()
                          )
                        }
                        onClick={(e) => handleSingleIntegration(e)}
                      />{" "}
                      <span className="checkbox_checkmark"></span>
                      <span className="checkbox_label">
                        WooCommerce Lost/Reset Password
                      </span>
                    </label>
                  </div>
                  <div
                    className={`integration-item ${
                      store?.integrations?.woocommerce &&
                      store.integrations.woocommerce.toString()
                        ? ""
                        : "disable_item"
                    }`}
                  >
                    <label htmlFor="wc_checkout">
                      <input
                        type="checkbox"
                        name="wc_checkout"
                        id="wc_checkout"
                        checked={store?.fields?.woocommerce?.includes(
                          "wc_checkout"
                        )}
                        disabled={
                          !toBoolean(
                            store?.integrations?.woocommerce &&
                              store?.integrations?.woocommerce.toString()
                          )
                        }
                        onClick={(e) => handleSingleIntegration(e)}
                      />{" "}
                      <span className="checkbox_checkmark"></span>
                      <span className="checkbox_label">
                        WooCommerce Checkout
                      </span>
                    </label>
                    {/* ECT Location for WooCommerce Checkout */}
                    {store?.fields?.woocommerce?.includes("wc_checkout") && (
                      <>
                        <div className="widget-placement">
                          {installed &&
                            loaded &&
                            toBoolean(
                              store?.integrations?.woocommerce &&
                                store.integrations.woocommerce.toString()
                            ) && (
                              <>
                                <p className="feature-status">
                                  Widget Location
                                  <span
                                    className="tooltip_icon"
                                    data-tip="Determines the ECT widget location in WooCommerce Checkout page"
                                    data-for="widget-location"
                                  >
                                    {Icons.tooltip_icon}
                                    <ReactTooltip
                                      id="widget-location"
                                      effect="solid"
                                      place="right"
                                      class="tooltip-container"
                                    />
                                  </span>
                                </p>
                                <select
                                  name="ectplacement"
                                  value={placement}
                                  onChange={handleSelectChange}
                                >
                                  <option value="before_payment">
                                    Before Payment
                                  </option>
                                  <option value="after_payment">
                                    After Payment
                                  </option>
                                  <option value="before_pay">
                                    Before Pay Button
                                  </option>
                                  <option value="before_billing">
                                    Before Billing
                                  </option>
                                  <option value="after_billing">
                                    After Billing
                                  </option>
                                </select>
                              </>
                            )}
                        </div>
                      </>
                    )}
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
            <label className="switch" htmlFor="woocommerce">
              <input
                type="checkbox"
                name="woocommerce"
                id="woocommerce"
                onClick={(e) => handleStatus(e)}
                checked={toBoolean(
                  store?.integrations?.woocommerce &&
                    store?.integrations?.woocommerce.toString()
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

export default WooCommerce;
