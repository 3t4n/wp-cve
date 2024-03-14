Vue.component('select_field', {
  inheritAttrs: false,
  template: '<div class="resp-table-row"><div class="table-body-cell"><label>{{label}}</label></div><div class="table-body-cell cell-right"><select :name="field_name" v-bind:value="field_value"><option :value="option.value" v-for="option in options">{{option.name}}</option></select></div></div>',
  props:["field_name","options","label","field_value"]
})

Vue.component('checkbox', {
  inheritAttrs: false,
  props:["field_name","options","label","fields","field_value","dependant","drag_field"],
  template: '<div class="resp-table-row"><div class="table-body-cell"><label>{{label}}</label></div><div class="table-body-cell"><label class="switch"><input type="checkbox" :name="field_name" :data-field="drag_field" @change="updatetoggle" v-bind:checked="field_value"><span class="slider round"></span></label></div></div>',
  methods: {
    updatetoggle(){
      this.$emit('change');
    }
  }
})

Vue.component('text_field', {
  inheritAttrs: false,
  props:["field_name","options","label","placeholder","dependant","field_value"],
  template: '<div class="resp-table-row"><div class="table-body-cell"><label>{{label}}</label></div><div class="table-body-cell cell-right"><input type="text" :name="field_name" :placeholder="placeholder" v-bind:value="field_value" @keyup="savekey()"></div></div>',
  methods: {
    savekey(){
      this.$emit('keyup');
    }
  }
})

Vue.component('number', {
  inheritAttrs: false,
  props:["field_name","options","label","placeholder","dependant","field_value"],
  template: '<div class="resp-table-row"><div class="table-body-cell"><label>{{label}}</label></div><div class="table-body-cell cell-right"><input type="number" :name="field_name" :placeholder="placeholder" v-bind:value="field_value"></div></div>'
})

Vue.component('file', {
  inheritAttrs: false,
  props:["field_name","options","label","placeholder","field_id","span_id","preview_id","field_value","preview","image_name"],
  template: '<div class="resp-table-row"><div class="table-body-cell"><label>{{label}}</label></div><div class="table-body-cell"><input type="file" :name="field_name" :id="field_id" class="inputfile" @change="fileselect()"><label :for="field_id">Choose file</label><div v-if="preview" class="img_preview"><span :id="span_id" class="filename">{{ image_name }}</span><img :id="preview_id" v-bind:src="field_value"><input type="hidden" :name="preview_id" v-bind:value="field_value"><a class="image-remove" title="remove" @click="remove_img()">×</a></div></div></div>',
  methods: {
    fileselect(){
      this.$emit('change');
    },
    remove_img(){
      this.$emit('click');
    }
  }
})

Vue.component('checkbox', {
  inheritAttrs: false,
  props:["field_name","options","label","fields","field_value","dependant","drag_field"],
  template: '<div class="resp-table-row"><div class="table-body-cell"><label>{{label}}</label></div><div class="table-body-cell"><label class="switch"><input type="checkbox" :name="field_name" :data-field="drag_field" @change="updatetoggle" v-bind:checked="field_value"><span class="slider round"></span></label></div></div>',
  methods: {
    updatetoggle(){
      this.$emit('change');
    }
  }
})

Vue.component('text_field', {
  inheritAttrs: false,
  props:["field_name","options","label","placeholder","dependant","field_value"],
  template: '<div class="resp-table-row"><div class="table-body-cell"><label>{{label}}</label></div><div class="table-body-cell cell-right"><input type="text" :name="field_name" :placeholder="placeholder" v-bind:value="field_value" @keyup="savekey()"></div></div>',
  methods: {
    savekey(){
      this.$emit('keyup');
    }
  }
})

Vue.component('text_area', {
  inheritAttrs: false,
  props:["field_name","label","placeholder",'field_value'],
  template: '<div class="resp-table-row"><div class="table-body-cell textarea-label"><label>{{label}}</label></div><div class="table-body-cell cell-right"><textarea  :name="field_name" class="thwac-css-textarea" v-bind:value="field_value" cols="50" rows="10"></textarea></div></div>'
  // mounted: function () {
  //   j(document).ready(function($) {
  //     var is_css_id_exist = j('.thwac-css-textarea');
  //     // if(is_css_id_exist.length > 0){
  //       $(".thwac-css-textarea").focus();
  //       var editor = wp.codeEditor.initialize(j('.thwac-css-textarea'), thwwac_var.cm_settings);
       
  //     // }
  //     editor.codemirror.refresh();
      
  //   })
  // } 

});


