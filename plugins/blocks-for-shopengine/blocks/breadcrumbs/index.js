const json = require('./controls.json')
const { blockManager } = gutenova

import { Style } from './style'

new blockManager('gutenova/breadcrumbs')
    .controls(json)
    .css(Style)
    .register()