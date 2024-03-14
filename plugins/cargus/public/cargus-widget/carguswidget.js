// cargus-widget.js
/*
 * Cargus Map Widget v1.0.0
 * Author: Cargus
 * Description: This widget provides a map interface for selecting pudo-points.
 */
function initializeCargus(mapContainerId, FnParams, VarParams) {

    function createLoadingScreen(targetElement) {
        const modal = document.createElement("div");
        modal.className = "cargus-loading-screen-modal";
        const loadingCircle = document.createElement("div");
        loadingCircle.className = "cargus-loading-circle";
        modal.appendChild(loadingCircle);
        targetElement.appendChild(modal);
    }
    
    function showLoadingScreen() {
        const targetElement = document.getElementById("cg-map-widget-container");
        if (targetElement) {
            const mapElement = document.querySelector(".cargus-map-widget #map");
            const sidebarElement = document.querySelector(".cargus-map-widget .sidebar");
            sidebarElement.style.opacity = "0.4";
            mapElement.style.opacity = "0.4";
            createLoadingScreen(targetElement);
        }
    }

    function hideLoadingScreen() {
        const modal = document.querySelector(".cargus-loading-screen-modal");
        if (modal) {
            const mapElement = document.querySelector(".cargus-map-widget #map");
            const sidebarElement = document.querySelector(".cargus-map-widget .sidebar");
            sidebarElement.style.opacity = "1";
            mapElement.style.opacity = "1";
            modal.remove();
        }
    }

    var scriptElement = document.querySelector('script[data-widget="cargus-widget"]');

    if (scriptElement) {

        var usemockupdata = scriptElement.getAttribute('use-mockup-data');

        if (usemockupdata === 'true') {
            useMockupdata = true;
        } else {
            useMockupdata = false;
        }

    } else {
        useMockupdata = false;
    }

    // ============================================

    const ChooseMarker = FnParams.ChooseMarker;
    const closeModal = FnParams.closeModal;
    const use_test_data = useMockupdata;
    const data_endpoint = VarParams.data_endpoint;
    const assets_path = VarParams.assets_path;
    const showCloseButton = VarParams.show_close_button ?? true;
    const useURL = VarParams.useURL ?? false;
    const dbName = 'pudoStorage'
    const DEFAULT_COORDINATES = VarParams.DEFAULT_COORDINATES || {
        latitude: 44.42721425196829,
        longitude: 26.102416039867997,
    };

    // ============================================

    const mockup_data = [{
            "Id": 1000085,
            "Symbol": "APM7103",
            "Name": "Locker",
            "LocationId": 1,
            "CountyId": 44,
            "County": "Bucuresti",
            "CityId": 150,
            "City": "BUCURESTI",
            "StreetId": 196,
            "StreetName": "Arghezi Tudor, poet",
            "ZoneId": 9336,
            "PostalCode": "020941",
            "Entrance": "",
            "Floor": "",
            "Apartment": "",
            "Sector": "2",
            "Address": "BUCURESTI, Strada Arghezi Tudor, poet, Nr. 3, Cod postal. 020941",
            "AddressDescription": "Description",
            "AdditionalAddressInfo": "Necesita Cargus Mobile App",
            "Longitude": 26.104956,
            "Latitude": 44.43717,
            "PointType": 55,
            "OpenHoursMoStart": "00:00",
            "OpenHoursMoEnd": "23:59",
            "OpenHoursTuStart": "00:00",
            "OpenHoursTuEnd": "23:59",
            "OpenHoursWeStart": "00:00",
            "OpenHoursWeEnd": "23:59",
            "OpenHoursThStart": "00:00",
            "OpenHoursThEnd": "23:59",
            "OpenHoursFrStart": "00:00",
            "OpenHoursFrEnd": "23:59",
            "OpenHoursSaStart": "00:00",
            "OpenHoursSaEnd": "23:59",
            "OpenHoursSuStart": "00:00",
            "OpenHoursSuEnd": "23:59",
            "StreetNo": "3",
            "PhoneNumber": "021 9282",
            "ServiceCOD": true,
            "PaymentType": 4,
            "Email": "",
            "MainPicture": "",
            "AcceptedPaymentType": {
                "Cash": true,
                "Card": true,
                "Online": true
            }
        },
        {
            "Id": 114309,
            "Symbol": "SG00247",
            "Name": "Punct de ridicare",
            "LocationId": 1,
            "CountyId": 44,
            "County": "Bucuresti",
            "CityId": 150,
            "City": "BUCURESTI",
            "StreetId": 1205,
            "StreetName": "Coposu Corneliu",
            "ZoneId": 5581,
            "PostalCode": "030601",
            "Entrance": "",
            "Floor": "",
            "Apartment": "",
            "Sector": "",
            "Address": "BUCURESTI, Piata Coposu Corneliu, Nr. 3-5, Cod postal. 030601",
            "AddressDescription": "Punct Corneliu Coposu 3-5 este punct partener Cargus cu adresa in Bucuresti, punct de reper: service GSM.\n\nlanga farmacia Dona\n\nRidic\u0103 un colet cu u\u0219urin\u021b\u0103 din punctele Cargus Ship&Go!\nCurierul a ajuns la tine c\u00e2nd tu nu erai acas\u0103? Nici o problem\u0103. Curierul va l\u0103sa coletul pentru tine la cel mai apropiat punct Cargus Ship&Go.\nPo\u021bi redirec\u021biona cu un singur click coletul catre un punct Ship&Go sau po\u021bi comanda livrarea direct \u00een Punctul Cargus Ship&Go ales sau in Locker Cargus Ship&Go.\n",
            "AdditionalAddressInfo": "",
            "Longitude": 26.1061033052418,
            "Latitude": 44.429926851278,
            "PointType": 2,
            "OpenHoursMoStart": "09:30",
            "OpenHoursMoEnd": "19:30",
            "OpenHoursTuStart": "09:30",
            "OpenHoursTuEnd": "19:30",
            "OpenHoursWeStart": "09:30",
            "OpenHoursWeEnd": "19:30",
            "OpenHoursThStart": "09:30",
            "OpenHoursThEnd": "19:30",
            "OpenHoursFrStart": "09:30",
            "OpenHoursFrEnd": "19:30",
            "OpenHoursSaStart": "",
            "OpenHoursSaEnd": "",
            "OpenHoursSuStart": "",
            "OpenHoursSuEnd": "",
            "StreetNo": "3-5",
            "PhoneNumber": "",
            "ServiceCOD": true,
            "PaymentType": 6,
            "Email": "",
            "MainPicture": "",
            "AcceptedPaymentType": {
                "Cash": false,
                "Card": true,
                "Online": true
            }
        },
        {
            "Id": 114311,
            "Symbol": "SG00247",
            "Name": "Punct de ridicare",
            "LocationId": 1,
            "CountyId": 44,
            "County": "Pitesti",
            "CityId": 150,
            "City": "Pitesti",
            "StreetId": 1205,
            "StreetName": "Coposu Corneliu",
            "ZoneId": 5581,
            "PostalCode": "030601",
            "Entrance": "",
            "Floor": "",
            "Apartment": "",
            "Sector": "",
            "Address": "Pitesti",
            "AddressDescription": "Punct Ship&Go",
            "AdditionalAddressInfo": "",
            "Longitude": 24.866292430481415,
            "Latitude": 44.87615920657333,
            "PointType": 2,
            "OpenHoursMoStart": "09:30",
            "OpenHoursMoEnd": "19:30",
            "OpenHoursTuStart": "",
            "OpenHoursTuEnd": "",
            "OpenHoursWeStart": "09:30",
            "OpenHoursWeEnd": "19:30",
            "OpenHoursThStart": "09:30",
            "OpenHoursThEnd": "19:30",
            "OpenHoursFrStart": "09:30",
            "OpenHoursFrEnd": "19:30",
            "OpenHoursSaStart": "",
            "OpenHoursSaEnd": "",
            "OpenHoursSuStart": "",
            "OpenHoursSuEnd": "",
            "StreetNo": "3-5",
            "PhoneNumber": "",
            "ServiceCOD": true,
            "PaymentType": 6,
            "Email": "",
            "MainPicture": "",
            "AcceptedPaymentType": {
                "Cash": true,
                "Card": true,
                "Online": true
            }
        },
    ];

    const assets_attributes = {
        'icons': {
            'pointPinIcon': {
                'iconUrl': `${assets_path}/pointpin.png`
            },
            'lockerPinIcon': {
                'iconUrl': `${assets_path}/lockerpin.png`
            },
            'selectedPointPinIcon': {
                'iconUrl': `${assets_path}/spointpin.png`
            },
            'selectedLockerPinIcon': {
                'iconUrl': `${assets_path}/slockerpin.png`
            },
            'currentLocationIcon': {
                'iconUrl': `${assets_path}/allow_current_location.svg`
            },
            'arrow_leftIcon': {
                'iconUrl': `${assets_path}/arrow-left.svg`
            },
            'closeIcon': {
                'iconUrl': `${assets_path}/close_icon.svg`
            }
        }
    };

    /**
     * Creates a loading screen and appends it to the specified target element.
     * @param {HTMLElement} targetElement - The element to which the loading screen will be appended.
     */

    function addViewportMetaTag() {
        const metaTag = document.createElement('meta');
        metaTag.name = 'viewport';
        metaTag.content = 'width=device-width, initial-scale=1, maximum-scale=1';
        document.head.appendChild(metaTag);
    }

    addViewportMetaTag();

    CargusMapWidget(mapContainerId);

    function CargusMapWidget(mapContainerId) {

        var mapWidgetContainer = document.getElementById(mapContainerId);

        var mapWidgetHTML = generateMapWidgetHTML();

        mapWidgetContainer.appendChild(mapWidgetHTML);

        showLoadingScreen();

        var list;
        let map;
        let markersLayer;
        let locationsData;
        let zoomSnap = 0.25;
        let zoomDelta = 0.01;
        let wheelPxPerZoomLevel = 300;
        let visibleMarkerIDs = [];
        let isMarkerVisible;
        let currentLocationPos = {};
        const objectStoreName = 'locations';

        const KEY_MAPPING = {
            ID: VarParams.KEY_MAPPING_VALUES?.ID ?? 'Id',
            Latitude: VarParams.KEY_MAPPING_VALUES?.Latitude ?? 'Latitude',
            Longitude: VarParams.KEY_MAPPING_VALUES?.Longitude ?? 'Longitude',
            PointType: VarParams.KEY_MAPPING_VALUES?.PointType ?? 'PointType',
            Name: VarParams.KEY_MAPPING_VALUES?.Name ?? 'Name',
            IsSelected: VarParams.KEY_MAPPING_VALUES?.IsSelected ?? 'isSelected',
            MainPicture: VarParams.KEY_MAPPING_VALUES?.MainPicture ?? 'MainPicture',
            AcceptedPaymentType: VarParams.KEY_MAPPING_VALUES?.AcceptedPaymentType ?? 'AcceptedPaymentType',
            City: VarParams.KEY_MAPPING_VALUES?.City ?? 'City',
            Address: VarParams.KEY_MAPPING_VALUES?.Address ?? 'Address',
            AddressDescription: VarParams.KEY_MAPPING_VALUES?.AddressDescription ?? 'AddressDescription',
            StreetNo: VarParams.KEY_MAPPING_VALUES?.StreetNo ?? 'StreetNo',
            OpenHoursMoStart: VarParams.KEY_MAPPING_VALUES?.OpenHoursMoStart ?? 'OpenHoursMoStart',
            OpenHoursMoEnd: VarParams.KEY_MAPPING_VALUES?.OpenHoursMoEnd ?? 'OpenHoursMoEnd',
            OpenHoursTuStart: VarParams.KEY_MAPPING_VALUES?.OpenHoursTuStart ?? 'OpenHoursTuStart',
            OpenHoursTuEnd: VarParams.KEY_MAPPING_VALUES?.OpenHoursTuEnd ?? 'OpenHoursTuEnd',
            OpenHoursWeStart: VarParams.KEY_MAPPING_VALUES?.OpenHoursWeStart ?? 'OpenHoursWeStart',
            OpenHoursWeEnd: VarParams.KEY_MAPPING_VALUES?.OpenHoursWeEnd ?? 'OpenHoursWeEnd',
            OpenHoursThStart: VarParams.KEY_MAPPING_VALUES?.OpenHoursThStart ?? 'OpenHoursThStart',
            OpenHoursThEnd: VarParams.KEY_MAPPING_VALUES?.OpenHoursThEnd ?? 'OpenHoursThEnd',
            OpenHoursFrStart: VarParams.KEY_MAPPING_VALUES?.OpenHoursFrStart ?? 'OpenHoursFrStart',
            OpenHoursFrEnd: VarParams.KEY_MAPPING_VALUES?.OpenHoursFrEnd ?? 'OpenHoursFrEnd',
            OpenHoursSaStart: VarParams.KEY_MAPPING_VALUES?.OpenHoursSaStart ?? 'OpenHoursSaStart',
            OpenHoursSaEnd: VarParams.KEY_MAPPING_VALUES?.OpenHoursSaEnd ?? 'OpenHoursSaEnd',
            OpenHoursSuStart: VarParams.KEY_MAPPING_VALUES?.OpenHoursSuStart ?? 'OpenHoursSuStart',
            OpenHoursSuEnd: VarParams.KEY_MAPPING_VALUES?.OpenHoursSuEnd ?? 'OpenHoursSuEnd'
        }

        const pointPinIcon = L.icon({
            iconUrl: assets_attributes.icons.pointPinIcon.iconUrl,
            iconSize: [64, 64],
        });
        const lockerPinIcon = L.icon({
            iconUrl: assets_attributes.icons.lockerPinIcon.iconUrl,
            iconSize: [64, 64],
        });
        const selectedPointPinIcon = L.icon({
            iconUrl: assets_attributes.icons.selectedPointPinIcon.iconUrl,
            iconSize: [64, 64],
        });
        const selectedLockerPinIcon = L.icon({
            iconUrl: assets_attributes.icons.selectedLockerPinIcon.iconUrl,
            iconSize: [64, 64],
        });

        const searchBox = document.getElementById('cargus-search-box');
        const closeSearchBox = document.getElementById('cargus-search-reset-button')
        const clearButton = document.getElementById('cargus-search-reset-button');
        const resultContainer = document.getElementById('cargus-search-results-container');
        const listContainer = document.querySelector('#cargus-listContainer');
        const searchBar = document.querySelector('#cargus-search-container');
        const lockersButton = document.getElementById("lockersButton");
        const filter_address_button = document.getElementById("filter_addressbutton");
        const shipgoButton = document.getElementById("shipgoButton");
        const sidebar = document.getElementById('cargus-sidebar');
        const currentLocationButton = document.getElementById('cargus-current-location');

        async function fetch_data() {
            // Check if is a valid url or a json already with the pins.
            try {
                if( useURL ) {
                    new URL(data_endpoint);
                }
            } catch (e) {
                return data_endpoint;
            }

            try {
                const response = await fetch(data_endpoint, {
                    method: 'GET',
                    headers: {
                        'content-type': 'application/json'
                    },
                    cache: 'no-cache'
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch PUDO data');
                }
                const pudodata = await response.json();
                return pudodata;
            } catch (error) {
                console.error('Error fetching PUDO data:', error);
                throw error;
            }
        }

        function once(fn) {
            let hasBeenCalled = false;
            let result;
            return function(...args) {
                if (!hasBeenCalled) {
                    hasBeenCalled = true;
                    result = fn.apply(this, args);
                }
                return result;
            };
        }

        const debounce = (func, delay) => {
            let timeoutId;
            return (...args) => {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => {
                    func(...args);
                }, delay);
            };
        };

        function handleMobileMenu() {
            const markedResultExtendedCard = document.querySelector('#cargus-markedResult .extended-card');
            if (window.screen.availWidth < 768 && markedResultExtendedCard !== null) {
                listContainer.style.setProperty('display', 'none', 'important');
                searchBar.style.setProperty('display', 'none', 'important');
            } else {
                const listContainer = document.querySelector('.results');
                searchBar.style.setProperty('display', 'block', 'important');
                listContainer.style.filter = 'blur(0px)';
            }
        }

        function generateMapWidgetHTML() {
            const mapWidgetContainer = document.createElement("div");
            mapWidgetContainer.classList.add("cg-map-widget-container");
            mapWidgetContainer.classList.add("cargus-map-widget");
            mapWidgetContainer.id = "cg-map-widget-container";
            mapWidgetContainer.innerHTML  = '';
            if (showCloseButton) {
                mapWidgetContainer.innerHTML  += `<span id="close-modal" style="background-image: url(${assets_attributes.icons.closeIcon.iconUrl});"></span>`;
            }
            mapWidgetContainer.innerHTML += `
				<span id="cargus-current-location" style="background-image: url(${assets_attributes.icons.currentLocationIcon.iconUrl});"></span>
                <div class="sidebar" id="cargus-sidebar">
                    <div class="search" id="cargus-query-container">
                        <div id="cargus-search-container">
                            <button id="cargus-clear-btn" style="display: none;">&times;</button>
							<p class="header hide-desktop"> Select Ship & Go </p>
                            <div class="search-container">
							<div class="magnify-icon-cargus-searchbar">
								<svg class="custom-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
								<path fill="#1D1D1B" d="M19.84 18.7599L16.36 15.2799C17.32 14.0799 17.92 12.5599 17.92 10.8799C17.92 8.07988 16.24 5.51988 13.64 4.43988C12.76 4.07988 11.88 3.87988 10.92 3.87988C7.08004 3.87988 3.92004 6.99988 3.92004 10.8799C3.92004 14.7599 7.04004 17.8799 10.92 17.8799C12.52 17.8799 14.04 17.3199 15.2 16.3999L18.68 19.8799C18.84 20.0399 19.04 20.1199 19.24 20.1199C19.44 20.1199 19.64 20.0399 19.8 19.8799C20.16 19.5599 20.16 19.0799 19.84 18.7599ZM5.52004 10.8799C5.52004 7.91988 7.92004 5.47988 10.92 5.47988C11.64 5.47988 12.36 5.63988 13 5.91988C15 6.75988 16.28 8.71988 16.28 10.8799C16.28 13.8399 13.88 16.2799 10.88 16.2799C7.88004 16.2799 5.52004 13.8399 5.52004 10.8799Z"/>
							</svg>  
							</div>
                            <input type="text" class="search-box" id="cargus-search-box" placeholder="Search SHIP & GO...">
                            <span id="cargus-search-reset-button" style="background-image: url(${assets_attributes.icons.closeIcon.iconUrl});"></span>
                            </div>
                            <div class="cargus-filter">
                                <div id="cargus-checkbox-container">
									<button class="select_filter-button active" id="lockersButton"> Lockers </button>
									<button class="select_filter-button active" id="shipgoButton"> Ship & Go </button>
									<button class="select_filter-button active" id="filter_addressbutton"> Address </button>
                                </div>
                            </div>
                        </div>
                            <div id="cargus-search-results-container"></div>
                    </div>
                    <div class="results" id="cargus-search-results">
                        <div class="MapContainer" id="cargus-markedResult"> </div>
                        <div id="cargus-listContainer">
                            <ul id="pointList"></ul>
                        </div>
                    </div>
                </div>
                <div id="cargus-map-wrapper">
                <div id="map"></div>
                </div>
            `;
            document.addEventListener('DOMContentLoaded', () => {

                const clearBtn = document.getElementById('cargus-clear-btn');
                const searchBox = document.getElementById('cargus-search-box');

                if (clearBtn && searchBox) {
                    clearBtn.addEventListener('click', (event) => {
                        event.preventDefault(); // Prevent the default behavior of the click event
                        searchBox.value = '';
                        clearBtn.style.display = 'none';
                    });

                    searchBox.addEventListener('input', () => {
                        clearBtn.style.display = searchBox.value ? 'block' : 'none';
                    });
                }
            });
            return mapWidgetContainer;
        }

        /**
         * Class representing a Schedule Generator.
         * Generates formatted opening hours schedule based on provided data.
         */

        class ScheduleGenerator {
            /**
             * Construct a new ScheduleGenerator instance.
             * @param {Object} data - The data containing opening hours for different days.
             */
            constructor(data) {
                this.data = data; // The data containing opening hours
                this.daysOfWeek = ['Luni', 'Marti', 'Miercuri', 'Joi', 'Vineri', 'Sambata', 'Duminica']; // List of days
            }
            /**
             * Get the opening hours for a specific day.
             * @param {string} day - The day of the week.
             * @param {string} startKey - The key for the start opening hour in the data.
             * @param {string} endKey - The key for the end opening hour in the data.
             * @returns {Object} The day and its corresponding opening hours.
             */
            getDayOpenHours(day, startKey, endKey) {
                return {
                    day: day,
                    start: this.data[startKey] || null,
                    end: this.data[endKey] || null,
                };
            }
            /**
             * Get the formatted opening hours for a specific interval of days.
             * @param {Object} startDay - The starting day's opening hours.
             * @param {Object} endDay - The ending day's opening hours.
             * @returns {string} Formatted opening hours for the specified interval of days.
             */
            getOpenHours(startDay, endDay) {
                if (startDay.day === endDay.day) {
                    if (startDay.start && startDay.end) {
                        return `${startDay.day}: ${startDay.start}-${startDay.end}`;
                    } else {
                        return `${startDay.day}: Closed`;
                    }
                } else {
                    if (startDay.start && startDay.end) {
                        return `${startDay.day}-${endDay.day}: ${startDay.start}-${startDay.end}`;
                    } else {
                        return `${startDay.day}-${endDay.day}: Closed`;
                    }
                }
            }
            /**
             * Generate the formatted opening hours schedule.
             * @returns {string} The formatted opening hours schedule.
             */
            generateSchedule() {
                const listOfDays = [];
                listOfDays.push(this.getDayOpenHours("Monday", "OpenHoursMoStart", "OpenHoursMoEnd"));
                listOfDays.push(this.getDayOpenHours("Tuesday", "OpenHoursTuStart", "OpenHoursTuEnd"));
                listOfDays.push(this.getDayOpenHours("Wednesday", "OpenHoursWeStart", "OpenHoursWeEnd"));
                listOfDays.push(this.getDayOpenHours("Thursday", "OpenHoursThStart", "OpenHoursThEnd"));
                listOfDays.push(this.getDayOpenHours("Friday", "OpenHoursFrStart", "OpenHoursFrEnd"));
                listOfDays.push(this.getDayOpenHours("Saturday", "OpenHoursSaStart", "OpenHoursSaEnd"));
                listOfDays.push(this.getDayOpenHours("Sunday", "OpenHoursSuStart", "OpenHoursSuEnd"));
                let result = '';
                let firstIntervalDay = undefined;
                let lastIntervalDay = undefined;
                listOfDays.forEach((day) => {
                    if (firstIntervalDay === undefined) {
                        firstIntervalDay = day;
                    }
                    if (lastIntervalDay === undefined) {
                        lastIntervalDay = day;
                    }
                    if (firstIntervalDay.start !== day.start || firstIntervalDay.end !== day.end) {
                        result += this.getOpenHours(firstIntervalDay, lastIntervalDay).replace(/\n/g, '<br>') + '<br>';
                        firstIntervalDay = day;
                        lastIntervalDay = day;
                    } else {
                        lastIntervalDay = day;
                    }
                });
                // for the last day
                result += this.getOpenHours(firstIntervalDay, lastIntervalDay).replace(/\n/g, '<br>') + '<br>';
                return result.trim();
            }
        }

        /**
         * Class representing a Pointer Tracker.
         * Tracks and manages the location and associated information of a selected PUDO point on map.
         */
        class PointerTracker {
            /**
             * Construct a new PointerTracker instance.
             * Initializes the pointer's ID, latitude, and longitude.
             */
            constructor() {
                this.pointerID = null; // ID of the tracked pointer
                this.latitude = null; // Latitude of the pointer's location
                this.longitude = null; // Longitude of the pointer's location
            }
            /**
             * Set the ID of the tracked pointer and update its location information.
             * @param {number} id - The ID of the pointer.
             */
            set(id) {
                this.pointerID = id;
                const location = getLocationObject(id);
                if (location) {
                    this.latitude = location[KEY_MAPPING.Latitude]
                    this.longitude = location[KEY_MAPPING.Longitude];
                }
            }
            /**
             * Get the icon associated with the tracked pointer.
             * @param {boolean} selected - Whether the pointer is selected.
             * @returns {string|null} The URL of the associated icon, or null if not found.
             */
            getAssociatedIcon(selected = false) {
                const location = getLocationObject(this.pointerID);
                if (!location) {
                    return null;
                }
                const pointType = location.PointType;
                let icon = null;
                // Current API Response returns values bigger than 50 for Lockers
                if (pointType < 50) {
                    icon = selected ? selectedPointPinIcon : pointPinIcon;
                } else {
                    icon = selected ? selectedLockerPinIcon : lockerPinIcon;
                }
                return icon.options.iconUrl;
            }
            /**
             * Set the location of the tracked pointer.
             * @param {number} latitude - The new latitude.
             * @param {number} longitude - The new longitude.
             */
            setPointerLocation(latitude, longitude) {
                this.latitude = latitude;
                this.longitude = longitude;
            }
            /**
             * Get the ID of the tracked pointer.
             * @returns {number|null} The ID of the pointer.
             */
            getPointerID() {
                return this.pointerID;
            }
            /**
             * Get the location of the tracked pointer.
             * @returns {Object} The latitude and longitude of the pointer's location.
             */
            getPointerLocation() {
                return {
                    latitude: this.latitude,
                    longitude: this.longitude
                };
            }
            /**
             * Clear the ID and location information of the tracked pointer.
             */
            clearPointerID() {
                this.pointerID = null;
                this.latitude = null;
                this.longitude = null;
            }
        }
        const pointerTracker = new PointerTracker();

        class AddressSearch {
            constructor() {
                this.geocodeUrl = 'https://nominatim.openstreetmap.org/search?format=json&q=';
                this.countryCode = 'RO'; // Country code for Romania
                this.results = [];
            }

            async search(address) {
                const query = encodeURIComponent(address);
                const url = `${this.geocodeUrl}${query}&countrycodes=${this.countryCode}`;

                try {
                    const response = await fetch(url);
                    const data = await response.json();
                    this.results = data;
                    return this.results; // Return the results
                } catch (error) {
                    console.error('Error searching for address:', error);
                    throw error; // Re-throw the error so it can be caught by the caller
                }
            }
        }

        const addressSearch = new AddressSearch();

        /**
         * A class for handling reactivity based on screen size and container content.
         */
        class Reactivity {

            // id listContainer is reponsible for displaying results seen on map
            // id search-results container is responsible for displaying results from search query
            // id cargus-sidebar container is responsible for entire search container

            constructor() {
                this.results_from_map_container = document.querySelector('#cargus-listContainer');
                this.results_from_search_container = document.getElementById('cargus-search-results-container');
                this.sidebar = document.getElementById('cargus-sidebar');
                this.search_container = document.getElementById('cargus-search-results');
                this.query_container = document.getElementById('cargus-query-container');
                this.markedContainer = document.getElementById('cargus-markedResult');
                this.map = document.getElementById('cargus-map-wrapper');
                this.search_input = document.getElementById('cargus-search-box');
            }

            isMobile() {
                return window.innerWidth <= 768;
            }

            isDesktop() {
                return window.innerWidth > 768;
            }

            watchOrientation() {
                let portrait = window.matchMedia("(orientation: portrait)");
                portrait.addEventListener("change", function(e) {
                    if (e.matches) {
                        map.invalidateSize();
                    } else {
                        map.invalidateSize();
                    }
                })
            }

            watchSearchInput() {
                var inputValue = this.search_input.value;
                if (inputValue.length == 0) {
                    clearSearch();
                }
            }

            isDisplayedResultsEmpty() {
                if (this.results_from_map_container.querySelectorAll('div').length > 0) {
                    return false;
                } else {
                    return true;
                }
            }

            isAnyMarkerSelected() {
                if (pointerTracker.getPointerID()) {
                    return true;
                } else {
                    return false;
                }
            }

            isSearchContainerEmpty() {
                return this.results_from_search_container.querySelectorAll('div').length === 0;
            }


            apply() {
                this.watchOrientation();
                if (this.isMobile()) {

                    if (!this.isAnyMarkerSelected() && !this.isDisplayedResultsEmpty() && this.isSearchContainerEmpty()) {
                        this.results_from_map_container.style.display = 'block';
                    }

                    if (this.isAnyMarkerSelected() && !this.isDisplayedResultsEmpty() && this.isSearchContainerEmpty()) {
                        this.results_from_map_container.style.display = 'none';
                        this.results_from_map_container.style.filter = 'blur(0px)';
                        this.search_container.style.display = 'block';
                    }

                    if (!this.isAnyMarkerSelected() && !this.isDisplayedResultsEmpty() && !this.isSearchContainerEmpty()) {
                        this.results_from_map_container.style.display = 'none';
                    }

                    if (this.isAnyMarkerSelected() && this.isDisplayedResultsEmpty() && this.isSearchContainerEmpty()) {
                        this.markedContainer.style.display = 'block';
                        this.search_container.style.display = 'block';
                    }

                    if (this.isAnyMarkerSelected() && !this.isDisplayedResultsEmpty() && !this.isSearchContainerEmpty()) {
                        this.markedContainer.style.display = 'none';
                    }

                    if (this.isAnyMarkerSelected() && this.isDisplayedResultsEmpty() && !this.isSearchContainerEmpty()) {
                        this.results_from_map_container.style.display = 'none';
                        this.markedContainer.style.display = 'block';
                        this.search_container.style.display = 'none';
                    }

                }

                if (this.isDesktop()) {

                    this.watchSearchInput()

                    if (this.isAnyMarkerSelected() && this.isDisplayedResultsEmpty() && !this.isSearchContainerEmpty()) {
                        this.results_from_map_container.style.display = 'none';
                        this.markedContainer.style.display = 'block';
                        this.results_from_map_container.style.filter = 'blur(3px)';
                        this.search_container.style.display = 'none';
                    }

                    if (!this.isAnyMarkerSelected() && !this.isDisplayedResultsEmpty() && this.isSearchContainerEmpty()) {
                        this.results_from_map_container.style.display = 'block';
                        this.results_from_map_container.style.filter = 'blur(0px)';
                        this.search_container.style.display = 'block';
                    }

                    if (!this.isAnyMarkerSelected() && !this.isDisplayedResultsEmpty() && !this.isSearchContainerEmpty()) {
                        this.search_container.style.display = 'none';
                    }

                    if (this.isAnyMarkerSelected() && !this.isDisplayedResultsEmpty() && this.isSearchContainerEmpty()) {
                        this.results_from_map_container.style.display = 'none';
                        this.search_container.style.display = 'block';
                        this.markedContainer.style.display = 'block';
                    }

                    if (this.isAnyMarkerSelected() && !this.isDisplayedResultsEmpty() && !this.isSearchContainerEmpty()) {
                        this.markedContainer.style.display = 'none';
                        this.search_container.style.display = 'none';
                    }

                    if (this.isAnyMarkerSelected() && this.isDisplayedResultsEmpty() && this.isSearchContainerEmpty()) {
                        this.markedContainer.style.display = 'block';
                        this.search_container.style.display = 'block';
                    }
                }
            }
        }

        var targetSearchResults = document.getElementById('cargus-search-results-container');
        var targetListContainer = document.getElementById('cargus-listContainer');

        // Create a new instance of MutationObserver
        var observer = new MutationObserver(function(mutations) {
            // Clear any existing timeout
            clearTimeout(observer.timeout);
            // Set a new timeout to call reactivity() after a short delay
            observer.timeout = setTimeout(function() {
                reactivity.apply();
            }, 60); // Adjust the delay as needed
        });
        // Configuration of the observer
        var config = {
            childList: true,
            subtree: true,
            characterData: true,
            attributes: true
        };
        // Start observing the target nodes
        observer.observe(targetSearchResults, config);
        observer.observe(targetListContainer, config);
        /**
         * Handles the click event on a marker or marker ID.
         * Updates pointer tracking and marker visibility accordingly.
         * @param {Object|string} eventOrId - The click event object or a marker ID.
         */
        function handleMarkerClick(eventOrId) {

            let markerId;
            // Determine the marker ID from the event or provided ID
            if (typeof eventOrId === 'object' && eventOrId.target.options) {
                markerId = eventOrId.target.options.Id;
            } else {
                markerId = eventOrId;
            }
            // Check if the clicked marker is the currently tracked pointer
            if (pointerTracker.getPointerID() && pointerTracker.getPointerID() == markerId) {
                // Clear the tracked pointer ID and update marker visibility
                pointerTracker.clearPointerID();
                updateMarkers();
            } else {
                // Set the clicked marker as the tracked pointer
                pointerTracker.set(markerId);
                const {
                    latitude,
                    longitude
                } = pointerTracker.getPointerLocation();

                GoTo(latitude, longitude, 14); // Navigate to the pointer's location
                const listContainer = document.querySelector('.results');
                listContainer.style.filter = 'blur(0px)'; // Remove blur effect from the results container
            }

            clearSearch();
        }

        const reactivity = new Reactivity();

        /**
         * Redirects to Google Maps for navigation from the starting location to a specified destination.
         * @param {number} latitude - The latitude of the destination.
         * @param {number} longitude - The longitude of the destination.
         */
        function redirectToGoogleMaps(latitude, longitude) {
            // Retrieve your actual coordinates from GPS_COORDINATES object
            const startingLatitude = DEFAULT_COORDINATES.latitude;
            const startingLongitude = DEFAULT_COORDINATES.longitude;
            // Construct the Google Maps URL with the starting point and destination
            const googleMapsUrl = `https://www.google.com/maps/dir/${startingLatitude},${startingLongitude}/${latitude},${longitude}`;
            // Open a new window with the Google Maps URL
            window.open(googleMapsUrl, '_blank');
        }
        /**
         * Retrieves the actual coordinates using the Geolocation API.
         * @returns {Promise<Object>} A Promise that resolves with an object containing the latitude and longitude.
         */
        function getActualCoordinates() {
            return new Promise((resolve) => {
                // Check if Geolocation API is available
                if (!navigator.geolocation) {
                    resolve(DEFAULT_COORDINATES); // Return default coordinates if unavailable
                }
                // Get the current position using Geolocation API
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        // Extract latitude and longitude from the position
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;
                        // Update GPS_COORDINATES object
                        DEFAULT_COORDINATES.latitude = latitude;
                        DEFAULT_COORDINATES.longitude = longitude;

                        // Resolve the promise with the retrieved coordinates
                        resolve({
                            latitude,
                            longitude,
                        });
                    }, (error) => {
                        resolve(DEFAULT_COORDINATES); // Return default coordinates on error
                    });
            });
        }
        /**
         * Adjusts the map view to a specified latitude and longitude.
         * @param {number} latitude - The latitude of the location to navigate to.
         * @param {number} longitude - The longitude of the location to navigate to.
         * @param {number} [zoomLevel=12] - The zoom level of the map (default is 12).
         * @returns {Array} An array of viewable layers on the map.
         */
        function GoTo(latitude, longitude, zoomLevel = 12) {

            const viewable_layers = [];
            if (map) {
                // Set the view of the map to the specified coordinates and zoom level
                map.setView([latitude, longitude], zoomLevel);
                // Listen for the 'moveend' event to capture viewable layers
                map.on('moveend', function() {
                    map.eachLayer(function(layer) {
                        viewable_layers.push(layer);
                    });
                });
            }

            clearSearch();
            return viewable_layers; // Return the array of viewable layers
        }
        /**
         * Retrieves a location object from the locations data based on the provided ID.
         * @param {number} id - The ID of the location to retrieve.
         * @returns {Object|null} The retrieved location object, or null if not found.
         */
        function getLocationObject(id) {
            // Filter locations data to find the matching location based on the ID
            const filteredLocations = locationsData.filter(location => location[KEY_MAPPING.ID] === id);
            // Return the first matching location object or null if not found
            return filteredLocations.length > 0 ? filteredLocations[0] : null;
        }
        /**
         * Fetches location data from a JSON source, updates the IndexedDB with the new data,
         * and triggers the update of markers on the map.
         */
        const fetchAndUpdateLocations = async () => {

            try {
                // Show loading screen during the process
                // Open the IndexedDB database
                const db = await openDatabase();
                // Fetch location data from the provided JSON path
                let response;

                if (use_test_data) {
                    response = mockup_data;
                } else {
                    response = await fetch_data();
                }

                const jsonData = await response;
                // Begin a transaction to update the object store
                const transaction = db.transaction([objectStoreName], 'readwrite');
                const objectStore = transaction.objectStore(objectStoreName);
                // Clear existing data in the object store
                const clearRequest = objectStore.clear();
                clearRequest.onsuccess = (event) => {
                    // Add each location from the fetched JSON data to the object store
                    jsonData.forEach(location => {
                        objectStore.add(location);
                    });
                    // Complete the transaction and handle success
                    transaction.oncomplete = (event) => {
                        hideLoadingScreen();
                        // Retrieve the updated data from IndexedDB and update markers
                        retrieveDataFromDatabase(db).then(updatedData => {
                            locationsData = updatedData;
                            updateMarkers();
                            map.on('moveend', updateMarkers);
                        }).catch(error => {
                            console.error('Error retrieving updated data:', error);
                        });
                    };
                    // Handle transaction errors
                    transaction.onerror = (event) => {
                        console.error('Transaction error:', event.target.error);
                    };
                };
                // Handle clear request errors
                clearRequest.onerror = (event) => {
                    console.error('Clear request error:', event.target.error);
                };
            } catch (error) {
                console.error('Error fetching or updating location data:', error);
            }
        };
        // Fetch actual coordinates using the getActualCoordinates function
        getActualCoordinates().then(coordinates => {
            // Destructure latitude and longitude from the retrieved coordinates or use default
            const {
                latitude,
                longitude
            } = coordinates || DEFAULT_COORDINATES;
            // Set initial zoom level
            var initialZoom = 10;
            if (coordinates) {
                initialZoom = 16;
            }
            currentLocationPos.latitude = coordinates.latitude;
            currentLocationPos.longitude = coordinates.longitude,
                // Initialize the map with the determined coordinates and initialZoom
                initMap({
                    latitude,
                    longitude,
                    initialZoom
                });
        });
        /**
         * Opens an IndexedDB database, creating or upgrading the object store if needed.
         * Fetches JSON data and stores it in the database during the upgrade process.
         * @returns {Promise<IDBDatabase>} A Promise that resolves with the opened database instance.
         */
        const openDatabase = () => {
            return new Promise((resolve, reject) => {
                // Open or create the indexedDB database
                const request = indexedDB.open(dbName);
                // Handle the upgrade needed event
                request.onupgradeneeded = (event) => {
                    const db = event.target.result;
                    // Create object store if not already present
                    if (!db.objectStoreNames.contains(objectStoreName)) {
                        const objectStore = db.createObjectStore(objectStoreName, {
                            keyPath: 'id',
                            autoIncrement: true
                        });
                        // Create an index if needed
                        objectStore.createIndex('locationIndex', 'location');
                    }
                    // Fetch JSON data and store it in the object store
                    const transaction = event.target.transaction;
                    const objectStore = transaction.objectStore(objectStoreName);

                    if (use_test_data) {
                        mockup_data.forEach(location => objectStore.add(location));
                    } else {
                        fetch_data().then(response => response.json()).then(data => {
                            data.forEach(location => objectStore.add(location));
                        }).catch(error => reject(error));
                    }  
                };
                // Handle the success event
                request.onsuccess = () => {
                    const db = request.result;
                    resolve(db); // Resolve the Promise with the opened database instance
                };
                // Handle the error event
                request.onerror = (event) => {
                    reject(event.target.error); // Reject the Promise with the error
                };
            });
        };
        /**
         * Retrieves data from an IndexedDB database using a read-only transaction.
         * @param {IDBDatabase} db - The IndexedDB database instance.
         * @returns {Promise<Array>} A Promise that resolves with the retrieved data array.
         */
        const retrieveDataFromDatabase = (db) => {
            return new Promise((resolve, reject) => {
                // Begin a read-only transaction
                const transaction = db.transaction([objectStoreName], 'readonly');
                const objectStore = transaction.objectStore(objectStoreName);
                // Request to retrieve all data from the object store
                const retrieveRequest = objectStore.getAll();
                // Handle the success event
                retrieveRequest.onsuccess = (event) => {
                    resolve(event.target.result); // Resolve the Promise with the retrieved data array
                };
                // Handle the error event
                retrieveRequest.onerror = (event) => {
                    reject(event.target.error); // Reject the Promise with the error
                };
            });
        };
        /**
         * Returns a debounced version of a function that delays its execution until after a specified delay.
         * @param {Function} func - The function to be debounced.
         * @param {number} delay - The delay in milliseconds before the function is executed.
         * @returns {Function} The debounced function.
         */

        const updateMarkers = () => {
            if (map && document.getElementById('lockersButton') && document.getElementById('lockersButton')) {
                const bounds = map.getBounds();
                const lockerCheckbox = document.getElementById('lockersButton');
                const shopCheckbox = document.getElementById('shipgoButton');
                // Open IndexedDB database
                const request = indexedDB.open(dbName);
                request.onsuccess = (event) => {
                    const db = event.target.result;
                    const transaction = db.transaction([objectStoreName], 'readonly');
                    const objectStore = transaction.objectStore(objectStoreName);
                    const visibleMarkers = [];
                    objectStore.openCursor().onsuccess = (event) => {
                        const cursor = event.target.result;
                        if (cursor) {
                            const location = cursor.value;
                            const ID = location[KEY_MAPPING.ID];
                            const Longitude = location[KEY_MAPPING.Longitude];
                            const Latitude = location[KEY_MAPPING.Latitude];
                            const PointType = location[KEY_MAPPING.PointType];
                            const point = turf.point([Longitude, Latitude], {
                                LocationID: ID
                            });
                            const boundsPolygon = turf.bboxPolygon([
                                bounds.getWest(),
                                bounds.getSouth(),
                                bounds.getEast(),
                                bounds.getNorth()
                            ]);
                            isMarkerVisible = turf.booleanPointInPolygon(point, boundsPolygon);
                            const isShop = PointType < 50;
                            const isLocker = PointType >= 50;
                            const isPreviousSelected = location.Id === pointerTracker.getPointerID();
                            const showLocker = lockersButton.classList.contains('active') && isLocker;
                            const showShop = shipgoButton.classList.contains('active') && isShop;

                            const shouldShowMarker = showLocker || showShop;
                            if ((isMarkerVisible && shouldShowMarker) || isPreviousSelected) {
                                visibleMarkers.push(location);
                            }
                            cursor.continue();
                        } else {
                            // All markers processed, update the map with visibleMarkers array
                            navbar_updatePointList(visibleMarkers);
                            const markerGroup = createMarkers(visibleMarkers);
                            if (markersLayer) {
                                markersLayer.clearLayers();
                                markersLayer.addLayer(markerGroup);
                            } else {
                                markersLayer = markerGroup;
                                map.addLayer(markersLayer);
                            }
                        }
                    };
                };
            }
        };
        const createMarkers = locations => {
            const markers = locations.map(location => {

                const Id = location[KEY_MAPPING.ID];
                const Latitude = location[KEY_MAPPING.Latitude];
                const Longitude = location[KEY_MAPPING.Longitude];
                const Name = location[KEY_MAPPING.Name];
                const PointType = location[KEY_MAPPING.PointType];
                const isSelected = location[KEY_MAPPING.IsSelected];

                const icon = PointType < 50 ? pointPinIcon : lockerPinIcon;
                if (pointerTracker.getPointerID() && pointerTracker.getPointerID() == Id) {
                    const marker = L.canvasMarker([Latitude, Longitude], {
                        radius: 20,
                        Id,
                        img: {
                            url: pointerTracker.getAssociatedIcon(true),
                            size: [35, 35], //image size ( default [40, 40] )
                            rotate: 0, //image base rotate ( default 0 )
                            offset: {
                                x: 0,
                                y: 0
                            }, //image offset ( default { x: 0, y: 0 } )
                        },
                    }).on('click', handleMarkerClick);
                    return marker;
                }
                // Create the marker and attach the 'click' event listener
                // Make sure handleMarkerClick is called only once.
                const marker = L.canvasMarker([Latitude, Longitude], {
                    Id,
                    radius: 20,
                    img: {
                        url: icon.options.iconUrl,
                        size: [35, 35],
                        rotate: 0,
                        offset: {
                            x: 0,
                            y: 0
                        },
                    },
                }).on('click', once(handleMarkerClick));
                return marker;
            });
            return L.layerGroup(markers);
        };
        const initMap = async ({
            latitude,
            longitude,
            zoom = 15
        }) => {
            try {
                map = L.map('map', {
                    preferCanvas: true,
                    zoomSnap,
                    zoomControl: false,
                    maxZoom: 45,
                    minZoom: 1,
                    wheelPxPerZoomLevel,
                    zoomDelta: 0.55,
                    zoom: 10.5,
                }).setView([latitude, longitude], zoom);

                L.control.zoom({
                    position: 'topright'
                }).addTo(map);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                await fetchAndUpdateLocations();

            } catch (error) {
                console.error('Error initializing map:', error);
            }
        };
        /** Navbar */
        const navbar_updatePointList = (newVisibleMarkerIDs) => {
            visibleMarkerIDs = newVisibleMarkerIDs.slice();
            markerList = newVisibleMarkerIDs.slice();
            let markedResult = document.getElementById('cargus-markedResult');
            if (markedResult) {
                markedResult.innerHTML = ``;
            }
            if (getLocationObject(pointerTracker.getPointerID())) {
                var markeditemData = getLocationObject(pointerTracker.getPointerID());
                const indexToRemove = markerList.findIndex(obj => obj.Id === pointerTracker.getPointerID());
                if (indexToRemove !== -1) {
                    markerList.splice(indexToRemove, 1);
                }
                var
                    Id = markeditemData[KEY_MAPPING.ID],
                    Name = markeditemData[KEY_MAPPING.Name],
                    City = markeditemData[KEY_MAPPING.City],
                    MainPicture = markeditemData[KEY_MAPPING.MainPicture],
                    Address = markeditemData[KEY_MAPPING.Address],
                    AcceptedPaymentType = markeditemData[KEY_MAPPING.AcceptedPaymentType],
                    StreetNo = markeditemData[KEY_MAPPING.StreetNo],
                    AddressDescription = markeditemData[KEY_MAPPING.AddressDescription],
                    Latitude = markeditemData[KEY_MAPPING.Latitude],
                    Longitude = markeditemData[KEY_MAPPING.Longitude];

                const OpenHoursMoStart = markeditemData[KEY_MAPPING.OpenHoursMoStart],
                    OpenHoursMoEnd = markeditemData[KEY_MAPPING.OpenHoursMoEnd],
                    OpenHoursTuStart = markeditemData[KEY_MAPPING.OpenHoursTuStart],
                    OpenHoursTuEnd = markeditemData[KEY_MAPPING.OpenHoursTuEnd],
                    OpenHoursWeStart = markeditemData[KEY_MAPPING.OpenHoursWeStart],
                    OpenHoursWeEnd = markeditemData[KEY_MAPPING.OpenHoursWeEnd],
                    OpenHoursThStart = markeditemData[KEY_MAPPING.OpenHoursThStart],
                    OpenHoursThEnd = markeditemData[KEY_MAPPING.OpenHoursThEnd],
                    OpenHoursFrStart = markeditemData[KEY_MAPPING.OpenHoursFrStart],
                    OpenHoursFrEnd = markeditemData[KEY_MAPPING.OpenHoursFrEnd],
                    OpenHoursSaStart = markeditemData[KEY_MAPPING.OpenHoursSaStart],
                    OpenHoursSaEnd = markeditemData[KEY_MAPPING.OpenHoursSaEnd],
                    OpenHoursSuStart = markeditemData[KEY_MAPPING.OpenHoursSuStart],
                    OpenHoursSuEnd = markeditemData[KEY_MAPPING.OpenHoursSuEnd];


                const schedule = {
                    OpenHoursMoStart,
                    OpenHoursMoEnd,
                    OpenHoursTuStart,
                    OpenHoursTuEnd,
                    OpenHoursWeStart,
                    OpenHoursWeEnd,
                    OpenHoursThStart,
                    OpenHoursThEnd,
                    OpenHoursFrStart,
                    OpenHoursFrEnd,
                    OpenHoursSaStart,
                    OpenHoursSaEnd,
                    OpenHoursSuStart,
                    OpenHoursSuEnd
                }
                const scheduleGenerator = new ScheduleGenerator(schedule);
                const formattedSchedule = scheduleGenerator.generateSchedule();
                let icon = assets_attributes.icons.selectedPointPinIcon.iconUrl;
                const container = document.createElement('div');
                // container.onclick = () => GoTo(Latitude, Longitude, 14)
                container.className = 'card extended-card';
                // Create the main div with icon, content, and image
                const mainDiv = document.createElement('div');
                mainDiv.className = 'main';
                const iconDiv = document.createElement('div');
                iconDiv.className = 'icon';
                const iconImage = document.createElement('img');
                iconImage.src = icon;
                iconImage.alt = 'Icon';
                iconDiv.appendChild(iconImage);
                const contentDiv = document.createElement('div');
                contentDiv.className = 'content';
                const titleParagraph = document.createElement('p');
                titleParagraph.className = 'title';
                titleParagraph.textContent = Name;
                const cityParagraph = document.createElement('p');
                cityParagraph.className = 'city';
                cityParagraph.textContent = City;
                const addressParagraph = document.createElement('p');
                addressParagraph.className = 'adress';
                addressParagraph.textContent = `${Address}`;
                const arrowIcon = document.createElement('i');
                arrowIcon.className = 'fa-solid fa-arrow-right fa-rotate-180';
                const leftArrow = document.createElement('div');
                leftArrow.className = 'cg_leftArrow';
                leftArrow.style.backgroundImage = `url(${assets_attributes.icons.arrow_leftIcon.iconUrl}`;
                const closingButton = document.createElement('span');
                closingButton.className = 'markedResultClosingButton';

                closingButton.textContent = `Back to list`;
                closingButton.addEventListener('click', (event) => {
                    event.preventDefault(); // Prevent the default behavior of the click event
                    clearSearch();
                    handleMarkerClick(markeditemData[KEY_MAPPING.ID]);
                });
                const image = document.createElement('img');
                image.src = markeditemData[KEY_MAPPING.MainPicture] || `${assets_path}/cargus_logo.png`;
                image.alt = 'Pudo Point';

                contentDiv.appendChild(closingButton);
                contentDiv.appendChild(leftArrow)
                closingButton.insertBefore(leftArrow, closingButton.firstChild);
                contentDiv.appendChild(image);
                contentDiv.appendChild(titleParagraph);
                contentDiv.appendChild(cityParagraph);
                contentDiv.appendChild(addressParagraph);
                const imageDiv = document.createElement('div');
                imageDiv.className = 'image';


                // mainDiv.appendChild(iconDiv);
                mainDiv.appendChild(contentDiv);
                mainDiv.appendChild(imageDiv);
                const metaDataDiv = document.createElement('div');
                metaDataDiv.className = 'meta-data';
                const scheduleDiv = document.createElement('div');
                scheduleDiv.className = 'schedule';
                const daysSpan = document.createElement('span');
                daysSpan.className = 'days';
                daysSpan.innerHTML = formattedSchedule.replace(/\n/g, '<br>').replace(/Text/g, '<span style="font-family: YOUR_TEXT_FONT">Text</span>').replace(/\d{1,2}:\d{2} [APap][Mm]/g, '<span style="font-family: YOUR_HOURS_FONT">$&</span>');
                scheduleDiv.appendChild(daysSpan);
                // Create the card-payment div
                const cardPaymentDiv = document.createElement('div');
                cardPaymentDiv.className = 'card-payment';
                const AcceptedPayment = document.createElement('p');
                AcceptedPayment.textContent = 'Payment methods:';
                AcceptedPayment.appendChild(document.createElement('br'));
                const acceptedPaymentSpan = document.createElement('span');
                acceptedPaymentSpan.className = 'accepted-payment';
                if (markeditemData[KEY_MAPPING.AcceptedPaymentType].Online) {
                    const onlineImage = document.createElement('img');
                    onlineImage.src = `${assets_path}/online_pay_icon.svg`;
                    onlineImage.alt = 'Cash';
                    acceptedPaymentSpan.appendChild(onlineImage);
                    acceptedPaymentSpan.appendChild(document.createTextNode(' Online'));
                }
                if (markeditemData[KEY_MAPPING.AcceptedPaymentType].Cash) {
                    const cashImage = document.createElement('img');
                    cashImage.src = `${assets_path}/cash_pay_icon.svg`;
                    cashImage.alt = 'Cash';
                    acceptedPaymentSpan.appendChild(cashImage);
                    acceptedPaymentSpan.appendChild(document.createTextNode(' Cash'));
                }
                if (markeditemData[KEY_MAPPING.AcceptedPaymentType].Card) {
                    const cardImage = document.createElement('img');
                    cardImage.src = `${assets_path}/card_pay_icon.svg`;
                    cardImage.alt = 'Card';
                    acceptedPaymentSpan.appendChild(cardImage);
                    acceptedPaymentSpan.appendChild(document.createTextNode(' Card'));
                }
                cardPaymentDiv.appendChild(AcceptedPayment);
                cardPaymentDiv.appendChild(acceptedPaymentSpan);
                const buttonsDiv = document.createElement('div');
                buttonsDiv.className = 'buttons';
                // Create the parent div for buttons
                const buttonsParentDiv = document.createElement('div');
                buttonsParentDiv.classList.add('text-buttons'); // Add the class 'buttons-parent'
                // Create the parent div for "Pinpoint" button
                const pinpointButtonDiv = document.createElement('div');
                pinpointButtonDiv.classList.add('pinpoint-button');

                pinpointButtonDiv.addEventListener('click', (event) => {
                    event.preventDefault(); // Prevent the default behavior of the click event
                    redirectToGoogleMaps(markeditemData[KEY_MAPPING.Latitude], markeditemData[KEY_MAPPING.Longitude]);
                });

                const resultContainer = document.getElementById('cargus-search-results-container');
                const listContainer = document.querySelector('.results');
                listContainer.style.filter = 'blur(0px)';
                const searchInput = mapWidgetContainer.querySelector('#cargus-search-box');
                searchInput.value = ''; // Clear the input value
                // Create the pinpoint icon element
                const pinpointIcon = document.createElement('img');
                pinpointIcon.src = `${assets_path}/icon-info-circle.svg`;
                // Create the text element
                const buttonText = document.createElement('span');
                buttonText.textContent = 'See the route';
                buttonText.classList.add('button-text');
                // Append the elements to the parent div
                pinpointButtonDiv.appendChild(pinpointIcon);
                pinpointButtonDiv.appendChild(buttonText);
                // Append the parent div to the buttonsParentDiv
                buttonsParentDiv.appendChild(pinpointButtonDiv);
                // Create the parent div for "Details" button
                const detailsButtonDiv = document.createElement('div');
                detailsButtonDiv.classList.add('details-button');
                const detailsIcon = document.createElement('img');
                detailsIcon.src = `${assets_path}/directions_icon.svg`;
                // Create the text element
                const detailsText = document.createElement('span');
                detailsText.textContent = 'Details';
                detailsText.classList.add('button-text');
                // Append the elements to the parent div
                detailsButtonDiv.appendChild(detailsIcon);
                detailsButtonDiv.appendChild(detailsText);
                // Append the parent div to the buttonsParentDiv
                buttonsParentDiv.appendChild(detailsButtonDiv);
                // Append the parent div to the buttonsDiv (or any other parent element you have)
                buttonsDiv.appendChild(buttonsParentDiv);
                // Function to create the modal content
                function createModalContent({
                    MainPicture,
                    Address
                }) {
                    const modalContent = document.createElement('div');
                    const modalImage = document.createElement('img');
                    modalImage.src = MainPicture || `${assets_path}/cargus_logo.png`;
                    modalImage.classList.add('modal-image');
                    const addressText = document.createTextNode(Address);
                    const addressElement = document.createElement('p');
                    addressElement.appendChild(addressText);
                    addressElement.classList.add('modal-address');
                    modalContent.appendChild(modalImage);
                    modalContent.appendChild(addressElement);
                    return modalContent;
                }

                function showDetails({
                    MainPicture,
                    Address
                }) {
                    const modalContent = createModalContent({
                        MainPicture,
                        Address
                    });
                    const modal = document.createElement('div');
                    modal.classList.add('modal', 'fade', 'cargus');
                    modal.tabIndex = '-1';
                    modal.role = 'dialog';
                    // Create the modal dialog
                    const modalDialog = document.createElement('div');
                    modalDialog.classList.add('modal-dialog');
                    const closeButton = document.createElement('span');
                    closeButton.style.backgroundImage = `url(${assets_attributes.icons.closeIcon.iconUrl})`;
                    closeButton.textContent = '';
                    closeButton.classList.add('close-button');
                    modalDialog.appendChild(closeButton);
                    modalDialog.role = 'document';
                    const modalContentWrapper = document.createElement('div');
                    modalContentWrapper.classList.add('modal-content');
                    const modal_image = document.createElement('img');
                    modal_image.classList.add('modal-img');
                    modalContentWrapper.appendChild(modalContent);
                    modalDialog.appendChild(modal_image);
                    modalDialog.appendChild(modalContentWrapper);
                    modal.appendChild(modalDialog);
                    document.body.appendChild(modal);
                    closeButton.addEventListener('click', (event) => {
                        event.preventDefault(); // Prevent the default behavior of the click event (usually form submission or link navigation)
                        modal.style.display = 'none';
                    });
                }
                detailsButtonDiv.addEventListener('click', (event) => {
                    event.preventDefault(); // Prevent the default behavior of the click event (usually form submission or link navigation)
                    showDetails({
                        MainPicture,
                        Address
                    });
                });

                const choosePoint = document.createElement('button');
                choosePoint.classList.add('orange-button')
                choosePoint.textContent = 'Set as delivery point';
                choosePoint.onclick = (event) => {
                    event.preventDefault();
                    setTimeout(() => {
                        ChooseMarker(markeditemData);
                    }, 50); // You can adjust the delay time in milliseconds as needed
                };
                buttonsDiv.appendChild(choosePoint);
                metaDataDiv.appendChild(scheduleDiv);
                metaDataDiv.appendChild(cardPaymentDiv);
                metaDataDiv.appendChild(buttonsDiv);
                container.appendChild(mainDiv);
                container.appendChild(metaDataDiv);
                markedResult.appendChild(container);
            }
            const markedResultExtendedCard = document.querySelector('#cargus-markedResult .extended-card');
            // Using library to create virtualized list for pudo points shown in sidebar.
            // Height need to be known before rendering, so we calculate it beforehand.
            var configHeight;

            if (reactivity.isMobile()) {
                var itemHeight = 220;
                var containerWidth = '336px';
                var elementsPadding = 2;
            } else {
                var itemHeight = 620;
                var containerWidth = '400px';
                var elementsPadding = 16;
            }

            configHeight = '90%';

            var config = {
                height: configHeight,
                itemHeight: 120,
                total: markerList.length,
                reverse: false,
                scrollerTagName: 'ul',
                rowClassName: 'MapContainer',
                generate(row) {
                    var listItem = document.createElement('div');
                    var itemData = markerList[row];
                    var icon;
                    if (itemData[KEY_MAPPING.PointType] > 50) {
                        icon = assets_attributes.icons.pointPinIcon.iconUrl
                    } else {
                        icon = assets_attributes.icons.lockerPinIcon.iconUrl
                    }
                    var {
                        Id = KEY_MAPPING.ID,
                            Name = KEY_MAPPING.Name,
                            City = KEY_MAPPING.City,
                            StreetName = KEY_MAPPING.StreetName,
                            StreetNo = KEY_MAPPING.StreetNo,
                            MainPicture = KEY_MAPPING.MainPicture,
                            Address = KEY_MAPPING.Address,
                            AcceptedPaymentType = KEY_MAPPING.AcceptedPaymentType,
                    } = itemData;

                    const OpenHoursMoStart = itemData[KEY_MAPPING.OpenHoursMoStart],
                        OpenHoursMoEnd = itemData[KEY_MAPPING.OpenHoursMoEnd],
                        OpenHoursTuStart = itemData[KEY_MAPPING.OpenHoursTuStart],
                        OpenHoursTuEnd = itemData[KEY_MAPPING.OpenHoursTuEnd],
                        OpenHoursWeStart = itemData[KEY_MAPPING.OpenHoursWeStart],
                        OpenHoursWeEnd = itemData[KEY_MAPPING.OpenHoursWeEnd],
                        OpenHoursThStart = itemData[KEY_MAPPING.OpenHoursThStart],
                        OpenHoursThEnd = itemData[KEY_MAPPING.OpenHoursThEnd],
                        OpenHoursFrStart = itemData[KEY_MAPPING.OpenHoursFrStart],
                        OpenHoursFrEnd = itemData[KEY_MAPPING.OpenHoursFrEnd],
                        OpenHoursSaStart = itemData[KEY_MAPPING.OpenHoursSaStart],
                        OpenHoursSaEnd = itemData[KEY_MAPPING.OpenHoursSaEnd],
                        OpenHoursSuStart = itemData[KEY_MAPPING.OpenHoursSuStart],
                        OpenHoursSuEnd = itemData[KEY_MAPPING.OpenHoursSuEnd];

                    schedule = {
                        OpenHoursMoStart,
                        OpenHoursMoEnd,
                        OpenHoursTuStart,
                        OpenHoursTuEnd,
                        OpenHoursWeStart,
                        OpenHoursWeEnd,
                        OpenHoursThStart,
                        OpenHoursThEnd,
                        OpenHoursFrStart,
                        OpenHoursFrEnd,
                        OpenHoursSaStart,
                        OpenHoursSaEnd,
                        OpenHoursSuStart,
                        OpenHoursSuEnd
                    }

                    const scheduleGenerator = new ScheduleGenerator(schedule);

                    const formattedSchedule = scheduleGenerator.generateSchedule();

                    let background;
                    const container = document.createElement('div');
                    container.style.backgroundColor = background;
                    container.className = 'card';
                    container.onclick = () => handleMarkerClick(itemData[KEY_MAPPING.ID]);
                    // Create the icon div and image element
                    const iconDiv = document.createElement('div');
                    iconDiv.className = 'icon';
                    const iconImage = document.createElement('img');
                    iconImage.src = icon;
                    iconImage.alt = 'Icon';
                    iconDiv.appendChild(iconImage);
                    const contentDiv = document.createElement('div');
                    contentDiv.className = 'content';
                    const titleParagraph = document.createElement('p');
                    titleParagraph.className = 'title';
                    titleParagraph.textContent = Name;
                    const cityParagraph = document.createElement('p');
                    cityParagraph.className = 'city';
                    cityParagraph.textContent = City;
                    const addressParagraph = document.createElement('p');
                    addressParagraph.className = 'adress';
                    addressParagraph.textContent = `${City}, ${StreetName} ${StreetNo}`;

                    const daysSpan = document.createElement('span');

                    const scheduleDiv = document.createElement('div');
                    daysSpan.className = 'days';
                    daysSpan.innerHTML = formattedSchedule.replace(/\n/g, '<br>').replace(/Text/g, '<span style="font-family: YOUR_TEXT_FONT">Text</span>').replace(/\d{1,2}:\d{2} [APap][Mm]/g, '<span style="font-family: YOUR_HOURS_FONT">$&</span>');
                    scheduleDiv.appendChild(daysSpan);
                    contentDiv.appendChild(titleParagraph);
                    contentDiv.appendChild(cityParagraph);
                    contentDiv.appendChild(addressParagraph);
                    contentDiv.appendChild(scheduleDiv);
                    const imageDiv = document.createElement('div');
                    imageDiv.className = 'image';
                    const image = document.createElement('img');
                    image.src = itemData[KEY_MAPPING.MainPicture] || `${assets_path}/cargus_logo.png`;
                    image.alt = 'Pudo Point';
                    // imageDiv.appendChild(image);
                    // container.appendChild(iconDiv);
                    container.appendChild(contentDiv);
                    container.appendChild(imageDiv);
                    listItem.appendChild(container);

                    const copiedListItem = listItem.cloneNode(true);
                    // Position the copied element off-screen
                    copiedListItem.className = 'MapContainer XXXXXXX';
                    copiedListItem.style.position = 'absolute';
                    copiedListItem.style.left = '-9999px';
                    copiedListItem.style.width = containerWidth;
                    // Append the copied element to the document body
                    mapWidgetContainer.appendChild(copiedListItem);
                    // Remove the copied element from the DOM
                    let elementHeight = copiedListItem.offsetHeight;
                    // mapWidgetContainer.removeChild(copiedListItem);

                    return {
                        element: listItem,
                        height: elementHeight + elementsPadding
                    };
                }
            };
            if (list) {
                list.destroy();
            }
            var listContainer = document.getElementById('cargus-listContainer');
            if (listContainer) {
                list = HyperList.create(listContainer, config);
            }
            // if (listContainer && visibleMarkerIDs.length > 0) {
            //     
            //     listContainer.style.display = 'block';
            //     

            // } else if (listContainer) {
            //     listContainer.style.display = 'none';
            // }
            handleMobileMenu();
        };
        var target = document.querySelector('#cargus-map-wrapper')
        // $('#cargus-map-wrapper').on('DOMSubtreeModified', function(){
        //     reactivity.apply();
        // })
        function showExtendedDetails() {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'btn btn-primary';
            button.setAttribute('data-toggle', 'modal');
            button.setAttribute('data-target', '#exampleModal');
            button.textContent = 'Launch demo modal';
            const modalDiv = document.createElement('div');
            modalDiv.className = 'cargus modal fade';
            modalDiv.id = 'exampleModal';
            modalDiv.tabIndex = '-1';
            modalDiv.role = 'dialog';
            modalDiv.setAttribute('aria-labelledby', 'exampleModalLabel');
            modalDiv.setAttribute('aria-hidden', 'true');
            const modalDialogDiv = document.createElement('div');
            modalDialogDiv.className = 'modal-dialog';
            modalDialogDiv.role = 'document';
            const modalContentDiv = document.createElement('div');
            modalContentDiv.className = 'modal-content';
            const modalHeaderDiv = document.createElement('div');
            modalHeaderDiv.className = 'modal-header';
            const modalTitle = document.createElement('h5');
            modalTitle.className = 'modal-title';
            modalTitle.id = 'exampleModalLabel';
            modalTitle.textContent = 'Modal title';
            modalHeaderDiv.appendChild(modalTitle);
            const closeButton = document.createElement('button');
            closeButton.type = 'button';
            closeButton.className = 'close';
            closeButton.setAttribute('data-dismiss', 'modal');
            closeButton.setAttribute('aria-label', 'Close');
            const closeSpan = document.createElement('span');
            closeSpan.setAttribute('aria-hidden', 'true');
            closeSpan.innerHTML = '&times;';
            closeButton.appendChild(closeSpan);
            modalHeaderDiv.appendChild(closeButton);
            // Create the modal body div
            const modalBodyDiv = document.createElement('div');
            modalBodyDiv.className = 'modal-body';
            modalBodyDiv.textContent = '...';
            const modalFooterDiv = document.createElement('div');
            modalFooterDiv.className = 'modal-footer';
            const closeButtonFooter = document.createElement('button');
            closeButtonFooter.type = 'button';
            closeButtonFooter.className = 'btn btn-secondary';
            closeButtonFooter.setAttribute('data-dismiss', 'modal');
            closeButtonFooter.textContent = 'Close';
            const saveButton = document.createElement('button');
            saveButton.type = 'button';
            saveButton.className = 'btn btn-primary';
            saveButton.textContent = 'Save changes';
            modalFooterDiv.appendChild(closeButtonFooter);
            modalFooterDiv.appendChild(saveButton);
            // Add the components to construct the modal
            modalContentDiv.appendChild(modalHeaderDiv);
            modalContentDiv.appendChild(modalBodyDiv);
            modalContentDiv.appendChild(modalFooterDiv);
            modalDialogDiv.appendChild(modalContentDiv);
            modalDiv.appendChild(modalDialogDiv);
            const parentElement = document.getElementById('your-parent-element-id');
            parentElement.appendChild(button);
            parentElement.appendChild(modalDiv);
            const tempContainer = document.createElement('div');
            tempContainer.innerHTML = modalContent;
            const modalElement = tempContainer.firstElementChild;
            document.body.appendChild(modalElement);
            $('#myModal').modal('show');
            $(modalElement).on('hidden.bs.modal', function() {
                document.body.removeChild(modalElement);
            });
        }

        function clearSearch() {

            const listContainer = document.querySelector('.results');
            if (listContainer) {
                listContainer.style.filter = 'blur(0px)';
                searchBox.value = '';
                clearButton.style.display = 'none';
                sidebar.style.height = '';
                setTimeout(() => {
                    resultContainer.innerHTML = '';
                }, 50); // Delay clearing by 100 milliseconds
            }
            return false;
        }

        function renderSearchResults(databaseSearchResults, apiSearchResults) {

            const listContainer = document.querySelector('.results');

            resultContainer.innerHTML = '';

            if (databaseSearchResults.length === 0 && searchBox.value.length === 0) {
                listContainer.style.display = 'flex';
                searchBar.style.display = 'block';
                return;
            }

            if (databaseSearchResults.length === 0 && apiSearchResults.length === 0) {
                resultContainer.innerHTML = '';
                return false;
            }

            databaseSearchResults && databaseSearchResults.forEach(result => {

                const OpenHoursMoStart = result.item[KEY_MAPPING.OpenHoursMoStart],
                    OpenHoursMoEnd = result.item[KEY_MAPPING.OpenHoursMoEnd],
                    OpenHoursTuStart = result.item[KEY_MAPPING.OpenHoursTuStart],
                    OpenHoursTuEnd = result.item[KEY_MAPPING.OpenHoursTuEnd],
                    OpenHoursWeStart = result.item[KEY_MAPPING.OpenHoursWeStart],
                    OpenHoursWeEnd = result.item[KEY_MAPPING.OpenHoursWeEnd],
                    OpenHoursThStart = result.item[KEY_MAPPING.OpenHoursThStart],
                    OpenHoursThEnd = result.item[KEY_MAPPING.OpenHoursThEnd],
                    OpenHoursFrStart = result.item[KEY_MAPPING.OpenHoursFrStart],
                    OpenHoursFrEnd = result.item[KEY_MAPPING.OpenHoursFrEnd],
                    OpenHoursSaStart = result.item[KEY_MAPPING.OpenHoursSaStart],
                    OpenHoursSaEnd = result.item[KEY_MAPPING.OpenHoursSaEnd],
                    OpenHoursSuStart = result.item[KEY_MAPPING.OpenHoursSuStart],
                    OpenHoursSuEnd = result.item[KEY_MAPPING.OpenHoursSuEnd];

                schedule = {
                    OpenHoursMoStart,
                    OpenHoursMoEnd,
                    OpenHoursTuStart,
                    OpenHoursTuEnd,
                    OpenHoursWeStart,
                    OpenHoursWeEnd,
                    OpenHoursThStart,
                    OpenHoursThEnd,
                    OpenHoursFrStart,
                    OpenHoursFrEnd,
                    OpenHoursSaStart,
                    OpenHoursSaEnd,
                    OpenHoursSuStart,
                    OpenHoursSuEnd
                }
                const scheduleGenerator = new ScheduleGenerator(schedule);

                const formattedSchedule = scheduleGenerator.generateSchedule();

                const resultItem = document.createElement('div');
                resultItem.style.backgroundColor = result.background;

                resultItem.addEventListener('click', (event) => {
                    event.preventDefault(); // Prevent the default behavior of the click event (usually form submission or link navigation)
                    handleMarkerClick(result.item.Id);
                });

                resultItem.classList.add('card');
                const iconDiv = document.createElement('div');
                iconDiv.classList.add('icon');
                const iconImg = document.createElement('img');
                const iconFilename = 'spointpin.png';
                iconImg.src = assets_attributes.icons.selectedPointPinIcon.iconUrl;
                iconImg.alt = 'Icon';
                iconDiv.appendChild(iconImg);
                // Create the content div
                const contentDiv = document.createElement('div');
                contentDiv.classList.add('content');
                // Create the title paragraph
                const titlePara = document.createElement('p');
                titlePara.classList.add('title');
                titlePara.textContent = result.item.Name;
                // Create the city paragraph
                const cityPara = document.createElement('p');
                cityPara.classList.add('city');
                cityPara.textContent = result.item.City;
                // Create the address paragraph
                const addressPara = document.createElement('p');
                addressPara.classList.add('address');
                addressPara.textContent = `${result.item.City}`;
                const scheduleDiv = document.createElement('div');

                const daysSpan = document.createElement('span');
                daysSpan.className = 'days';
                daysSpan.innerHTML = formattedSchedule.replace(/\n/g, '<br>').replace(/Text/g, '<span style="font-family: YOUR_TEXT_FONT">Text</span>').replace(/\d{1,2}:\d{2} [APap][Mm]/g, '<span style="font-family: YOUR_HOURS_FONT">$&</span>');

                contentDiv.appendChild(titlePara);
                contentDiv.appendChild(addressPara);
                contentDiv.append(daysSpan);
                const imageDiv = document.createElement('div');
                imageDiv.classList.add('image');
                resultItem.appendChild(contentDiv);
                resultItem.appendChild(imageDiv);
                resultContainer.appendChild(resultItem);
            });

            apiSearchResults && apiSearchResults.forEach(result => {

                const Latitude = result.lat;
                const Longitude = result.lon;
                const displayName = result.display_name;
                const resultItem = document.createElement('div');

                resultItem.addEventListener('click', (event) => {
                    event.preventDefault();
                    GoTo(Latitude, Longitude, 16);
                    pointerTracker.clearPointerID();
                    updateMarkers();
                });

                resultItem.classList.add('card');

                const contentDiv = document.createElement('div');
                contentDiv.classList.add('content');
                const titlePara = document.createElement('p');
                titlePara.classList.add('title');
                titlePara.textContent = displayName;
                contentDiv.appendChild(titlePara);
                resultItem.appendChild(contentDiv);
                resultContainer.appendChild(resultItem);

            })

        }

        async function performSearch() {

            const searchTerm = searchBox.value.toLowerCase();

            let apiSearchResults;

            if (!filter_address_button.classList.contains('active')) {
                apiSearchResults = false;
            } else {
                apiSearchResults = await addressSearch.search(searchTerm);
            }

            const request = indexedDB.open(dbName);

            request.onsuccess = (event) => {
                const db = event.target.result;
                const transaction = db.transaction([objectStoreName], 'readonly');
                const objectStore = transaction.objectStore(objectStoreName);
                const storedData = [];
                objectStore.openCursor().onsuccess = (event) => {
                    const cursor = event.target.result;
                    if (cursor) {
                        const location = cursor.value;
                        storedData.push(location);
                        cursor.continue();
                    } else {
                        const options = {
                            keys: ['Name'],
                            threshold: 0.3,
                        };
                        const fuse = new Fuse(storedData, options);
                        const databaseSearchResults = fuse.search(searchTerm, {
                            limit: 15
                        });
                        resultContainer.innerHTML = '';
                        renderSearchResults(databaseSearchResults, apiSearchResults);
                    }
                };
            };
            request.onerror = (event) => {
                console.error('Database error:', event.target.error);
            };
        }

        clearButton.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent the default behavior of the click event (usually form submission or link navigation)
            clearSearch();
        });

        filter_address_button.addEventListener("click", function() {
            if (filter_address_button.classList.contains("active")) {
                filter_address_button.classList.remove("active");
                performSearch();
            } else {
                filter_address_button.classList.add("active");
                performSearch();
            }
        });

        lockersButton.addEventListener("click", function() {
            if (lockersButton.classList.contains("active")) {
                lockersButton.classList.remove("active");
            } else {
                lockersButton.classList.add("active");
            }
            updateMarkers();
        });

        shipgoButton.addEventListener("click", function() {
            if (shipgoButton.classList.contains("active")) {
                shipgoButton.classList.remove("active");
            } else {
                shipgoButton.classList.add("active");
            }
            updateMarkers();
        });

        const closeButton = document.getElementById('close-modal');

        // searchBox.addEventListener('keydown', event => {
        // 	if(event.key === 'Enter') {
        // 		performSearch();
        // 	}
        // });
        // searchBox.addEventListener('keydown', event => {
        // 	if(event.key === 'Enter') {
        // 		performSearch();
        // 	}
        // });

        const debouncedPerformSearch = debounce(performSearch, 300); // Adjust the delay as needed

        searchBox.addEventListener('input', () => {
            if (searchBox.value.length > 0) {
                closeSearchBox.style.display = 'flex';
                sidebar.style.height = window.visualViewport.height;
                const resultContainer = document.getElementById('cargus-search-results-container');
                const searchResultBox = document.querySelectorAll('.cargus-map-widget .search');
                resultContainer.style.display = 'block';

            } else {
                closeSearchBox.style.display = 'none';
                const resultContainer = document.getElementById('cargus-search-results-container');
                resultContainer.style.display = 'block';
                sidebar.style.height = '';
                clearSearch();
            }

            debouncedPerformSearch();

        });

        searchBox.addEventListener('click', function() {
            // Check if the device is a mobile device based on screen width or other criteria
            if (window.innerWidth <= 768) { // Adjust the width as needed for your mobile breakpoint
                // Change something when clicked on mobile
                sidebar.style.heights = window.visualViewport.height;
                // You can add more changes here as needed
            }
        });

        // searchBox.addEventListener('blur', function() {
        //     sidebar.style.minHeight = '50%';
        // });

        currentLocationButton.addEventListener('click', (event) => {
            event.preventDefault();
            GoTo(currentLocationPos.latitude, currentLocationPos.longitude, 14);
        })
        closeSearchBox.addEventListener('click', (event) => {
            event.preventDefault();
            clearSearch();
        });

        if (closeButton) {
            closeButton.addEventListener('click', (event) => {
                event.preventDefault();
                setTimeout(() => {
                    closeModal();
                }, 50);
            });
        }
    }
    window.initMapWidget = CargusMapWidget;
    return {
        initialize: CargusMapWidget
    }
};