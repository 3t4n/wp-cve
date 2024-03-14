document.addEventListener('DOMContentLoaded', function() {
	let whIcon = document.getElementById('iwpPublicWhatsAppChatIcon');
	let whWindow = document.getElementById('iwpPublicWhatsAppWindow');
	let autoOpen;
	createQrCode();
	init();
	closeChat();
	manualOpenChat();
	openWhatsAppChat();

	function hexToRgb(hex, alpha) {
		if (hex === '') {
			return '';
		}
		const bigint = parseInt(hex.substring(1), 16);
		const r = (bigint >> 16) & 255;
		const g = (bigint >> 8) & 255;
		const b = bigint & 255;

		return `rgba(${r}, ${g}, ${b}, ${alpha})`;
	}

	function init() {
		let iconSleep = parseInt(whIcon.getAttribute('data-sleep'));
		iconSleep = (iconSleep <= 0) ? 0 : iconSleep;
		setTimeout(function() {
			whIcon.classList.remove("fadeOut");
			whIcon.classList.add("fadeIn");
			whIcon.style.display = "flex";
		}, (iconSleep * 1000));

		let chatSleep = parseInt(whWindow.getAttribute('data-sleep'));
		chatSleep = (chatSleep === 0) ? 0 : chatSleep;
		autoOpen = (chatSleep >= 0);
		if (chatSleep >= 0) {
			// Si la suma es 0, añadimos 2 segundos de margen para ver el efecto de pasar del icono al chat
			let sumaSleep = (iconSleep + chatSleep === 0) ? 2 : (iconSleep + chatSleep);
			if (autoOpen) {
				// Solamente tendrá efecto si no se ha abierto manualmente
				setTimeout(function () {
					transitionButtonChat(true);
				}, (sumaSleep * 1000));
			}
		}

		let chatColor = whWindow.getAttribute('data-color');
		let backColor = hexToRgb(chatColor, '0.12');
		let chatHeader = whWindow.querySelector('.iwp-public-whatsAppChat-chat-header');
		let chatBody = whWindow.querySelector('.iwp-public-whatsAppChat-chat-body');
		let chatButton = whWindow.querySelector('.iwp-public-whatsAppChat-chat-body-icon');
		chatHeader.style.backgroundColor = chatColor;
		chatBody.style.backgroundColor = backColor;
		chatButton.style.backgroundColor = chatColor;
	}

	function closeChat() {
		let closeButton = whWindow.querySelector('.iwp-public-whatsAppChat-chat-header-close');
		closeButton.addEventListener("click", function() {
			transitionButtonChat(false);
		});
	}
	function manualOpenChat() {
		let url = atob(whIcon.getAttribute('data-url'));
		if (url !== '') {
			whIcon.addEventListener("click", function() {
				window.open(url, '_blank');
			});
		} else {
			whIcon.addEventListener("click", function() {
				autoOpen = false;
				transitionButtonChat(true);
			});
		}
	}
	function openWhatsAppChat() {
		let chatButton = whWindow.querySelector('.iwp-public-whatsAppChat-chat-body-icon');
		chatButton.addEventListener("click", function() {
			let url = atob(chatButton.getAttribute('data-url'));
			window.open(url, '_blank');
		});
	}
	function createQrCode() {
		let qrTag = document.getElementById('iwpPublicQrCode');
		if (qrTag) {
			let chatButton = whWindow.querySelector('.iwp-public-whatsAppChat-chat-body-icon');
			let url = atob(chatButton.getAttribute('data-url'));
			qrTag.innerHTML = '';
			new QRCode(qrTag, url);
		}
	}


	function transitionButtonChat(open = true) {
		if (open) {
			whIcon.classList.remove("fadeIn");
			whIcon.classList.add("fadeOut");
			setTimeout(function () {
				whIcon.style.display = "none";
			}, 900);

			whWindow.classList.remove("fadeOut");
			whWindow.classList.add("fadeIn");
			whWindow.style.display = "flex";
		} else {
			whWindow.classList.remove("fadeIn");
			whWindow.classList.add("fadeOut");
			setTimeout(function () {
				whWindow.style.display = "none";
			}, 900);

			whIcon.classList.remove("fadeOut");
			whIcon.classList.add("fadeIn");
			whIcon.style.display = "flex";
		}
	}
});