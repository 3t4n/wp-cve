import React, { FC, useState, useEffect } from "react";
import Title from "../../core/Title";
import ReactTooltip from "react-tooltip";

import {
  toBoolean,
  isGravityFormsInstalled,
  isGravityFormsLoaded,
  gravityECTPlacement,
  gravityDisableIds,
  getAjaxNonce,
  getNonce,
} from "../../Helpers";

import Icons from "../../icons";
import GForm from "../../images/gravityforms.png";

type Props = {
  store: any;
  setStore: any;
};

const GravityForms: FC<Props> = ({ store, setStore }) => {
  const [installed, setInstall] = useState<boolean>(isGravityFormsInstalled());
  const [placement, setPlacement] = useState<string>(gravityECTPlacement());
  const [ids, setIds] = useState<string>(gravityDisableIds());
  const [loaded, setLoad] = useState<boolean>(isGravityFormsLoaded());
  const [loader, setLoader] = useState<boolean>(false);
  const [collapse, setCollapse] = useState<boolean>(
    toBoolean(localStorage.getItem("ect_gravityforms_integration_collapse"))
  );

  const handleCollapse = (value) => {
    setCollapse(value);
    localStorage.setItem("ect_gravityforms_integration_collapse", value);
  };

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
    const link = "https://www.gravityforms.com/";
    window.open(link, "_blank");

    wp.ajax.send("wp_ajax_install_plugin", {
      data: {
        _ajax_nonce: getAjaxNonce(),
        slug: "gravityforms",
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
        slug: "gravityforms",
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

  const handleSelectChange = (e) => {
    setPlacement(e.target.value);
    localStorage.setItem("gravityformPlacement", e.target.value);
    wp.ajax.send("ect_placement", {
      data: {
        nonce: getNonce(),
        selected_option: e.target.value,
        form: "gravityforms",
      },
      success(response) {
        console.log(response);
      },
      error(err) {
        console.error(err);
      },
    });
  };

  const handlegravityDisableIds = (e) => {
    setIds(e.target.value);
    localStorage.setItem("disabled_ids", e.target.value);
    wp.ajax.send("ect_disabled_ids", {
      data: {
        nonce: getNonce(),
        disabled_ids: e.target.value,
        form: "gravityforms",
      },
      success(response) {
        console.log(response);
      },
      error(err) {
        console.error(err);
      },
    });
  };

  useEffect(() => {
    const storedOption = localStorage.getItem("gravityformPlacement");
    const disabledIds = localStorage.getItem("disabled_ids");
    if (storedOption) {
      setPlacement(storedOption);
    }
    if (disabledIds) {
      setIds(disabledIds);
    }
  }, [placement, ids]);
  return (
    <>
      <div className="integration_media">
        <img src={GForm} alt="Gravity Form Logo" />
      </div>
      <div className="integration_content">
        <div className="left_content">
          <Title>
            <h3>Gravity Forms</h3>
          </Title>

          {!installed && (
            <div className="active_install_wrapper">
              <div className="info_message">
                <span>{Icons.info_icon}</span>
                <span>Please get the Gravity Form first!</span>
              </div>
            </div>
          )}

          {installed && !loaded && (
            <div className="active_install_wrapper">
              <div className="info_message">
                <span>{Icons.info_icon}</span>
                <span>Please activate Gravity Form first!</span>
              </div>
            </div>
          )}

          {installed &&
            loaded &&
            toBoolean(
              store?.integrations?.gravityforms &&
                store.integrations.gravityforms.toString()
            ) && (
              <>
                {collapse ? (
                  <>
                    <label htmlFor="widget-location" className="ect-form-label">
                      <p className="feature-status">
                        Widget Location{" "}
                        <span
                          className="tooltip_icon"
                          data-tip="Make the Turnstile Widget Before or After Submit Button"
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
                      <div className="widget-placement">
                        {installed &&
                          loaded &&
                          toBoolean(
                            store?.integrations?.gravityforms &&
                              store.integrations.gravityforms.toString()
                          ) &&
                          collapse && (
                            <>
                              <select
                                name="ectplacement"
                                value={placement}
                                onChange={handleSelectChange}
                              >
                                <option value="before">Before</option>
                                <option value="after">After</option>
                              </select>
                            </>
                          )}
                      </div>
                    </label>

                    <label htmlFor="disabled_ids" className="ect-form-label">
                      <p className="feature-status">
                        Disabled Form Ids{" "}
                        <span
                          className="tooltip_icon"
                          data-tip="if you want to DISABLE the Turnstile widget on certain
                      forms, enter the Form ID in the Field, for example: 2,5"
                          data-for="disabled-id"
                        >
                          {Icons.tooltip_icon}
                          <ReactTooltip
                            id="disabled-id"
                            effect="solid"
                            place="right"
                            class="tooltip-container"
                          />
                        </span>
                      </p>
                      <input
                        type="text"
                        className="disabled-id-input"
                        value={ids}
                        onChange={handlegravityDisableIds}
                      />
                    </label>
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
              </>
            )}
        </div>

        <div className="right_content">
          {installed && loaded && (
            <>
              <label className="switch" htmlFor="gravityforms">
                <input
                  type="checkbox"
                  name="gravityforms"
                  id="gravityforms"
                  onClick={(e) => handleStatus(e)}
                  checked={toBoolean(
                    store?.integrations?.gravityforms &&
                      store?.integrations?.gravityforms.toString()
                  )}
                />
                <span className="slider round"></span>
              </label>{" "}
            </>
          )}
          {!installed && (
            <div onClick={(e) => handlePluginInstall(e)}>
              <button className="ect-install-button">Get the plugin</button>
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

export default GravityForms;
