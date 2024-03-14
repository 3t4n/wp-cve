jQuery(function($){
    var $dom = $('#wpcom-metas');
	// Uploader
    $dom.on('click', '.btn-upload', function(e) {
        e.preventDefault();
        var uploader, id = $(this).attr('id');
        if (uploader) {
            uploader.open();
        }else{
	        uploader = wp.media.frames.file_frame = wp.media({
	            title: '选择文件',
	            button: {
	                text: '选择文件'
	            },
	            multiple: false
	        });
	        uploader.on('select', function() {
	            var attachment = uploader.state().get('selection').first().toJSON();
	            var inputId = id.replace(/_upload/i,'');
	            $('#'+inputId).val(attachment.url);
	        });
	        uploader.open();
        }
    });

    $dom.on('click', '.j-repeat-add', function () {
        var $el = $(this).parent().prev();
        var $wrap = $el.closest('.wpcom-meta-repeat');
        var html = $el.html();
        var id = parseInt($el.data('id')) + 1;
        html = html.replace(/for="wpcom_(\S+)_(\d+)"/igm, 'for="wpcom_$1_'+id+'"');
        html = html.replace(/id="wpcom_(\S+)_(\d+)"/igm, 'id="wpcom_$1_'+id+'"');
        html = html.replace(/id="wpcom_(\S+)_(\d+)_upload"/igm, 'id="wpcom_$1_'+id+'_upload"');
        html = html.replace(/name="(\S+)\[(\d+)\](\[\])?"/igm, 'name="$1['+id+']$3"');
        if($el.find('.repeat-del').length==0){
            $('<div class="repeat-wrap" data-id="'+id+'">'+html+'<div class="repeat-action"><div class="repeat-item repeat-up j-repeat-up"><i class="dashicons dashicons-arrow-up-alt"></i></div><div class="repeat-item repeat-down j-repeat-down"><i class="dashicons dashicons-arrow-down-alt"></i></div><div class="repeat-item repeat-del j-repeat-del"><i class="dashicons dashicons-no-alt"></i></div></div></div>').insertAfter($el);
        }else{
            $('<div class="repeat-wrap" data-id="'+id+'">'+html+'</div>').insertAfter($el);
        }
        $wrap.find('[data-id="'+id+'"]').find('input[type=text]').val('');
        $wrap.find('[data-id="'+id+'"]').find('textarea').val('');
    }).on('click', '.wpcom-meta-tab li', function(){
        var $this = $(this);
        var index = $this.index();
        $dom.find('.wpcom-meta-tab li').removeClass('active');
        $this.addClass('active');
        $dom.find('.wpcom-meta-box').removeClass('active').eq(index).addClass('active');
    });

    $dom.on('click', '.j-repeat-del', function () {
        $(this).closest('.repeat-wrap').remove();
    }).on('click', '.j-repeat-up,.j-repeat-down', function () {
        var $el = $(this);

        var $this = $el.closest('.repeat-wrap');
        var $prevEl = $this.prev();
        var preID = $prevEl.data('id');
        var thisID = $this.data('id');

        if($el.hasClass('j-repeat-down')){
            $prevEl = $this.next();
            if(!$prevEl.hasClass('repeat-wrap')) return;
            preID = $prevEl.data('id');
        }

        var preVals = {};
        $prevEl.find('input,textarea,select').each(function(i, item){
            var $item = $(item);
            if($item.attr('type')!='checkbox' && $item.attr('type')!='radio') {
                var preName = $item.attr('name');
                if (preName) {
                    var name = preName.replace('[' + preID + ']', '[' + thisID + ']');
                    preVals[name] = $item.val();
                    $item.val($this.find('[name="' + name + '"]').val()).trigger('change');
                }
            }
        });
        $prevEl.find('input:checkbox:checked,input:radio:checked').each(function(i, item) {
            var $item = $(item);
            var preName = $item.attr('name');
            if (preName) {
                var name = preName.replace('[' + preID + ']', '[' + thisID + ']');
                preVals[name] = preVals[name] ? preVals[name] : [];
                preVals[name].push($item.val());
            }
        });

        var thisVals = [];
        $this.find('input:checkbox:checked,input:radio:checked').each(function(i, item) {
            var $item = $(item);
            var name = $item.attr('name');
            if (name) {
                var preName = name.replace('[' + thisID + ']', '[' + preID + ']');
                thisVals[preName] = thisVals[preName] ? thisVals[preName] : [];
                thisVals[preName].push($item.val());
            }
        });

        $prevEl.find('input:checkbox,input:radio').each(function(i, item){
            var $item = $(item);
            var name = $item.attr('name');
            if(name){
                $item.prop('checked', false);
                if($.inArray($item.val(), thisVals[name])>=0) {
                    $item.prop('checked', true).trigger('change');
                }
            }
        });

        $this.find('input, textarea, select').each(function(i, item){
            var $item = $(item);
            if($item.attr('type')!='checkbox' && $item.attr('type')!='radio') {
                $item.val(preVals[$item.attr('name')]).trigger('change');
            }
        });

        $this.find('input:checkbox,input:radio').each(function(i, item){
            var $item = $(item);
            var name = $item.attr('name');
            if(name){
                $item.prop('checked', false);
                if($.inArray($item.val(), preVals[name])>=0) {
                    $item.prop('checked', true).trigger('change');
                }
            }
        });
    });


    // Color picker
    $dom.find('.color-picker').wpColorPicker();

    $dom.on('click', '.toggle', function(){
		var $label = $(this);
		if($label.hasClass('active')){
			$label.removeClass('active');
			$label.next().val(0);
		}else{
			$label.addClass('active');
			$label.next().val(1);
		}
	}).on('change', '.toggle-wrap input', function(){
        var $this = $(this);
        if($this.val()==1){
            $this.parent().find('.toggle').addClass('active');
        }else{
            $this.parent().find('.toggle').removeClass('active');
        }
    })
});