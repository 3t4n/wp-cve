import React, { Component } from 'react';
import { render } from 'react-dom';

const ReuseForm = __REUSEFORM__;
const fields = REUSEB_ADMIN.fields;

export default class GeoboxPreview extends Component {
  constructor(props) {
    super(props);
    let preValue = {};
    try {
      preValue = REUSEB_ADMIN.GEOBOX_PREVIEW
        ? JSON.parse(REUSEB_ADMIN.GEOBOX_PREVIEW)
        : {};
    } catch (err) {
      console.log(err);
    }
    this.state = {
      preValue,
    };
  }
  render() {
    const { preValue } = this.state;
    const getUpdatedFields = data => {
      const newData = {};
      fields.forEach(field => {
        const id = field.id.replace('GeoboxPreview__', '');
        if (data[id] === undefined) {
          newData[id] = field.value;
        } else {
          newData[id] = data[id];
        }
      });
      document.getElementById('_reuseb_geobox_preview').value = JSON.stringify(
        newData
      );
    };

    const reuseFormOption = {
      reuseFormId: 'GeoboxPreview__',
      fields,
      getUpdatedFields,
      errorMessages: {},
      preValue,
    };
    return (
      <div>
        <ReuseForm {...reuseFormOption} />
      </div>
    );
  }
}

const documentRoot = document.getElementById('reuseb_geobox_preview');
if (documentRoot) {
  render(<GeoboxPreview />, documentRoot);
}
