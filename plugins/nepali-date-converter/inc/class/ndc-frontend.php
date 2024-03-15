<?php
/**
 * check if key/s exists in an array
 *
 * @since NDC 1.1.0
 */
if ( ! class_exists( 'NDC_Frontend' ) ) {
    /**
     * Class for Frontend Display
     *
     * @package
     * @since 1.0
     */
    class NDC_Frontend {

        /**
         * Variable to hold english nepali number
         *
         * @var array
         * @access protected
         * @since 1.0
         *
         */
        protected $eng_nep_num	= array(
            '0'=>'०',
            '1'=>'१',
            '2'=>'२',
            '3'=>'३',
            '4'=>'४',
            '5'=>'५',
            '6'=>'६',
            '7'=>'७',
            '8'=>'८',
            '9'=>'९'
        );

	    /**
	     * Gets an instance of this object.
	     * Prevents duplicate instances which avoid artefacts and improves performance.
	     *
	     * @static
	     * @access public
	     * @since 2.0.0
	     * @return object
	     */
	    public static function get_instance() {

		    // Store the instance locally to avoid private static replication
		    static $instance = null;

		    // Only run these methods if they haven't been ran previously
		    if ( null === $instance ) {
			    $instance = new self();
		    }

		    // Always return the instance
		    return $instance;

	    }

        /**
         * Function get nepali number
         *
         * @since 1.0
         *
         * @param int $num
         * @return false|string
         *
         */
        public function get_nep_num( $num ){
            $number = array('०','१','२','३','४','५','६','७','८','९');
            if( isset ( $number[$num-1] ) ){
                return $number[$num-1];
            }
            else{
                return false;
            }
        }
        /**
         * Function get nepali month/mahina
         *
         * @since 1.0
         *
         * @param int $num
         * @return false|string
         *
         */
        public function get_mahina($num) {
            $mahinas = array('बैशाख','जेष्ठ','असार','साउन','भदौ','अशोज','कार्तिक','मंसिर','पुस','माघ','फाल्गुन','चैत्र');
            if( isset ( $mahinas[$num-1] ) ){
                return $mahinas[$num-1];
            }
            else{
                return false;
            }
        }

        /**
         * Function get nepali month
         *
         * @since 1.0
         *
         * @param int $num
         * @return false|string
         *
         */
        public function get_months($num) {
            $months = array('January','February','March','April','May','June','July','August','September','October','November','December');
            if( isset ( $months[$num-1] ) ){
                return $months[$num-1];
            }
            else{
                return false;
            }
        }

        /**
         * Function get nepali day/bar
         *
         * @since 1.0
         *
         * @param int $num
         * @return false|string
         *
         */
        function get_bar($num){
            $bars = array('आइतवार','सोमवार','मङ्लबार','बुधबार','बिहिबार','शुक्रबार','शनिबार');
            if( isset ( $bars[$num-1] ) ){
                return $bars[$num-1];
            }
            else{
                return false;
            }
        }

        /**
         * Function nepali date converter display
         *
         * @since 1.0
         *
         * @param array $ndc_datas
         * @return void
         *
         */
        public function nepali_date_converter( $ndc_datas ){

            if( isset( $ndc_datas ['before'] ) ){
                echo $ndc_datas ['before'];
            }
            if( isset( $ndc_datas ['before_title'] ) ){
                echo $ndc_datas ['before_title'];
            }
            if( isset( $ndc_datas ['title'] ) ){
                echo $ndc_datas ['title'];
            }
            if( isset( $ndc_datas ['after_title'] ) ){
                echo $ndc_datas ['after_title'];
            }

            $result_format = 'D, F j, Y';
            if( isset( $ndc_datas ['result_format'] ) ){
                $result_format = $ndc_datas ['result_format'];
            }

            $nepali_date_lang = 'nep_char';
            if( isset( $ndc_datas ['nepali_date_lang'] ) ){
                $nepali_date_lang = $ndc_datas ['nepali_date_lang'];
            }
            /*today eng date*/
            $today_eng_date = date("Y-m-d");
            $today_eng_date_result = date($result_format);
            $today_eng_dates = explode('-', $today_eng_date);
            $current_eng_yr = (int) $today_eng_dates[0];
            $current_eng_mth = (int) $today_eng_dates[1];
            $current_eng_day = (int) $today_eng_dates[2];

            /*today nepali date*/

            $today_nepali_date_data = ndc_eng_to_nep_date(array(
                'year' => $current_eng_yr,
                'month' => $current_eng_mth,
                'day' => $current_eng_day
            ),$nepali_date_lang,$result_format );

            $default_result = $today_nepali_date_data['result'];
            $current_nep_yr = (int) $today_nepali_date_data['nep_date']['year'];
            $current_nep_mth = (int) $today_nepali_date_data['nep_date']['month'];
            $current_nep_day = (int) $today_nepali_date_data['nep_date']['date'];
            if( isset( $ndc_datas ['disable_ndc_convert_nep_to_eng'] ) && 1 == $ndc_datas ['disable_ndc_convert_nep_to_eng'] ){
                /*disable nep to eng convert*/
            }
            else{
                /*$select_name, $min_num, $max_num, $selected, $type, from, $char = 'eng'*/
                echo "<div class='nep-to-eng nepali-date-converter'>";
                echo "<div class='wrapper-select'>";
                echo $this->selection_options( 'nte_year', 2000, 2089, $current_nep_yr, 'year', 'nep', 'nep' );
                echo $this->selection_options( 'nte_month', 1, 12, $current_nep_mth, 'month', 'nep', 'nep' );
                echo $this->selection_options( 'nte_day', 1, 32, $current_nep_day, 'day', 'nep', 'nep' );
                echo "<br /><button class='nepali-date-converter-trigger from-nep' data-result='".$result_format."'>".__('To Eng Date','nepali-date-converter')."</button>";
                echo "</div>";/*wrapper-select*/
                echo "</div>";/*nep-to-eng*/
            }

            if( isset( $ndc_datas ['disable_ndc_convert_eng_to_nep'] ) && 1 == $ndc_datas ['disable_ndc_convert_eng_to_nep'] ){
                /*disable nep to eng convert*/
                $default_result = $today_eng_date_result;
            }
            else{
                /*$select_name, $min_num, $max_num, $selected, $type, from, $char = 'eng'*/
                echo "<div class='eng-to-nep nepali-date-converter'>";
                echo "<div class='wrapper-select'>";
                echo $this->selection_options( 'etn_year', 1944, 2033, $current_eng_yr, 'year', 'eng', '' );
                echo $this->selection_options( 'etn_month', 1, 12, $current_eng_mth, 'month', 'eng', '' );
                echo $this->selection_options( 'etn_day', 1, 31, $current_eng_day, 'day', 'eng', '' );
                echo "<br /><button type='submit' class='nepali-date-converter-trigger from-eng' data-result='".$result_format."' data-lang='".$nepali_date_lang."'>".__('To Nep Date','nepali-date-converter')."</button>";
                echo "</div>";/*wrapper-select*/
                echo "</div>";/*eng-to-nep*/
            }
            echo "<div class='nepali-date-converter-result'>".
                $default_result
            ."</div>";
            if( isset( $ndc_datas ['after'] ) ){
                echo $ndc_datas ['after'];
            }
        }

        /**
         * Function to create select option
         *
         * @access public
         * @since 1.0
         *
         * @param string $select_name
         * @param int $min_num
         * @param int $max_num
         * @param int $selected
         * @param string $type that may contain following year, month, day
         * @param string $from that may contain following eng/nep
         * @param string $char that may contain following eng or nep
         * @return string
         *
         */
        public function selection_options( $select_name, $min_num, $max_num, $selected, $type, $from, $char = 'eng' ) {

            $ndc_select = "<select name='{$select_name}' class='ndc-select {$type}'>";
            for( $i = $min_num; $i <= $max_num; $i++ ){

                if( 'nep' == $from && 'nep' == $char ){
                    if( 'year' == $type ){
                        $option = strtr( $i, $this->eng_nep_num );
                    }
                    elseif( 'month' == $type ){
                        $option = $this->get_mahina($i);
                    }
                    elseif('day' == $type ) {
                        $option = strtr( $i, $this->eng_nep_num );
                    }
                    else{
                        $option = $i;
                    }
                }
                else{
                    if( 'month' == $type ){
                        $option = $this->get_months($i);
                    }
                    else{
                        $option = $i;
                    }
                }

                if( $i == $selected ){
                    $ndc_selected = "selected='selected'";
                }
                else{
                    $ndc_selected = '';
                }
                $ndc_select .= "<option value='{$i}' $ndc_selected>{$option}</option>";
            }
            $ndc_select .= "</select>";

            return $ndc_select;

        }

        /**
         * Function nepali date converter display today date
         *
         * @since 1.0
         *
         * @param array $ndc_datas
         * @return void
         *
         */
        public function today_date( $ndc_datas ) {

            if( isset( $ndc_datas ['before'] ) ){
                echo $ndc_datas ['before'];
            }
            if( isset( $ndc_datas ['before_title'] ) ){
                echo $ndc_datas ['before_title'];
            }
            if( isset( $ndc_datas ['title'] ) ){
                echo $ndc_datas ['title'];
            }
            if( isset( $ndc_datas ['after_title'] ) ){
                echo $ndc_datas ['after_title'];
            }

            $result_format = 'D, F j, Y';
            if( isset( $ndc_datas ['result_format'] ) ){
                $result_format = $ndc_datas ['result_format'];
            }

            $nepali_date_lang = 'nep_char';
            if( isset( $ndc_datas ['nepali_date_lang'] ) ){
                $nepali_date_lang = $ndc_datas ['nepali_date_lang'];
            }
            /*today eng date*/
            $today_eng_date = date("Y-m-d");
            $today_eng_date_result = date($result_format);
            $today_eng_dates = explode('-', $today_eng_date);
            $current_eng_yr = (int) $today_eng_dates[0];
            $current_eng_mth = (int) $today_eng_dates[1];
            $current_eng_day = (int) $today_eng_dates[2];

            /*today nepali date*/
            $today_nepali_date_data = ndc_eng_to_nep_date(array(
                'year' => $current_eng_yr,
                'month' => $current_eng_mth,
                'day' => $current_eng_day
            ),$nepali_date_lang,$result_format );

            $default_result = $today_nepali_date_data['result'];

            if( isset( $ndc_datas ['disable_today_nep_date'] ) && 1 == $ndc_datas ['disable_today_nep_date'] ){
                /*disable nep to eng convert*/
            }
            else{
                /*$select_name, $min_num, $max_num, $selected, $type, from, $char = 'eng'*/
                echo "<div class='nep-to-eng nepali-date-converter'>";
                echo $default_result;
                echo "</div>";/*nep-to-eng*/
            }

            if( isset( $ndc_datas ['disable_today_eng_date'] ) && 1 == $ndc_datas ['disable_today_eng_date'] ){
                /*disable nep to eng convert*/
            }
            else{
                /*$select_name, $min_num, $max_num, $selected, $type, from, $char = 'eng'*/
                echo "<div class='eng-to-nep nepali-date-converter'>";
                echo $today_eng_date_result;
                echo "</div>";/*eng-to-nep*/
            }
            if( isset( $ndc_datas ['after'] ) ){
                echo $ndc_datas ['after'];
            }
        }
    }
}

/**
 * Return Instance
 * NDC_Frontend
 *
 * @since    2.0.0
 */
if( !function_exists( 'ndc_frontend')){

	function ndc_frontend() {

		return NDC_Frontend::get_instance();
	}
}