<?php
namespace BDroppy\Services\Remote;

if (!defined('ABSPATH')) exit;


class MainRemote extends BaseServer
{


    public function getToken($email,$password)
    {

        $this->init('/api/');

        return $this->post("auth/login", json_encode([
            'email' => $email,
            'password' => $password
        ]));

    }

    public function connectCatalog($catalog)
    {
        return $this->post("user_catalog/$catalog/woocommercePlugin", json_encode([
            "shopName" => get_bloginfo('name'),
            "url" => get_bloginfo('url')
        ]));
    }

    public function setCronJob()
    {
        return $this->post("user_cron",
            json_encode([
                "name" => get_bloginfo('name'),
                "description" => "auto created by wc module",
                "url" => get_bloginfo('url') . '/wp-cron.php?doing_wp_cron',
                "catalog" => $this->config->catalog->get('catalog'),
                "interval" => 2,
                "status" => 1,
            ]));

    }

    public function categories($lang)
    {
        $response = $this->get('category');
        if ($response["response"]['code'] !== 200) return 500;

        $result = [];
        foreach ($response['body'] as $item){
            $value = isset($item->translations->{$lang})? $item->translations->{$lang} : $item->code;
            $result[$item->code] = $value;
        }
        return $result;
    }

    public function subcategories($category,$lang)
    {
        $response = $this->get('subcategory?tag_4='.$category);
        if ($response["response"]['code'] !== 200) return 500;

        $result = [];
        foreach ($response['body'] as $item){
            $value = isset($item->translations->{$lang})? $item->translations->{$lang} : $item->code;
            $result[$item->code] = $value;
        }
        return $result;
    }

    public function getMe()
    {
        $this->init('/api/');
        $response = $this->send('user/me');
        return $response;
    }

}