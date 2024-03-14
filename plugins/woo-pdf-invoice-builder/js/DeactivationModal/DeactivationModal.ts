let $modal=jQuery(`<div class="woopdfinv-backdrop">
        <div class="woopdfinv-dialog">
            <div class="woopdfinv-header" style="border-bottom: #eeeeee solid 1px;background: #fbfbfb;padding: 15px 20px;position: relative;margin-bottom: -10px;">
                <h4 style="margin: 0;padding: 0;text-transform: uppercase;font-size: 1.2em;font-weight: bold;color: #cacaca;text-shadow: 1px 1px 1px #fff;letter-spacing: 0.6px;-webkit-font-smoothing: antialiased;">Quick Feedback</h4>
            </div>
            <div class="woopdfinv-body" style="    border: 0;background: #fefefe;padding: 20px;">
                <h3 style="margin:0;"><strong>If you have a moment, please let us know why you are deactivating:</strong></h3>
                <ul>
                    <li>
                        <input value="Didn't work" class="woopdfinv-deactivationReason" name="deactivationReason" type="radio" id="woopdfinv-didntwork"/>
                        <label  for="woopdfinv-didntwork">The plugin didn't work</label>
                        <input  class="woopdfinv-deactivation-detail" type="text" placeholder="Could you briefly explain the issue so we do our best to fix it?"/>
                    </li>
                    <li>
                        <input value="Better plugin" class="woopdfinv-deactivationReason" name="deactivationReason" type="radio" id="woopdfinv-found-better"/>
                        <label for="woopdfinv-found-better">I found a better plugin</label>
                        <input class="woopdfinv-deactivation-detail" type="text" placeholder="What's the plugin name?"/>
                    </li>
                    <li>
                        <input value="Temporal" class="woopdfinv-deactivationReason" name="deactivationReason" type="radio" id="woopdfinv-temporary"/>
                        <label for="woopdfinv-temporary">It's a temporary deactivation. I will activate it later.</label>
                    </li>
                    <li>
                        <input value="Other" class="woopdfinv-deactivationReason" name="deactivationReason" type="radio" id="woopdfinv-other"/>
                        <label for="woopdfinv-other">Other</label>
                        <input class="woopdfinv-deactivation-detail" type="text" placeholder="Kindly tell us the reason so we can improve."/>
                    </li>
                </ul>
            </div>
            <div class="woopdfinv-footer" style="border-top: #eeeeee solid 1px;padding:10px;text-align: right;">
                <a href="#" class="wooPdfSubmitButton button button-secondary button-deactivate allow-deactivate">Skip &amp; Deactivate</a>
                <a href="#" class="wooPdfCancelButton button button-primary button-close">Cancel</a>                
            </div>
        </div>
        
    </div>`);
jQuery('body').append($modal);

jQuery('.woopdfinv-backdrop').click(()=>{
    $modal.removeClass('woopdfinv-show');
});
jQuery('.woopdfinv-dialog').click((e)=>{e.stopImmediatePropagation()});

$modal.find('input:radio').click((e)=>{
    $modal.find('li').removeClass('woopdfinv-selected');
    let $radio=jQuery(e.currentTarget);
    $radio.parent().addClass('woopdfinv-selected');
    $radio.parent().find('.woopdfinv-deactivation-detail').focus();
    if($modal.find('.woopdfinv-deactivationReason:checked').length>0)
    {
        $modal.find('.wooPdfSubmitButton').text('Submit & Deactivate');
    }else{
        $modal.find('.wooPdfSubmitButton').text('Skip & Deactivate');
    }
});


$modal.find('.wooPdfSubmitButton').click(()=>{
    if($modal.find('.woopdfinv-deactivationReason:checked').length>0)
    {
        $modal.find('.wooPdfSubmitButton').attr('disabled','disabled').text('Deactivating plugin...');
        let reason=$modal.find('.woopdfinv-deactivationReason:checked').val();
        if(reason!='Temporal')
        {
            let details=$modal.find('.woopdfinv-deactivationReason:checked').parent().find('.woopdfinv-deactivation-detail').val();
            jQuery.post('http://wooinvoice.rednao.com/wp-admin/admin-ajax.php',{
               reason:reason,
               details:(details==null?'':details),
                action:'rednao_woo_deactivation_reason'
            });

        }
        setTimeout(()=>{
            window.location.href=wooPDFInvoiceDeactivationLink;
        },2000);
    }else{
        window.location.href=wooPDFInvoiceDeactivationLink;
    }
});

var wooPDFInvoiceDeactivationLink=jQuery('[data-plugin="woo-pdf-invoice-builder/woocommerce-pdf-invoice.php"] .deactivate a').attr('href');
jQuery('[data-plugin="woo-pdf-invoice-builder/woocommerce-pdf-invoice.php"] .deactivate a').click((e)=>{
    e.preventDefault();
    $modal.find('.woopdfinv-deactivationReason:checked').removeAttr('checked');
    $modal.find('li').removeClass('woopdfinv-selected');
    $modal.addClass('woopdfinv-show');
});

$modal.find('.wooPdfCancelButton').click(()=>{
    $modal.removeClass('woopdfinv-show')
});