Vue.component('number', {
  inheritAttrs: false,
  props:["field_name","options","label","placeholder","dependant","field_value"],
  template: '<div class="resp-table-row"><div class="table-body-cell"><label>{{label}}</label></div><div class="table-body-cell cell-right"><input type="number" :name="field_name" :placeholder="placeholder" v-bind:value="field_value"></div></div>'
})

Vue.component('select_change', {
  inheritAttrs: false,
  props:["field_name","options","label","placeholder","field_value","custom_position","custom_content"],
  template: '<div class="resp-table-row"><div class="table-body-cell"><label>{{label}}</label></div><div class="table-body-cell cell-right"><select :name="field_name" @change="selectChange" v-bind:value="field_value" ><option :value="option.value" v-for="option in options">{{option.name}}</option></select><div v-if="custom_position"><textarea readonly>{{ custom_content }}</textarea></div></div></div>',
  methods: {
    selectChange(){
      this.$emit('change');
    }
  }
})

Vue.component('file', {
  inheritAttrs: false,
  props:["field_name","options","label","placeholder","field_id","span_id","preview_id","field_value","preview","image_name"],
  template: '<div class="resp-table-row"><div class="table-body-cell"><label>{{label}}</label></div><div class="table-body-cell"><input type="file" :name="field_name" :id="field_id" class="inputfile" @change="fileselect()"><label :for="field_id">Choose file</label><div v-if="preview" class="img_preview"><span :id="span_id" class="filename">{{ image_name }}</span><img :id="preview_id" v-bind:src="field_value"><input type="hidden" :name="preview_id" v-bind:value="field_value"><a class="image-remove" title="remove" @click="remove_img()">×</a></div></div></div>',
  methods: {
    fileselect(){
      this.$emit('change');
    },
    remove_img(){
      this.$emit('click');
    }
  }
})
Vue.component('color_picker', {
    inheritAttrs: false,
    props: ['field_value','field_name','label'],
    template: '<div class="resp-table-row"><div class="table-body-cell"><label>{{label}}</label></div><div class="table-body-cell cell-right"><input type="text" :name="field_name" class ="colorpicker" v-if v-bind:value="field_value"></div></div>',
    mounted: function () {
            j('.colorpicker').wpColorPicker({
                defaultColor: this.color,
                    change: function(event, ui) {
                    // emit change event on color change using mouse
                    vm.$emit('input', ui.color.toString());
                }});
          var className = 'thwwac_wishlist_btn';
          j('.wp-picker-container').each(function(i, obj) {
              j(obj).addClass(className);
          });
    },
    watch: {
        value: function (value) {
            // update value
            j(this.$el).wpColorPicker('color', value);
        }
    },
    destroyed: function () {
        // j(this.$el).off().wpColorPicker('destroy'); // (!) Not tested
    }
});

Vue.component('file_compare', {
  inheritAttrs: false,
  props:["field_name","options","label","placeholder","field_id","span_id","preview_id","field_value","preview","image_name"],
  template: '<div class="resp-table-row"><div class="table-body-cell"><label>{{label}}</label></div><div class="table-body-cell"><input type="file" :name="field_name" :id="field_id" class="inputfile" @change="fileselect()"><label :for="field_id">Choose file</label><div v-if="preview" class="img_preview"><span :id="span_id" class="filename">{{ image_name }}</span><img :id="preview_id" v-bind:src="field_value"><input type="hidden" :name="preview_id" v-bind:value="field_value"></div></div></div>',
  methods: {
    fileselect(){
      this.$emit('change');
    },
    remove_img(){
      this.$emit('click');
    }
  }
})

var j = jQuery.noConflict();

