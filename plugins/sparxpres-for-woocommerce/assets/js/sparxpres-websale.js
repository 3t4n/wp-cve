jQuery(document).ready(function ($) {
	const spxLoanCalcEngine = {
		debug: false,
		loanId: 0,
		prevCalculationPeriod: 0,
		prevCalculationPrice: 0,
		$webSaleElem: null,
		$periodElem: null,
		$rangeStepsElem: null,

		init: function () {
			const x = document.getElementById("sparxpres-compliance-text");
			if (x && x.textContent === '') {
				console.debug("Missing calculation, call calculation...");
				spxLoanCalcEngine.loanCalculation();
			}

			window.addEventListener("sparxpresPeriodChange", function (event) {
				if (spxLoanCalcEngine.debug) console.log("sparxpresPeriodChange event caught");
				spxLoanCalcEngine.loanCalculation(parseInt(event.detail.period));
			});

			window.addEventListener("sparxpresPeriodInput", function (event) {
				if (spxLoanCalcEngine.debug) console.log("sparxpresPeriodInput event caught");
				spxLoanCalcEngine._updateSliderBackground(
					parseInt(event.detail.period),
					parseInt(event.detail.min),
					parseInt(event.detail.max)
				);
			});

			window.addEventListener("sparxpresRuntimeRecalculate", function (event) {
				if (spxLoanCalcEngine.debug) console.log("sparxpresRuntimeRecalculate event caught");
				const _price = event.detail && event.detail.price ? parseInt(event.detail.price) : null;
				const _period = event.detail && event.detail.period ? parseInt(event.detail.period) : null;
				spxLoanCalcEngine.loanCalculation(_period, _price);
			});

			/**
			 * Add event listener for modal information page
			 */
			window.addEventListener("sparxpresInformationPageOpen", function (_event) {
				if (spxLoanCalcEngine.debug) console.log("sparxpresInformationPageOpen event caught..");
				let infoModal = $("#sparxpresInformationPageModal");
				if (!infoModal.length) {
					infoModal = $("<div></div>")
						.attr("id", "sparxpresInformationPageModal")
						.attr("class", "sparxpres-modal")
						.attr("role", "dialog")
						.attr("tabindex", "-1")
						.attr("aria-modal", "true");
					$("body").append(infoModal);
				}

				if (infoModal.length) {
					if (infoModal.is(":empty")) {
						if (spxLoanCalcEngine.debug) console.log("sparxpresInformationPage not loaded, load and show it..");
						infoModal.html("<div class=\"sparxpres-modal-content\">" +
							"<span class=\"sparxpres-modal-close\" onclick=\"document.getElementById('sparxpresInformationPageModal').style.display='none';\">&times;</span>" +
							"<div class=\"sparxpres-dynamic-content\"></div>" +
							"</div>");

						infoModal.show();
						spxLoanCalcEngine.loadPageInformation($("#sparxpres_web_sale").data("linkId"));
					} else {
						if (spxLoanCalcEngine.debug) console.log("sparxpresInformationPage already loaded, show it..");
						infoModal.show();
					}
				}
			});

			/**
			 * Add event listener for modal credit information page
			 */
			window.addEventListener("XpresPayInformationPageOpen", function(_event) {
				if (spxLoanCalcEngine.debug) console.log("XpresPayInformationPageOpen event caught..");
				let xpresPayModal = $("#XpresPayInformationPageModal");
				if (!xpresPayModal.length) {
					xpresPayModal = $("<div></div>")
						.attr("id", "XpresPayInformationPageModal")
						.attr("class", "sparxpres-modal")
						.attr("role", "dialog")
						.attr("tabindex", "-1")
						.attr("aria-modal", "true");
					$("body").append( xpresPayModal );
				}

				if (xpresPayModal.length) {
					if (xpresPayModal.is(":empty")) {
						if (spxLoanCalcEngine.debug) console.log("XpresPayInformationPageOpen not loaded, load and show it..");
						xpresPayModal.html("<div class=\"sparxpres-modal-content\">" +
							"<span class=\"sparxpres-modal-close\" onclick=\"document.getElementById('XpresPayInformationPageModal').style.display='none';\">&times;</span>" +
							"<div class=\"sparxpres-dynamic-content\"></div>" +
							"</div>");

						xpresPayModal.show();
						spxLoanCalcEngine._callRemoteXpresPayInformationPage( $("#sparxpres_web_sale").data("linkId") );
					} else {
						if (spxLoanCalcEngine.debug) console.log("XpresPayInformationPageOpen already loaded, show it..");
						xpresPayModal.show();
					}
				}
			});
		},

		loanCalculation: function (period = null, price = null) {
			let webSaleElem = spxLoanCalcEngine._getSparxpresWebSaleElement();
			if (!webSaleElem || !webSaleElem.getAttribute("data-link-id")) {
				if (spxLoanCalcEngine.debug) console.log("loanCalculation returning because element was not found or has no data attributes.");
				return;
			}

			if (spxLoanCalcEngine.prevCalculationPeriod === 0) spxLoanCalcEngine.prevCalculationPeriod = parseInt(webSaleElem.getAttribute("data-period")) || 0;
			if (spxLoanCalcEngine.prevCalculationPrice === 0) spxLoanCalcEngine.prevCalculationPrice = parseInt(webSaleElem.getAttribute("data-price")) || 0;
			if (spxLoanCalcEngine.loanId === 0) spxLoanCalcEngine.loanId = parseInt(webSaleElem.getAttribute("data-loan-id")) || 0;

			const linkId = webSaleElem.getAttribute("data-link-id");

			period = period || spxLoanCalcEngine.prevCalculationPeriod;
			price = price || spxLoanCalcEngine.prevCalculationPrice;
			if (spxLoanCalcEngine.debug) console.log("loanCalculation period is %d price is %d", period, price);

			spxLoanCalcEngine._callRemoteLoanCalculation(linkId, period, price);
		},

		loadPageInformation: function (linkId) {
			if (!linkId) return;

			$.getJSON("https://sparxpres.dk/app/webintegration/info/", {
				linkId: linkId
			}).done(function (pageInfo) {
				if (pageInfo && pageInfo.hasOwnProperty("html")) {
					$("#sparxpresInformationPageModal .sparxpres-dynamic-content").html(pageInfo.html);
				}
			});
		},

		_getSparxpresWebSaleElement: function () {
			spxLoanCalcEngine.$webSaleElem = spxLoanCalcEngine.$webSaleElem || document.getElementById("sparxpres_web_sale");
			return spxLoanCalcEngine.$webSaleElem;
		},

		_getSparxpresPeriodElement: function () {
			spxLoanCalcEngine.$periodElem = spxLoanCalcEngine.$periodElem || document.querySelector("#sparxpres_web_sale_period .sparxpres-slider") || document.querySelector("#sparxpres_web_sale_period .sparxpres-select");
			return spxLoanCalcEngine.$periodElem;
		},

		_getSparxpresRangeStepsElement: function () {
			spxLoanCalcEngine.$rangeStepsElem = spxLoanCalcEngine.$rangeStepsElem || document.querySelector("#sparxpres_web_sale_period .sparxpres-slider-steps");
			return spxLoanCalcEngine.$rangeStepsElem;
		},

		_callRemoteLoanCalculation: function (linkId, period, price) {
			$.getJSON("https://sparxpres.dk/app/loancalc/", {
				linkId: linkId,
				period: period,
				amount: price
			}).done(function (loanCalc) {
				if (loanCalc && loanCalc.hasOwnProperty("success")) {
					if (loanCalc.success === true) {
						spxLoanCalcEngine.prevCalculationPeriod = loanCalc.termsInMonths;
						spxLoanCalcEngine.prevCalculationPrice = loanCalc.loanAmount;

						if (loanCalc.loanId !== spxLoanCalcEngine.loanId) {
							spxLoanCalcEngine._callRemoteLoanInformation(linkId, loanCalc.termsInMonths, loanCalc.loanAmount);
						}

						$("#sparxpres-formatted-monthly-payments").text(loanCalc.formattedMonthlyPayments);
						$("#sparxpres-compliance-text").text(loanCalc.complianceText);
						$("#sparxpres-information-url").show();
					} else if (loanCalc.hasOwnProperty("errorMessage")) {
						$("#sparxpres-formatted-monthly-payments").text("N/A");
						$("#sparxpres-compliance-text").text(loanCalc.errorMessage);
						$("#sparxpres-information-url").hide();
					}
				} else {
					$("#sparxpres_web_sale").hide();
				}
			});
		},

		_callRemoteLoanInformation: function (linkId, period, price) {
			$.getJSON("https://sparxpres.dk/app/loaninfo/", {
				linkId: linkId,
				period: period,
				amount: price
			}).done(function (loanInfo) {
				if (loanInfo && loanInfo.hasOwnProperty("loanPeriods")) {
					spxLoanCalcEngine.loanId = loanInfo.loanId;
					spxLoanCalcEngine._updateDynamicRange(loanInfo.loanPeriods, period);
				}
			});
		},

		_callRemoteXpresPayInformationPage: function(linkId) {
			if (!linkId) return;

			$.getJSON("https://sparxpres.dk/app/xprespay/info/",{
				linkId: linkId
			}).done(function (xpresPayInfo) {
				if (xpresPayInfo && xpresPayInfo.hasOwnProperty("html")) {
					$("#XpresPayInformationPageModal .sparxpres-dynamic-content").html(xpresPayInfo.html);
				}
			});
		},

		_updateDynamicRange: function (loanPeriods = [], period = 0) {
			if (spxLoanCalcEngine.debug) console.log("updateSparxpresDynamicRange");
			const periodElem = spxLoanCalcEngine._getSparxpresPeriodElement();
			if (periodElem) {
				if (periodElem.classList.contains("sparxpres-slider")) {
					if (spxLoanCalcEngine.debug) console.log("Update slider range...");
					const min = loanPeriods[0].id;
					const max = loanPeriods[loanPeriods.length-1].id;

					periodElem.setAttribute("min", min);
					periodElem.setAttribute("max", max);
					periodElem.value = period;

					spxLoanCalcEngine._updateSliderBackground(period, min, max);
					spxLoanCalcEngine._updateRangeSteps(loanPeriods);
				} else if (periodElem.classList.contains("sparxpres-select")) {
					if (spxLoanCalcEngine.debug) console.log("Update dropdown range");
					periodElem.innerHTML = "";
					loanPeriods.forEach(itm => {
						periodElem.add(new Option(itm.text, itm.id, false, itm.id === period), null);
					});
				}
			}
		},

		_updateSliderBackground: function(value = 0, min = 0, max = 0) {
			const periodElem = spxLoanCalcEngine._getSparxpresPeriodElement();
			if (periodElem) {
				const pct = value === min ? 0 : (value - min) / (max - min) * 100;
				periodElem.style.setProperty("--sparxpres-slider-pct", pct + "%");
			}
		},

		_updateRangeSteps: function(loanPeriods = []) {
			const rangeStepsElem = spxLoanCalcEngine._getSparxpresRangeStepsElement();
			if (rangeStepsElem) {
				rangeStepsElem.innerHTML = "";
				loanPeriods.forEach(itm => {
					const divStep = document.createElement("div");
					divStep.className = "sparxpres-slider-step";
					divStep.textContent = itm.id;
					rangeStepsElem.appendChild(divStep);
				});
			}
		}
	};

	spxLoanCalcEngine.init();
});
