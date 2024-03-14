const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/return-to-shop')
    .controls(json)
    .css(Style)
    .register()