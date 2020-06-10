<?php

$app->group('/absoft', function() use ($app) {
    $app->get('/customers', function() use ($app) {

        $endpoint = new MailWizzApi_Endpoint_Customers();
        $response = $endpoint->getLists();
        echo MailWizzApi_Json::encode($response->body);
    });

    $app->get('/get-lists-by-customer', function() use ($app) {

        $endpoint = new MailWizzApi_Endpoint_Lists();
        if(!$app->request()->get('customer_id'))
        {
            echo json_encode(array('status' => 'error', 'result' => 'Parameter [customer_id] is missing'));
            $app->stop();
        }
        $response = $endpoint->getListsByCustomer($app->request()->get('customer_id'));
        echo MailWizzApi_Json::encode($response->body);
    });

    $app->post('/create-list', function() use ($app) {

        $endpoint = new MailWizzApi_Endpoint_Lists();
        $post = $app->request()->post();
        $data = array(
            'general' => array(
                'name'          => $post['name'], // required
                'description'   => $post['description'], // required
            ),
            'defaults' => array(
                'from_name' => $post['from_name'],
                'from_email'=>  $post['from_email'],
                'reply_to'  => $post['reply_to'],
                'subject'   => $post['subject'],
            ),
            'company' => array(
                'name'      => isset($post['company_name'])?$post['company_name']:'', // required
                'country'   => isset($post['company_country'])?$post['company_country']:'', // required
                'zone'      => isset($post['company_zone'])?$post['company_zone']:'', // required
                'address_1' => isset($post['company_address_1'])?$post['company_address_1']:'', // required
                'address_2' => '',
                'zone_name' => '', // when country doesn't have required zone.
                'city'      => isset($post['from_name'])?$post['from_name']:'',
                'zip_code'  => isset($post['from_name'])?$post['from_name']:'',
            ),
        );
        $response = $endpoint->createByCustomerId($post['customer_id'],$data);
        echo MailWizzApi_Json::encode($response->body);
    });

    $app->post('/upload',function () use ($app) {
        $endpoint = new MailWizzApi_Endpoint_Customers();
        $postData = $app->request()->post();
        $reponse = $endpoint->upload($postData);
        echo MailWizzApi_Json::encode($reponse->body);
    });

    $app->post('/upload-base64',function () use ($app) {
        $endpoint = new MailWizzApi_Endpoint_Customers();
        $postData = $app->request()->post();
        $reponse = $endpoint->upload_base64($postData);
        echo MailWizzApi_Json::encode($reponse->body);
    });
    $app->get('/subscribers', function() use ($app){
        $endpoint = new MailWizzApi_Endpoint_ListSubscribers();
        $postData = $app->request()->get('list_uid');
        // $data = array('list_uid' => 'qb742ov5qf758');
        $response = $endpoint->getSubscribers($postData);
        
        echo MailWizzApi_Json::encode($response->body); 
    });
});