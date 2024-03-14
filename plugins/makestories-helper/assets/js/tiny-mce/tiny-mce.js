(function(config){

   // Select list for selecting **TYPE** of shortcode
   function get_shortcode_list() {
    let ary = [
      { text: 'Show all stories', value: 'all_publish_stories' },
      { text: 'Show stories in one category', value: 'according_category' },
      { text: 'Show single story', value: 'according_story_name' },
      { text: 'Show single widget', value: 'according_widget_name' }
    ];

    return ary;
  }

  // Select list for selecting **CATEGORY** of shortcode
  function get_category_list() {
    let cat_list = [];

    config.categories.map((category) => {
      let data = {}; 
      data.text = category.name;
      data.value = category.id;
      cat_list.push(data);
    })

    return cat_list;
  }

  // Select list for selecting **STORY** of shortcode
  function get_story_list() {
    let story_list = [];

    config.stories.map((story) => {
      let data = {};
      data.text = story.name;
      data.value = story.id;
      story_list.push(data);
    })

    return story_list;
  }

  // Select list for selecting **WIDGET** of shortcode
  function get_widgets_list() {
    let story_list = [];

    config.widgets.map((widget) => {
      let data = {};
      data.text = widget.name;
      data.value = widget.id;
      widget_list.push(data);
    })

    return story_list;
  }

  // *SHOW* and *HIDE* field according to selection of type
  $(document).on('click','.mce-shortcodeGenerator', function() {
    $('.mce-container-body').css('max-height', 100);
    $('.mce-window').css('height',190);
    $('.mce-category_field').parent().parent().css('display','none');
    $('.mce-story_field').parent().parent().css('display','none');
  })

  function showNoExtraField() {
    $('.mce-category_field').parent().parent().css('display','none');
    $('.mce-story_field').parent().parent().css('display','none');
  }

  function showCategoryDropdown() {
    $('.mce-story_field').parent().parent().css('display','none');
    $('.mce-category_field').parent().parent().css({'display':'block','top': 55});
  }

  function showStoryDropdown() {
    $('.mce-category_field').parent().parent().css('display','none');
    $('.mce-story_field').parent().parent().css({'display':'block','top': 55});
  }

  function showWidgetDropdown() {
    $('.mce-category_field').parent().parent().css('display','none');
    $('.mce-widget_field').parent().parent().css({'display':'block','top': 55});
  }

  // ** TINYMCE PLUGIN ** ------------------
  tinymce.create('tinymce.plugins.MyPluginName', {
    init: function(ed, url){
      ed.addButton('myblockquotebtn', {
        title: 'Shortcode Generator',
        cmd: 'myBlockquoteBtnCmd',
        classes: 'shortcodeGenerator',
        image: url + '/img/quote.png'
      });
      ed.addCommand('myBlockquoteBtnCmd', function(){
        
        var selectedText = ed.selection.getContent({format: 'html'});
        var win = ed.windowManager.open({
          title: 'Generate Shortcode',
          body: [
            {
              type: 'listbox',
              name: 'select_type',
              label: 'Select Type',
              minWidth: 500,
              values: get_shortcode_list(),
              value : '',
              onselect: function(e) {
                let value = this.value();
                if(value == 'all_publish_stories') {
                  showNoExtraField();
                } else if(value == 'according_category') {
                  showCategoryDropdown();
                } else if(value == 'according_story_name') {
                  showStoryDropdown();
                } else if (value == 'according_widget_name') {
                  showWidgetDropdown();
                }
              },
            },
            {
              type: 'listbox',
              name: 'select_category',
              label: 'Select Category',
              classes: 'category_field',
              minWidth: 500,
              values: get_category_list(),
              value : '',
            },
            {
              type: 'listbox',
              name: 'select_story',
              label: 'Select Story',
              classes: 'story_field',
              minWidth: 500,
              values: get_story_list(),
              value : '',
            },
            {
              type: 'listbox',
              name: 'select_widget',
              label: 'Select Widget',
              classes: 'widget_field',
              minWidth: 500,
              values: get_widgets_list(),
              value : '',
            },
          ],
          buttons: [
            {
              text: "Ok",
              subtype: "primary",
              onclick: function() {
                win.submit();
              }
            },
            {
              text: "Cancel",
              onclick: function() {
                win.close();
              }
            }
          ],
          onsubmit: function(e){
            let shortcode;
            let type = e.data.select_type;
            let category = e.data.select_category;
            let story = e.data.select_story;
            let widget = e.data.select_widget;

            if(type == 'all_publish_stories') {
              shortcode = '[ms_get_published_post]' + '<br>';
            } else if(type == 'according_category') {
              shortcode = "[ms_get_post_by_category category_id='"+category+"']" + '<br>';
            } else if(type == 'according_story_name') {
              shortcode = "[ms_get_single_post post_id='"+story+"']" + "<br>";
            } else if(type == 'according_widget_name') {
              shortcode = "[ms_get_single_widget widget_id='"+widget+"']" + "<br>";
            }

            ed.execCommand('mceInsertContent', 0, shortcode);
          }
        });
      });
    },
    getInfo: function() {
      return {
        longname : 'My Custom Buttons',
        author : 'Plugin Author',
        authorurl : 'https://www.axosoft.com',
        version : "1.0"
      };
    }
  });
  tinymce.PluginManager.add( 'mytinymceplugin', tinymce.plugins.MyPluginName );

})(window.MS_SC_API_CONFIG);


