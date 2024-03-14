import React from 'react';

export default function renderPanelHeader(viewMode, panelMode, changeView, showLogic, conditionName, hideSettings) {
  switch(viewMode) {
    case 'add_field':
      if (panelMode !== 'logic')
        return(<div className="scwpPanelHeadingWrapper">
          <a
            className="scwpLogicBuildingBtn"
          >
            Add Field
          </a>
        </div>);
    case 'edit_field':
      if (panelMode !== 'logic')
        return(<div className="scwpPanelHeadingWrapper">
          <a
            className="scwpLogicBuildingBtn"
          >
            Edit Field
          </a>
        </div>);
    default:
      const changeViewPanel = (newViewMode) => {
        changeView(newViewMode);
      }
      return(<div>
        { panelMode ? <div className="scwpPanelHeadingWrapper scwpLogicPanelHeading">
          <a
            className={viewMode === 'logic' ? "scwpLogicBuildingBtn activePanel" : 'scwpLogicBuildingBtn' }
          >
            { conditionName }
          </a> </div> :
          <div className="scwpPanelHeadingWrapper">
            <a
              className={viewMode === 'preview' ? "scwpPreviewBtn activePanel" : 'scwpPreviewBtn' }
              onClick={changeView.bind(this, 'preview')}
            >
              Preview
            </a>
            {hideSettings !== false ? <a
              className={viewMode === 'globalSettings' ? "scwpSettingsBtn activePanel" : 'scwpSettingsBtn' }
                onClick={changeView.bind(this, 'globalSettings')}
              >
                Settings
              </a> : null}
          </div>
        }
      </div>);
  }
}
