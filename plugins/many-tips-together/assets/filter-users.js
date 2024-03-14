jQuery(document).ready( $=>{
    const oldTotal = $('.displaying-num').eq(0).text();
    const totalLength = $('#the-list tr').length;

    const updtTotals = ()=> {
        let nowTotal = $('#the-list tr:visible').length - $('#the-list tr.plugin-update-tr:visible').length;
        let show = nowTotal == totalLength ? oldTotal : nowTotal;
        $('.displaying-num').text(show);
    }

    const daDelay = (callback, param) => {
        setTimeout(function() {
            callback(param);
        }, 800);
    };

    const daNormalizeStr = str => { // stackoverflow.com/a/37511463
        return str.normalize('NFD').replace(/[\u0300-\u036f]/g, "").toLowerCase();
    };

    const daFilterByKeyword = (e) => {
        var regex = new RegExp('((.|\\n)*)' + e); // stackoverflow.com/a/159140
        $('tbody#the-list tr').hide().filter((i,v)=>{
            let captio = daNormalizeStr( $(v).text() );
            return regex.test(captio);
        }).show();
        updtTotals();
    }; 
    
    $('div.tablenav.top div.tablenav-pages.one-page').before(ADTW.html);

    $("#b5f-plugins-filter").on('input', function() {
        daDelay( daFilterByKeyword, daNormalizeStr($(this).val()) );
    });

    $('.close-icon').click(()=>{ 
        $('tbody#the-list tr').show(); 
        updtTotals(); 
    });
});