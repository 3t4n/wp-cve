// get_cash_qrcode // wp_localize_script object
console.log("get_cash_qrcode", get_cash_qrcode);

jQuery(document).ready(async function ($) {
	await GC_QRCodeGenerator($(".gc_qrcode_styled"), get_cash_qrcode);
});

async function GC_QRCodeGenerator(elements, qrcode_object) {
	// // url = data-url vs qrcode_object.url = plugins_url_dir
	if (elements == undefined || typeof qrcode_object.url == undefined) return;
	// console.log(elements);

	for (let element of elements) {
		console.debug(element);

		if (!element || typeof element == undefined) return;

		let qrcode;
		let url = element.getAttribute("data-url");
		let brand = element.getAttribute("data-brand");
		let logo = brand ? `${qrcode_object.url}/${brand ?? "default"}.png` : null;

		if (!logo || !url) return;

		try {
			// https://qr-code-styling.com
			qrcode = new QRCodeStyling({
				width: qrcode_object.width,
				height: qrcode_object.height,
				margin: 0,
				data: url,
				image: logo,
				imageOptions: { hideBackgroundDots: true, imageSize: 0.5, margin: 5 },
				qrOptions: {
					typeNumber: "0",
					mode: "Byte",
					errorCorrectionLevel: "Q",
				},
				dotsOptions: {
					type: qrcode_object.dotsType,
					color: qrcode_object.darkcolor,
				},
				dotsOptionsHelper: {
					colorType: { single: true, gradient: false },
					gradient: {
						linear: true,
						radial: false,
						color1: qrcode_object.darkcolor,
						color2: qrcode_object.darkcolor,
						rotation: "0",
					},
				},
				cornersSquareOptions: {
					type: qrcode_object.cornersSquareType,
					color: qrcode_object.darkcolor,
				},
				cornersSquareOptionsHelper: {
					colorType: { single: true, gradient: false },
					gradient: {
						linear: true,
						radial: false,
						color1: qrcode_object.darkcolor,
						color2: qrcode_object.darkcolor,
						rotation: "0",
					},
				},
				cornersDotOptions: {
					type: qrcode_object.cornersDotType,
					color: qrcode_object.darkcolor,
				},
				cornersDotOptionsHelper: {
					colorType: { single: true, gradient: false },
					gradient: {
						linear: true,
						radial: false,
						color1: qrcode_object.darkcolor,
						color2: qrcode_object.darkcolor,
						rotation: "0",
					},
				},
				backgroundOptions: { color: qrcode_object.backgroundcolor },
				backgroundOptionsHelper: {
					colorType: { single: true, gradient: false },
					gradient: {
						linear: true,
						radial: false,
						color1: qrcode_object.backgroundcolor,
						color2: qrcode_object.backgroundcolor,
						rotation: "0",
					},
				},
			});
			// console.log("qrcode", qrcode);
			let b64 = null;
			// // https://github.com/kozakdenys/qr-code-styling/blob/master/README.md#qrcodestyling-methods
			// qrcode.append(element);
			// https://github.com/kozakdenys/qr-code-styling/blob/master/README.md#qrcodestyling-methods
			qrcode.getRawData("png").then((blob) => {
				var reader = new FileReader();
				reader.readAsDataURL(blob);
				return new Promise((resolve) => {
					reader.onloadend = function () {
						b64 = reader.result;
						// console.log(b64);
						var img = document.createElement("img");
						img.src = b64;
						// console.log("element", element);
						// return resolve(element.appendChild(img));

						var html = `<a href="${url}" target="_blank"><img src="${img.src}" alt="QR Code" width="150" height="150" /></a>`;
						// console.log("html", html);
						// console.log("element", element);

						// element.html(html);
						element.innerHTML = html;
						console.log("QR Code", element);
						return resolve(element);
					};
				});
			});
		} catch (error) {
			console.log(error);
			qrcode = document.createElement("img");
			qrcode.src =
				"https://chart.googleapis.com/chart?cht=qr&chs=150x150&chl=" +
				encodeURI(url);
			// qrcode.width = "150px";
			// qrcode.height = "150px";
			// // element.appendChild(qrcode);
			// element.html(qrcode);

			var html = `<a href="${url}" target="_blank"><img src="${qrcode.src}" alt="QR Code" width="150" height="150" /></a>`;
			// element.innerHTML = html;
			// console.log("element", element);

			element.html(html);
			console.log("QR Code", element);
		}
	}
}