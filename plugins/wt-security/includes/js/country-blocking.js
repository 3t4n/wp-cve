const regions = [
  {
    region: __('Asia', 'wtotem'),
    countries: [
      "AF",
      "AM",
      "AZ",
      "BH",
      "BD",
      "BT",
      "BN",
      "KH",
      "CN",
      "CY",
      "GE",
      "HK",
      "IN",
      "ID",
      "IR",
      "IQ",
      "IL",
      "JP",
      "JO",
      "KZ",
      "KP",
      "KR",
      "KW",
      "KG",
      "LA",
      "LB",
      "MO",
      "MY",
      "MV",
      "MN",
      "MM",
      "NP",
      "OM",
      "PK",
      "PS",
      "PH",
      "QA",
      "SA",
      "SG",
      "LK",
      "SY",
      "TW",
      "TJ",
      "TH",
      "TL",
      "TR",
      "TM",
      "AE",
      "UZ",
      "VN",
      "YE",
    ],
  },
  {
    region: __('Europe', 'wtotem'),
    countries: [
      "AX",
      "AL",
      "AD",
      "AT",
      "BY",
      "BE",
      "BA",
      "BG",
      "HR",
      "CZ",
      "DK",
      "EE",
      "FO",
      "FI",
      "FR",
      "DE",
      "GI",
      "GR",
      "GG",
      "VA",
      "HU",
      "IS",
      "IE",
      "IM",
      "IT",
      "JE",
      "LV",
      "LI",
      "LT",
      "LU",
      "MT",
      "MD",
      "MC",
      "ME",
      "NL",
      "MK",
      "NO",
      "PL",
      "PT",
      "RO",
      "RU",
      "SM",
      "RS",
      "SK",
      "SI",
      "ES",
      "SJ",
      "SE",
      "CH",
      "UA",
      "GB",
    ],
  },
  {
    region: __('Africa', 'wtotem'),
    countries: [
      "DZ",
      "AO",
      "BJ",
      "BW",
      "IO",
      "BF",
      "BI",
      "CV",
      "CM",
      "CF",
      "TD",
      "KM",
      "CG",
      "CD",
      "CI",
      "DJ",
      "EG",
      "GQ",
      "ER",
      "SZ",
      "ET",
      "TF",
      "GA",
      "GM",
      "GH",
      "GN",
      "GW",
      "KE",
      "LS",
      "LR",
      "LY",
      "MG",
      "MW",
      "ML",
      "MR",
      "MU",
      "YT",
      "MA",
      "MZ",
      "NA",
      "NE",
      "NG",
      "RE",
      "RW",
      "SH",
      "ST",
      "SN",
      "SC",
      "SL",
      "SO",
      "ZA",
      "SS",
      "SD",
      "TZ",
      "TG",
      "TN",
      "UG",
      "EH",
      "ZM",
      "ZW",
    ],
  },
  {
    region: __('Oceania', 'wtotem'),
    countries: [
      "AS",
      "AU",
      "CX",
      "CC",
      "CK",
      "FJ",
      "PF",
      "GU",
      "HM",
      "KI",
      "MH",
      "FM",
      "NR",
      "NC",
      "NZ",
      "NU",
      "NF",
      "MP",
      "PW",
      "PG",
      "PN",
      "WS",
      "SB",
      "TK",
      "TO",
      "TV",
      "UM",
      "VU",
      "WF",
    ],
  },
  {
    region: __('Americas', 'wtotem'),
    countries: [
      "AI",
      "AG",
      "AR",
      "AW",
      "BS",
      "BB",
      "BZ",
      "BM",
      "BO",
      "BQ",
      "BV",
      "BR",
      "CA",
      "KY",
      "CL",
      "CO",
      "CR",
      "CU",
      "CW",
      "DM",
      "DO",
      "EC",
      "SV",
      "FK",
      "GF",
      "GL",
      "GD",
      "GP",
      "GT",
      "GY",
      "HT",
      "HN",
      "JM",
      "MQ",
      "MX",
      "MS",
      "NI",
      "PA",
      "PY",
      "PE",
      "PR",
      "BL",
      "KN",
      "LC",
      "MF",
      "PM",
      "VC",
      "SX",
      "GS",
      "SR",
      "TT",
      "TC",
      "US",
      "UY",
      "VE",
      "VG",
      "VI",
    ],
  },
];

const attacksContainer = document.querySelector(
  ".country-blocking-modal-attacks-container"
);
const countrySearchForm = document.querySelector("#country-search");
const searchInput = countrySearchForm.querySelector("input");
const countryBlockingForm = document.querySelector("#country-blocking");
const regionsContainer = countryBlockingForm.querySelector(
  ".country-blocking-form__main"
);
const numOfBlockedCountries = document.querySelector("#num-of-countries");
const selectAll = document.querySelector("#select-all");

