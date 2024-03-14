import Util from "../lib/util";
import './layout-importer.scss';

jQuery(window).load(function () {
    let baseUrl = Util.unSlash(window.location.origin);

    // baseUrl += '/attire-blocks'
    baseUrl = 'https://demo.wpattire.com';

    let license_data;
    let license_key = js_data.license_key || false;

    try {
        license_data = JSON.parse(js_data.license_data);
    } catch (e) {
        license_data = {};
    }
    const $ = jQuery;
    const tabs = `<a class="rounded-0 nav-link active" id="v-pills-pages-tab" data-toggle="pill" href="#v-pills-pages" role="tab" aria-controls="v-pills-pages" aria-selected="true">Landing Pages</a>
                  <a class="rounded-0 nav-link" id="v-pills-packs-tab" data-toggle="pill" href="#v-pills-packs" role="tab" aria-controls="v-pills-packs" aria-selected="false">Starter Pack</a>
                  <a class="rounded-0 nav-link d-none" id="v-pills-packs2-tab" data-toggle="pill" href="#v-pills-packs2" role="tab" aria-controls="v-pills-packs2" aria-selected="false">Starter Pack2</a>
                  <a class="rounded-0 nav-link" id="v-pills-blog-tab" data-toggle="pill" href="#v-pills-blog" role="tab" aria-controls="v-pills-blog" aria-selected="false">Blog</a>
                  <a class="rounded-0 nav-link" id="v-pills-wpdm-tab" data-toggle="pill" href="#v-pills-wpdm" role="tab" aria-controls="v-pills-wpdm" aria-selected="false">Download Manager</a>
                  <a class="rounded-0 nav-link" id="v-pills-wpdm-woocommerce-tab" data-toggle="pill" href="#v-pills-woocommerce" role="tab" aria-controls="v-pills-woocommerce" aria-selected="false">WooCommerce</a>
                  <a class="rounded-0 nav-link" id="v-pills-photography-tab" data-toggle="pill" href="#v-pills-photography" role="tab" aria-controls="v-pills-photography" aria-selected="false">Photography</a>
                  <a class="rounded-0 nav-link" id="v-pills-header-tab" data-toggle="pill" href="#v-pills-header" role="tab" aria-controls="v-pills-header" aria-selected="false">Headers</a>
                  <a class="rounded-0 nav-link" id="v-pills-footer-tab" data-toggle="pill" href="#v-pills-footer" role="tab" aria-controls="v-pills-footer" aria-selected="false">Footers</a>
                  <a class="rounded-0 nav-link" id="v-pills-cta-tab" data-toggle="pill" href="#v-pills-cta" role="tab" aria-controls="v-pills-cta" aria-selected="false">Call To Actions</a>
                  <a class="rounded-0 nav-link" id="v-pills-team-tab" data-toggle="pill" href="#v-pills-team" role="tab" aria-controls="v-pills-team" aria-selected="false">Team</a>
                  <a class="rounded-0 nav-link" id="v-pills-features-tab" data-toggle="pill" href="#v-pills-features" role="tab" aria-controls="v-pills-features" aria-selected="false">Features</a>
                  <a class="rounded-0 nav-link" id="v-pills-pricing-tab" data-toggle="pill" href="#v-pills-pricing" role="tab" aria-controls="v-pills-pricing" aria-selected="false">Pricing Table</a>
                  <a class="rounded-0 nav-link" id="v-pills-everything-tab" data-toggle="pill" href="#v-pills-everything" role="tab" aria-controls="v-pills-everything" aria-selected="true">Everything</a>
                 `;
    const tabsContents = `
                          <div class="tab-pane fade show active" id="v-pills-pages" role="tabpanel" aria-labelledby="v-pills-pages-tab"><div class="row m-0"></div></div>
                          <div class="tab-pane fade" id="v-pills-packs" role="tabpanel" aria-labelledby="v-pills-packs-tab"><div class="row m-0"></div></div>
                          <div class="tab-pane fade st-page" id="v-pills-packs2" role="tabpanel" aria-labelledby="v-pills-packs2-tab">
                            <div class="row mt-3 mx-0 px-3 w-100">
                              <a id="back-to-packs" href="#" class="text-decoration-none">
                                 <i class="fas fa-arrow-left"></i> 
                                 <h5 class="d-inline-block ml-3">All Starter Packs</h5>
                              </a>
                              <h5 class="d-inline-block ml-auto" id="active-st-pack-title"></h5>
                            </div>
                            <div class="row m-0" id="pack-layout-contents"></div>
                          </div>
                          <div class="tab-pane fade" id="v-pills-header" role="tabpanel" aria-labelledby="v-pills-header-tab"><div class="row m-0"></div></div>
                          <div class="tab-pane fade" id="v-pills-footer" role="tabpanel" aria-labelledby="v-pills-footer-tab"><div class="row m-0"></div></div>
                          <div class="tab-pane fade" id="v-pills-blog" role="tabpanel" aria-labelledby="v-pills-blog-tab"><div class="row m-0"></div></div>
                          <div class="tab-pane fade" id="v-pills-wpdm" role="tabpanel" aria-labelledby="v-pills-wpdm-tab"><div class="row m-0"></div></div>
                          <div class="tab-pane fade" id="v-pills-woocommerce" role="tabpanel" aria-labelledby="v-pills-woocommerce-tab"><div class="row m-0"></div></div>
                          <div class="tab-pane fade" id="v-pills-photography" role="tabpanel" aria-labelledby="v-pills-photography-tab"><div class="row m-0"></div></div>
                          <div class="tab-pane fade" id="v-pills-cta" role="tabpanel" aria-labelledby="v-pills-cta-tab"><div class="row m-0"></div></div>
                          <div class="tab-pane fade" id="v-pills-team" role="tabpanel" aria-labelledby="v-pills-team-tab"><div class="row m-0"></div></div>
                          <div class="tab-pane fade" id="v-pills-features" role="tabpanel" aria-labelledby="v-pills-features-tab"><div class="row m-0"></div></div>
                          <div class="tab-pane fade" id="v-pills-pricing" role="tabpanel" aria-labelledby="v-pills-pricing-tab"><div class="row m-0"></div></div>
                          <div class="tab-pane fade" id="v-pills-everything" role="tabpanel" aria-labelledby="v-pills-everything-tab"><div class="row m-0"></div></div>
                          <div class="tab-pane fade" id="v-pills-by-tags" role="tabpanel" aria-labelledby="v-pills--by-tags-tab"><div class="row m-0"></div></div>
                         `;
    $('body')
        .append(`<div class="modal fade" id="atbsLayoutsModal" tabindex="-1" role="dialog" aria-labelledby="atbsLayoutsModalTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="row no-gutters bg-lighter">
                        <div class="col-2 bg-dark atb-logo">
                            <h3 class="modal-title m-0" id="atbsLayoutsModalTitle"><img width="24" src="${js_data.assets_url}/static/images/atbs_icon.png" alt=""> &nbsp;Attire Layouts</h3>
                        </div>
                        <div class="col-10">
                            <div class="modal-header d-block m-0">
                                <div class="search mx-auto">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="Search...">
                                    </div>
                                </div>
                                <button style="line-height: inherit;" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body p-0">
                        <div class="row no-gutters">
                            <div class="col-2 nav-items bg-dark">
                                <div class="sticky-top">
                                    <div class="nav d-inline-block nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    ${wp.hooks.applyFilters('atbs_layouts_tabs', tabs)}
                                    </div>
                                </div>
                            </div>
                            <div class="col-10 pb-4">
                                <div class="tab-content" id="atbs_layouts_tab_content">
                                    <div class="tags mx-4 mt-4 text-center atbs-layout-tags">
                                        <a href="#">shop</a>
                                        <a href="#">header</a>
                                        <a href="#">footer</a>
                                        <a href="#">Team</a>
                                        <a href="#">woocommerce</a>
                                        <a href="#">Pricing</a>
                                        <a href="#">Business</a>
                                        <a href="#">Agency</a>
                                        <a href="#">download manger</a>
                                    </div> 
                                    ${wp.hooks.applyFilters('atbs_layouts_tabs_content', tabsContents)}
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>`)
        .on('click', '#insert_layout', function () {
            $(this).html('<i class="fas fa-spinner fa-spin mr-1"></i> Import').prop('disabled', true);
            atbsInsertLayout($(this).data('layout'), $(this).data('license'));
        })
        .on('click', '.atbs-layout-tags a', function () {
            let type = $(this).data('type');
            getLayoutsOfType(type, '#v-pills-by-tags');
        })
        .on('click', '#back-to-packs', function () {
            $('#v-pills-packs-tab').click();
        })
        .on('click', '.see-pack-layouts', function (e) {
            getStarterPackLayouts($(this).data('lids'), $(this).data('title'));
        });

    let checkExist = setInterval(function () {
        if ($('.edit-post-header__toolbar').length) {
            $('.edit-post-header__toolbar')
                .prepend(`<button style="min-width:fit-content;min-width:-moz-fit-content;padding:7px 12px;height:32px;margin:2px;line-height: 100%;border-radius: 2px; background: #007cba; color: white; font-size: 13px;" type="button" class="ml-1 btn" data-toggle="modal" data-target="#atbsLayoutsModal">
            <svg style="width: 16px; margin-right: 5px;"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 644.02 589.98"><defs><style>.cls-1{fill:none;clip-rule:evenodd;}.cls-2{clip-path:url(#clip-path);}.cls-3{fill:#ffffff;}.cls-4{clip-path:url(#clip-path-2);}.cls-5{clip-path:url(#clip-path-3);}</style><clipPath id="clip-path" transform="translate(10.13 1.49)"><path class="cls-1" d="M612.87,271.94c15.85-29.32,11-35.24-16.19-81.57-14.29-24.33-25.15-42.75-39.4-67.05q-18.94-32.1-37.75-64.2C484-1.49,489.7,1.83,419.42,1.32Q382.22,1,345,.73C316.78.54,295.39.35,267.17.13c-53.72-.4-61.26-1.62-78.73,26.77a14.31,14.31,0,0,0-1.32,4.7c-.06,6.47,20.62,39.95,24.15,46q15.6,26.49,31.17,53c9.13.12,18.28.19,27.39.23q37.28.34,74.47.59c70.26.55,64.5-2.77,100.11,57.8q18.85,32.06,37.75,64.2c4.59,7.86,9.23,15.76,13.89,23.6l61.46.51c7,0,46.37,1.18,52-2.1a14.72,14.72,0,0,0,3.41-3.5"/></clipPath><clipPath id="clip-path-2" transform="translate(10.13 1.49)"><path class="cls-1" d="M633.88,285.18l-25,43.31L586.65,367c-37.28,64.57-30.64,62.51-105.51,61.94-31.08-.21-54.61-.42-85.66-.67q-41-.3-82-.64c-77.33-.59-71,3.06-110.19-63.62q-20.79-35.29-41.55-70.69c-15.74-26.76-27.69-47-43.41-73.84C80.46,155,82,161.71,119.23,97.13l22.23-38.5,25-43.31c-19.21,34.69-14.41,40.1,16.35,92.47,15.75,26.77,27.67,47.09,43.41,73.85Q247,217,267.78,252.33C307,319,300.64,315.36,378,315.94q41,.38,82,.64c31.05.26,54.61.42,85.66.67,60.73.46,67.82,1.91,88.26-32.07"/></clipPath><clipPath id="clip-path-3" transform="translate(10.13 1.49)"><path class="cls-1" d="M543.29,442.1l-25,43.3-22.23,38.51c-37.28,64.57-30.64,62.51-105.5,61.94-31.08-.21-54.61-.42-85.66-.67q-41-.3-82-.64c-77.33-.59-71,3.06-110.18-63.62q-20.8-35.29-41.55-70.68c-15.75-26.77-27.69-47-43.41-73.85-37.88-64.52-36.39-57.77.89-122.34l22.23-38.51,25-43.31c-19.2,34.69-14.4,40.1,16.36,92.47,15.75,26.77,27.66,47.09,43.41,73.85q20.76,35.37,41.55,70.69c39.18,66.68,32.85,63,110.19,63.62q41,.36,82,.64c31.05.25,54.61.41,85.66.66,60.73.46,67.82,1.91,88.26-32.06"/></clipPath></defs><title>Asset 4</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><g class="cls-2"><rect class="cls-3" x="197.2" width="441.66" height="280.22"/></g><g class="cls-4"><rect class="cls-3" x="90.59" y="16.81" width="553.42" height="416.25"/></g><g class="cls-5"><rect class="cls-3" y="173.73" width="553.42" height="416.25"/></g></g></g></svg>
             Attire Layouts
            </button>`);
            clearInterval(checkExist);
        }
    }, 300);

    $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
        $('.atbs-layout-tags').css('display', 'block');
        let layoutType = 'page';
        let pushHtmlHere;
        if ($(e.target).text() === 'Headers') {
            layoutType = 'header';
        } else if ($(e.target).text() === 'Footers') {
            layoutType = 'footer';
        } else if ($(e.target).text() === 'Call To Actions') {
            layoutType = 'cta';
        } else if ($(e.target).text() === 'Download Manager') {
            layoutType = 'wpdm';
        } else if ($(e.target).text() === 'Blog') {
            layoutType = 'blog';
        } else if ($(e.target).text() === 'WooCommerce') {
            layoutType = 'woocommerce';
        } else if ($(e.target).text() === 'Photography') {
            layoutType = 'photography';
        } else if ($(e.target).text() === 'Landing Pages') {
            layoutType = 'page';
        } else if ($(e.target).text() === 'Team') {
            layoutType = 'team';
        } else if ($(e.target).text() === 'Features') {
            layoutType = 'features';
        } else if ($(e.target).text() === 'Pricing Table') {
            layoutType = 'pricing';
        } else if ($(e.target).text() === 'Starter Pack') {
            layoutType = 'packs';
        } else if ($(e.target).text() === 'Starter Pack2') {
            $('.atbs-layout-tags').css('display', 'none');
            return;
        }


        if ($(e.target).text() === 'Everything') {
            pushHtmlHere = '#v-pills-everything';
            layoutType = 'all'
        } else {
            pushHtmlHere = `#v-pills-${layoutType}`;
        }
        if (layoutType === 'packs') {
            getStarterPacks(pushHtmlHere)
        } else getLayoutsOfType(layoutType, pushHtmlHere)
    })

    $('#atbsLayoutsModal')
        .on('show.bs.modal', function () {
            // $('#v-pills-pages .row').html('<i class="loading-animation fa-3x fas fa-circle-notch fa-spin"></i>');
            $('#v-pills-pages .row').html(`<img class="tab-content-placeholder img-fluid image loading" src="${js_data.assets_url}/static/images/loading_placeholder.gif" alt="Card image cap">`);
            fetch(baseUrl + "/wp-json/atbs/get_layouts_types")
                .then(response => {
                    return response.json();
                })
                .then(types => {
                    let typesHTML = '';
                    Object.keys(types).forEach(type => {
                        typesHTML += '<a href="#" data-type="' + types[type] + '" data-id="' + type + '">' + types[type] + '</a>&nbsp;'
                    })
                    $('.atbs-layout-tags').html(typesHTML);
                })
            getLayoutsOfType('page', '#v-pills-pages');
        })
        .on('hide.bs.modal', function () {
        });

    function atbsInsertLayout(layout_id, license) {
        let url = baseUrl + '/wp-json/atbs/get_the_layout/' + layout_id;
        if (license === 'pro') {
            url += '?oder_id=' + license_data.order_id;
            url += '&license_key=' + license_key;
        }
        fetch(url)
            .then(response => {
                return response.json();
            })
            .then(layout => {
                if (layout.code) {
                    $('#atbs_layouts_tab_content').prepend(`
                        <div class="alert alert-warning m-3" id="single_layout_alert alert-dismissible fade show">
                            <strong>${layout.message}</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    `);
                }
                if (layout && !layout.content) return;
                const insertedBlock = wp.blocks.rawHandler({HTML: layout.content});

                if (layout.type.includes('page')) {
                    wp.data.dispatch('core/block-editor').resetBlocks([]);
                }
                wp.data.dispatch('core/block-editor').insertBlocks(insertedBlock);
                $('#atbsLayoutsModal').modal('toggle');
            });
    }

    function getStarterPacks(showIn) {
        $(showIn + ' .row').html(`<img class="tab-content-placeholder img-fluid image loading" src="${js_data.assets_url}/static/images/loading_placeholder.gif" alt="Card image cap">`);
        fetch(baseUrl + "/wp-json/atbs/get_starter_packs")
            .then(response => {
                return response.json();
            })
            .then(packs => {
                let html = '';
                if (!Array.isArray(packs)) {
                    packs = [];
                }
                packs.forEach(pack => {
                    const disabled = (pack.license === 'pro' && !js_data.is_pro) ? 'disabled' : '';
                    const dNone = (pack.license === 'free' || js_data.is_pro) ? 'd-none' : '';
                    const dNoneCrown = (pack.license === 'free') ? 'd-none' : '';

                    html += `<div class="col-4 d-flex">
                                <div class="card st-pack">
                                    <div class="card-body position-relative">
                                        <i title="This is a premium template" class="${dNoneCrown} fas fa-crown p-2"></i>
                                        <div class="upgrade-to-pro-overlay ${dNone} position-absolute"></div>
                                        <a target="_blank" href="https://wpattire.com/blocks-pricing/" class="btn btn-sm p-2 upgrade-to-pro-button ${dNone} position-absolute">Get Premium</a>
                                        <img class="card-img-top img-fluid image layout-thumb loading" src="${js_data.assets_url}/static/images/loading_placeholder.gif" data-src="${pack.thumbnail}" alt="Card image cap">
                                    </div>
                                    <div class="card-footer d-flex justify-content-around">
                                        <h6>${pack.name}</h6>
                                        <a data-title="${pack.name}" data-lids="${pack.layout_ids}" href="#" class="btn btn-sm see-pack-layouts"><i class="fas fa-file-alt mr-1"></i>See Details</a>
                                    </div>
                                </div>
                            </div>`;

                    let url = pack.thumbnail;
                    let newImg = new Image();
                    newImg.onload = function () {
                        $('[data-src="' + pack.thumbnail + '"]').attr("src", url);
                    }
                    newImg.src = url;

                });

                // if (showIn === '#v-pills-by-tags') {
                //     $('#v-pills-tab a').removeClass('show active');
                //     $('#atbs_layouts_tab_content > div').removeClass('show active');
                //     $(showIn).addClass('show active');
                // }

                $(showIn + ' .row').html(html);
            });
    }

    function getStarterPackLayouts(ids, title = '') {
        $('#v-pills-packs2-tab').click();
        $('#v-pills-packs2 #pack-layout-contents').html(`<img class="tab-content-placeholder img-fluid image loading" src="${js_data.assets_url}/static/images/loading_placeholder.gif" alt="Card image cap">`);
        $('#active-st-pack-title').text(title);
        fetch(baseUrl + "/wp-json/atbs/get_starter_pack_layouts?lids=" + ids)
            .then(response => {
                return response.json();
            })
            .then(layouts => {
                let html = '';
                if (!Array.isArray(layouts)) {
                    layouts = [];
                }
                layouts.forEach(layout => {
                    const disabled = (layout.license === 'pro' && !js_data.is_pro) ? 'disabled' : '';
                    const dNone = (layout.license === 'free' || js_data.is_pro) ? 'd-none' : '';
                    const dNoneCrown = (layout.license === 'free') ? 'd-none' : '';

                    html += `
                            <div class="col-4 d-flex">
                                <div class="card">
                                    <div class="card-header"><h6>${layout.name}</h6></div>
                                    <div class="card-body position-relative">
                                        <i title="This is a premium template" class="${dNoneCrown} fas fa-crown p-2"></i>
                                        <div class="upgrade-to-pro-overlay ${dNone} position-absolute"></div>
                                        <a target="_blank" href="https://wpattire.com/blocks-pricing/" class="btn btn-sm p-2 upgrade-to-pro-button ${dNone} position-absolute">Get Premium</a>
                                        <img class="card-img-top img-fluid image layout-thumb loading" src="${js_data.assets_url}/static/images/loading_placeholder.gif" data-src="${layout.thumbnail}" alt="Card image cap">
                                    </div>
                                    <div class="card-footer d-flex justify-content-around">
                                        <button ${disabled} data-layout="${layout.id}" data-license="${layout.license}" class="btn btn-sm" id="insert_layout"><i class="fas fa-file-import mr-1"></i> Import</button>
                                        <a href="${layout.url}" target="_blank" class="btn btn-sm" id="preview-item"><i class="fas fa-external-link-alt mr-1"></i> Preview</a>
                                    </div>
                                </div>
                            </div>`;

                    let url = layout.thumbnail;
                    let newImg = new Image();
                    newImg.onload = function () {
                        $('[data-src="' + layout.thumbnail + '"]').attr("src", url);
                    }
                    newImg.src = url;

                });

                $('#v-pills-packs2 #pack-layout-contents').html(html);
            });
    }

    function getLayoutsOfType(type, showIn) {
        let ep = type === 'all' ? 'get_layouts' : 'get_layouts_of_type';
        $(showIn + ' .row').html(`<img class="tab-content-placeholder img-fluid image loading" src="${js_data.assets_url}/static/images/loading_placeholder.gif" alt="Card image cap">`);
        fetch(baseUrl + "/wp-json/atbs/" + ep + "?type=" + type)
            .then(response => {
                return response.json();
            })
            .then(layouts => {
                let html = '';

                if (js_data.wpdm_blocks_active === '' && type === 'wpdm') {
                    html += '<div class="col-12 mt-3"><div class="alert alert-warning"> <i class="fa fa-exclamation-circle"></i> &nbsp;You need to install and activate <a target="_blank" href="https://wordpress.org/plugins/wpdm-gutenberg-blocks/">WPDM - Gutenberg Blocks</a> to use these layouts.</div></div>';
                }
                if (!Array.isArray(layouts)) {
                    layouts = [];
                }
                layouts.forEach(layout => {
                    const disabled = (layout.license === 'pro' && !js_data.is_pro) ? 'disabled' : '';
                    const dNone = (layout.license === 'free' || js_data.is_pro) ? 'd-none' : '';
                    const dNoneCrown = (layout.license === 'free') ? 'd-none' : '';

                    html += `
                           <div class="col-4 d-flex">
                            <div class="card">
                              <div class="card-body position-relative">
                                <i title="This is a premium template" class="${dNoneCrown} fas fa-crown p-2"></i>
                                <div class="upgrade-to-pro-overlay ${dNone} position-absolute"></div>
                                <a target="_blank" href="https://wpattire.com/blocks-pricing/" class="btn btn-sm p-2 upgrade-to-pro-button ${dNone} position-absolute">Get Premium</a>
                                <img class="card-img-top img-fluid image layout-thumb loading" src="${js_data.assets_url}/static/images/loading_placeholder.gif" data-src="${layout.thumbnail}" alt="Card image cap">
                              </div>
                              <div class="card-footer d-flex justify-content-around">
                                <button ${disabled} data-layout="${layout.id}" data-license="${layout.license}" class="btn btn-sm" id="insert_layout"><i class="fas fa-file-import mr-1"></i> Import</button>
                                <a href="${layout.url}" target="_blank" class="btn btn-sm" id="preview-item"><i class="fas fa-external-link-alt mr-1"></i> Preview</a>
                              </div>
                            </div>
                           </div>`;

                    let url = layout.thumbnail;
                    let newImg = new Image();
                    newImg.onload = function () {
                        $('[data-src="' + layout.thumbnail + '"]').attr("src", url);
                    }
                    newImg.src = url;

                });
                if (showIn === '#v-pills-by-tags') {
                    $('#v-pills-tab a').removeClass('show active');
                    $('#atbs_layouts_tab_content > div').removeClass('show active');
                    $(showIn).addClass('show active');
                }

                $(showIn + ' .row').html(html);
            });
    }

    let delayTimer;

    function atbsDoSearch() {
        let term = $('#inlineFormInputGroup').val();
        clearTimeout(delayTimer);
        delayTimer = setTimeout(function () {
            getLayoutsOfType(term, '#v-pills-by-tags');
        }, 500);
    }

    $("#inlineFormInputGroup").keyup(function () {
        atbsDoSearch();
    });
});


