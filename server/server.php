<?php

require_once 'backend.php';

$backend = new Backend();

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

routeRequest( $requestMethod, $requestUri, $backend );

function routeRequest(string $method, string $uri, Backend $backend): void
{
    if ( $method === 'POST' && $uri === '/dbviewer/server/server.php/getone' ) {
        handleGetOne($backend);
        return;
    }

    if ( $method === 'GET' && $uri === '/dbviewer/server/server.php/getall' ) {
        handleGetAll($backend);
        return;
    }

    if ( $method === 'GET' && $uri === '/dbviewer/server/server.php/gethtml' ) {
        handleGetHtml($backend);
        return;
    }

    sendText( "Invalid Request on {$uri}", 400 );
}

function handleGetOne(Backend $backend): void
{
    parse_str( file_get_contents('php://input'), $params );

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

    
    $result = $backend->connect($host, $port, $db, $user, $pass);

    if ( !$result["success"] ) {
        sendJson( json_encode($result), 500 );
    }

    $result = $backend->fetchOne($table, $key, $id);

    if ( !$result["success"] ) {
        sendJson( json_encode($result), 400 );
    }

    sendJson( $backend->renderJson() );
}

function handleGetAll(Backend $backend): void
{
    if ( empty($_GET['db']) || empty($_GET['table']) || empty($_GET['user']) ) {
        sendJson( json_encode([
            "error" => "Invalid Data received"
        ]), 400 );
    }

    $host  = $_GET['host'] ?? 'localhost';
    $port  = $_GET['port'] ?? '3306';
    $db    = $_GET['db'];
    $table = $_GET['table'];
    $user  = $_GET['user'];
    $pass  = $_GET['pass'] ?? '';

    
    $result = $backend->connect($host, $port, $db, $user, $pass);

    if ( !$result["success"] ) {
        sendJson( json_encode($result), 500 );
    }

    $result = $backend->fetchAll($table);

    if ( !$result["success"] ) {
        sendJson( json_encode($result), 400 );
    }

    sendJson( $backend->renderJson() );
}

function handleGetHtml(Backend $backend): void
{
    if ( empty($_GET['db']) || empty($_GET['table']) || empty($_GET['user']) ) {
        sendJson( json_encode([
            "error" => "Invalid Data received"
        ]), 400);
    }

    $host  = $_GET['host'] ?? 'localhost';
    $port  = $_GET['port'] ?? '3306';
    $db    = $_GET['db'];
    $table = $_GET['table'];
    $user  = $_GET['user'];
    $pass  = $_GET['pass'] ?? '';

    $result = $backend->connect($host, $port, $db, $user, $pass);

    if ( !$result["success"] ) {
        sendHtml( "error: " . $result["error"], 500 );
    }

    $result = $backend->fetchAll($table);
    
    if ( !$result["success"] ) {
        sendHtml( "error: " . $result["error"], 400 );
    }

    sendHtml( $backend->renderHtml() );
}

function sendJson(string $json, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json');

    echo $json;
    exit;
}

function sendHtml(string $html, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: text/html');

    echo $html;
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