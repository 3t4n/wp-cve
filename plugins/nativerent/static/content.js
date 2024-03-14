(function () {
	/**
	 * @typedef {Object} AdUnit
	 * @property {string} type
	 * @property {string|undefined} unitId
	 * @property {string} insert
	 * @property {string} autoSelector
	 * @property {string} selector
	 * @property {Object} settings
	 * @property {Element|undefined} element
	 */
	var LOADING_TIMEOUT_MS = 10000
	var EVENT_ERROR_LOADING_SCRIPT = 'error_loading_script'
	var verbose = /[\?\&]verbose([\?|\&|\#]|$)/.test(window.location.search)
	var checkTimeout = true
	var logger = {
		log: function () {
			console.log.apply(null, arguments)
		},
		debug: function () {
			if (verbose) {
				console.log.apply(null, arguments)
			}
		},
		warn: function () {
			console.warn.apply(null, arguments)
		},
		error: function () {
			console.error.apply(null, arguments)
		}
	}

	/**
	 * @param {Element} el
	 * @return {number}
	 */
	function calcOffsetTop(el) {
		return (el.getBoundingClientRect().top + window.pageYOffset)
	}

	function contentIntegration() {
		if (window.NRentContentIntegrated === true) {
			return false
		}

		var contentBlock = Array.from(window.document.querySelectorAll('.nativerent-content-integration')).pop()
		if (contentBlock == null) {
			return false
		}

		window.NRentContentIntegrated = true
		var article = contentBlock.parentNode
		var articleWidth = article.clientWidth
		var minDistance = (articleWidth < 500 ? 150 : 100)
		var minDistanceNTGB = 500;
		var paragraphs = article.querySelectorAll('p')
		var titles = article.querySelectorAll('h2')
		var insertedUnits = {}
		var unitsToCheckDistance = {
			regular: {},
			ntgb: {}
		}
		var ntgbUnitsCount = 0;

		for (var i = 0; i < window.NRentAdUnits.length; i++) {
			try {
				/** @type {AdUnit} **/
				var adUnit = window.NRentAdUnits[i]
				var anchor = undefined
				var dataPlacementAttr = true

				switch (adUnit.autoSelector) {
					case 'firstParagraph': {
						anchor = paragraphs[0]
						break
					}
					case 'middleParagraph': {
						anchor = paragraphs[Math.floor(paragraphs.length / 2)]
						break
					}
					case 'lastParagraph': {
						anchor = paragraphs[paragraphs.length - 1]
						break
					}
					case 'firstTitle': {
						anchor = titles[0]
						break
					}
					case 'middleTitle': {
						anchor = titles[Math.floor(titles.length / 2)]
						break
					}
					case 'lastTitle': {
						anchor = titles[titles.length - 1]
						break
					}
					case 'body': {
						anchor = window.document.body
						dataPlacementAttr = false
						break
					}
					default: {
						try {
							dataPlacementAttr = false
							var customSelector = decodeURIComponent(adUnit.selector)
							if (!/^body[^a-z0-9]/i.test(customSelector)) {
								anchor = article.querySelector(customSelector)
							} else {
								anchor = window.document.querySelector(customSelector)
							}
						} catch (error) {
							logger.error(error)
							logger.warn(
								'NRentPlugin: Anchor for adUnit \'' + adUnit.type + '\' was not found by selector \'' +
								adUnit.selector + '\'',
							)
							continue
						}
						break
					}
				}
				logger.debug("NRentPlugin: placement info", adUnit, anchor)

				if (anchor == null || typeof anchor !== 'object') {
					logger.warn(
						'NRentPlugin: Anchor for adUnit \'' + adUnit.type +
						'\' was not found by selector \'' + adUnit.selector + '\'',
					)
					continue
				}

				adUnit.element = document.createElement('div')
				adUnit.element.id = 'NRent-' +
					(window.NRentCounter.options || window.NRentCounter || {id: 'undefined'}).id + '-' + i

				if (dataPlacementAttr) {
					adUnit.element.setAttribute(
						'data-placement',
						(typeof adUnit.insert === 'string' && adUnit.insert !== '' ? adUnit.insert : 'after')
					)
				}

				switch (adUnit.insert) {
					case 'after': {
						if (anchor.nextSibling == null) {
							anchor.parentNode.appendChild(document.createElement('meta'))
						}
						anchor = anchor.nextSibling
						anchor.parentNode.insertBefore(adUnit.element, anchor)
						break
					}
					case 'inside': {
						anchor.appendChild(adUnit.element)
						break
					}
					case 'before':
					default: {
						anchor.parentNode.insertBefore(adUnit.element, anchor)
						break
					}
				}

				insertedUnits[adUnit.element.id] = adUnit
				if (adUnit.type === 'ntgb') {
					++ntgbUnitsCount
					if (adUnit.unitId === undefined) {
						adUnit.unitId = ntgbUnitsCount
					}
					adUnit.element.setAttribute('data-nrent-ntgb-id', adUnit.unitId)
					unitsToCheckDistance.ntgb[adUnit.element.id] = adUnit
				} else if (i < 3) {
					unitsToCheckDistance.regular[adUnit.element.id] = adUnit
				}

			} catch (error) {
				logger.error(error)
			}
		}

		try {
			logger.debug('NRentPlugin: Units to check distance', unitsToCheckDistance)
			checkDistanceOfGroupUnits(unitsToCheckDistance.regular, insertedUnits, minDistance)
			checkDistanceOfGroupUnits(unitsToCheckDistance.ntgb, insertedUnits, minDistanceNTGB)
			logger.debug('NRentPlugin: Units with valid distance', unitsToCheckDistance)
		} catch (error) {
			logger.error(error)
		}

		logger.debug('NRentPlugin: Init inserted ad-units', insertedUnits)
		initInsertedAdUnits(insertedUnits)
	}

	/**
	 * @param {Object<string, AdUnit>} insertedUnits
	 */
	function initInsertedAdUnits(insertedUnits) {
		for (var id in insertedUnits) {
			var adUnit = insertedUnits[id]
			if ('ntgb' === adUnit.type) {
				(window.NtgbManager = window.NtgbManager || []).push({
					renderTo: adUnit.element.id,
					position: 'ntgb',
					place: 'place-' + adUnit.unitId
				})
			} else {
				(window.NRentManager = window.NRentManager || []).push({
					renderTo: adUnit.element.id,
					position: adUnit.type,
					settings: adUnit.settings
				})
			}
		}
	}

	/**
	 * @param {Object<string, AdUnit>} unitsMap
	 * @param {Object<string, AdUnit>} insertedUnits
	 * @param {number} minDistance
	 */
	function checkDistanceOfGroupUnits(unitsMap, insertedUnits, minDistance) {
		var ids = Object.keys(unitsMap)
		if (ids.length < 2) {
			return
		}
		var selector = ids
			.map(function (id) {
				return '#' + id
			})
			.join(', ')
		var foundElems = document.querySelectorAll(selector)
		logger.debug('NRentPlugin: Found elements to check distance by selector `' + selector + '`', foundElems)
		for (var i = 0; i < foundElems.length; i++) {
			if (i === 0) {
				continue
			}
			if (!checkDistance(foundElems[i], foundElems[i - 1], minDistance)) {
				if (insertedUnits.hasOwnProperty(foundElems[i].id)) {
					delete insertedUnits[foundElems[i].id]
				}
				delete unitsMap[foundElems[i].id]
				checkDistanceOfGroupUnits(unitsMap, insertedUnits, minDistance)
			}
		}
	}

	/**
	 * @param {Element} element
	 * @param {Element} prevElement
	 * @param {number} minDistance
	 *
	 * @return {Boolean}
	 */
	function checkDistance(element, prevElement, minDistance) {
		if (prevElement === undefined) {
			logger.error('NRentPlugin: Failed to calculate distance between elements', element, prevElement)
			return false;
		}
		var distance = (calcOffsetTop(element) - calcOffsetTop(prevElement))
		if (window.document.body.clientHeight > minDistance && distance < minDistance) {
			logger.warn(
				'NRentPlugin: AdUnit element `#' + element.id + '` is too close ' +
				'to adUnit element `#' + prevElement.id + '` (distance = ' + distance + ')',
				element, prevElement
			)
			return false;
		}

		return true;
	}

	function cloneScriptElement(script) {
		var scriptClone = window.document.createElement('script')
		if (script.innerHTML) {
			scriptClone.insertAdjacentText('afterbegin', script.innerHTML)
		}
		if (script.onload) {
			scriptClone.onload = script.onload
		}
		if (script.hasAttributes()) {
			var attrs = script.attributes
			for (var i = 0; i < attrs.length; i++) {
				var attr = attrs[i]
				if (attr !== undefined) {
					if (
						attr.name === 'type' &&
						attr.value === 'rocketlazyloadscript' &&
						window.NRentRocketDOMContentLoaded
					) {
						logger.log('Removed attr `type="rocketlazyloadscript"`', script)
						continue
					}
					if (
						attr.name === 'data-rocket-src' &&
						attr.value !== '' &&
						window.NRentRocketDOMContentLoaded
					) {
						scriptClone.setAttribute('src', attr.value)
						if (attrs.getNamedItem('src') !== null) {
							attrs.removeNamedItem('src')
						}
						logger.log('Replaced attr `data-rocket-src` to `src`', scriptClone)
						continue
					}

					scriptClone.setAttribute(attr.name, attr.value)
				}
			}
		}
		return scriptClone
	}

	function unblockElements() {
		var blocked = window.document.querySelectorAll('meta[property="nativerent-block"]')
		logger.debug('NRentPlugin: Found blocked elements', blocked)

		for (var i = 0; i < blocked.length; i++) {
			var blockedElement = blocked[i]
			try {
				if (blockedElement.content.length) {
					var tpl = window.document.createElement('div')
					tpl.insertAdjacentHTML('afterbegin', atob(blockedElement.content))
					for (var ci = 0; ci < tpl.childNodes.length; ci++) {
						var newNode = tpl.childNodes[ci]
						if (newNode.nodeName.toLowerCase() === 'script') {
							newNode = cloneScriptElement(newNode)
						}
						var inserted = blockedElement.insertAdjacentElement('afterend', newNode)
						if (inserted) {
							logger.debug('NRentPlugin: Unblocked element', inserted)
							blockedElement.remove()
						} else {
							logger.warn('NRentPlugin: Failed to unblock element', blockedElement)
						}
					}
				}
			} catch (e) {
				logger.error(e)
				logger.error('NRentPlugin: Failed to unblock element', blockedElement)
			}
		}
	}

	function errorLoadingScriptHandler() {
		logger.warn('NRentPlugin: Error loading nativerent script. Unblock blocked elements...')
		unblockElements()
		checkTimeout = false
	}

	function NRentPlugin(queued) {
		queued = (queued || [])
		for (var i = 0; i < queued.length; i++) {
			this.push(queued[i])
		}
	}

	NRentPlugin.prototype.push = function (event) {
		if (typeof event === 'string') {
			this.handleEvent(event)
			return
		}

		if (typeof event === 'object' && event.hasOwnProperty('name')) {
			this.handleEvent(event.name, event.hasOwnProperty('payload') ? event.payload : null)
		}
	}

	NRentPlugin.prototype.handleEvent = function (eventName, payload) {
		switch (eventName) {
			case EVENT_ERROR_LOADING_SCRIPT:
				errorLoadingScriptHandler()
				break
		}
	}

	NRentPlugin.prototype.getOptions = function () {
		return {
			verbose: verbose,
			loadingTimeout: LOADING_TIMEOUT_MS,
			checkTimeout: checkTimeout
		}
	}

	try {
		if (/complete|interactive|loaded/.test(window.document.readyState)) {
			contentIntegration()
		} else {
			window.document.addEventListener('DOMContentLoaded', contentIntegration)
		}
	} finally {
		window.NRentPlugin = new NRentPlugin(window.NRentPlugin || [])
		setTimeout(function () {
			logger.debug('NRentPlugin: Check timeout...')
			if (checkTimeout && !('NRentBlocker' in window)) {
				logger.warn('NRentPlugin: Timeout script loading. Sending a script loading error event...')
				window.NRentPlugin = (window.NRentPlugin || []).push(EVENT_ERROR_LOADING_SCRIPT)
			}
		}, LOADING_TIMEOUT_MS)
	}
})()
