var FFxF_detect_sku = document.getElementById('_sku'); // value SKU input

FFxF_detect_sku.value = ffxf_slug.slug_product;

function FFxF_makeid(length) {

	if ( FFxF_detect_sku.value === '' ) {
		var result = FFxF_detect_sku.value = ffxf_slug.slug_product;
	}

    var element = document.getElementById('sku_description');
    if (!element) {
        var FFxF_el = document.createElement("div");
        FFxF_el.innerHTML = '<p id="sku_description"><span class="dashicons dashicons-info"></span><span>' + ffxf_slug.skuautoffxf_text_description + '</span></p>';
        document.querySelectorAll("._sku_field")[0].append(FFxF_el);
    }

    return result;
}


// Add setting link 
var FFxF_el = document.createElement("div");
FFxF_el.className = "FFxF_icon_setting";
FFxF_el.innerHTML =
    '<a target="_blank" data-tooltip="Settings SKU" data-tooltip-bottom="" href="' + ffxf_slug.skuautoffxf_site_url + '/wp-admin/admin.php?page=wc-settings&tab=products&section=skuautoffxf">' +
    '<span class="dashicons dashicons-admin-generic"></span>' +
    '</a>' +
    '<a target="_blank" id="rating-1" href="https://wordpress.org/support/plugin/easy-woocommerce-auto-sku-generator/" data-tooltip="' + ffxf_slug.data_tooltip_bottom + '" data-tooltip-bottom="">' +
    '<div class="circlephone" style="transform-origin: center;"></div>' +
    '<div class="circle-fill" style="transform-origin: center;"></div>' +
    '<div class="img-circle" style="transform-origin: center;" >' +
    '<div data-tooltip="' + ffxf_slug.data_tooltip_left + '" data-tooltip-left="">' +
    '<div data-tooltip="' + ffxf_slug.data_tooltip_right + '" data-tooltip-right="">' +
    '<div class="img-circleblock" style="transform-origin: center;">' +
    '<span class="dashicons dashicons-warning"></span>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</a>';

// Уазываем селектор где будет отображаться подсказка
document.querySelectorAll("._sku_field")[0].append(FFxF_el);

document.addEventListener("DOMContentLoaded", function () {
    function getData() {
        var data = "ratingData" in localStorage ?
            JSON.parse(localStorage.ratingData) : {
                count: 1
            };
        return Promise.resolve(data);
    }

    function saveData(ratingData) {
        localStorage.ratingData = JSON.stringify(ratingData);
        return Promise.resolve("ok");
    }

    var PREFIX = "rating-";
    var links = Array.from(
        document.querySelectorAll("a[id^='" + PREFIX.replace(/'/g, "\\'") + "']")
    );

    var hasBeenRatedElement = document.createElement("span");
    hasBeenRatedElement.innerHTML = "<a id='reflesh' data-tooltip='" + ffxf_slug.data_tooltip_trigger_script + "' data-tooltip-bottom='' href='#' onclick='FFxF_makeid_reflesh();return false;'><span class='dashicons dashicons-update-alt'></span></a>";

    // Уазываем селектор где будет отображаться подсказка
    document.querySelectorAll(".FFxF_icon_setting")[0].append(hasBeenRatedElement);

    var thanksElement = document.createElement("span");
    thanksElement.innerHTML = ffxf_slug.data_tooltip_trigger_script_thanks;


    getData().then(function (ratingData) {
        links.forEach(function (link) {
            var id = link.id.replace(PREFIX, "");

            if (id in ratingData) {
                link.parentNode.removeChild(link);
            } else {
                if (ratingData.count !== 0) {
                    link.parentNode.removeChild(link);
                    return;
                }

                link.target = "_blank";
                link.addEventListener("click", function () {
                    ratingData[id] = 1;
                    saveData(ratingData);

                    link.parentNode.insertBefore(thanksElement.cloneNode(true), link);
                    link.parentNode.removeChild(link);
                });
            }
        });

        ratingData.count = (ratingData.count + 1) % 2;
        saveData(ratingData);
    });

});

function FFxF_makeid_reflesh() {
    var reflesh_js = document.getElementById("reflesh");
    var animation_sku = document.getElementById("_sku");
    var FFxF_random_sku = ffxf_slug.slug_product;

    animation_sku.classList.add('animation_sku');

    FFxF_detect_sku.value = FFxF_random_sku;
    FFxF_makeid();
}

