window.Church_Tithe_WP_Spinner = class Church_Tithe_WP_Spinner extends React.Component{

		get_color_mode_class() {

			if ( this.props.color_mode ) {
				return ( ' ' + this.props.color_mode );
			} else {
				return '';
			}
		}

	  render(){
        return(
					<div className={ "church-tithe-wp-spinner-container" }>
						<div className={ "church-tithe-wp-spinner" + this.get_color_mode_class() }>
              <div className="church-tithe-wp-double-bounce1"></div>
              <div className="church-tithe-wp-double-bounce2"></div>
            </div>
					</div>
        )
    }
}
export default Church_Tithe_WP_Spinner;
