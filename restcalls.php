<?php
require 'vendor/autoload.php';



class TodoApiConsumer
{
    const privateApiKey = "itU0ptytTpakSkVtlYYYVTsfAf5mNQ3h";
    const publicApiKey = "1yILfUsp5Odkiki1VtDNyqoqJ3jYR86i";

    function __construct()
    {
        $this->client = new GuzzleHttp\Client();
    }

    public function getAllTodos()
    {
        $data = ['$publicApiKey' => TodoApiConsumer::publicApiKey, 'randomString' => uniqid()];
        $serverHash = $this->getServerHash($data);
        $url = 'http://homestead.app/todo/?public_key=' . TodoApiConsumer::publicApiKey . '&hashed_data=' . $serverHash . '&data=' . urlencode(json_encode($data));
        $res = $this->client->request('GET', $url);
        $results = json_decode($res->getBody());
        return $results;
    }
    public function getTodo($id)
    {
        $data = ['$publicApiKey' => TodoApiConsumer::publicApiKey, 'randomString' => uniqid()];
        $serverHash = $this->getServerHash($data);
        $url = 'http://homestead.app/todo/' . $id .'?public_key=' . TodoApiConsumer::publicApiKey . '&hashed_data=' . $serverHash . '&data=' . urlencode(json_encode($data));
        $res = $this->client->request('GET', $url);
        $results = json_decode($res->getBody());
        return $results;
    }
    public function postNewTodo($description, $done)
    {
        $data = ['$publicApiKey' => TodoApiConsumer::publicApiKey, 'randomString' => uniqid()];
        $serverHash = $this->getServerHash($data);
        $url = 'http://homestead.app/todo/?public_key=' . TodoApiConsumer::publicApiKey;
        $res = $this->client->post($url, ['json' => ['data' => json_encode($data), 'hashed_data' => $serverHash, 'description' => $description, 'done' => $done]]);
        $results = json_decode($res->getBody());
        return $results;
    }

    public function updateTodo($id, $description,$done)
    {
        $data = ['$publicApiKey' => TodoApiConsumer::publicApiKey, 'randomString' => uniqid()];
        $serverHash = $this->getServerHash($data);
        $url = 'http://homestead.app/todo/' . $id .'?public_key=' . TodoApiConsumer::publicApiKey;
        $res = $this->client->put($url, ['json' => ['data' => json_encode($data), 'hashed_data' => $serverHash, 'description' => $description, 'done' => $done]]);
        $results = json_decode($res->getBody());
        return $results;
    }

    public function deleteTodo($id)
    {
        $data = ['$publicApiKey' => TodoApiConsumer::publicApiKey, 'randomString' => uniqid()];
        $serverHash = $this->getServerHash($data);
        $url = 'http://homestead.app/todo/' . $id .'?public_key=' . TodoApiConsumer::publicApiKey . '&hashed_data=' . $serverHash . '&data=' . urlencode(json_encode($data));
        $res = $this->client->delete($url);
        $results = json_decode($res->getBody());
        return $results;
    }

    private function getServerHash($data)
    {
        return hash_hmac("sha256", json_encode($data), TodoApiConsumer::privateApiKey);
    }

}

$todoApi = new TodoApiConsumer();
$results = $todoApi->postNewTodo('testTodo', 0);
$posted_id = $results->id;
$todo = $todoApi->getTodo($posted_id);
$todo->description = "testupdate";
$todoApi->updateTodo($todo->id,$todo->description,$todo->done);
$result = $todoApi->getTodo($todo->id);
$todoApi->deleteTodo($result->id);









