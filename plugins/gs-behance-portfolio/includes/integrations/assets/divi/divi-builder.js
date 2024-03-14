(function($) {

    class GsBehance extends React.Component {

        static slug = 'divi_gs_behance';

        componentDidUpdate() {
            this.triggerScriptProcess();
        }

        triggerScriptProcess() {
            if ( interval ) return;
            let count = 0;
            let interval = setInterval( () => {
                $(document).trigger( 'gsbeh:scripts:reprocess' );
                if ( count > 20 ) clearInterval( interval );
                count++;
            }, 100 );
        }
      
        render() {
            return <div className='gs-behance' dangerouslySetInnerHTML={{ __html: this.props.__shortcode }}></div>
        }
    }

    $(window).on('et_builder_api_ready', (event, API) => {
        API.registerModules([GsBehance]);
    });

})(jQuery);