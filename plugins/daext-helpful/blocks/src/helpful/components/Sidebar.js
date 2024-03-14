const {dispatch, select} = wp.data;
const {PluginDocumentSettingPanel} = wp.editPost;
const {Component} = wp.element;
const {__} = wp.i18n;
const {SelectControl} = wp.components;

export default class Sidebar extends Component {

  constructor(props) {

    super(...arguments);

    //The state is only used to rerender the component with setState
    this.state = {
      statusValue: '',
    };

  }

  render() {

    const meta = select('core/editor').getEditedPostAttribute('meta');
    const statusMeta = meta['_helpful_status'];

    const positiveValue = window.DAEXTHEFU_PARAMETERS.statistics.positive_value;
    const negativeValue = window.DAEXTHEFU_PARAMETERS.statistics.negative_value;

    let positivePercentageValue = 0;
    let negativePercentageValue = 0;
    if ((positiveValue + negativeValue) > 0) {
      positivePercentageValue = Math.round(
          (positiveValue / (positiveValue + negativeValue)) * 100);
      negativePercentageValue = Math.round(
          negativeValue / (positiveValue + negativeValue) * 100);
    }

    return (
        <PluginDocumentSettingPanel
            name="daexthefu-options"
            title={__('Helpful', 'daext-helpful')}
        >
          <SelectControl
              label={__('Status', 'daext-helpful')}
              help={__(
                  'Select whether to enable or disable the form.',
                  'helpful-pro')}
              value={statusMeta}
              onChange={(status) => {

                dispatch('core/editor').editPost({
                  meta: {
                    '_helpful_status': status,
                  },
                });

                //used to rerender the component
                this.setState({
                  statusValue: status,
                });

              }}
              options={[
                {label: 'Disabled', value: 0},
                {label: 'Enabled', value: 1},
              ]}
          />

          <div className="daexthefu-section-title">{__('Statistics',
              'daext-helpful')}</div>
          <div className="daexthefu-stat daexthefu-positive-stat">
            <div className="daexthefu-stat-label">{__('Positive',
                'daext-helpful')}</div>
            <div
                className="daexthefu-stat-value">{positiveValue} ({positivePercentageValue}%)
            </div>
          </div>
          <div className="daexthefu-stat daexthefu-negative-stat">
            <div className="daexthefu-stat-label">{__('Negative',
                'daext-helpful')}</div>
            <div
                className="daexthefu-stat-value">{negativeValue} ({negativePercentageValue}%)
            </div>
          </div>

        </PluginDocumentSettingPanel>
    );
  }
}