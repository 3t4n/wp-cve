import React, { FC, useState, useEffect } from "react";
import {
  toBoolean,
  isFormidableFormsLoaded,
  isFormidableFormsInstalled,
  formidableECTPlacement,
  formidableDisableIds,
  getNonce,
  getAjaxNonce,
} from "./../../Helpers";
import Title from "./../../core/Title";

import Icons from "../../icons";
import FormidableImage from "../../images/formidable-form.png";
import ReactTooltip from "react-tooltip";

type Props = {
  store: any;
  setStore: any;
};

const Formidable: FC<Props> = ({ store, setStore }) => {
  const [installed, setInstall] = useState<boolean>(
    isFormidableFormsInstalled()
  );
  const [placement, setPlacement] = useState<string>(formidableECTPlacement());
  const [ids, setIds] = useState<string>(formidableDisableIds());
  const [loaded, setLoad] = useState<boolean>(isFormidableFormsLoaded());
  const [loader, setLoader] = useState<boolean>(false);
  const [collapse, setCollapse] = useState<boolean>(
    toBoolean(localStorage.getItem("ect_formidable_integration_collapse"))
  );

  const handleCollapse = (value) => {
    setCollapse(value);
    localStorage.setItem("ect_formidable_integration_collapse", value);
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
    e.preventDefault();

    setLoader(true);

    wp.ajax.send("wp_ajax_install_plugin", {
      data: {
        _ajax_nonce: getAjaxNonce(),
        slug: "formidable",
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
        slug: "formidable",
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
    localStorage.setItem("formidablePlacement", e.target.value);
    wp.ajax.send("ect_placement", {
      data: {
        nonce: getNonce(),
        selected_option: e.target.value,
        form: "formidable",
      },
      success(response) {
        console.log(response);
      },
      error(err) {
        console.error(err);
      },
    });
  };

  const handleformidableDisableIds = (e) => {
    setIds(e.target.value);
    localStorage.setItem("formidable_disabled_ids", e.target.value);
    wp.ajax.send("ect_disabled_ids", {
      data: {
        nonce: getNonce(),
        disabled_ids: e.target.value,
        form: "formidable",
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
    const storedOption = localStorage.getItem("formidablePlacement");
    const disabledIds = localStorage.getItem("formidable_disabled_ids");
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
        <img src={FormidableImage} alt="WP Forms Logo" />
      </div>
      <div className="integration_content">
        <div className="left_content">
          <Title>
            <h3>Formidable Forms</h3>
          </Title>
          {!installed && (
            <div className="active_install_wrapper">
              <div className="info_message">
                <span>{Icons.info_icon}</span>
                <span>Please install Formidable Form first!</span>
              </div>
            </div>
          )}

          {installed && !loaded && (
            <div className="active_install_wrapper">
              <div className="info_message">
                <span>{Icons.info_icon}</span>
                <span>Please activate Formidable Form first!</span>
              </div>
            </div>
          )}

          {installed &&
            loaded &&
            toBoolean(
              store?.integrations?.formidable &&
                store.integrations.formidable.toString()
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
                            store?.integrations?.formidable &&
                              store.integrations.formidable.toString()
                          ) &&
                          collapse && (
                            <div className="gravity-right">
                              <select
                                name="ectplacement"
                                value={placement}
                                onChange={handleSelectChange}
                              >
                                <option value="before">Before</option>
                                <option value="after">After</option>
                              </select>
                            </div>
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
                          data-for="disabled-ids"
                        >
                          {Icons.tooltip_icon}
                          <ReactTooltip
                            id="disabled-ids"
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
                        onChange={handleformidableDisableIds}
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

          {installed && loaded && (
            <>
              <label className="switch" htmlFor="formidable">
                <input
                  type="checkbox"
                  name="formidable"
                  id="formidable"
                  onClick={(e) => handleStatus(e)}
                  checked={toBoolean(
                    store?.integrations?.formidable &&
                      store?.integrations?.formidable.toString()
                  )}
                />
                <span className="slider round"></span>
              </label>{" "}
            </>
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

export default Formidable;
