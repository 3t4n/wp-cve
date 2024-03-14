jQuery(function ($) {
    var $productsPricesSpans = $('.premmerce-multicurrency-data');

    var productsIds = $productsPricesSpans.map(function () {
        return $(this).data('product-id');
    }).get();
    var originalPageCurrency = $('.premmerce-multicurrency.original-page-currency').data('page_original_currency_id');
    var filterRangeMin = 0;
    var filterRangeMax = 0;
    var filterMin = 0;
    var filterMax = 0;

    var priceFormats = {
        left: '%s%v',
        right: '%v%s',
        left_space: '%s %v',
        right_space: '%v %s'
    };

    if ($('#min_price').length && $('#max_price').length) {


        filterRangeMin = $('#min_price').data('min');

        filterRangeMax = $('#max_price').data('max');
        filterMin = $('#min_price').val();
        filterMax = $('#max_price').val();

    }

    else if ($('.filter__slider-control').length) {
        filterRangeMin = $('.filter__slider-control[name="min_price"]').data('premmerce-filter-slider-min');
        filterRangeMax = $('.filter__slider-control[name="max_price"]').data('premmerce-filter-slider-max');

        filterMin = $('.filter__slider-control[name="min_price"]').val();
        filterMax = $('.filter__slider-control[name="max_price"]').val();
    }

    $.ajax({
        url: premmerce_multicurrency_data.ajaxurl,
        dataType: 'json',
        data: {
            action: 'premmerce_get_prices',
            productsIds: productsIds,
            filterValues: {
                rangeMin: filterRangeMin,
                rangeMax: filterRangeMax,
                min: filterMin,
                max: filterMax
            },
            originalPageCurrency: originalPageCurrency
        },
        success: function (response) {
            console.log(response);
            if (parseInt(response.currency.id) === parseInt(originalPageCurrency)) {
                return;
            }

            $('select.premmerce-multicurrency').val(response.currency.id);

            //Update found spans because Woocommerce changes them if they have default variations
            $productsPricesSpans = $('.premmerce-multicurrency-data');

            $.each($productsPricesSpans, function (index, spanObject) {
                var id = $(spanObject).data('product-id');
                var $replace = $(response.prices[id]);
                $(spanObject).replaceWith($replace);
            });

            var $woocommerceSlider = $('.price_slider.ui-slider');
            var $premmerceSlider = $('[data-premmerce-filter-range-slider]');


            $('[data-premmerce-filter-slider-form] input[name="min_price"]').val(response.filter.min);
            $('[data-premmerce-filter-slider-form] input[name="max_price"]').val(response.filter.max);

            $.each([$woocommerceSlider, $premmerceSlider], function (index, $slider) {
                let opt = $slider.slider('option');

                $slider.slider('destroy');

                opt.min = response.filter.rangeMin;
                opt.max = response.filter.rangeMax;
                opt.values = [response.filter.min, response.filter.max];

                $slider.slider(opt);
            });

            //Change prices below woo slider
            woocommerce_price_slider_params = {
                currency_format: priceFormats[response.currency.position],
                currency_format_decimal_sep: response.currency.decimal_separator,
                currency_format_num_decimals: response.currency.decimals_num,
                currency_format_symbol: response.currency.symbol,
                currency_format_thousand_sep: response.currency.thousand_separator
            };

            $(document.body).trigger('price_slider_slide', [response.filter.min, response.filter.max]);
        }
    });
});
