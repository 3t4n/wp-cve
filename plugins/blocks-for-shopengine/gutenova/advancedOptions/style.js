
const advancedOptionsStyle = ({settings, breakpoints, cssHelper})=>{
   
    cssHelper.add('', settings.shopengine_advanced_block_align, (val) => {
        return `
         justify-content: ${val};
         text-align: ${val};
         align-items: center
        `
    });
  
    cssHelper.add('', settings.shopengine_advanced_block_margin, (val) => {
        return( val && `
            margin-top: ${val.top};
            margin-right: ${val.right};
            margin-bottom: ${val.bottom};
            margin-left: ${val.left};
        `)
    });
  
    
     cssHelper.add('', settings.shopengine_advanced_block_padding,(val) =>{
        
     return (`
           padding: ${val.top} ${val.right} ${val.bottom} ${val.left};
       `) 
    });
  
    return cssHelper.get()
  }
  
  export { advancedOptionsStyle };
  
  