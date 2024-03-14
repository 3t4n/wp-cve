
let isMobileDevice = 0;
if (typeof PUBLIC_PARAMS !== "undefined") {
	// Si 'PUBLIC_PARAMS' existe, intentamos cargar los valores correspondientes de las variables globales
	// Con cada variable es necesario comprobar que existe dentro de 'PUBLIC_PARAMS'
	if (PUBLIC_PARAMS.hasOwnProperty('isMobileDevice')) {
		isMobileDevice = (PUBLIC_PARAMS.isMobileDevice === '1');
	}
}