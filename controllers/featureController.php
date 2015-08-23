<?php

include_once '../class/StringHelper.class.php';
include_once '../class/RestAPIHelper.class.php';

include_once 'config.php';

$action = StringHelper::ifPostNotExistThenNull('action');

error_reporting(E_ALL);
ini_set('display_errors', TRUE);

switch ( $action ){
    case 'addNewFeatureRequest':
        $title =  StringHelper::ifPostNotExistThenNull('title');
        $description = StringHelper::ifPostNotExistThenNull('description');
        $client = StringHelper::ifPostNotExistThenNull('client');
        $priority = StringHelper::ifPostNotExistThenNull('priority');
        $date = StringHelper::ifPostNotExistThenNull('date');
        $url = StringHelper::ifPostNotExistThenNull('url');
        $product = StringHelper::ifPostNotExistThenNull('product');

        $url = RestAPIHelper::createLocalRESTAPIUrl(BASE_FOLDER.'/api/requests.php/request');

        $data = array(
            'title' => $title,
            'description' => $description,
            'client' => $client,
            'priority' => $priority,
            'date' => $date,
            'url' => $url,
            'product' => $product);
        $jsonResult = RestAPIHelper::CallAPI("POST", $url, json_encode($data));

        if ($jsonResult->status == 'fail'){
            header("Location: "."../index.php?msg=".$jsonResult->reason);
        }else {
            header("Location: "."../index.php");
        }

        break;

    default:
        break;

}

