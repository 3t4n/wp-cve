(function($){
    "use strict";
    var firstClicked = false;

    $(document).ready(function(){
        repeater_actions()
        localStorage.setItem('test', 'hi');
        let rep = $('.repeater');

        rep.each(function(e){
            let classList = $(this).attr("class").split(" ")
            let key = classList[classList.length - 1]

            if(localStorage.getItem(key) != null){
                localStorage.removeItem(key)
            }

        })
    })

    $(document).on('row_loaded', function(){
        var rcntrlIsPressed;


        $(document).keydown(function(event){
            if(event.which=="17"){
                rcntrlIsPressed = true;
            }
            $('.tm-repeater-select-field option').each(function(indx, e){
                $(e).on('click', function(ev){
                    if(rcntrlIsPressed){
                        $(e).attr('selected', 'selected')
                    }
                })
            });
        });
        
        $(document).keyup(function(){
            rcntrlIsPressed = false;
        });

        $('.tm-repeater-select-field option').each(function(indx, e){
            $(e).on('click', function(ev){
                if($(e).attr('selected') != undefined){
                    $(e).removeAttr('selected')
                }
            })
        });
        
        $('.tp-delete-row').on('click', function(){
            
            const rows = $(this).closest('.tp-metabox-repeater').find('.tp-metabox-repeater-row').length
            if(rows > 1){
                $(this).parent().remove()
            }
        })

        $('.tm-repeater-select-field.select2').each(function(indx, el){
            $(this).select2();
            $(el).on('select2:select', function (e) {
                $(e.target).closest('.repeater-field').find('input[type="hidden"]').val(JSON.stringify($(e.target).val()));
            });
        });

        $('.tm-repeater-select-field.normal').each(function(indx, el){
            $(el).on('change', function (e) {
                $(e.target).closest('.repeater-field').find('input[type="hidden"]').val(JSON.stringify($(e.target).val()));
            });
        });
    })

    const repeater_actions = function(){

        $('.tp-add-row').on('click', function(){

            let $cloneElement = $(this).closest('.tp-repeater').find('.tp-metabox-repeater > .tp-metabox-repeater-row').first().clone();
            // console.log($cloneElement)
            let classList = $(this).closest('.repeater').attr("class").split(" ");
            let key = classList[classList.length - 1];
            let value = $(this).closest('.tp-repeater').find('.tp-metabox-repeater-row').length;


            let itemCount;
            if(localStorage.getItem(key) != null){
                itemCount = parseInt(localStorage.getItem(key));
                localStorage.setItem(key, parseInt(itemCount) + 1 );
                itemCount += 1
            }else{
                itemCount = 1;
                localStorage.setItem(key, value + itemCount );
                itemCount += value;
            }
            $cloneElement.find('.tp-metabox-repeater-collapse').find('.tp-metabox-repeater-collapse-text').text(`Item ${itemCount}`).attr('data-count', itemCount)
            $(this).closest('.tp-repeater').find('.tp-metabox-repeater-row')


            $cloneElement.appendTo($(this).closest('.tp-repeater').find('.tp-metabox-repeater'))
            $(document).trigger('row_loaded')
        })

        $('.tp-delete-row').on('click', function(){
            
            const rows = $(this).closest('.tp-metabox-repeater').find('.tp-metabox-repeater-row').length
            if(rows > 1){
                $(this).parent().remove()
            }
        })

        $('.tm-repeater-conditional').on('click, change', function(){
            var closestRow      = $(this).closest('.tp-metabox-repeater-row')
            var key             = $(this).data('key')
            var targetElement   = key != ''? closestRow.find(`.tm-field-row.${key}`) : '';
            var operand         = targetElement != ''? targetElement.data('operand') : '';
            var value           = targetElement != ''? targetElement.data('value') : '';

            if(targetElement != '' && operand != '' && value != ''){
                if($(this).is('input')){
                    if($(this).is(':checked')){
                        $(this).val('on')
                        $(this).prev().val('on')
                        elementVisibility($(this).val(), operand, value, targetElement, closestRow)
                    }else{
                        $(this).val('off')
                        $(this).prev().val('off')
                        elementVisibility($(this).val(), operand, value, targetElement, closestRow)
                    }
                }else if($(this).is('select')){
                    elementVisibility($(this).val(), operand, value, targetElement, closestRow)
                }else{
                    console.warn('Input type not matched!')
                }
            }else{
                console.warn('Target element id not found!')
            }
            
        })

        const elementVisibility = function(current_val, operand, compare_val, target_el, closest_el){
            let evaluate = eval(`'${current_val}' ${operand} '${compare_val}'`)
            if(evaluate){
                if(closest_el.length){
                    target_el.show(300)
                }else{
                    console.error('Closest Eelement not found!')
                }
            }else{
                target_el.hide(400)
            }
        }
        
        const repeaters = document.querySelectorAll(".tp-metabox-repeater");
        dragula(Array.from(repeaters),{
            moves: function (el, container, handle) {
                return handle.classList.contains('tp-metabox-repeater-collapse');
            },
            direction:'vertical'
        })

        imageFunctionality();
        galleryFunctionality();
    }

    const imageFunctionality = function(){
        $('.tm-add-image').each(function(indx, el){
            $(el).click(function(e){
                e.preventDefault();
                let frame, editFrame;
                let $this = $(this);
                let $imageContainer = $this.closest('.tm-image-field').find('.tm-image-container');
    
                frame = wp.media({
                    title:'Select an image',
                    button:{
                        text:'Add Image'
                    },
                    multiple:false
                })
    
                frame.on('select', function(){
                    let attachment, attchmentURL;
                    attachment = frame.state().get('selection').first().toJSON();
                    attchmentURL = attachment.sizes.thumbnail? attachment.sizes.thumbnail.url : attachment.sizes.full.url;
                    $imageContainer.html(`<div class="tm-gallery-item">
                        <div class="tm-gallery-img">
                            <img src="${attchmentURL}" alt=""/>
                        </div>
                        <div class="tm-image-actions">
                            <a data-attachment-id="${attachment.id}" href="#" class="tm-delete"><span class="dashicons dashicons-trash"></span></a>
                        </div>
                    </div>`)
                    
                    $this.prev('input.tm-image-value').val(attachment.id)
        
                    $imageContainer.find('.tm-image-actions > a.tm-delete').on('click', function(e){
                        e.preventDefault();
                        var selected = $( e.target ).parent().attr( 'data-attachment-id' );
                        $(e.target).closest('.tm-gallery-field').find('input.tm-image-value').val('')
                        $(e.target).parent().parent().parent().remove()
                    })
                    frame.close();
                    return false;
                })
    
                frame.open()
                return false;
            })
        })



        $('.tm-image-actions > a.tm-delete').on('click', function(e){
            e.preventDefault();
            var selected = $( e.target ).parent().attr( 'data-attachment-id' );
            $(e.target).closest('.tm-image-field').find('input.tm-image-value').val('')
            $(e.target).parent().parent().parent().remove();
        })
    }


    const galleryFunctionality = function(){
        $('.tm-add-gallery').on('click', function(e){
            e.preventDefault();
            let $this = $(this);
            let $frame = wp.media({
                title:'Choose images for your gallery',
                library: { type: 'image' },
                button: { text: 'Insert' },
                multiple:true
            });

            $frame.on('select', function(){
                let attachments = $frame.state().get('selection').toJSON();
                let ids = $this.prev('input.tm-gallery-value').val() != '' ? $this.prev('input.tm-gallery-value').val().split(',') : [];
                var attachmentURL;
               
                attachments.map(function(el, i){
                    ids = [...ids, el.id]
                    attachmentURL = el.sizes.thumbnail? el.sizes.thumbnail.url : el.sizes.full.url;
                    $this.closest('.repeater-field').find('.tm-gallery-container').append(`
                    <div class="tm-gallery-item">
                        <div class="tm-gallery-img">
                            <img src="${attachmentURL}" alt=""/>
                        </div>
                        <div class="tm-gallery-img-actions">
                            <a data-attachment-id="${el.id}" href="#" class="tm-delete"><span class="dashicons dashicons-trash"></span></a>
                        </div>
                    </div>
                    `)
                })
                $this.prev('input.tm-gallery-value').val(ids.join(','))


                $('.tm-gallery-img-actions > a.tm-delete').on('click', function(e){
                    e.preventDefault();
                    const $this = $(this);
                    const selected = $( e.target ).parent().attr( 'data-attachment-id' );
                    ids = ids.filter( id => id != selected )
                    $($this).closest('.tm-gallery-field').find('.tm-gallery-value').val(ids.join(','));
                    $($this).closest('.tm-gallery-item').remove();
                });

                $frame.close();
                return false;
            })
            
            $frame.open();
            return false;
        })


        $('.tm-gallery-img-actions > a.tm-delete').on('click', function(e){
            e.preventDefault();
            const $this = $(this);
            const selected    = $($this).attr( 'data-attachment-id' );
            let ids         =  $($this).closest('.tm-gallery-field').find('.tm-gallery-value').val();
            ids = ids.split(',');
            ids = ids.filter( id => id != selected );
            $($this).closest('.tm-gallery-field').find('.tm-gallery-value').val(ids.join(','));
            $($this).closest('.tm-gallery-item').remove();
        })

        $('.tm-repeater-select-field.select2').each(function(indx, el){
            $(this).select2();
            $(el).on('select2:select', function (e) {
                $(e.target).closest('.repeater-field').find('input[type="hidden"]').val(JSON.stringify($(e.target).val()));
            });
        });
        $('.tm-repeater-select-field.select2').each(function(indx, el){
            $(this).select2();
            $(el).on('select2:unselect', function (e) {
                console.log($(e.target).val())
                $(e.target).closest('.repeater-field').find('input[type="hidden"]').val(JSON.stringify($(e.target).val()));
            });
        });

        $('.tm-repeater-select-field.normal').each(function(indx, el){
            $(el).on('change', function (e) {
                $(e.target).closest('.repeater-field').find('input[type="hidden"]').val(JSON.stringify($(e.target).val()));
            });
        });

        var rcntrlIsPressed;


        $(document).keydown(function(event){
            if(event.which=="17"){
                rcntrlIsPressed = true;
            }
            $('.tm-repeater-select-field option').each(function(indx, e){
                $(e).on('click', function(ev){
                    if(rcntrlIsPressed){
                        $(e).attr('selected', 'selected')
                    }
                })
            });
        });
        
        $(document).keyup(function(){
            rcntrlIsPressed = false;
        });

        $('.tm-repeater-select-field option').each(function(indx, e){
            $(e).on('click', function(ev){
                if($(e).attr('selected') != undefined){
                    $(e).removeAttr('selected')
                }
            })
        });
    }
})(jQuery)
