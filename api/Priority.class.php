<?php

require_once "vendor/autoload.php";
require_once "config.php";


class Priority
{
    public static function ArrangePriority($clientId, $priority){
        $requestsRows = DB::query("select * from requests, clients, products where
          requests.client_id = %i and
          requests.product_id = products.id", $clientId);

        $maxPriority = count($requestsRows);

        foreach ( $requestsRows as $requestsRow){

        }
    }
}