var vm = new Vue({
  el: '#dynamic-component',
  data: {
    form: {},
    result: {},
    opacity: false,
    showsuccess: false,
    resetsuccess: false,
    none: false,
    currentTab: '',
    setting_success: '',
    spagesuccess: '',
    ppagesuccess: '',
    wpagesuccess: '',
    countersuccess: '',
    success: '',
    image: '',
    tabs: [],
    required: false,
    prequired: false,
    crequired: false,
    sub: '',
    isActive: false,
    loading: true,
    hide: '',
    icon_hide: '',
  },
  mounted() {
    axios.get(thwwac_var.site_url+"/wp-json/thwwac/v1/data")
    .then(response => {this.tabs = response.data,this.loading = false});
  },

  methods:{
    slide: function(tab){
      if(tab.active == true){
        vm.isActive = false;
        vm.opacity = false;
        tab.active = false;
        j('#wpfooter').show();
        j('.thwwc-admin-notice').show();
      }else{
        j('#wpfooter').hide();
        j('.notice').hide();
        vm.isActive = true;
        vm.opacity = true;
        tab.active = true;
      }
      
      this.tabs.forEach((item,index)=>{
        if(!(tab == item)){
          item.active = false;
        }
      });
    },

    change: function(field){
      if (field.field_name == 'success_notice') {
        if (event.target.checked == true) {
          this.tabs.forEach((item,index)=>{
            item.settings.forEach((setting,index)=>{
              setting.fields.forEach((fields,index)=>{
                if(fields.field_name == 'redirect_wishlist') {
                  fields.field_value = true;
                }
              })
            })
          })
        }
      }
      else if (field.field_name == 'show_loop' || field.field_name == 'show_in_pdctpage') {
        if (event.target.checked == false) {
          vm.required = false;
        }
      }
      else if (field.type == 'checkbox') {
          field.field_value = event.target.checked;
      }
      // hide and show icon file and color    
      if (field.icon_field == true) {
        var icon = event.target.value;
        if( icon == 'custom' && field.field_name == 'wish_icon' ){
          vm.required = true;
        }else if( field.field_name == 'wish_icon' ){
          vm.required = false;
        }
        if( icon == 'custom' && field.field_name == 'icon_pdct_page' ){
          vm.prequired = true;
        }else if( field.field_name == 'icon_pdct_page' ){
          vm.prequired = false;
        }
        if( icon == 'custom' && field.field_name == 'counter_icon' ){
          vm.crequired = true;
        }else if( field.field_name == 'counter_icon' ){
          vm.crequired = false;
        }
        this.tabs.forEach((item,index)=>{
          item.settings.forEach((setting,index)=>{
            setting.fields.forEach((fields,index)=>{
              if((field.field_name == fields.parent)){
                if(fields.type == 'file'){
                  if( fields.dependant == true || icon != 'custom' ){
                    fields.dependant = false;
                  }else{
                    fields.preview = false;
                    fields.dependant = true;
                  }
                }else{
                  if( fields.dependant == false && icon != 'custom' ){
                    fields.dependant = true;
                  }else if(fields.dependant == true && icon == 'custom' ){
                    fields.dependant = false;
                  }
                }
              }
           });
          });
        });
      }
      else if(field.field_name == 'wishlist_position' || field.field_name == 'button_pstn_pdct_page'){
        this.tabs.forEach((item,index)=>{
          if(item.settings!=undefined){
            item.settings.forEach((setting,index)=>{
              setting.fields.forEach((fields,index)=>{
                if((field.field_name == fields.parent)){
                  if(fields.dependant == true && event.target.value != 'above_thumb'){
                    fields.dependant = false;
                  }else if(event.target.value == 'above_thumb'){
                    fields.dependant = true;
                  }
                }
              });
            }); 
          }
        });
        field.field_value = event.target.value;
        if(event.target.value == 'custom'){
          field.custom_position = true;
        }else{
          field.custom_position = false;
        }
      }
      else if(field.field_name == 'shop_btn_type' || field.field_name == 'pdct_btn_type'){
        this.tabs.forEach((item,index)=>{
          if(item.settings!=undefined){
            item.settings.forEach((setting,index)=>{
              setting.fields.forEach((fields,index)=>{
                if((field.field_name == fields.parent)){
                  if(fields.subparent_dependent == false){
                    if(fields.field_name == 'wishlist_btn_style_shop' || fields.field_name == 'wishlist_btn_style_pdct'){
                      fields.field_value = 'default';
                    }
                    else if(fields.parent_value == false &&  event.target.value == 'button'){
                      fields.parent_value = true;
                      fields.subparent_value = 'button';
                    } else if (fields.parent_value == true &&  event.target.value != 'button'){
                      fields.parent_value = false;
                      fields.subparent_value = '';
                    } else if (fields.parent_value == false &&  event.target.value != 'button'){
                      fields.parent_value = true;
                      fields.subparent_value = 'link';
                    } else if (fields.parent_value == true &&  event.target.value == 'button'){
                      fields.parent_value = false;
                      fields.subparent_value = '';
                    }
                  }else{
                    if(fields.field_name == 'wishlist_btn_style_pdct' || fields.field_name == 'wishlist_btn_style_shop'){
                      fields.dependant = true;
                    }
                    else if(fields.parent_value == true && fields.subparent_value != '' && fields.field_name != 'wishlist_btn_style'){
                      fields.dependant = false;
                      fields.parent_value = false;
                      fields.subparent_value = '';
                      fields.subparent_dependent = true;
                    }else if(fields.parent_value == true && fields.subparent_value.length == 0 ){
                      fields.dependant = true;
                    }else if(fields.parent_value == false && fields.subparent_value.length == 0 ){
                      fields.dependant = true;
                      fields.parent_value = true;
                      fields.subparent_dependent = true;
                      if(event.target.value == 'button'){
                        fields.subparent_value = 'button';
                      }else{
                        fields.subparent_value = 'link';
                      }
                    }
                  }
                }
              });
            });
          }
        });
      }

      else if (field.children == true) {
        this.tabs.forEach((item,index)=>{
          if (item.settings != undefined) {
            item.settings.forEach((setting,index)=>{
              setting.fields.forEach((fields,index)=>{
                if (field.field_name == fields.parent) {
                  if(fields.field_value == false && fields.subchild == true) {
                    vm.hide = true;
                  }
                  else{
                    vm.hide = false;
                  }
                  if (fields.field_value == true && fields.subchild == true) {
                    vm.sub = fields.field_name;
                  }
                  if (fields.subchild == true && fields.field_name == 'wish_icon' || fields.field_name == 'icon_pdct_page') {
                    vm.icon_hide = fields.field_value;
                  }
                  if (fields.dependant == true && event.target.checked == false) {
                    fields.dependant = false;
                  } else {
                    fields.dependant = true;
                  }
                } else if (field.field_name == fields.main_parent) {
                  if (fields.dependant == true && event.target.checked == false || vm.hide == true) {
                    fields.dependant = false;
                  } else if (fields.parent == vm.sub && event.target.checked == true) {
                    fields.dependant = true;
                  } else if(event.target.checked == true && vm.icon_hide == 'heart' && (fields.field_name == 'wish_icon_color' || fields.field_name == 'wish_icon_color_pdctpage')) {
                    fields.dependant = true;
                  } else if(event.target.checked == true && vm.icon_hide == 'custom' && (fields.field_name == 'icon_upload' || fields.field_name == 'iconp_upload')) {
                    fields.dependant = true;
                  } else if(event.target.checked == true && (fields.parent == 'shop_btn_type' || fields.parent == 'pdct_btn_type') && fields.parent_value == true && fields.subparent_dependent == true){
                    if((fields.field_name == 'wishlist_btn_style_shop' && fields.field_value == 'custom') || (fields.field_name == 'wishlist_btn_style_pdct' && fields.field_value == 'custom')){
                      fields.dependant = true;
                      fields.field_value = 'custom';
                    }
                    fields.dependant = true;
                  }
                }else if(field.field_name == fields.sub_parent && event.target.value != 'custom' && fields.parent_value == true ){
                  fields.dependant = false;
                  field.field_value = 'default';
                  fields.subparent_dependent = false;
                }else if(field.field_name == fields.sub_parent && event.target.value != 'custom' && fields.parent_value == false ){
                  fields.subparent_dependent = false;
                }else if(field.field_name == fields.sub_parent && event.target.value == 'custom' && fields.parent_value == true ){
                  field.field_value = 'custom';
                  fields.dependant = true;
                  fields.subparent_dependent = true;
                }else if(field.field_name == fields.sub_parent && event.target.value == 'custom' && fields.parent_value == false ){
                  fields.subparent_dependent = true;
                }
              });
            }); 
          }
        });
      }
      
      else if( field.type == 'file' ){
        var type = event.target.files[0]['type'];
        if( type == 'image/png' || type == 'image/jpg' || type == 'image/jpeg' ){
          field.preview = true;
          if( field.preview_id == 'shop_preview' ){
            vm.required = false;
          }else if( field.preview_id == 'pdct_preview' ){
            vm.prequired = false;
          }else if( field.preview_id == 'count_preview' ){
            vm.crequired = false;
          }
          var reader = new FileReader();
          reader.onload = function (e) {
            j('#'+field.preview_id).attr('src', e.target.result);
          }
          reader.readAsDataURL(event.target.files[0]);

          var result = event.target.value;
          result = result.split('\\');
          field.image_name = result[2];
        }else{
          alert('File cannot be uploaded ( upload image of format jpg, jpeg or png )');
          j('#'+field.field_id).val('');
        }
      }
    },

    keyup: function(field){
      field.field_value = event.target.value;
    },

    click: function(field){
      j('#'+field.field_id).val('');
      field.preview = false;
      field.field_value = '';
      field.image_name = '';
      if(field.preview_id == 'shop_preview'){
        vm.required = true;
      }else if(field.preview_id == 'pdct_preview'){
        vm.prequired = true;
      }else if(field.preview_id == 'count_preview'){
        vm.crequired = true;
      }
    },

    cancel: function(){
      vm.opacity = false;
      vm.isActive = false;
      this.tabs.forEach((item,index)=>{
        item.active = false;
      });
      j('#wpfooter').css('display','block');
    },
    reset: function(value){
      if(confirm(thwwac_var.reset_msg)){
        var data = {
          action: 'reset_to_default',
          reset: value,
          thwwac_r_security: thwwac_var.resetnonce,
        };
        axios.post( thwwac_var.ajaxurl, Qs.stringify( data ) )
        .then( response => {
          location.reload();
          sessionStorage.setItem("flashmessage",'reset');
        })
        .catch( error => console.log( error ) );
      }
    },

    successclose(){
      vm.showsuccess = false;
    },
    resetclose(){
      vm.resetsuccess = false;
    },

    submit: function (event) {
        vm.setting_success = '';
        vm.showsuccess = false;
        event.preventDefault();
        var formHTML = event.target; // this.$refs.formHTML
        var data = new FormData( formHTML );
        var formlength = this.$refs.formHTML[0].elements.length;

        for(var i=0;i<formlength;i++){
          if(this.$refs.formHTML[0].elements[i].type=='checkbox'){   
            data.append(this.$refs.formHTML[0].elements[i].name,this.$refs.formHTML[0].elements[i].checked);
          }
        }

        data.append('action','save_general_settings');
        data.append('thwwac_security',thwwac_var.ajaxnonce);

        j.ajax({
          type:"POST",
          url: thwwac_var.ajaxurl,
          data: data,
          processData: false, 
          contentType: false,
          success:function(response){
            if(response){
              vm.showsuccess = true;
              setTimeout(() => vm.showsuccess = false, 2000);
            }else{
              vm.none = true;
              setTimeout(() => vm.none = false, 3000);
            }
          },
          error: function(error){
            vm.setting_success = thwwac_var.error_msg;
          }
      });
    },

    shopPageSubmit: function(event){
      vm.setting_success = '';
      vm.showsuccess = false;
      event.preventDefault();
      var formHTML = event.target; 
      var formlength = this.$refs.formHTML[1].elements.length;
      var data = new FormData( formHTML );  
      var required = vm.required;

      for(var i=0;i<formlength;i++){
        if(this.$refs.formHTML[1].elements[i].type == 'checkbox'){   
          data.append(this.$refs.formHTML[1].elements[i].name,this.$refs.formHTML[1].elements[i].checked);
        }else if(this.$refs.formHTML[1].elements[i].type == 'file' && this.$refs.formHTML[1].elements[i].value!=''){          
          data.append(this.$refs.formHTML[1].elements[i].name,this.$refs.formHTML[1].elements[i].files[0].name);
        }

      }
      if( required == true ){
          alert("Custom icon is required");
      }

      if( required == false ){
        data.append('action','save_shop_page_settings');
        data.append('thwwac_s_security',thwwac_var.shopnonce);

        j.ajax({
            type:"POST",
            url: thwwac_var.ajaxurl,
            data: data,
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            
            success:function(response){
              if(response){
                vm.showsuccess = true;
                setTimeout(() => vm.showsuccess = false, 2000);
              }else{
                vm.none = true;
                setTimeout(() => vm.none = false, 3000);
              }
            },
            error: function(error){
              vm.spagesuccess = thwwac_var.error_msg;
            }
        });
      }
    },

    productPageSubmit: function(event){
      vm.setting_success = '';
      vm.showsuccess = false;
      event.preventDefault();
      var formHTML = event.target;
      var data = new FormData( formHTML );     
      var formlength = this.$refs.formHTML[2].elements.length;
      var required = vm.prequired;

      for(var i=0;i<formlength;i++){
        if(this.$refs.formHTML[2].elements[i].type == 'checkbox'){   
          data.append(this.$refs.formHTML[2].elements[i].name,this.$refs.formHTML[2].elements[i].checked);
        }else if(this.$refs.formHTML[2].elements[i].type == 'file' && this.$refs.formHTML[2].elements[i].value!=''){          
          this.file = this.$refs.formHTML[2].elements[i].files[0]; 
          data.append(this.$refs.formHTML[2].elements[i].name,this.file.name);         
        }
      }
      if( required == true ){
          alert("Custom icon is required");
      }
      if( required == false ){
        data.append('action','save_product_page_settings');
        data.append('thwwac_p_security',thwwac_var.productnonce);
        j.ajax({
            type:"POST",
            url: thwwac_var.ajaxurl,
            data: data,
            processData: false, 
            contentType: false,
            success:function(response){
              if(response){
                vm.showsuccess = true;
                setTimeout(() => vm.showsuccess = false, 2000);
              }else{
                vm.none = true;
                setTimeout(() => vm.none = false, 3000);
              }
            },
            error: function(error){
              vm.ppagesuccess = thwwac_var.error_msg;
            }
        });
      }
    },

    wishlistPageSubmit: function(event){
      vm.setting_success = '';
      vm.showsuccess = false;
      event.preventDefault();
      var formHTML = event.target;
      var data = new FormData( formHTML );     
      var formlength = this.$refs.formHTML[3].elements.length;

      for(var i=0;i<formlength;i++){
        if(this.$refs.formHTML[3].elements[i].type == 'checkbox'){   
          data.append(this.$refs.formHTML[3].elements[i].name,this.$refs.formHTML[3].elements[i].checked);
        }
      }

      data.append('action','save_wishlist_page_settings');
      data.append('thwwac_w_security',thwwac_var.wishpagenonce);
      j.ajax({
          type:"POST",
          url: thwwac_var.ajaxurl,
          data: data,
          processData: false, 
          contentType: false,
          success:function(response){
            if(response){
              vm.showsuccess = true;
              setTimeout(() => vm.showsuccess = false, 2000);
            }else{
              vm.none = true;
              setTimeout(() => vm.none = false, 3000);
            }
          },
          error: function(error){
            vm.wpagesuccess = thwwac_var.error_msg;
          }
      });
    },

    wishlistCounterSubmit: function(event){
      vm.setting_success = '';
      vm.showsuccess = false;
      event.preventDefault();
      var formHTML = event.target;
      var data = new FormData( formHTML );     
      var formlength = this.$refs.formHTML[4].elements.length;
      var required = vm.crequired;

      for(var i=0;i<formlength;i++){
        if(this.$refs.formHTML[4].elements[i].type=='checkbox'){   
          data.append(this.$refs.formHTML[4].elements[i].name,this.$refs.formHTML[4].elements[i].checked);
        }else if(this.$refs.formHTML[4].elements[i].type == 'file' && this.$refs.formHTML[4].elements[i].value!=''){          
          data.append(this.$refs.formHTML[4].elements[i].name,this.$refs.formHTML[4].elements[i].files[0].name);
        }
      }
      if( required == true ){
          alert("Custom icon is required");
      }
      if( required == false ){
        data.append('action','save_wishlist_counter_settings');
        data.append('thwwac_c_security',thwwac_var.counternonce);
        j.ajax({
            type:"POST",
            url: thwwac_var.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success:function(response){
              if(response){
                vm.showsuccess = true;
                setTimeout(() => vm.showsuccess = false, 2000);
              }else{
                vm.none = true;
                setTimeout(() => vm.none = false, 3000);
              }
            },
            error: function(error){
              vm.countersuccess = thwwac_var.error_msg;
            }
        });
      }
    },

    socialMediaSubmit: function(event){
      vm.setting_success = '';
      vm.showsuccess = false;
      event.preventDefault();
      var formHTML = event.target;
      var data = new FormData( formHTML );     
      var formlength = this.$refs.formHTML[5].elements.length;

      for(var i=0;i<formlength;i++){
        if(this.$refs.formHTML[5].elements[i].type=='checkbox'){   
          data.append(this.$refs.formHTML[5].elements[i].name,this.$refs.formHTML[5].elements[i].checked);
        }
      }

      data.append('action','save_socialmedia_settings');
      data.append('thwwac_sm_security',thwwac_var.socialnonce);
      j.ajax({
          type:"POST",
          url: thwwac_var.ajaxurl,
          data: data,
          processData: false, 
          contentType: false,
          success:function(response){
            if(response){
              vm.showsuccess = true;
              setTimeout(() => vm.showsuccess = false, 2000);
            }else{
              vm.none = true;
              setTimeout(() => vm.none = false, 3000);
            }
          },
          error: function(error){
            vm.success = thwwac_var.error_msg;
          }
      });
    }
  },
});

