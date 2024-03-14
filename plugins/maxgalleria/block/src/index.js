import { registerBlockType } from '@wordpress/blocks';
const { __ } = wp.i18n;

// in the terminal, run npm run build to build

registerBlockType( 'maxgalleria/gallery', {
    title: esc_html__('MaxGalleria Gallery'),
    icon: 'format-gallery',
    category: 'embed',
    keywords: [
      'gallery',
      'maxgalleria'
    ],
    attributes: {
      galleryID: {
        type: 'string',
        selector: 'select',
        attribute: 'value',
        default: ''
      }
    },  


//        source: 'attribute',
    
    edit: function({ attributes, setAttributes, className, isSelected }) {

      //var galleryID = props.attributes.content;

    function onChangeContent( newGalleryID ) {
      console.log('newGalleryID',newGalleryID);
      setAttributes( { galleryID: newGalleryID } );
    } 

    function getGalleryList(gallery_id) {
      
      return mg_block_info.galleries.map((item, i) => {
        if(item.id === gallery_id )
          return (
            <option key={i} value={item.id} selected="selected">{item.name}</option>
          )
        else  
          return (
            <option key={i} value={item.id}>{item.name}</option>
          )
      });    
  
    }  
    
    return (
      <div className={className}>
        <div>{ esc_html__( 'MaxGalleria Gallery', 'maxgalleria' ) }</div>
        {mg_block_info.galleries.length == 0
        ? <div>{ esc_html__( 'No galleries found', 'maxgalleria' ) }</div>
        : <select onChange={() => onChangeContent(event.target.value)}>
          <option value="" disabled >{ esc_html__( 'Select a gallery', 'maxgalleria' ) }</option>
          {getGalleryList(attributes.galleryID)}
          </select>
      }
      </div>
    )

         
    },
    save: function({attributes, className}) {
      return (
        <div  className={className}>
          {'[maxgallery id="'+ attributes.galleryID + '"]'}
        </div>
      )
    }
  
} );