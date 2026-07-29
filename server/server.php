<?php

require_once 'backend.php';

$backend = new Backend();

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

routeRequest( $requestMethod, $requestUri, $backend );

function routeRequest(string $method, string $uri, Backend $backend): void
{
    if ( $method === 'POST' && $uri === '/dbviewer/server/server.php/getall' ) {
        handleGetAll($backend);
        return;
    }

    if ( $method === 'POST' && $uri === '/dbviewer/server/server.php/getone' ) {
        handleGetOne($backend);
        return;
    }

    sendText( "Invalid Request on {$uri}", 400 );
}

function handleGetAll(Backend $backend): void
{
    try {
        $params = json_decode(file_get_contents('php://input'), true);

        if ( !is_array($params) ) {
            sendJson( json_encode([
                "error" => "Invalid JSON received"
            ]), 400 );
        }

        if ( empty($params['db']) || empty($params['user']) ) {
            sendJson( json_encode([
                "error" => "Invalid Data received"
            ]), 400 );
        }

        $host  = $params['host'] ?? 'localhost';
        $port  = $params['port'] ?? '3306';
        $db    = $params['db'];
        $user  = $params['user'];
        $pass  = $params['pass'] ?? '';
        $cert  = $params['cert'] ?? '';
        
        $result = $backend->connect($host, $port, $db, $user, $pass, $cert);

        if ( !$result["success"] ) {
            sendJson( json_encode($result), 500 );
        }

        $table = !empty($params['table']) ? $params['table'] : $backend->getFirstTable();

        $result = $backend->fetchAll($table);

        if ( !$result["success"] ) {
            sendJson( json_encode($result), 400 );
        }

        sendJson( $backend->renderJson() );
    }
    catch (Throwable $e) {
        sendJson(json_encode([
            "error" => $e->getMessage(),
            "type" => get_class($e)
        ]), 500);
    }
}

function handleGetOne(Backend $backend): void
{
    $params = json_decode(file_get_contents('php://input'), true);

    if ( !is_array($params) ) {
        sendJson( json_encode([
            "error" => "Invalid JSON received"
        ]), 400 );
    }

    if ( empty($params['id']) || !is_numeric($params['id']) || empty($params['db']) || empty($params['table']) || empty($params['user']) ) {
        sendJson( json_encode([
            "error" => "Invalid Data received"
        ]), 400 );
    }

    $host  = $params['host'] ?? 'localhost';
    $port  = $params['port'] ?? '3306';
    $db    = $params['db'];
    $table = $params['table'];
    $user  = $params['user'];
    $pass  = $params['pass'] ?? '';
    $key   = $params['key'] ?? 'id';
    $id    = $params['id'];
    $cert  = $params['cert'] ?? '';
    
    $result = $backend->connect($host, $port, $db, $user, $pass, $cert);

    if ( !$result["success"] ) {
        sendJson( json_encode($result), 500 );
    }

    $result = $backend->fetchOne($table, $key, $id);

    if ( !$result["success"] ) {
        sendJson( json_encode($result), 400 );
    }

    sendJson( $backend->renderJson() );
}

function sendJson(string $json, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json');

    echo $json;
    exit;
}

function sendText(string $text, int $status = 400): void
{
    http_response_code($status);
    header('Content-Type: text/plain');

    echo $text;
    exit;
}

?>