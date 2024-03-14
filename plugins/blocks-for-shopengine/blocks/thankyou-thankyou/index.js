const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/thankyou-thankyou')
    .controls(json)
    .css(Style)
    .register()