selectAll.addEventListener("change", (event) => {
  const allCountries = regionsContainer.querySelectorAll("[type=checkbox]");
  allCountries.forEach((checkbox) => {
    checkbox.checked = event.target.checked;
    checkbox.dataset.checked = event.target.checked;
  });

  const countries = Array.from(
      regionsContainer.querySelectorAll(".country-checkbox")
  )
      .filter((checkbox) => JSON.parse(checkbox.dataset.checked))
      .map((checkbox) => checkbox.name);

  checkedCountries = checkedCountries.concat(countries);

  AmplitudeAnalytics.addAllCountry(checkedCountries);

  const numNodes = document.querySelectorAll(".blocked-counter");
  const countriesContainers = document.querySelectorAll(
    ".country-blocking-form__region-countries"
  );
  numNodes.forEach((node, index) => {
    node.textContent = event.target.checked
      ? countriesContainers[index].querySelectorAll(".country-checkbox").length
      : 0;
  });

  if (searchInput.value.length === 0 && !event.target.checked) {
    checkedCountries = [];
  }
  if (searchInput.value.length > 0 && event.target.checked) {
    checkedCountries = [...new Set(checkedCountries.concat(countries))];
  }
});

const getCountryNameByCode = new Intl.DisplayNames(["en"], { type: "region" });

function toggleOpen() {
  const countriesContainer = this.nextElementSibling;
  const svg = this.querySelector("svg");
  countriesContainer.classList.toggle("country-blocking-accordion--open");
  countriesContainer.classList.toggle(
    "country-blocking-form__region-countries--open"
  );
  svg.classList.toggle("chevron--open");
}

function toggleSelectRegion(event) {
  const regionCheckboxes =
      this.parentElement.parentElement.querySelectorAll(".country-checkbox");
  const { checked } = event.target;
  regionCheckboxes.forEach((checkbox) => {
    checkbox.checked = checked;
    checkbox.dataset.checked = checked;
    if (checked) checkedCountries.push(checkbox.name);
    else
      checkedCountries = checkedCountries.filter(
          (country) => country !== checkbox.name
      );
  });
}

let checkedCountries = [];

function updateCounter(node) {
  return (event) => {
    const { checked } = event.target;

    if (event.target.classList.contains("select-all-region")) {
      const allRegion =
          event.target.parentElement.parentElement.querySelectorAll(
              ".country-checkbox"
          );
      return checked
          ? (node.textContent = allRegion.length)
          : (node.textContent = 0);
    }
    const num = Number(node.textContent);
    node.textContent = checked ? num + 1 : num - 1;
    event.target.dataset.checked = checked;
    if (!checked) {
      checkedCountries = checkedCountries.filter(
          (country) => country !== event.target.name
      );
    } else {
      checkedCountries.push(event.target.name);
    }

    AmplitudeAnalytics.addContinent(checkedCountries);

    const allRegionCheckbox =
        event.target.parentElement.parentElement.querySelector(
            ".select-all-region"
        );
    const allRegionCheckboxes = Array.from(
        event.target.parentElement.parentElement.querySelectorAll(
            ".country-checkbox"
        )
    );
    allRegionCheckboxes.splice(
        allRegionCheckboxes.findIndex(
            (checkbox) => checkbox.name === event.target.name
        ),
        1,
        event.target
    );
    const isRegionChecked = allRegionCheckboxes.every((checkbox) =>
        JSON.parse(checkbox.dataset.checked)
    );

    if (isRegionChecked) {
      allRegionCheckbox.checked = true;
      allRegionCheckbox.dataset.checked = true;
    } else {
      allRegionCheckbox.checked = false;
      allRegionCheckbox.dataset.checked = false;
    }

  };
}

const ALL_COUNTRIES = regions.map((region) => region.countries).flat();

