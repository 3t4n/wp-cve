import { mpgGetState } from '../helper.js';
import { translate } from '../../lang/init.js';

jQuery('a[href="#logs"]').on('click', function () {

    const projectId = mpgGetState('projectId');
    const initObject = {
        processing: true,
        ajax: {
            "url": `${ajaxurl}?action=mpg_get_log_by_project_id&projectId=${projectId}`,
            "type": "POST"
        },
        columns: [
            { data: 'id' },
            { data: 'project_id' },
            { data: 'level' },
            { data: 'url' },
            { data: 'message' },
            { data: 'datetime' }
        ],
        serverSide: true,
        searching: false,
        retrieve: true
    }

    jQuery('#mpg_logs_table').DataTable(initObject);


    jQuery('#mpg_clear_log_by_project_id').on('click', async function () {

        const projectId = mpgGetState('projectId');

        let project = await jQuery.post(ajaxurl, {
            action: 'mpg_clear_log_by_project_id',
            projectId,
            securityNonce: backendData.securityNonce
        });

        let projectData = JSON.parse(project)

        if (!projectData.success) {
            toastr.error(projectData.error, translate['Can not clear log for current project']);
            return false;
        }

        toastr.success(translate['Log was cleared'], translate['Done!']);

        const logsTable = jQuery('#mpg_logs_table');

        logsTable.DataTable(initObject).clear().destroy();
        logsTable.empty();
        logsTable.DataTable(initObject);

    });
})