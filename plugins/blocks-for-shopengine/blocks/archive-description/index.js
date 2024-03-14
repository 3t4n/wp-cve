const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/archive-description')
    .controls(json)
    .css(Style)
    .register()