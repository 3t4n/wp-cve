<?php
if (!defined('ABSPATH')) {
    die('Do not open this file directly.');
}

class JEMEXP_BaseEntity
{

    public $fields;            //The field list
    public $id;                //What entity is this
    public $enabled;        //is it enabled?
    public $fields_to_export; //What fields does the user want to export
    public $filters;        //Filters for the query
    public $settings;        //The plugin settings for this user
    public $delimiter;        //what delimiter is used

    /**
     * outputs the HTML for the fields for this entity
     */
    public function render_fields()
    {
    }


    /**
     * gets the overrides from the settings and updates the fields array
     */
    private function get_label_overrides()
    {
    }

    /**
     * Generates the HTML for the filter screen for this product. Gets overriden in the appropriate entity class
     * 
     */
    public function generate_filters()
    {
    }

    /**
     * This actually runs the query!  Gets overriden in the appropriate entity class
     */
    public function run_query($file)
    {
    }


    /**
     * Takes the form POST as input and gets the filter params from it
     * They are entity specific 
     * @param unknown $post
     */
    public function extract_filters($post)
    {
    }

    /**
     * Creates the default sort order for an entity
     */
    public function generate_default_sort_order()
    {
        $tempArray = array();

        $i = 1;

        foreach ($this->fields as $field) {
            $tempArray[$field['name']] = $i;
            $i = $i + 1;
        }

        return $tempArray;
    }

    /**
     * Creates the header row for the CSV file...
     * This is common across all entities so is in the base class
     */
    protected function create_header_row()
    {

        //lets get the options for these labels
        $labels = get_option(JEMEXP_DOMAIN . '_' . $this->id . '_labels');

        $data = array();

        foreach ($this->fields_to_export as $key => $field) {

            //do we have a custom label for this one?
            $val = (isset($labels[$key])) ? $labels[$key] : $this->fields[$key]['placeholder'];
            array_push($data, $val);
        }

        //Now add any meta
        if (isset($this->meta_array)) {
            foreach ($this->meta_array as $meta) {
                array_push($data, $meta);
            }
        }


        //Now add any CUSTOM fields
        if (isset($this->custom_array)) {
            foreach ($this->custom_array as $cust) {
                array_push($data, $cust);
            }
        }

        return $data;
    }


    /**
     * This extracts the entitiy specific fields from the schedule $_POST
     */
    public function extract_schedule_settings($post)
    {
    }

    /*
	 * Writes out the headers
	 *
	 */
    protected function write_headers($file_name)
    {

        //funky headers!
        //TODO - put this in a function - need to work out how to handle non-western characters etc
        //http://www.andrew-kirkpatrick.com/2013/08/output-csv-straight-to-browser-using-php/ with some mods
        header("Expires: 0");
        header("Pragma: no-cache");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$file_name.csv");
    }

    /**
     * Gets the array of applicable labels from the options table for this Export Type
     * @return array|mixed|object|void
     */
    public function load_labels()
    {
        $labels = get_option(JEMEXP_DOMAIN . '_' . $this->id . '_labels', '');

        $labels = json_decode($labels, true);

        return $labels;
    }

    /**
     * Gets the sequence that the labels are in for this extract
     * TODO - we'll nbe hacking this up as we create a SINGLE option for a SINGLE export
     *
     * @return array|mixed|object
     */
    public function load_label_sequence()
    {
        $get_sort_labels = get_option(JEMEXP_DOMAIN . '_' . $this->id . '_option', "");
        $sort_order_decode = json_decode($get_sort_labels, true);

        return $sort_order_decode;
    }

    /**
     * Creates the HTML for the export page
     *
     */
    public function generate_export_html($settings)
    {
        return "New stuf<BR>more new stuf<BR>and yet more<BR>";
    }

    /**
     * generates the dropdown for the export type
     * It's in the base class as each type uses it
     * @return string
     */
    public function generate_export_type_html()
    {

        ob_start();
        include('templates/export-export-type.php');
        $html = ob_get_clean();

        return $html;
    }
}
