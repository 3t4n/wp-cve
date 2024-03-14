var dependencies = [];
var pendingRecalculation = false;

(function ($) {
    $('body').on('focus', '.acf-input-wrap > input', function (e) {
        e.preventDefault();
        if (isDependancy(e.currentTarget.id)) {
            let el = $('#' + e.currentTarget.id);
            el.attr('prev', el.val());
        }
    });

    $('body').on('blur', '.acf-input-wrap > input', function (e) {
        e.preventDefault();
        if (!isDependancy(e.currentTarget.id)) {
            return;
        }
        if (!hasChanged(e.currentTarget.id)) {
            return;
        }
        recalculate();
    });

    $('body').on('change', '.acf-input > select', function (e) {
        e.preventDefault();
        if (!isDependancy(e.currentTarget.id)) {
            return;
        }
        recalculate();
    });

    acf.addAction('remove', function (e) {
        if (pendingRecalculation) {
            pendingRecalculation = false;
            setTimeout(recalculate, 260);
        }
    });

    acf.addAction('remove_field', function (e) {
        if (isDependancy(e.data.key)) {
            pendingRecalculation = true;
        }
    });

    function isDependancy(id)
    {
        let el = dependencies.filter(function (a) {
            return id.indexOf(a) > -1
        });
        return el.length > 0;
    }

    function hasChanged(id)
    {
        let el = $('#' + id);
        return el.val() != el.attr('prev');
    }

    function recalculate()
    {
        args = {};
        $('.acf-field input,select').each(function (index, input) {
            if (input.id) {
                args[input.name] = {'id': input.id, 'value': input.value};
            }
        });

        $.ajax({
            url: CalculatedFields.ajaxurl,
            delay: 1000,
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'calculated_field_update',
                acf: JSON.stringify(args),
                _acf_post_id: acf.get('post_id')
            },
            success: function (result) {
                for (let i=0; i < result.length; i++) {
                    let id = result[i].id;
                    let value = result[i].value;
                    $('#' + result[i].id).val(result[i].value);
                    $('#' + result[i].id).trigger('change');
                }
            }
        });
    }

})(jQuery);

initDependencies();

function initDependencies()
{
    if (typeof CalculatedFields === 'undefined') {
        return;
    }
    let newdependencies = Object.values(CalculatedFields.dependencies);
    dependencies.push.apply(dependencies, newdependencies);
}