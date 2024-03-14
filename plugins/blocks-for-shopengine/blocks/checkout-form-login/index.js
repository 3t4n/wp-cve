const json = require('./controls.json')
const {blockManager} = gutenova

import { Style } from './style'

new blockManager('gutenova/checkout-form-login')
    .controls(json)
    .css(Style)
    .register()