var vc = new Vue({
  el: '#compare-component',
  data: {
    opacity: false,
    showsuccess: false,
    resetsuccess: false,
    none: false,
    tabs: [],
    isActive: false,
    loading: true,
    required: false,
    prequired: false,
  },
  mounted() {
    axios.get(thwwac_var.site_url+"/wp-json/thwwac/v1/compare")
    .then(response => {this.tabs = response.data,this.loading = false});
  },
  methods:{
    onmove: function(){
      
    },
    slide: function(tab){
      vc.showsuccess = false;
      if(tab.active == true){
        vc.isActive = false;
        vc.opacity = false;
        tab.active = false;
        j('#wpfooter').show();
        j('.thwwc-admin-notice').show();
      }else{
        vc.isActive = true;
        vc.opacity = true;
        tab.active = true;
        j('#wpfooter').hide();
        j('.notice').hide();
      }     
      this.tabs.forEach((item,index)=>{
        if(!(tab == item)){
          item.active = false;
        }
      });
    },
    cancel:function(){
      vc.showsuccess = false;
      vc.opacity = false;
      vc.isActive = false;
      this.tabs.forEach((item,index)=>{
        item.active = false;
      });
    },
    change: function(field){
      if( field.type == 'checkbox' ){
       field.field_value = event.target.checked;
      }
      if (field.icon_field == true) {
        var icon = event.target.value;
        if( icon == 'custom' && field.field_name == 'cmp_icon' ){
          vc.required = true;
        }else if( field.field_name == 'cmp_icon' ){
          vc.required = false;
        }
        if( icon == 'custom' && field.field_name == 'cmp_icon_pdct_page' ){
          vc.prequired = true;
        }else if( field.field_name == 'cmp_icon_pdct_page' ){
          vc.prequired = false;
        }
        this.tabs.forEach((item,index)=>{
          item.settings.forEach((setting,index)=>{
            setting.fields.forEach((fields,index)=>{
              if( ( field.field_name == fields.parent ) ){
                if(fields.type == 'file_compare'){
                  if( fields.dependant == true || icon != 'custom' ){
                    fields.dependant = false;
                  }else{
                    fields.preview = false;
                    fields.dependant = true;
                  }
                }else{
                  if( fields.dependant == false && icon == 'compare' ){
                    fields.dependant = true;
                  }else{
                    fields.dependant = false;
                  }
                }
              }
           });
          });
        });
      }
      else if(field.children == true){
        this.tabs.forEach((item,index)=>{
          if(item.settings!=undefined){
            item.settings.forEach((setting,index)=>{
              setting.fields.forEach((fields,index)=>{
                if((field.field_name == fields.parent)){
                  if(fields.dependant == true){
                    fields.dependant = false;
                  }else{
                    fields.dependant = true;
                  }
                }
              });
            }); 
          }
        });
      }
      else if(field.field_name == 'shoppage_position' || field.field_name == 'productpage_position'){
        this.tabs.forEach((item,index)=>{
          if(item.settings!=undefined){
            item.settings.forEach((setting,index)=>{
              setting.fields.forEach((fields,index)=>{
                if((field.field_name == fields.parent)){
                  if(fields.dependant == true && event.target.value != 'above_thumb'){
                    fields.dependant = false;
                  }else if(event.target.value == 'above_thumb'){
                    fields.dependant = true;
                  }
                }
              });
            }); 
          }
        });
        field.field_value = event.target.value;
        if(event.target.value == 'custom'){
          field.custom_position = true;
        }else{
          field.custom_position = false;
        }
      }
      else if( field.type == 'file_compare' ){
        var type = event.target.files[0]['type'];
        if( type == 'image/png' || type == 'image/jpg' || type == 'image/jpeg' ){
          field.preview = true;
          if( field.preview_id == 'shop_preview' ){
            vc.required = false;
          }
          var reader = new FileReader();
          reader.onload = function (e) {
            j('#'+field.preview_id).attr('src', e.target.result);
          }
          reader.readAsDataURL(event.target.files[0]);

          var result = event.target.value;
          result = result.split('\\');
          field.image_name = result[2];
        }else{
          alert('File cannot be uploaded ( upload image of format jpg, jpeg or png )');
          j('#'+field.field_id).val('');
        }
      }
    },

    click: function(field){
      j('#'+field.field_id).val('');
      field.preview = false;
      field.field_value = '';
      field.image_name = '';
      if( field.preview_id == 'shop_preview' ){
        vc.required = true;
      }
    },

    reset: function(value){
      if(confirm(thwwac_var.reset_msg)){
        var data = {
          action: 'reset_to_default',
          reset: value,
          thwwac_r_security: thwwac_var.resetnonce
        };
        axios.post( thwwac_var.ajaxurl, Qs.stringify( data ) )
        .then( response => {
          location.reload();
          sessionStorage.setItem("flashmessage",'reset');
        })
        .catch( error => console.log( error ) );
      }
    },

    successclose(){
      vc.showsuccess = false;
    },
    resetclose(){
      vc.resetsuccess = false;
    },
    submit: function(){
      vc.setting_success = '';
      vc.showsuccess = false;
      event.preventDefault();
      var formHTML = event.target; // this.$refs.formHTML
      var data = new FormData( formHTML );
      var formlength = this.$refs.formHTML[0].elements.length;
      a=0;

      for(var i=0;i<formlength;i++){
        if(this.$refs.formHTML[0].elements[i].type=='checkbox'){
          var dname = this.$refs.formHTML[0].elements[i].name
          data.append(dname,this.$refs.formHTML[0].elements[i].checked);
          if(this.$refs.formHTML[0].elements[i].checked && ("show_image" != dname && "show_title" != dname && "show_price" != dname && "show_description" != dname && "show_addtocart" != dname && "show_sku" != dname && "show_available" != dname && "show_weight" != dname && "show_dimension" != dname)){

          }
        }else if(this.$refs.formHTML[0].elements[i].type == 'file_compare' && this.$refs.formHTML[0].elements[i].value!=''){
          data.append(this.$refs.formHTML[0].elements[i].name,this.$refs.formHTML[0].elements[i].files[0].name);
        }
      }

      if (vc.required == true) {
          alert("Custom icon is required");
      } else {
        data.append('action','save_compare_settings');
        data.append('thwwac_cp_security',thwwac_var.comparenonce);
        j.ajax({
            type:"POST",
            url: thwwac_var.ajaxurl,
            data: data,
            processData: false, 
            contentType: false,
            success:function(response){
              if(response){
                vc.showsuccess = true;
                setTimeout(() => vc.showsuccess = false, 2000);
              }else{
                vc.none = true;
                setTimeout(() => vc.none = false, 3000);
              }
            },
            error: function(error){
              vc.setting_success = thwwac_var.error_msg;
            }
        });
      }
    },
    tablesubmit: function(){
      vc.setting_success = '';
      vc.showsuccess = false;
      event.preventDefault();
      var formHTML = event.target; // this.$refs.formHTML
      var data = new FormData( formHTML );
      var formlength = this.$refs.formHTML[1].elements.length;
      a=0;

      for(var i=0;i<formlength;i++){
        if(this.$refs.formHTML[1].elements[i].type=='checkbox'){
          var dname = this.$refs.formHTML[1].elements[i].name;
          data.append(dname,this.$refs.formHTML[1].elements[i].checked);
          var drag_field = j('input[name='+dname+']').data('field');
          if(this.$refs.formHTML[1].elements[i].checked == true && drag_field == 'drag'){
            data.append(this.$refs.formHTML[1].elements[i].name + "_order", a);
            a++;
          }
          // 1 == this.$refs.formHTML[1].elements[i].checked && (("show_image" != dname && "show_title" != dname && "show_price" != dname && "show_description" != dname && "show_addtocart" != dname && "show_sku" != dname && "show_available" != dname && "show_weight" != dname && "show_dimension" != dname) ||
                                    // (data.append(this.$refs.formHTML[1].elements[i].name + "_order", a), a++));
        }
      }

      data.append('action','save_compare_table_settings');
      data.append('thwwac_ct_security',thwwac_var.tablenonce);
      j.ajax({
          type:"POST",
          url: thwwac_var.ajaxurl,
          data: data,
          processData: false, 
          contentType: false,
          success:function(response){
            if(response){
              vc.showsuccess = true;
              setTimeout(() => vc.showsuccess = false, 2000);
            }else{
              vc.none = true;
              setTimeout(() => vc.none = false, 3000);
            }
          },
          error: function(error){
            vc.setting_success = thwwac_var.error_msg;
          }
      });
    },
  },
})

if (sessionStorage.flashmessage) {
  vm.resetsuccess = true;
  setTimeout(() => vm.resetsuccess = false, 3000);
  vc.resetsuccess = true;
  setTimeout(() => vc.resetsuccess = false, 3000);
  sessionStorage.removeItem('flashmessage');
}

var url = window.location.href;
var myMenuLinks = j('#thwwac-header a');
myMenuLinks.filter(function() {
    return this.href == url;
}).addClass('active');