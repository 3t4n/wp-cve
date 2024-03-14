const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/comparison-button')
    .controls(json)
    .css(Style)
    .register()