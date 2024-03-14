(function ($) {
	'use strict';
	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 */
	$(function () {
		String.prototype.capitalize = function () {
			return this.charAt(0).toUpperCase() + this.slice(1)
		};

		var weatherWidgets = $(".clima-widget");
		for(var i =0; i < weatherWidgets.length; i++) {
			var weatherWidget = weatherWidgets[i];
			if (weatherWidget) {
				var city = weatherWidget.getAttribute("data-city");
				var country = weatherWidget.getAttribute("data-country");
				var language = weatherWidget.getAttribute("data-language") || "spanish";
				var location_for_rest = city.replace(" ", "_") + "," + country;
				loadXMLDoc(weatherWidget, city, country, language, location_for_rest);
			}
		}
		function loadXMLDoc(weatherWidget, city, country, language, location_for_rest) {
			var xhr = new XMLHttpRequest();
			xhr.onload = function () {
				// Process our return data
				if (xhr.status >= 200 && xhr.status < 300) {
					// Runs when the request is successful
					handleResponse(JSON.parse(xhr.responseText), weatherWidget, city, country, language);
				} else {
					// Runs when it's not
					console.log(xhr.responseText);
				}
			};
			xhr.open('GET', 'https://www.weatherwp.com/api/common/publicWeatherForLocation2.php?city=' + encodeURIComponent(city) + '&country=' + encodeURIComponent(country) + '&place=' + location_for_rest + '&domain=' + document.location + '&language=' + language + "&widget=Spanish2");
			xhr.send();
		}


		function handleResponse(response, weatherWidget, city, country, language) {
			var header = weatherWidget.getAttribute("data-header");
			var widgetInputWidth = weatherWidget.getAttribute("data-width");
			var days = weatherWidget.getAttribute("data-days");
			var isCurrent = weatherWidget.getAttribute("data-current");
			var isWind = weatherWidget.getAttribute("data-wind");
			var isSunrise = weatherWidget.getAttribute("data-sunrise");
			var backgroundColor = weatherWidget.getAttribute("data-background");
			if (!backgroundColor || backgroundColor === '' || backgroundColor === '#16a2d0') {
				backgroundColor = 'linear-gradient(to right top, #3ad7f6, #16a2d0)';
			}
			var textColor = weatherWidget.getAttribute("data-text-color");
			if (!textColor || textColor === '') {
				textColor = 'white';
			}

			//console.log(response);
			var deepLink = response.deepLink;
			var temp = response.temp;

			var sunrise =  getWeatherInfoLine("Atardecer",response.sunrise,"sunrise",'');
			if (language === "english") {
				sunrise = getWeatherInfoLine("Sunrise",response.sunrise,"sunrise",'');
			}

			var sunset = getWeatherInfoLine("Puesta de sol",response.sunset,"sunset",'');
			if (language === "english") {
				sunset = getWeatherInfoLine("Sunset",response.sunset,"sunset",'');
			}
			var imageURL = response.icon;
			var humidity = getWeatherInfoLine("Humedad",response.humidity,"humidity",'%');
			if (language === "english") {
				humidity = getWeatherInfoLine("Humidity",response.humidity,"humidity",'%');
			}
			var windspeedKmph = getWeatherInfoLine("Viento",response.windspeedKmph,"wind",'Km/h');
			if (language === "english") {
				windspeedKmph = getWeatherInfoLine("Wind Speed",response.windspeedKmph,"wind",'Km/h');
			}

			var chanceofrain = response.chanceofrain + "%";
			var daysForecast = response.days;
			var description = response.description;


			// set tight if user asked for not 100%
			if (widgetInputWidth === 'tight') {
				$(weatherWidget).addClass('tight');
			} else {
				$(weatherWidget).addClass('maxwidth');
			}

			// get widget width
			var widgetWidth = $(weatherWidget).width();
			var widthClass = "regular";
			if (widgetWidth < 200) {
				widthClass = "super-small";
			} else if (widgetWidth < 300) {
				widthClass = "small";
			} else if (widgetWidth < 380) {
				widthClass = "medium";
			}


			// wrap deep link
			var mainDeepLinkElm = document.createElement("a");
			mainDeepLinkElm.setAttribute("href", deepLink);
			mainDeepLinkElm.setAttribute("class", "main-link");
			mainDeepLinkElm.setAttribute("style", "color:" + textColor);
			mainDeepLinkElm.setAttribute("target", "_blank");
			var title = "El clima en "+city;
			if(language === 'english'){
				title = "The weather in "+city;
			}

			mainDeepLinkElm.setAttribute("title", title);
				mainDeepLinkElm.innerHTML = (response.anchorText);


			//mainWrapElm
			var mainWrapElm = document.createElement("div");
			mainWrapElm.setAttribute("class", 'main_wrap ' + widthClass);
			mainWrapElm.setAttribute("style", "background: " + backgroundColor + " ;color:" + textColor);


			if (true) {
				// center wrap
				var centerWrapElm = document.createElement("div");
				centerWrapElm.setAttribute("class", "weather_center_wrap");

				// temp wrap
				var tempWrap = document.createElement("div");
				tempWrap.setAttribute("class", "weather_temp_wrap");

				// temp
				var tempElm = document.createElement("span");
				tempElm.innerHTML = temp + '&#176;';
				tempElm.setAttribute("class", "weather_temp");
				tempWrap.appendChild(tempElm);

				// image
				var imageWrapElm = document.createElement("div");
				var imageElm = document.createElement("img");
				imageElm.setAttribute("src", imageURL);

				imageElm.setAttribute("class", "weather_image");
				imageWrapElm.setAttribute("class", "weather_image_wrap");
				imageWrapElm.appendChild(imageElm);

				// center center
				var centerCenterElm = document.createElement("div");
				centerCenterElm.setAttribute("class", "center-center");

				// description
				var descriptionElm = document.createElement("div");
				descriptionElm.innerHTML = description;
				descriptionElm.setAttribute("class", "weather_description");


				// city name
				var cityNameElm = document.createElement("div");
				cityNameElm.innerHTML = city.capitalize();
				cityNameElm.setAttribute("class", "city-name");

				centerCenterElm.appendChild(cityNameElm);
				centerCenterElm.appendChild(descriptionElm);

				centerWrapElm.appendChild(imageWrapElm);
				centerWrapElm.appendChild(centerCenterElm);
				centerWrapElm.appendChild(tempWrap);
				mainWrapElm.appendChild(centerWrapElm);
			}

			if (isSunrise === 'on' || isWind === 'on'){
				var weatherDataElm = document.createElement("div");
				weatherDataElm.setAttribute("class", "weather-info-wrap");
			}

			if (isSunrise === 'on') {
				weatherDataElm.appendChild(sunrise);
				weatherDataElm.appendChild(sunset);
			}


			if (isWind === 'on') {
				weatherDataElm.appendChild(humidity);

				weatherDataElm.appendChild(windspeedKmph);
			}


			if (isSunrise === 'on' || isWind === 'on') {
				mainWrapElm.appendChild(weatherDataElm);
			}

			// days
			if (Number(days) > 0) {
				var daysWrap = document.createElement("div");
				daysWrap.setAttribute("class", "weather_days_wrap"); //"");

				for (var i = 0; i < Number(days); i++) {
					var dayElm = document.createElement("div");
					var dayElmWidth = "33.3%";
					if (Number(days) === 2 || Number(days) === 4) {
						dayElmWidth = "50%";
					}
					dayElm.setAttribute("class", "weather_day_wrap"); //"width: " + dayElmWidth + "; text-align: center;margin: 10px 0px;");
					// day name
					var dayNameElm = document.createElement("div");
					dayNameElm.innerHTML = daysForecast[i].dayName;
					dayNameElm.setAttribute("class", "weather_day_name"); //"");
					dayElm.appendChild(dayNameElm);

					// day image
					var dayImageWrapElm = document.createElement("div");
					var dayImageElm = document.createElement("img");
					dayImageElm.setAttribute("class", "weather_day_image"); //"");
					dayImageElm.setAttribute("src", daysForecast[i].icon);
					dayImageWrapElm.setAttribute("class", "weather_day_image_wrap"); //"");
					dayImageWrapElm.appendChild(dayImageElm);
					dayElm.appendChild(dayImageWrapElm);

					// day temp
					var dayTempElm = document.createElement("div");
					dayTempElm.innerHTML = daysForecast[i].max + '&#176;';
					dayTempElm.setAttribute("class", "weather_day_temp"); //"");
					dayElm.appendChild(dayTempElm);
					daysWrap.appendChild(dayElm);
				}
				mainWrapElm.appendChild(daysWrap);
			}


			// mainDeepLinkElm.prepend(mainWrapElm);

			mainWrapElm.append(mainDeepLinkElm);
			weatherWidget.prepend(mainWrapElm);
		}

	});

})(jQuery);

function getWeatherInfoLine(label,value,classToUse,sign){

	var data = '<div class="weather-info-label">'+label+'</div>' +
		'<div class="weather-info-value">' + value + sign + '</div>';

	var elm = document.createElement("div");
	elm.setAttribute("class", "weather-info-point "+classToUse);
	elm.innerHTML = data;

	return elm;
}