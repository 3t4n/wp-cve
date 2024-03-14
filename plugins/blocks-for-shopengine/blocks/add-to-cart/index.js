const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/add-to-cart')
    .controls(json)
    .css(Style)
    .register()