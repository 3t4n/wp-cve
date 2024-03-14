
const { registerBlockType } = wp.blocks;
const { getCurrentPostId } = wp.data.select("core/editor");

    registerBlockType('mo-sps/gutenburg',{
      
        title:'Sharepoint Library',
        description:'Block to generate a SharePoint Library block',
        icon: 'open-folder',
        category:'widgets',
        "type": "module",
        keywords: [ 'gutenburg','short', 'sharepoint','documents','one drive','folders','files','media','embed'],
        
       
        attributes: {
            cover: {
                type: 'string',
                source: 'attribute',
                selector: 'img',
                attribute: 'src',
            },
            author: {
                type: 'number',
                source: 'html',
                selector: '.book-author',
            },
            pages: {
                type: 'number',
            },
        },
        

        edit: function(props) { 
    
          var final_height='800px';
          var final_width='100%';
    
            function updateHeight(event){
              final_height = event.target.value+'px';
              
              document.getElementById('shortcode').value = '[MO_SPS_SHAREPOINT width='+final_width+' height='+final_height+']';
            }
            function updateWidth(event){
              final_width = event.target.value + '%';
              document.getElementById('shortcode').value = '[MO_SPS_SHAREPOINT width='+final_width+' height='+final_height+']';
            }
            
            function updateColor(value) {
              props.setAttributes({color: value.hex})
            }
          
            var h = {background:'white'};
            var styles = {width:'90%',marginLeft:'15px',resize:'none',disabled:'true',background:'white'};
            var textt = {marginLeft:'15px'};
            var heightt = {width:'50%',marginLeft:'15px',top:'5px',display:'flex',flexdirection:'row '};
            var width = {width:'50%',marginLeft:'15px',top:'5px',display:'flex',flexdirection:'column',marginBottom:'10px'};
    
            var shortcode = '[MO_SPS_SHAREPOINT width='+final_width+' height='+final_height+']';
            const ele1 = React.createElement("h5",{style:h},"Enter the height and width to cover your page/post");
            const ele2 = React.createElement("label",{htmlFor:"height",style: textt,},"Enter The height:");
            const ele3 = React.createElement("input",{type:"number",id:"height",style: heightt ,value: props.attributes.content, onChange: updateHeight});
            const ele4 = React.createElement("label",{htmlFor:"width",style: textt},"Enter The width:");
            const ele5 = React.createElement("input",{type:"number",id:"width",style: width,value: props.attributes.content, onChange: updateWidth});
            const ele6 = React.createElement("textarea", { type: "text",id:"shortcode",style: styles },shortcode)
          
            
       return React.createElement("div",{style:h,class:"wp-block-mo-sps-gutenburg"},
              ele1,
              ele2,
              ele3,
              ele4,
              ele5,
              ele6,
          );
          },

          save: function(props) {
           var shortcode;
    


            if(document.getElementById('shortcode')==null){
              shortcode = post_content;
              
            }
            else{
              shortcode = document.getElementById('shortcode').value;
            }

            return wp.element.createElement(
              "h6",
               {class:"wp-block-mo-sps-gutenburg"} ,
              shortcode
            );
          }
        
      
    })

