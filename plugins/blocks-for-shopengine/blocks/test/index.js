const json = require('./controls.json')
const { blockManager } = gutenova

import {Screen} from './screen'
import {Style} from './style'

new blockManager(shopengine/test')
    .controls(json)
    .render(Screen)
    .css(Style)
    .register()