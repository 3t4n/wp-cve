const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/currency-switcher')
    .controls(json)
    .css(Style)
    .register()