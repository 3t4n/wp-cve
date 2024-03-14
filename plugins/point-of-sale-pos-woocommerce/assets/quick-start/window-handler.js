const BASE_URL_POS = 'https://pos.bizswoop.app';

const WINDOW_NAME = 'ServiceAuthGateway';

let popUpWindow = null;

document.addEventListener('DOMContentLoaded', function () {
	const buttons = document.querySelectorAll('#ssoRegisterBtn, #ssoLoginBtn');

	buttons.forEach((button) => {
		button.addEventListener('click', handleBtnClick);
	});

	window.addEventListener('message', handleWindowActions, false);
});

function handleBtnClick(event) {
	event.preventDefault();

	const isRegisterBtn = event.target.id === 'ssoRegisterBtn';

	const url = new URL('/service-onboarding', BASE_URL_POS);

	url.searchParams.set('authMode', isRegisterBtn ? 'register' : 'login');

	popUpWindow = openCenteredWindow(url.href, WINDOW_NAME, 550, 620);
}

function handleWindowActions(event) {
	const { origin, data } = event;

	if (![BASE_URL_POS].includes(origin)) return;

	switch (data.type) {
		case 'content-loaded':
			if (popUpWindow) {
				popUpWindow.postMessage({ type: 'parent-origin' }, BASE_URL_POS);
			}
			break;

		case 'setup-complete':
			if (data.stationURL) {
				window.open(data.stationURL, '_blank');

				setTimeout(() => {
					if (popUpWindow) popUpWindow.close();
					window.location.href = '/wp-admin/edit.php?post_type=pos-station';
				}, 10000);
			}
			break;

		default:
			break;
	}
}

function openCenteredWindow(url, title, width, height) {
	const screenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
	const screenTop = window.screenTop !== undefined ? window.screenTop : window.screenY;

	const innerWidth = window.innerWidth
		? window.innerWidth
		: document.documentElement.clientWidth
		? document.documentElement.clientWidth
		: window.screen.width;

	const innerHeight = window.innerHeight
		? window.innerHeight
		: document.documentElement.clientHeight
		? document.documentElement.clientHeight
		: window.screen.height;

	const left = (innerWidth - width) / 2 + screenLeft;
	const top = (innerHeight - height) / 2 + screenTop;

	const options = `width=${width},height=${height},top=${top},left=${left}`;

	const newWindow = window.open(url, title, options);

	if (window.focus && newWindow) newWindow.focus();

	return newWindow;
}
