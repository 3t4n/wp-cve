<?php namespace BDroppy\Services\Config;


class baseConfig {

    protected $settings ;
    protected $base ;

    public function __construct()
    {
        $this->getSettings();

    }

    private function getSettings()
    {
        $this->settings =  [
            'setting' => (array) get_option( 'bdroppy-setting'),
            'api' => (array) get_option( 'bdroppy-api' ,[
                'api-base-url' => 'https://prod.bdroppy.com'
            ]),
            'catalog' => (array) get_option( 'bdroppy-catalog' , [
                'catalog' => 0,
                'import-images' => 3,
                'update-prices' => 1,
                'import-brand-to-title' => 0,
                'import-tag-to-title' => 0,
                'add-image-url-tools'=> 0
            ] ),
        ];
    }

    public function get($name = null,$defaultValue = false)
    {
        if ($name == null && isset($this->settings[$this->base]))
        {
            return $this->settings[$this->base] ;
        }else if (isset($this->settings[$this->base][$name] ))
        {
            return $this->settings[$this->base][$name] ;
        }
        return $defaultValue;
    }
    public function set($name,$value = false)
    {
        if(is_array($name))
        {
            $this->settings[$this->base] = array_merge( $this->settings[$this->base],$name);

        }else{
            $this->settings[$this->base][$name] = $value;
        }
        return update_option( 'bdroppy-'.$this->base,$this->settings[$this->base] );
    }

}