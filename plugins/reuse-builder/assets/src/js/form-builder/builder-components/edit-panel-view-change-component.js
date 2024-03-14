import React from 'react';
import Styles from './edit-panel-view-change-component.less';

export default function renderEditPanelHeader(viewMode, changeView, configField) {

  if (configField.showHeader === false) {
    return '';
  }
  return(<div className={Styles.scwpSettingsBtnWrapper}>
    {viewMode ?
      <a
        className={viewMode === 'appearance' ? `${Styles.scwpSettingBtn} ${Styles.activePanel}` : Styles.scwpSettingBtn }
        onClick={changeView.bind(this, 'appearance')}
      >
        Appearance
      </a> : ''
    }
    {configField.data ?
      <a
        className={viewMode === 'data' ? `${Styles.scwpSettingBtn} ${Styles.activePanel}` : Styles.scwpSettingBtn }
        onClick={changeView.bind(this, 'data')}
      >
        Data
      </a> : ''
    }
    {configField.validationRequire === false ? '' : <a
      className={viewMode === 'validation' ? `${Styles.scwpSettingBtn} ${Styles.activePanel}` : Styles.scwpSettingBtn }
      onClick={changeView.bind(this, 'validation')}
    >
      Validation
    </a>}
    {configField.preValueRequire === false ? '' : <a
      className={viewMode === 'preValue' ? `${Styles.scwpSettingBtn} ${Styles.activePanel}` : Styles.scwpSettingBtn }
      onClick={changeView.bind(this, 'preValue')}
    >
      Value
    </a>}
  </div>);
}
