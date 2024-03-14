let iwpWorkerUrl = '';

if (typeof PUBLIC_PARAMS !== "undefined") {
	// Si 'PUBLIC_PARAMS' existe, intentamos cargar los valores correspondientes de las variables globales
	// Con cada variable es necesario comprobar que existe dentro de 'PUBLIC_PARAMS'
	if (PUBLIC_PARAMS.hasOwnProperty('workerUrl')) {
		iwpWorkerUrl = PUBLIC_PARAMS.workerUrl;
	}
}

document.addEventListener('DOMContentLoaded', function() {
	removeDuplicatedWorkers();
});

function removeDuplicatedWorkers() {
	navigator.serviceWorker.getRegistrations().then((registrations) => {
		// console.log('Workers found:', registrations.length);
		registrations.forEach((registration) => {
			if (registration.scope.includes('indigitall')) {
				if (registration.active && (registration.active.scriptURL !== iwpWorkerUrl)) {
					console.log('Indigitall old worker found:', registration.scope);
					console.log('Unregistered worker with URL ' + registration.active.scriptURL);
					registration.unregister();
				}
			}
		});
	});
}