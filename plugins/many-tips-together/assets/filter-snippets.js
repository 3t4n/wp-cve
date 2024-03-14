jQuery(document).ready( $=>
    {
        let descCookie = localStorage.getItem('showHideDescSnippets');
        const oldTotal = $('.displaying-num').eq(0).text();
        const calcTotal = $('#the-list tr').length - $('#the-list tr.plugin-update-tr').length;
    
        const updtTotals = ()=> {
            let nowTotal = $('#the-list tr:visible').length - $('#the-list tr.plugin-update-tr:visible').length;
            let theNum = nowTotal == calcTotal ? oldTotal : nowTotal;
            $('.displaying-num').text(theNum);
        }
    
        const descAction = e=>{
            if( !$(e.target).hasClass('active') ) {
                $('div.plugin-description, div.row-actions').hide();
                localStorage.setItem('showHideDescSnippets', true);
                $(e.target).addClass('active b5f-active');
            } 
            else {
                $('div.plugin-description, div.row-actions').show();
                localStorage.removeItem('showHideDescSnippets');
                $(e.target).removeClass('active b5f-active');
            }
        };
        const allEData = ()=> {
            $('button.b5f-btn-status').each((i,v)=>{
                $(v).attr('title', $(v).data('titleShow'));
            });
        };
        const swapClasses = e=>{
            let status = $(e.target).attr('id').includes('inactive') 
                ? 'active-snippet' : 'inactive-snippet';
            if( !$(e.target).hasClass('active') ) {
                resetList();
                $('tr.'+status+', tr.plugin-update-tr').hide();
                $(e.target).addClass('active b5f-active');
                allEData();
                $(e.target).attr('title', $(e.target).data('titleHide'))
            } 
            else {
                $('#the-list tr').show();
                $(e.target).removeClass('active b5f-active');
                $(e.target).attr('title', $(e.target).data('titleShow'))
            }
            updtTotals();
        };
    
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
    
        const resetList = () => {
            $('tbody#the-list tr').show();
            $("#b5f-plugins-filter").val('');
            $('button.b5f-btn-status').removeClass('active b5f-active');
        }
    
        $('div.tablenav.top div.alignleft.actions.bulkactions').after(ADTW.html);
    
        $('#hide-desc,#hide-active,#hide-inactive').click( e=>e.preventDefault() );
    
        $('#hide-desc').click(descAction);
        $('#hide-active').click(swapClasses);
        $('#hide-inactive').click(swapClasses);
    
        $("#b5f-plugins-filter").on('focus', e=>{
            if ($(e.target).val().length === 0) resetList();
        });
        $("#b5f-plugins-filter").on('input', function() {
            daDelay( daFilterByKeyword, daNormalizeStr($(this).val()) );
        });
    
        $('.close-icon').click(()=>{ 
            resetList();
            updtTotals(); 
        });
    
        if( descCookie ) $('#hide-desc').click();
    });