const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/view-single-product')
    .controls(json)
    .css(Style)
    .register()