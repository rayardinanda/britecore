<?php

require_once 'vendor/autoload.php';
require_once 'config.php';

$app = new \Slim\Slim(array(
    'debug' => true
));

$app->get('/clients', function () use ($app) {
    $clients = DB::query("select * from clients");
    foreach ($clients as $client) {
        $clientsArray[]= array(
            'id' => $client['id'],
            'name' => $client['name']
        );
    }
    echo json_encode($clientsArray);
});

$app->get('/priorities/:id', function ($id) use ($app) {
    $requests = DB::query("select * from requests where client_id = %i", $id);
    foreach ($requests as $request) {
        $requestsArray[]= array(
            'id' => $request['priority'],
            'name' => $request['priority']
        );
    }

    if ( count($requests) == 0 ){
        $requestsArray[]= array(
            'id' => 1,
            'name' => 1
        );
    } else {
        $requestsArray[]= array(
            'id' => count($requests)+1,
            'name' => count($requests)+1
        );
    }

    echo json_encode($requestsArray);
});

$app->get('/products', function () use ($app) {
    $products = DB::query("select * from products");
    foreach ($products as $product) {
        $productsArray[]= array(
            'id' => $product['id'],
            'name' => $product['name']
        );
    }
    echo json_encode($productsArray);
});

$app->get('/requests/datatable', function () use ($app) {

    $requests = DB::query("select requests.*, clients.name as clientsName, products.name as productsName from requests, clients, products where requests.client_id = clients.id and requests.product_id = products.id");

    $dataTable = null;
    $dataTable["recordsTotal"] = count($requests);
    $dataTable["recordsFiltered"] = count($requests);

    foreach ($requests as $request) {
        $dataTable["data"][] = array(
            $request['id'],
            $request['title'],
            $request['description'],
            $request['client_id'],
            $request['clientsName'],
            $request['priority'],
            $request['target_date'],
            $request['url'],
            $request['product_id'],
            $request['productsName']
        );
    }

    if (count($requests) == 0) {
        $dataTable["data"] = [];
        $dataTable["iTotalRecords"] = 0;
        $dataTable["iTotalDisplayRecords"] = 0;
    }

    echo json_encode($dataTable);
});

$app->post('/request', function () use ($app) {
    $app->response()->header("Content-Type", "application/json");
    $json = $app->request()->getBody();
    $data = json_decode($json, true);

    $title = $data['title'];
    $desc = $data['description'];
    $clientId = $data['client'];
    $priority = $data['priority'];
    $date = $data['date'];
    $url = $data['url'];
    $productId = $data['product'];

    DB::$error_handler = false; // since we're catching errors, don't need error handler
    DB::$throw_exception_on_error = true;

    try {
        //Handle the case if the priority inserted is used by another request
        $requestsRows = DB::query("select * from requests where client_id = %i order by priority", $clientId);
        //If priority of the feature is higher than the features already submitted
        $priorityPropagatePoint = 1;
        if ( $priorityPropagatePoint < count($requestsRows) ){
            foreach ( $requestsRows as $requestRow ){
                $requestRowId = intval($requestRow['id']);
                $requestRowPriority = intval($requestRow['priority']);
                if ($requestRowPriority >= $priority){
                    DB::update('requests', array(
                        'priority' => $requestRowPriority + 1),
                        "id=%i", $requestRowId);
                }
            }
        }

        //At this point, we assure that there is no duplicate...
        DB::insert('requests', array(
            'title' => $title,
            'description' => $desc,
            'client_id' => $clientId,
            'priority' => $priority,
            'target_date' => $date,
            'url' => $url,
            'product_id' => $productId));
        echo json_encode(array("status" => "success", "id" => DB::insertId()));

    } catch (MeekroDBException $e) {
        echo json_encode(array("status" => "fail", "reason" => $e->getMessage()));
    }

    DB::$error_handler = 'meekrodb_error_handler';
    DB::$throw_exception_on_error = false;
});

$app->run();