const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/up-sells')
    .controls(json)
    .css(Style)
    .register()