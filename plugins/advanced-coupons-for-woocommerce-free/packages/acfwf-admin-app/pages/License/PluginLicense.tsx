// #region [Imports] ===================================================================================================

// Libraries
import React from 'react';

// #endregion [Imports]

// #region [Variables] =================================================================================================

declare var acfwAdminApp: any;
declare var acfwpElements: any;

const { LicensePremium } = acfwpElements;
const is_acfwp_active = parseInt(acfwpElements.is_acfwp_active);

// #endregion [Variables]

// #region [Component] =================================================================================================

const PluginLicense = () => {
  const {
    license_page: { title, desc, feature_comparison, license_status, content, specs },
  } = acfwAdminApp;

  if (is_acfwp_active) {
    return <LicensePremium />;
  }

  return (
    <div className="acfw-license-box">
      <div className="overview">
        <h1>{title}</h1>
        <p>{desc}</p>
        <a
          className="action-button feature-comparison"
          href={feature_comparison.link}
          target="_blank"
          rel="noopener noreferrer"
        >
          {feature_comparison.text}
        </a>
      </div>
      <div className="license-info">
        <div className="heading">
          <div className="left">
            <span>{license_status.label}</span>
          </div>
          <div className="right">
            <a
              className="action-button upgrade-premium"
              href={license_status.link}
              target="_blank"
              rel="noopener noreferrer"
            >
              {license_status.text}
            </a>
          </div>
        </div>

        <div className="content">
          <h2>{content.title}</h2>
          <p>{content.text}</p>

          <table className="license-specs">
            <thead>
              <tr>
                {specs.map((s: any) => (
                  <th key={s.label}>{s.label}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              <tr>
                {specs.map((s: any) => (
                  <td key={s.value}>{s.value}</td>
                ))}
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};

export default PluginLicense;

// #endregion [Component]
