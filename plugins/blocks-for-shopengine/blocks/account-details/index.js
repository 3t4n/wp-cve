const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager("gutenova/account-details")
    .controls(json)
    .css(Style)
    .register()