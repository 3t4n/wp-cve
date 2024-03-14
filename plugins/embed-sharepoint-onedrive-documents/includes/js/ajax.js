jQuery(document).ready(function ($) {

  let config = {'site_id':'', 'site_name':'', 'drive_id':'', 'drive_name':'', 'folder_id':'', 'folder_path':'', 'currentView':'', 'is_plugin':doc_sync_data.is_plugin, 'searched_doc':false};
  let search_ajax = '';

  if(config['is_plugin'] == 'n') {
    config['currentView'] = 'drive';
    config['drive_id'] = doc_sync_data.selected_drive??'';
    config['drive_name'] = doc_sync_data.selected_drive_name??'';
  }
  else {

    $('#mo_sps_site_select').select2();
    $('#mo_sps_drive_select').select2();
    
    config['site_id'] = doc_sync_data.selected_site??'';
    config['drive_id'] = doc_sync_data.selected_drive??'';
    config['folder_path'] = doc_sync_data.selected_folder??'';
    config['drive_name'] = doc_sync_data.selected_drive_name??'';
    config['currentView'] = config['folder_path'] ? 'folder_path' : (config['drive_id'] ? 'drive' : (config['site_id'] ? 'site' : 'root'));
    if(doc_sync_data.connector =='personal' && doc_sync_data.app_type == 'auto' && config['folder_id'] != '') {
      config['folder_id'] = config['folder_path'];
      config['currentView'] = "folder";
    }
  }

  let breadcrumbs = '';
  let saved_breadcrumb = [];
  if(doc_sync_data.breadcrumbs && doc_sync_data.breadcrumbs != undefined) {
      let breadcrumb = doc_sync_data.breadcrumbs;
      breadcrumb = breadcrumb ? breadcrumb : [];
      breadcrumb.forEach(ele=>{
          saved_breadcrumb.push(ele.replaceAll('\\',''));
      });
  }
  breadcrumbs = $.isEmptyObject(saved_breadcrumb) ? [`<span index="0" class="mo_sps_doc_breadcrumbs_items" data-id="${config['drive_id']}" id="mo_sps_breadcrumb_drive">${config['drive_name']}</span>`] : saved_breadcrumb;

  let load_paths = {
    'root': {
      'init': function() {
        load_sites();
      }
    },
    'site': {
        'init': function() {
          config['currentView'] = 'site';
          load_drives(config['site_id'], config['site_name']);
        }
    },
    'drive': {
        'init': function () {
            config['currentView'] = 'drive';
            load_drive_docs(config['drive_id'], config['drive_name']);
        }
    },
    'folder': {
        'init': function () {
            config['currentView'] = 'folder';
            load_folder_docs(config['drive_id'], config['folder_id']);
        }
    },
    'folder_path': {
        'init': function () {
            config['currentView'] = 'folder_path';
            load_folder_docs_using_path(config['folder_path']);
        }
    }
  }

  setTimeout(() => {
    load_breadcrumbs(breadcrumbs);
    if(config['currentView']) {
      load_paths[config['currentView']].init();
    } else load_paths['root'].init();
  }, 900);

  handle_events();

  function load_breadcrumbs(breadcrumbs) {
    var new_html = '';
    breadcrumbs.forEach(ele=>{
      new_html += ele + ' > ';
    });
    if(new_html != '') new_html = new_html.slice(0,new_html.length - 2);
    $('#mo_sps_breadcrumb').html(new_html);
  }

  function load_sites() {
    $('#mo_sps_breadcrumb').html('');
    $('#mo_sps_doc_table').hide();
    $('#mo_sps_site_drive_not_selected').show();
    $('#mo_sps_drive_not_selected').hide();
    $(`#mo_sps_doc_refresh`).css('pointer-events','none');
  }

  function load_drives(site_id, site_name) {
    $('#mo_sps_breadcrumb').html('');
    $('#mo_sps_doc_table').hide();
    $('#mo_sps_site_drive_not_selected').hide();
    $('#mo_sps_drive_not_selected').show();
    $(`#mo_sps_all_errors`).hide();
    $(`#mo_sps_doc_refresh`).css('pointer-events','none');
    docSyncHandleBackedndCalls('mo_sps_load_drives',{site_id:site_id, site_name:site_name}).then((res)=>{
      if(!res.success){
        mo_sps_show_error(res);
        return;
      }

      all_drives = res.data.value;
      var default_drive = res.data.default_drive;
      var drive_select_drpdn = `<div id="mo_sps_select_drive_loader" style="display:none;justify-content:center;align-items:center;">
      <span><img width="40px" height="40px" src="${doc_sync_data.loader_gif}"></span>
      </div>
      <div id="mo_sps_select_drive">
          <select id="mo_sps_drive_select" style="width: 50%;">
              <option disabled value="">--select a drive--</option>`;
              all_drives.forEach(drive => {
                if(default_drive && default_drive == drive.id) {
                  config['drive_id'] = drive.id;
                  config['drive_name'] = drive.name;
                }
                drive_select_drpdn += `<option ${drive.id==default_drive ? 'selected' : ''} value="${drive.name}" data-id="${drive.id}">${drive.name}</option>`
              });
      drive_select_drpdn += `</select></div>`;

      $(`#mo_sps_drive_select_td`).html(drive_select_drpdn);
      $('#mo_sps_drive_select').select2();

      if(default_drive) {
        load_paths['drive'].init();
        load_breadcrumbs([`<span index="0" class="mo_sps_doc_breadcrumbs_items" data-id="${config['drive_id']}" id="mo_sps_breadcrumb_drive">${config['drive_name']}</span>`]);
      }
    });
  }

  function load_drive_docs(drive_id, drive_name) {
    $('#mo_sps_site_drive_not_selected').hide();
    $('#mo_sps_drive_not_selected').hide();
    $(`#mo_sps_all_errors`).hide();
    $(`#mo_sps_doc_refresh`).css('pointer-events','');
    docSyncHandleBackedndCalls('mo_sps_load_drive_docs', {drive_id:config['drive_id'], drive_name:config['drive_name'], breadcrumbs:breadcrumbs, is_plugin:config['is_plugin']}).then((res)=>{
      if(!res.success){
        mo_sps_show_error(res);
        return;
      }

      process_docs(res);
    });
  }

  function load_folder_docs(drive_id, folder_id) {
    $('#mo_sps_site_drive_not_selected').hide();
    $('#mo_sps_drive_not_selected').hide();
    $(`#mo_sps_all_errors`).hide();
    $(`#mo_sps_doc_refresh`).css('pointer-events','');
    docSyncHandleBackedndCalls('mo_sps_load_folder_docs', {drive_id:config['drive_id'], folder_id:config['folder_id'], breadcrumbs:breadcrumbs, is_plugin:config['is_plugin']}).then((res)=>{
      if(!res.success) {
        mo_sps_show_error(res);
        return;
      }

      process_docs(res);
    });
  }

  function load_folder_docs_using_path(folder_path) {
    $('#mo_sps_site_drive_not_selected').hide();
    $('#mo_sps_drive_not_selected').hide();
    $(`#mo_sps_all_errors`).hide();
    $(`#mo_sps_doc_refresh`).css('pointer-events','');
    docSyncHandleBackedndCalls('mo_sps_get_folder_items_using_path', {folder_id:config['folder_id'], folder_path:folder_path, breadcrumbs:breadcrumbs, is_plugin:config['is_plugin']}).then((res)=>{
      if(!res.success) {
        mo_sps_show_error(res);
        return;
      }

      process_docs(res);
    });
  }

  function process_docs(res) {
    let docs = res.data.value;
    var content = '';
    if($.isEmptyObject(docs)) {
      content += `<tr class="mo_sps_table_tr">
        <td colspan="4"><img style="width:300px;height:300px;display:flex;align-items:center;display: block;margin-left: auto;margin-right: auto;" src="${doc_sync_data.emptyFolderDrop_icon}">
        <div style="display:flex;justify-content:center;">This folder is empty</div></td>
      </tr>`
    } else {
      docs.forEach(doc=>{
        if('folder' in doc) {
          content += `<tr class="mo_sps_table_tr" style="cursor:pointer;" id="mo_sps_folder_doc_sync__${doc.id}" item_name="${doc.name}" web-url="${doc.webUrl}" item_path="${doc.parentReference.path}" item_id="${doc.id}">
            <td class="mo_sps_table_tbody_td" style="display:flex;"><img style="width:20px;height:20px;margin-right:10px;" src="${doc_sync_data.folder_icon_url}" >
              <div style="max-width: 18rem;overflow-x: hidden;text-overflow: ellipsis;word-wrap: normal !important;white-space: nowrap !important;">${doc.name}</div>
                <div id="mo_sps_file_share_download" class="mo_sps_file_share_download">
                <span class="mo_sps_share_download_span copytooltip-span">
                  <img web-url="${doc.webUrl}" id="mo_sps_file_doc_preview_${doc.id}" style="width:20px;height:20px;cursor:pointer;" src="${doc_sync_data.redirect}">
                  <span class="copytooltiptext-span">Preview in Sharepoint</span>
                </span>
              </div>
            </td>
            <td class="mo_sps_table_tbody_td">${mo_sps_formatDate(doc.fileSystemInfo.lastModifiedDateTime.split('T')[0])}</td>
            <td class="mo_sps_table_tbody_td">${doc.size ? mo_sps_formatBytes(doc.size, 0) : '0 KB'}</td>
          </tr>`;
        } else {
          let file_name_crump = (doc.name).split('.');
          let file_type = file_name_crump[file_name_crump.length - 1];
          let content_type = doc_sync_data.mime_types[file_type];

          let file_url = doc_sync_data.file_icon;
          if (content_type && content_type.includes("image"))
            file_url = doc_sync_data.file_icon;
          else if (file_type == 'doc' || file_type == 'docx' || file_type == 'docm')
            file_url = doc_sync_data.worddoc_icon;
          else if (file_type == 'xlw' || file_type == 'xls' || file_type == 'xlt' || file_type == 'xlsm' || file_type == 'xlsb' || file_type == 'xltx' || file_type == 'xltm' || file_type == 'xlam' || file_type == 'ods' || file_type == 'xlsx')
            file_url = doc_sync_data.exceldoc_icon;
          else if (file_type == 'pdf')
            file_url = doc_sync_data.pdfdoc_icon;

          content += `<tr class="mo_sps_table_tr">
            <td class="mo_sps_table_tbody_td" style="display:flex;"><img style="width:20px;height:20px;margin-right:10px;" src="${file_url}">
              <div id="mo_sps_file_doc_sync__${doc.id}" file-id="${doc.id}" class="mo_sps_table_tr_file_name_div" style="max-width: 16rem;overflow-x: hidden;text-overflow: ellipsis;white-space:nowrap !important;">${doc.name}</div>
              <div id="mo_sps_file_share_download" class="mo_sps_file_share_download">
                <span class="mo_sps_share_download_span copytooltip-span">
                  <img file-id="${doc.id}" drive-id="${config['drive_id']}" id="mo_sps_file_doc_download_${doc.id}" style="width:20px;height:20px;cursor:pointer;" src="${doc_sync_data.download}">
                  <span class="copytooltiptext-span">Download</span>
                </span>
                <span class="mo_sps_share_download_span copytooltip-span">
                  <img web-url="${doc.webUrl}" id="mo_sps_file_doc_preview_${doc.id}" style="width:20px;height:20px;cursor:pointer;" src="${doc_sync_data.redirect}">
                  <span class="copytooltiptext-span">Preview in Sharepoint</span>
                </span>
              </div>
            </td>
            <td class="mo_sps_table_tbody_td">${mo_sps_formatDate(doc.fileSystemInfo.lastModifiedDateTime.split('T')[0])}</td>
            <td class="mo_sps_table_tbody_td">${mo_sps_formatBytes(doc.size, 0)}</td>
          </tr>`
        }

      });
    }
    $('#mo_sps_table_tbody').html(content);
  }

  function process_search_documents(res) {
    $("#searching_div").hide();
    $("#listItems").empty();

    all_docs = res.data.value;
    var content = '';
    all_docs.forEach(doc=>{
      if('folder' in doc) {
        content += `<div id="mo_sps_search_drpdn_folder_${doc.id}" data-id="${doc.id}" web-url="${doc.webUrl}" class="list_items"><div><img style="display:block;width:1.2rem;height:1.2rem;margin-right:10px;" src="${doc_sync_data.folder_icon_url}" alt></div><div><div style="width:11rem"><span style="word-wrap:break-word;font-size:0.9rem;">${doc.name}</span></div></div></div>`;
      } else {
        let file_name_crump = (doc.name).split('.');
        let file_type = file_name_crump[file_name_crump.length - 1];
        let content_type = doc_sync_data.mime_types[file_type];

        let file_url = doc_sync_data.file_icon;
        if (content_type && content_type.includes("image"))
          file_url = doc_sync_data.file_icon;
        else if (file_type == 'doc' || file_type == 'docx' || file_type == 'docm')
          file_url = doc_sync_data.worddoc_icon;
        else if (file_type == 'xlw' || file_type == 'xls' || file_type == 'xlt' || file_type == 'xlsm' || file_type == 'xlsb' || file_type == 'xltx' || file_type == 'xltm' || file_type == 'xlam' || file_type == 'ods' || file_type == 'xlsx')
          file_url = doc_sync_data.exceldoc_icon;
        else if (file_type == 'pdf')
          file_url = doc_sync_data.pdfdoc_icon;
        content += `<div id="mo_sps_search_drpdn_file_${doc.id}" file-id="${doc.id}" class="list_items"><div><img style="display:block;width:1.2rem;height:1.2rem;margin-right:10px;" src="${file_url}" alt></div><div><div style="width:11rem"><span style="word-wrap:break-word;font-size:0.9rem;">${doc.name}</span></div></div></div>`;
      }
    });
    if(all_docs.length == 0 || all_docs == undefined) content = `<div><h4 class="heading-4-red">No Search Results Found.</h4></div>`;
    $("#listItems").append(content);
  }

  // handle site select event
  function handle_events() {
    $(document).on('change', '#mo_sps_site_select', function (e) {
      var ele = $("#mo_sps_site_select");
      config['site_name'] = ele.val();
      var selected_option = ele.find('option:selected');
      config['site_id'] = selected_option.data('id');
      load_paths['site'].init();
    });

    $(document).on('change', '#mo_sps_drive_select', function (e) {
      var ele = $("#mo_sps_drive_select");
      config['drive_name'] = ele.val();
      var selected_option = ele.find('option:selected');
      config['drive_id'] = selected_option.data('id');
      breadcrumbs = {};
      breadcrumbs = [`<span index="0" class="mo_sps_doc_breadcrumbs_items" data-id="${config['drive_id']}" id="mo_sps_breadcrumb_drive">${config['drive_name']}</span>`];
      load_breadcrumbs(breadcrumbs);
      load_paths['drive'].init();
    });

    $(document).on('click', "img[id^='mo_sps_file_doc_download_'],div[id^='mo_sps_file_doc_sync__'],div[id^='mo_sps_search_drpdn_file_']", function(e){
      e.preventDefault();
      var ele = e.currentTarget;
      var file_id =  ele.getAttribute('file-id');
      
      docSyncHandleBackedndCallsDownloadUrl('mo_sps_get_file_download_url', {drive_id:config['drive_id'], file_id:file_id}).then((res)=>{
        if(!res.success)
          return;
        var file_info = res.data;
        var download_url = file_info['@microsoft.graph.downloadUrl'] ?? file_info['@content.downloadUrl'] ?? '';
        window.location.href = download_url
      });

    });

    $(document).on('click', "img[id^='mo_sps_file_doc_preview_'],img[id^='mo_sps_folder_doc_preview_']", function(e){
      e.preventDefault();
      e.stopPropagation();
      var ele = e.currentTarget;
      var file_id =  ele.getAttribute('web-url');
      window.open(file_id, '_blank');
    });

    $(document).on('click', "#mo_sps_breadcrumb_drive", function(e){
      config['drive_id'] = e.target.getAttribute('data-id');
      index = Number(e.target.getAttribute('index'));
      breadcrumbs = breadcrumbs.splice(0, index + 1);
      load_breadcrumbs(breadcrumbs);
      load_paths['drive'].init();
    });

    $(document).on('click', "span[id^='mo_sps_breadcrumb_folder_']", function(e){
      config['folder_id'] = e.target.getAttribute('data-id');
      var folder_name = e.target.getAttribute('data-name');

      index = Number(e.target.getAttribute('index'));
      breadcrumbs = breadcrumbs.splice(0, index + 1);

      if((doc_sync_data.connector == 'personal') && doc_sync_data.app_type == 'auto') {
        load_breadcrumbs(breadcrumbs);
        load_paths['folder'].init();
      } else {
        config['folder_path'] = e.target.getAttribute('data-path') + '/' + folder_name + ':';
        load_breadcrumbs(breadcrumbs);
        load_paths['folder_path'].init();
      }
    });

    $(document).on('click', "div[id^='mo_sps_search_drpdn_folder_'],tr[id^='mo_sps_folder_doc_sync__']", function(e){
      $('#mo_sps_file_search').val('');
      $("#mySearchDropdown").slideUp("fast");
      e.preventDefault();
      var ele = e.currentTarget;
      var web_url = ele.getAttribute('web-url');
      var item_id = ele.getAttribute('item_id');
      var item_name = ele.getAttribute('item_name');
      const [first, ...rest] = web_url.split(encodeURI(config['drive_name']));
      var relative_path = rest.join(encodeURI(config['drive_name']));

      config['folder_id'] = item_id;

      if((doc_sync_data.connector == 'personal') && doc_sync_data.app_type == 'auto') {
        breadcrumbs.push(`<span index="${breadcrumbs.length}" class="mo_sps_doc_breadcrumbs_items" data-name="${item_name}" id="mo_sps_breadcrumb_folder_${config['folder_id']}" data-id="${config['folder_id']}">${item_name}</span>`);

        config['folder_id'] = item_id;
        load_breadcrumbs(breadcrumbs);
        load_paths['folder'].init();
      } 
      else {
      // var relative_path = web_url.split(encodeURI(config['drive_name'])).pop();
        if(doc_sync_data.connector == 'onedrive' && doc_sync_data.app_type == 'auto') {
          const [first, ...rest] = web_url.split(encodeURI('Documents'));
          var relative_path = rest.join(encodeURI('Documents'));
        }
        var path_ele = [];
        if(relative_path) {
          path_ele = relative_path.split('/');
          breadcrumbs = [`<span index="0" class="mo_sps_doc_breadcrumbs_items" data-id="${config['drive_id']}" id="mo_sps_breadcrumb_drive">${config['drive_name']}</span>`];
          var item_folder_path = `/drives/${config['drive_id']}/root:`;
          for(var i = 1; i<path_ele.length; i++) {
            var folder_name = decodeURI(path_ele[i]);
            breadcrumbs.push(`<span index="${breadcrumbs.length}" class="mo_sps_doc_breadcrumbs_items" data-path="${item_folder_path}" data-name="${folder_name}" id="mo_sps_breadcrumb_folder_${config['folder_id']}">${folder_name}</span>`)
            item_folder_path += '/' + path_ele[i];
          }
          config['folder_path'] = item_folder_path + ':';
          load_breadcrumbs(breadcrumbs);
          load_paths['folder_path'].init();
        }
      }
    });

    // Search for the documents
    $('#mo_sps_search_button').on('click',function(e){
      $('#mo_sps_search_button').hide();
      $('#mo_sps_exit_button').show();
      $('#mo_sps_file_search').css('opacity', '1');
      $('#mo_sps_file_search').css('width', '15rem');
    });

    $('#mo_sps_exit_button').on('click', function(e){
      $('#file_search').val('');
      $("#mySearchDropdown").slideUp("fast");
      $('#mo_sps_search_button').show();
      $('#mo_sps_exit_button').hide();
      $('#mo_sps_file_search').css('opacity', '0');
      $('#mo_sps_file_search').css('width', '0rem');
    });
  
    $('#mo_sps_file_search').bind("keypress", function (e) {
      if (e.keyCode == 13) {
        e.preventDefault();
        return false;
      }
    });

    $(document).on("click", function(event){
      if(!$(event.target).closest("#mySearch").length){
        $("#mySearchDropdown").slideUp("fast");
      }
    });
  
    $(window).blur(function(e) {
      $("#file_search").blur();
      $("#mySearchDropdown").hide();
    });

    $('#mo_sps_file_search').on('keyup click', function (evnt) {
      $("#listItems").empty();
      if(search_ajax != '') search_ajax.abort();

      let query_text = temp_text = $(this).val();
  
      if (query_text && query_text.length > 2) {
        search_ajax = docSyncHandleSearchBackedndCalls('mo_sps_document_search_observer', {drive_id:config['drive_id'], folder_id:config['folder_id'],query_text:query_text}, evnt.keyCode)
        search_ajax.then((res)=>{
          if(!res.success)
            return;

          if(evnt.keyCode == 13) {
            config['searched_doc'] = true;
            process_docs(res);
          } else {
            process_search_documents(res);
          }
        });
      } else {
        $("#mySearchDropdown").hide();
        $("#searching_div").hide();
      }

      if(query_text == '') {
        if(config['searched_doc'] == true) {
          config['searched_doc'] = false;
          load_breadcrumbs(breadcrumbs);
          load_paths[config['currentView']].init();
        }
      }
    });
  }

  function mo_sps_show_error(res) {
    res = (res.data.data) ? res.data.data : 'error';
    $('#mo_sps_doc_table').hide();
    $(`#mo_sps_drive_not_selected`).hide();
    $('#mo_sps_site_drive_not_selected').hide();

    var content = `<div id="mo_sps_doc_embed_error" class="mo_sps_table_loader_div">
        <div><img style="width:35px;height:35px;display:flex;align-items:center;display: block;margin-left: auto;margin-right:auto;" src="${doc_sync_data.error}"></div>
        &nbsp;&nbsp;
        <div>${res.error ? res.error : 'Error'}</div>
        &nbsp;
        <div>${res.error_description ? res.error_description : 'Something went wrong.'}</div>
    </div>`;
    $(`#mo_sps_all_errors`).show();
    $('#mo_sps_all_errors').html(content);
  }

  function delay(callback, ms) {
    var timer = 0;
    return function () {
      var context = this, args = arguments;
      clearTimeout(timer);
      timer = setTimeout(function () {
        callback.apply(context, args);
      }, ms || 0);
    };
  }

  function mo_sps_formatBytes(size, precision = 2) {
    base = Math.log(size) / Math.log(1024);
    suffixes = ['', 'KB', 'MB', 'GB', 'TB'];
    return Math.round(Math.pow(1024, base - Math.floor(base)), precision) + ' ' + suffixes[Math.floor(base)];
  }

  function mo_sps_formatDate(date) {
    var month_array = {'01':'January', '02':'February', '03':'March', '04':'April', '05':'May', '06':'June', '07':'July', '08':'August', '09':'September','10':'October', '11':'November', '12':'December'};
    var currentDate = new Date();
    var currentYear = currentDate.getFullYear();
    var date_array = date.split('-');
    var res = month_array[date_array[1]] + ' ' + date_array[2] + ', ' + date_array[0];
    return res;
  }

  function docSyncHandleBackedndCalls(task, payload) {
    return $.ajax({
      url:`${doc_sync_data.admin_ajax_url}?action=mo_doc_embed&nonce=${doc_sync_data.nonce}`,
      type: "POST",
      data: {
          task,
          payload
      },
      cache: false,
      beforeSend: function() {
        $('#mo_sps_table_tbody').html('');
        $('#mo_sps_table_tbody_loader').show();
        if(config['currentView'] == 'site') {
          $('#mo_sps_select_drive_loader').show();
          $('#mo_sps_select_drive').hide();
        } else if(config['currentView'] == 'drive') {
          $('#mo_sps_doc_table').show();
        }
      },
      success: function(data) {
        $('#mo_sps_table_tbody_loader').hide();
        if(config['currentView'] == 'site') {
          $('#mo_sps_select_drive_loader').hide();
          $('#mo_sps_select_drive').show();
        } else if(config['currentView'] == 'drive') {
          
        }
        return data;
      }
    });
  }

  function exit_file_search(element) {
    document.getElementById('file_search').value = "";
    load_breadcrumbs(breadcrumbs);
    load_paths(config['currentView']);
  }

  function docSyncHandleBackedndCallsDownloadUrl(task, payload) {
    return $.ajax({
      url:`${doc_sync_data.admin_ajax_url}?action=mo_doc_embed&nonce=${doc_sync_data.nonce}`,
      type: "POST",
      data: {
          task,
          payload
      },
      cache: false,
      beforeSend: function() {
      },
      success: function(data) {
        return data;
      }
    });
  }

  function docSyncHandleSearchBackedndCalls(task, payload, keycode) {
    return $.ajax({
      url:`${doc_sync_data.admin_ajax_url}?action=mo_doc_embed&nonce=${doc_sync_data.nonce}`,
      type: "POST",
      data: {
          task,
          payload
      },
      cache: false,
      beforeSend: function() {
        if(keycode == 13) {
          $("#mySearchDropdown").hide();
          $("#searching_div").hide();
          $('#mo_sps_table_tbody').html('');
          $('#mo_sps_table_tbody_loader').show();
          new_breadcrumb = [`<span index="0" id="">Searched Documents</span>`]
          load_breadcrumbs(new_breadcrumb);
        } else {
          $("#mySearchDropdown").show();
          $("#searching_div").show();
        }
      },
      success: function(data) {
        $('#mo_sps_table_tbody_loader').hide();
        return data;
      }
    });
  }
});