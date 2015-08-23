<?php
include 'httpful.phar';


class RestAPIHelper
{
    public static function createLocalRESTAPIUrl( $url ){
        return 'http://'.$_SERVER['HTTP_HOST'].$url;
    }

    public static function CallAPI($method, $url, $data = false){
        $response = null;
        switch ( $method ){
            case "GET":
                $response = \Httpful\Request::get($url)->expects("json")->send();
                break;
            case "POST":
                $response = \Httpful\Request::post($url)->body($data)->sendsAndExpects("json")->send();
                break;
            case "PUT":
                $response = \Httpful\Request::put($url)->body($data)->sendsAndExpects("json")->send();
                break;
            case "DELETE":
                $response = \Httpful\Request::delete($url)->body($data)->sendsAndExpects("json")->send();
                break;
        }

        return is_null($response) ? null : $response->body;


    }
}