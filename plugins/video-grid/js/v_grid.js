function videoPlacements(pId_container,$n){
    
    var cont_width=$n('#'+pId_container).width();
    
    
    if(cont_width>=100 && cont_width<=365){
        
        $n('#'+pId_container+' > .box_parent').css( "width", "100%" );
        $n('#'+pId_container+' .box_grid').css( "width", "100%" );
        $n('#'+pId_container+' .box_grid').css( "padding-bottom", "76%" );
        
    }
    else if(cont_width>=366 && cont_width<=500){
        
        
        $n('#'+pId_container+' > .box_parent').css( "width", "80%" );
        $n('#'+pId_container+' > .box_parent').css( "margin", "0 auto" );
        $n('#'+pId_container+' .box_grid').css( "width", "100%" );
        $n('#'+pId_container+' .box_grid').css( "padding-bottom", "76%" );
        
    }
    else if(cont_width>=500 && cont_width<=700){
        
        $n('#'+pId_container+' > .box_parent').css( "width", "100%" );
        $n('#'+pId_container+' .box_grid').css( "width", "50%" );
        $n('#'+pId_container+' .box_grid').css( "padding-bottom", "38%" );
        
    }
    else if(cont_width>=700 && cont_width<=1000){
        
        $n('#'+pId_container+' > .box_parent').css( "width", "100%" );
        $n('#'+pId_container+' .box_grid').css( "width", "33.3%" );
        $n('#'+pId_container+' .box_grid').css( "padding-bottom", "25.3%" );
        
    }
    else if(cont_width>=1000 && cont_width<=1200){
        
        $n('#'+pId_container+' > .box_parent').css( "width", "100%" );
        $n('#'+pId_container+' .box_grid').css( "width", "25%" );
        $n('#'+pId_container+' .box_grid').css( "padding-bottom", "19%" );
        
    }
    else if(cont_width>=1200 && cont_width<=1500){
        
        $n('#'+pId_container+' > .box_parent').css( "width", "100%" );
        $n('#'+pId_container+' .box_grid').css( "width", "25%" );
        $n('#'+pId_container+' .box_grid').css( "padding-bottom", "19%" );
        
    }
     else if(cont_width>=1500 ){
        
        $n('#'+pId_container+' > .box_parent').css( "width", "100%" );
         $n('#'+pId_container+' > .box_parent').css( "max-width", "1500px" );
         $n('#'+pId_container+' > .box_parent').css( "margin", "0 auto" );
        $n('#'+pId_container+' .box_grid').css( "width", "22%" );
        $n('#'+pId_container+' .box_grid').css( "padding-bottom", "17%" );
        
    }
    
    
} 
