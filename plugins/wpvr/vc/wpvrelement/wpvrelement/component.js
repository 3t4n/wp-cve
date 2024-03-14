import React from 'react'
import {getService} from 'vc-cake'
import apiFetch from '@wordpress/api-fetch';
const vcvAPI = getService('api')

export default class WpvrElement extends vcvAPI.elementComponent {

  render() {
    const {id, atts, editor} = this.props
    const {wpvr_id, wpvr_height, wpvr_width, wpvr_radius ,wpvr_width_unit,wpvr_height_unit ,wpvr_radius_unit,fullwidth} = atts // destructuring assignment for attributes from settings.json with access public
    var  vrshortcode = '[wpvr id="' + wpvr_id + '" width="' + wpvr_width + wpvr_width_unit +'" height="' + wpvr_height + wpvr_height_unit +'" radius="'+ wpvr_radius + wpvr_radius_unit +'"]'
    if(fullwidth == 'fullwidth'){
      vrshortcode = '[wpvr id="' + wpvr_id + '" width="' +fullwidth +'" height="' + wpvr_height + wpvr_height_unit +'" radius="'+ wpvr_radius + wpvr_radius_unit +'"]'
    }
    return <div>
      <div>
        {vrshortcode}
      </div>
    </div>
  }
}
