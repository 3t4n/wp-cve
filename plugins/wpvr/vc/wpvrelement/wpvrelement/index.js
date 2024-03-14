/* eslint-disable import/no-webpack-loader-syntax */
import { getService } from 'vc-cake'
import WpvrElement from './component'

const vcvAddElement = getService('cook').add

vcvAddElement(
  require('./settings.json'),
  // Component callback
  function (component) {
    component.add(WpvrElement)
  },
  // css settings // css for element
  {
    css: false,
    editorCss: require('raw-loader!./editor.css')
  }
)
