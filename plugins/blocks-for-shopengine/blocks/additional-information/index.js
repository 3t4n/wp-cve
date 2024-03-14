const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/additional-information')
    .controls(json)
    .css(Style)
    .register()