/**
 * Let A Pro Do IT! Easy Dev Communication
 *
 * @package		tspedev
 * @filename	easy-dev-comm.js
 * @version		1.0.0
 * @author		Sharron Denice, Let A Pro Do IT! (www.letaprodoit.com)
 * @copyright	Copyright 2018 SLet A Pro Do IT! (www.letaprodoit.com). All rights reserved
 * @license		APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 *
 */
function getNativeWindow() {
    return window;
}

getNativeWindow().addEventListener('message', function (event) {
    if (event.origin !== 'null') {
        if ((event.origin !== this.window.location.origin)) {
            var obj = JSON.parse(event.data);

            if (TSPED_DEBUG)
                console.log('Message Received: ', event);

            if (event.origin == 'https://www.abcdomain.com')
            {
                // TODO: Process Results
            }
        }
    }
});