const populateCountries = (array, open = false, blockedCountries = []) => {
  const buttons = regionsContainer.querySelectorAll("button");
  buttons.forEach((button) => button.removeEventListener("click", toggleOpen));
  const regionBtns = regionsContainer.querySelectorAll("button");
  regionBtns.forEach((button) =>
      button.removeEventListener("click", toggleOpen)
  );

  if (!isRepaint) checkedCountries = blockedCountries;

  if (isRepaint) {
    blockedCountries.length > 0
        ? (checkedCountries = blockedCountries)
        : checkedCountries.concat(
            Array.from(regionsContainer.querySelectorAll(".country-checkbox"))
                .filter((checkbox) => (checkbox.dataset.checked = "true"))
                .map((checkbox) => checkbox.name)
        );
  }

  regionsContainer.innerHTML = "";
  selectAll.checked = false;

  array.forEach((item) => {
    regionsContainer.innerHTML += `
    <button type="button" class="country-blocking-form__region-container country-blocking-accordion">
            <div>
              <p class="country-blocking-form__region-name">
                ${item.region}
              </p>
              <p class="country-blocking-form__region-blocked">
                <span class="blocked-counter">${
        item.countries.filter((country) =>
            checkedCountries.includes(country)
        ).length
    }</span> ${__('countries blocked from','wtotem')} <span>${item.region}</span>
              </p>
            </div>
            <svg class="chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" width="16" height="16">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
    </button>
    <div class="country-blocking-form__region-countries ${
        open &&
        item.countries.length &&
        "country-blocking-form__region-countries--open"
    }">
            <div class="country-blocking-form__select-region">
              <label for="select-all-${item.region}">${__('Select all','wtotem')}</label>
              <input id="select-all-${
        item.region
    }" type="checkbox" class="wt-checkbox country-blocking-form__checkbox select-all-region">
            </div>
          ${item.countries
        .map((country) => {
          const isChecked = checkedCountries.includes(country);
          return `<div class="country-blocking-form__country-container">
              <label for="${country}" class="country-blocking-form__country">
                <img width="19" height="13" src="https://assets.wtotem.net/images/flags/${country.toLowerCase()}.png" alt="Flag of ${country}">
                <p>${getCountryNameByCode.of(country)}</p>
              </label>
              <input name="${country}" id="${country}" ${
              isChecked ? "checked" : ""
          }
              data-checked="${isChecked}"
              type="checkbox" class="wt-checkbox country-blocking-form__checkbox country-checkbox">
            </div>`;
        })
        .join("")}
    </div>`;
  });
  const countryContainers = document.querySelectorAll(
      ".country-blocking-form__region-countries"
  );

  countryContainers.forEach((container, index) => {
    const numNodes = document.querySelectorAll(".blocked-counter");
    container.addEventListener("change", updateCounter(numNodes[index]));
  });
  const regionButtons = regionsContainer.querySelectorAll("button");
  regionButtons.forEach((button) =>
      button.addEventListener("click", toggleOpen)
  );
  const selectAllRegion =
      regionsContainer.querySelectorAll(".select-all-region");
  selectAllRegion.forEach((checkbox) => {
    checkbox.addEventListener("click", toggleSelectRegion);
    const regionCountries = Array.from(
        checkbox.parentElement.parentElement.querySelectorAll(".country-checkbox")
    );

    const isRegionChecked = regionCountries.every((checkbox) =>
        JSON.parse(checkbox.dataset.checked)
    );

    checkbox.dataset.checked = isRegionChecked;
    checkbox.checked = isRegionChecked;
  });

  if (checkedCountries.length >= ALL_COUNTRIES.length) {
    selectAll.checked = true;
    selectAllRegion.forEach((checkbox) => (checkbox.checked = true));
  }

  numOfBlockedCountries.textContent = isRepaint
      ? [...new Set(checkedCountries)].length
      : blockedCountries.length;
};

let timeout;

searchInput.addEventListener("input", (event) => {
  clearTimeout(timeout);
  timeout = setTimeout(() => {
    const query = event.target.value.trim().toLowerCase();
    AmplitudeAnalytics.searchCountry(query);
    const filteredCountries = regions.map((region) => ({
      region: region.region,
      countries: region.countries.filter((country) =>
          getCountryNameByCode.of(country).toLowerCase().includes(query)
      ),
    }));
    if (query) populateCountries(filteredCountries, true);
    else populateCountries(regions);
  }, 500);
});

countrySearchForm.addEventListener("submit", (event) => {
  event.preventDefault();
  clearTimeout(timeout);
  const query = searchInput.value.trim().toLowerCase();
  AmplitudeAnalytics.searchCountry(query);
  const filteredCountries = regions.map((region) => ({
    region: region.region,
    countries: region.countries.filter((country) =>
        getCountryNameByCode.of(country).toLowerCase().includes(query)
    ),
  }));
  populateCountries(filteredCountries, true);
});

countryBlockingForm.addEventListener("submit", (event) => {
  event.preventDefault();

  //checkedCountries = countries to block

});

const populateAttacks = (array) => {
  attacksContainer.innerHTML = "";
  array.forEach(
    (country) =>
      (attacksContainer.innerHTML += `
    <div class="country-blocking-modal-attack-block">
            <p class="country-blocking-modal-attack-block__percent"><span>${country.percent}</span>%</p>
            <p class="country-blocking-modal-attack-block__country">${__('Attack from','wtotem')} <span>${country.country}</span></p>
          </div>
  `)
  );
};

var isRepaint = false;

window.addEventListener('DOMContentLoaded', function () {

  if(typeof blocked_countries_list !== 'undefined') {
    populateCountries(regions, false, blocked_countries_list);
  }

  if(typeof mockAttacks !== 'undefined') {
    populateAttacks(mockAttacks);
  }

  isRepaint = true;
});