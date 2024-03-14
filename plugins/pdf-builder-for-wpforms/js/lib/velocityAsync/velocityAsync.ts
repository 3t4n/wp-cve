(jQuery.fn as any).velocityAsync=function(property:any, duration,easing:'easeInExp'|'easeOutExp'|'linear'){
    let $element=this;
    return new Promise((resolve => {
        $element.velocity(property,duration,easing,resolve);
    }));
};