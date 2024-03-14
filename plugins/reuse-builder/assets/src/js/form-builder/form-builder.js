import React from 'react';
import { render } from 'react-dom';
import { Provider } from 'react-redux';
// import { DevTools } from './../components';
import Store from './builderStore';
import BuilderFields from './builder-container/fields/builderFields';
import BuilderPanel from './builder-container/panel/panelIndex';
const formBuilderOptions = [
  {
    root: 'reuse_form_builder',
    output: 'reuse_form_builder_data',
    preValue: REUSEB_ADMIN.UPDATED_FORM_BUILDER,
  },
  {
    root: 'reuseb_term_meta_builder_metabox_form_builder',
    output: 'reuseb_term_meta_builder_metabox_output',
    preValue: REUSEB_ADMIN.UPDATED_TERM_META,
    hideSettings: false,
  },
  {
    root: 'reuseb_metabox_builder_form_builder',
    output: 'reuseb_metabox_builder_output',
    preValue: REUSEB_ADMIN.UPDATED_METABOX,
    hideSettings: false,
  },
];

const conditionBuilderOptions = [
  {
    root: 'condition-builder',
    output: '',
    panelMode: 'logic',
  },
  {
    root: 'condition-builder',
    output: '',
    panelMode: 'logic',
  },
  {
    root: 'condition-builder',
    output: '',
    panelMode: 'logic',
  },
];

formBuilderOptions.forEach(option => {
  const documentRoot = document.getElementById(option.root);
  if (documentRoot) {
    render(
      <Provider store={Store}>
        <div className="scwpFormBuilderMainWrapper">
          <BuilderFields />
          <BuilderPanel {...option} />
        </div>
      </Provider>,
      documentRoot
    );
  }
});

conditionBuilderOptions.forEach(option => {
  const documentRoot = document.getElementById(option.root);
  if (documentRoot) {
    render(
      <Provider store={Store}>
        <div className="scwpFormBuilderMainWrapper">
          <BuilderFields {...option} />
          <BuilderPanel {...option} />
        </div>
      </Provider>,
      documentRoot
    );
  }
});
