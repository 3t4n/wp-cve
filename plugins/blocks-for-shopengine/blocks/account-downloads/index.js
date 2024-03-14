const json = require('./controls.json')
const { blockManager } = gutenova

import {Style} from './style'

new blockManager('gutenova/account-downloads')
    .controls(json)
    .css(Style)
    .register()