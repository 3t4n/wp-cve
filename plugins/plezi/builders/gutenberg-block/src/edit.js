import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { SelectControl } from '@wordpress/components';
import './editor.scss';
const { Component } = wp.element;
const { Spinner } = wp.components;

class Edit extends Component {
  constructor(props) {
    super(props);

    this.props = props;
    this.state = {
      list: [],
      loading: true
    }
  }

  componentDidMount() {
    this.runApiFetch();
  }

  componentDidUpdate(prevProps) {
    if(this.props.attributes.plezi_form && this.props.attributes.plezi_form.length > 0) {
      const script = document.createElement( "script" );
      script.src = `https://brain.plezi.co/api/v1/web_forms/scripts?content_web_form_id=${this.props.attributes.plezi_form}`;
      script.async = true;

      document.body.appendChild(script);
    }
  }

  runApiFetch() {
    const filters = {};

    filters.sort_by = 'created_at';
    filters.sort_dir = 'desc';
    filters.page = '1';
    filters.per_page = '20';

    wp.apiFetch({
      path: 'plz/v2/configuration/get-forms-list',
      method: 'POST',
      data: { args: 'sort_by=created_at&sort_dir=desc&page=1&per_page=20', filters: filters, default: true },
    }).then(data => {
      const options = [];

      data.list.map((option, index) => {
        options[index] = {};
        options[index].value = option.id;
        options[index].label = option.attributes.custom_title;
      });

      this.setState({
        list: options,
        loading: false
      });
    });
  }

  render(useBlockProps) {
    return(
      <div {...useBlockProps} class="plz-gutenberg">
        {this.state.loading ? (
          <Spinner />
        ) : (
          <div>
            <SelectControl
              label={ __( 'Choose a Plezi form', 'plezi-for-wordpress' ) }
              value={ this.props.attributes.plezi_form }
              onChange={ ( selection ) => { this.props.setAttributes( { plezi_form: selection	} ); } }
              options={ this.state.list }
            />
            <div>
              {this.props.attributes.plezi_form && this.props.attributes.plezi_form.length > 0 &&
                <div>
                  <form formid={this.props.attributes.plezi_form} id={`plz-form-${this.props.attributes.plezi_form}`}></form>
                  <script src={`https://brain.plezi.co/api/v1/web_forms/scripts?content_web_form_id=${this.props.attributes.plezi_form}`}></script>
                </div>
              }
            </div>
          </div>
        )}
      </div>
    );
  }
}

export default Edit;
