function NativeRentAdmin_insertChange(insertSelect, autoSelectorName) {
	var autoSelectorSelect = document.querySelectorAll('select[name="' + autoSelectorName + '"]')[0]
	var optionsTexts = []
	switch (insertSelect.value) {
		case 'before':
			optionsTexts['firstParagraph'] = 'первым абзацем (p)'
			optionsTexts['middleParagraph'] = 'средним абзацем (p)'
			optionsTexts['lastParagraph'] = 'последним абзацем (p)'
			optionsTexts['firstTitle'] = 'первым заголовком (h2)'
			optionsTexts['middleTitle'] = 'средним заголовком (h2)'
			optionsTexts['lastTitle'] = 'последним заголовком (h2)'
			optionsTexts[''] = '(задать свой селектор)'
			break

		case 'after':
			optionsTexts['firstParagraph'] = 'первого абзаца (p)'
			optionsTexts['middleParagraph'] = 'среднего абзаца (p)'
			optionsTexts['lastParagraph'] = 'последнего абзаца (p)'
			optionsTexts['firstTitle'] = 'первого заголовка (h2)'
			optionsTexts['middleTitle'] = 'среднего заголовка (h2)'
			optionsTexts['lastTitle'] = 'последнего заголовка (h2)'
			optionsTexts[''] = '(задать свой селектор)'
			break
	}

	var options = autoSelectorSelect.getElementsByTagName('option')
	for (var i = 0; i < options.length; i++) {
		options[i].innerText = optionsTexts[options[i].value]
	}
}

function NativeRentAdmin_autoSelectorChanged(autoSelectorSelect, customSelectorName) {
	var customSelectorInput = document.querySelectorAll('input[name="' + customSelectorName + '"]')[0]
	customSelectorInput.style.display = (autoSelectorSelect.value == '' ? '' : 'none');
}

function NativeRentAdmin_updateSelectors() {
	var NativeRentAdmin_insertChangeSelects = document.getElementsByClassName('NativeRentAdmin_insertChange')
	var NativeRentAdmin_autoSelectorSelects = document.getElementsByClassName('NativeRentAdmin_autoSelector')
	for (var i = 0; i < NativeRentAdmin_insertChangeSelects.length; i++) {
		NativeRentAdmin_insertChangeSelects[i].onchange()
		NativeRentAdmin_autoSelectorSelects[i].onchange()
	}
}

function NativeRentAdmin_submitEnable(formElement) {
	formElement.querySelector('input[type="submit"]').disabled = false
	if (formElement.querySelector('#NativeRentAdmin_dropSiteCacheContainer input:checked')) {
		formElement.querySelector('#NativeRentAdmin_dropSiteCacheContainer').style.visibility = 'visible'
	}
}

function NativeRentAdmin_dropMainJSCache() {
	document.getElementById('NativeRentAdmin_dropMainJSCache').submit()
}

window.onload = function () {
	var settingsForm = document.getElementById('NativeRentAdmin_settingsForm')
	var ntgbFallbackCodeTextArea = document.getElementById('NativeRentAdmin_fallbackCodeTextArea')
	if (ntgbFallbackCodeTextArea) {
		ntgbFallbackCodeTextArea.addEventListener('keyup', function () {
			NativeRentAdmin_submitEnable(settingsForm);
		});
	}

	var ntgbNumInput = document.getElementById('NativeRentAdmin_ntgbUnitsNum')
	var ntgbUnitConfigs = document.querySelectorAll('.NativeRentAdmin_settings-section .ntgb-config-item')
	if (ntgbNumInput) {
		ntgbNumInput.addEventListener('change', function () {
			var label = this.parentNode.getElementsByClassName('_label')
			if (label.length > 0) {
				label[0].textContent = label[0].textContent.replace(
					this.value < 2 ? 'блока ' : 'блок ',
					this.value < 2 ? 'блок ' : 'блока '
				)
			}

			for (var i = 0; i < ntgbUnitConfigs.length; i++) {
				var unit = ntgbUnitConfigs[i]
				var unitNum = parseInt(unit.getAttribute('data-unit-num'))
				var value = parseInt(this.value)
				var inactiveInput = unit.querySelector('.ntgb-config-item-inactive-input')
				if (unitNum > value) {
					unit.classList.add('ntgb-config-item-inactive')
					if (inactiveInput) {
						inactiveInput.value = 1
					}
				} else {
					unit.classList.remove('ntgb-config-item-inactive')
					if (inactiveInput) {
						inactiveInput.value = 0
					}
				}
			}
		});
	}
}
