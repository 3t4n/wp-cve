<?php
namespace PDFPro\Model;

class Presets {
    protected $table_name = '';

    protected $cast = [
        'preset' => [
            'titleFontSize' => 'string',
            'height' => 'string',
            'width' => 'string',
            'showName' => 'boolean',
            'print' => 'boolean',
            'onlyPDF' => 'boolean',
            'defaultBrowser' => 'boolean',
            'downloadButton' => 'boolean',
            'downloadButtonText' => 'string',
            'fullscreenButton' => 'boolean',
            'fullscreenButtonText' => 'string',
            'newWindow' => 'boolean',
            'protect' => 'boolean',
            'thumbMenu' => 'boolean',
            'initialPage' => 'string',
            'zoomLevel' => 'string',
            'alert' => 'boolean',
            'lastVersion' => 'boolean',
            'hrScroll' => 'boolean',
            'additional' => 'array',
            'adobeEmbedder' => 'boolean',
            'adobeOptions' => [
                "showDownloadPDF" => "boolean",
                "showPrintPDF" => "boolean",
                "showAnnotationTools" => "boolean",
                "showFullScreen" => "boolean",
                "embedMode" => "string",
                "updated" => "boolean"
            ],
            'popupBtnStyle' => 'array',
            'popupBtnText' => 'string',
        ]
    ];

    public function __construct(){
        global $wpdb;
        $this->table_name = $wpdb->prefix.'pdfposter_presets';
    }

    public function all(){
        global $wpdb;

        $data = (array) $wpdb->get_results("SELECT * FROM $this->table_name", 'ARRAY_A');
        $newData = [];

        foreach($data as $value){
            $newData[] = $this->prepareData($value, 'maybe_unserialize');
        }

        return new \WP_REST_Response($newData);
    }

    public function create($data){
        global $wpdb;
        $newData = $data;

        foreach($this->cast as $property => $type){
            if($type === 'array' || is_array($type)){
                $newData[$property] = maybe_serialize($data[$property]);
            }
        }
        $id = null;
        if(isset($data['id']) && $data['id'] != ''){
            $updated = $wpdb->update($this->table_name, $newData, ['id' => $data['id']]);
            $id = $data['id'];
        }else {
           $wpdb->insert($this->table_name, $newData, '%s');
            $id = $wpdb->insert_id;
        }
        
        return new \WP_REST_Response(wp_parse_args(['id' => $id], $data ), 200);
    }

    public function delete($data){
        global $wpdb;
        if(!$data['id']){
            return new \WP_REST_Response(false, 403);
        }

        $deleted = $wpdb->delete($this->table_name, ['id' => $data['id']], ['%d']);
        if($deleted){
            return new \WP_REST_Response(true, 200);
        }
        return new \WP_REST_Response(false, 404);
    }

    public function update($args = [], $where = []){
        global $wpdb;
        // $table_name = $wpdb->prefix.'pdfposter_presets';
        return $wpdb->update($this->table_name, $args, $where);
    }

    private function prepareData(array $data, $do = 'maybe_serialize'){
        $newData = $data;
        foreach($this->cast as $property => $type){
            if(is_array($type)){
                $data[$property] = $do($data[$property]);
                $newData[$property] = $data[$property];
                foreach($type as $inner_property => $inner_type){
                    if(is_array($inner_type)){
                        foreach($inner_type as $ii_property => $ii_type){
                            $newData[$property][$inner_property][$ii_property] = $this->typeRender($data[$property][$inner_property][$ii_property], $ii_type, $do);
                        }
                    }else {
                        $newData[$property][$inner_property] = $this->typeRender($data[$property][$inner_property], $inner_type, $do);
                    }
                }
            }else {
                $newData[$property] = $this->typeRender($data[$property], $type, $do);
            }
        }
        return $newData;
    }

    private function render_cast($data, $type, $do){
        
    }

    private function typeRender($data, $type, $do = 'maybe_serialize'){
        if($type === 'boolean'){
            return in_array($data, ['0', 'false']) ? false : true;
        }
        if($type === 'array'){
            return $do($data);
        }
        return $data;
    }
}

