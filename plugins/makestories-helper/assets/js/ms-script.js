if (typeof $ === "undefined" && typeof jQuery === "function") {
    $ = jQuery;
}
$(document).on('ready', function () {
    const getClassNames = {
        0 : "largestory",
        1 : "replaceStory",
        2 : "smallStory",
        3 : "smallStory",
        4 : "smallStory",
        5 : "mobilesmallStory",
        6 : "bigmiddleStory",
        7 : "mobilesmallStory"
    }

    if (typeof $ === "undefined" && typeof jQuery === "function") {
        $ = jQuery;
    }
    let sliderWidth = $('.story-curousal').width();
    let slideNo = 5;

    if (sliderWidth < 587) {
        slideNo = 1;
    } else if (sliderWidth < 587) {
        slideNo = 2;
    } else if (sliderWidth < 761) {
        slideNo = 3;
    } else if (sliderWidth < 979) {
        slideNo = 4;
    }

    // Curousal for all publishes stories
    // $('.story-curousal').slick({
    //     slidesToShow: slideNo,
    //     slidesToScroll: 1,
    //     autoplay: true,
    //     autoplaySpeed: 2000,
    //     fade: false,
    //     responsive: [
    //         {
    //             breakpoint: 550,
    //             settings: {
    //                 centerMode: true,
    //             }
    //         }
    //     ]
    // });

    $story = '';
    $default = '';

    // Get value on form submit
    $('.category-allow-form').on('submit', function (e) {
        e.preventDefault();

        $story = $('.category').val();
        $default = $('.default').val();

    });

    //generate post grid function
    function generatePostGrid(data, design = "1") {
        let clsName = design ? "story-thumb-card card" : "single-story-col";
        if (data.length > 8) {
            //creating array chunks
            let mainArr = [];
            for (let i=0; i < children.length; i += 8) {
                let temporary;
                temporary = children.slice(i, i + 8);
                mainArr.push(temporary);
            }

            let parentArr = [];
            let gridBlock;
            for (let i = 0; i<mainArr.length; i++) {

                let currentDiv = mainArr[i];
                gridBlock = document.createElement("div");
                $(gridBlock).addClass('ms-grid');
                $(gridBlock).attr('id', 'listing-grid');
                for (let j = 0; j < currentDiv.length; j++) {
                    let cssClass = getClassNames[j];
                    let mainElm = currentDiv[j];
                    mainElm.removeClass();
                    mainElm.addClass(clsName);
                    mainElm.addClass(cssClass);
                    gridBlock.append(mainElm[0]);
                    parentArr.push(mainElm[0]);
                }

                gridBlock.append(parentArr);
            }
            $("#ajax-posts").append(gridBlock);
        } else {
            let gridBlock = document.createElement("div");
            $(gridBlock).addClass('ms-grid');
            $(gridBlock).attr('id', 'listing-grid');
            for (let i = 0; i < data.length; i++) {
                let cssClass = getClassNames[i];
                let mainElm = data[i];
                mainElm.removeClass();
                mainElm.addClass(clsName);
                mainElm.addClass(cssClass);

                gridBlock.append(mainElm[0]);
            }
            $("#ajax-posts").append(gridBlock);
        }
    }

    // Load more functionality
    let ajaxUrl = $('#ajax-posts').attr('data-ajax');
    let post_per_page = $('#ajax-posts').attr('data-posts');
    let page = 1;
    let ppp = parseInt(post_per_page);

    //reinitialize the player class and load more button function
    function reInitializePlayer(page, ppp) {
        $.post(ajaxUrl, {
            action: "load_post_data_ajax",
            offset: page,
            ppp: ppp,
        })
        .success(function (posts) {
            return posts;
        });
    }

    $("#more_posts").on("click", function () {

        // When btn is pressed.
        $(this).attr("disabled", true);
        let offset = page * ppp;
        let design = $('#listing-grid').data('design');
        let clsName = design ? ".story-thumb-card" : ".single-story-col";
        let clsName2 = design ? "story-thumb-card card" : "single-story-col";
        console.log({design});
        // Disable the button, temp.
        $.post(ajaxUrl, {
            action: "more_post_ajax",
            offset: offset,
            ppp: ppp,
            beforeSend: function () {
                $('body').addClass('ms_loading');
            },
        })
            .success(function (posts) {
                let post_length = posts.htmlData.length;
                let count = posts?.count?.publish;
                if (post_length > 0) {
                    page++;
                    let jelm = $(posts.htmlData);
                    let childElm = $(jelm).find(clsName);
                    let lastGrid = $('.ms-grid:last-child');
                    let lastPostCount = lastGrid.children().length;

                    let emptySlots = 8 - lastPostCount;

                    // let postJSON = reInitializePlayer(offset, ppp);

                    if (emptySlots != 0) {
                        let domChild = [];

                        for (let i = 0; i < emptySlots; i++) {
                            let cssClass = getClassNames[lastPostCount];
                            let mainElm = $(childElm).eq(i);
                            mainElm.removeClass();
                            mainElm.addClass(clsName2);
                            mainElm.addClass(cssClass);
                            domChild.push(mainElm);
                            lastPostCount++;
                        }
                        lastGrid.append(domChild);
                       
                        let newGridData = [];
                        for (let i = emptySlots; i < childElm.length; i++) {
                            let mainElm = $(childElm).eq(i);
                            newGridData.push(mainElm);
                        }

                        if(newGridData.length > 0) {
                            generatePostGrid(newGridData, design);
                        }
                        
                    } else {
                        $("#ajax-posts").append(posts.htmlData);
                    }

                    // reInitializePlayer();

                    // CHANGE THIS!
                    $("#more_posts").attr("disabled", false);
                    let post_count = $('.single-story-col').length;
                    if (post_count >= count) {
                        $('body').addClass('ms_no_more_posts');
                    }
                } else {
                    $('body').addClass('ms_no_more_posts');
                }
                $('body').removeClass('ms_loading');
            })
            .error(function(error) {
                console.log("error");
            });
    });


    // story player on click function
    $('#ajax-posts').on('click', '.single-story-col', function(e){
        let elm = $(this);
        let url = elm.attr('data-story-url');
        window.loadStoryPlayer(e, elm, url);

     });

     $('#ajax-posts').on('click', '.story-thumb-card', function(e){
        e.preventDefault();
        let elm = $(this);
        let url = elm.attr('data-story-url');
        window.loadStoryPlayer(e, elm, url);
     });

     $('#single-story').click(function(e) {
        let elm = $(this);
        let url = elm.attr('data-story-url');
        window.loadStoryPlayer(e, elm, url);
     })

     $('.story-widget').each(function() {
        if ($(this).find('div').is(':empty') && $(this).hasClass('widget-message')) {
            $(this).children('div').append('<p class="no-stories-text">No Published Available, Publish stories before using widget</p>')
        }
     })

})