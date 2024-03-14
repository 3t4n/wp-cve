/**
 * Function name: AlignClass
 * @param array attributes settign array of attributes.
 * @param int index_val  index values.
 */
function AlignClass( attributes, index_val ) {

	let align_class = ""
	if( "left" == attributes.timelinAlignment ){
		align_class = "timeline-widget timeline-left"
	}else if( "right" == attributes.timelinAlignment ){
		align_class = "timeline-widget timeline-right"
	}else if( "center" == attributes.timelinAlignment ){
		if( index_val % 2 == "0" ){
			align_class = "timeline-widget timeline-right"
		}else{
			align_class = "timeline-widget timeline-left"
		}  
	}     
        
	return [
		align_class        
	]
}

export default AlignClass