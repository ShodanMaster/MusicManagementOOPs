<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

error_log("Request method: " . $_SERVER["REQUEST_METHOD"]);

require_once("../controllers/MusicController.php");

$musicController = new MusicController();

header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    $response = $musicController->getMusics();

    if (!json_decode($response, true)) {
        echo json_encode(["status" => 500, "message" => "Invalid JSON response", "debug" => $response]);
        exit;
    }

    echo $response;
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"] ?? "";
    $music = $_POST["music"] ?? "";
    $creator = $_POST["creator"] ?? "";
    $status = $_POST["status"] ?? "";

    if ($action === 'add') {
        $response = $musicController->addMusic($music, $creator);
        echo $response;
        exit;
    }

    // if ($action === 'edit') {
    //     $response = $musicController->editTask($id, $music, $creator);
    //     echo $response;
    //     exit;
    // }

    // if ($action === 'delete') {
        
    //     $response = $musicController->deleteTask($id);
    //     echo $response;
    //     exit;
    // }

    // if ($action === 'status') {
        
    //     $response = $musicController->updateStatus($id, $status);
    //     echo $response;
    //     exit;
    // }
}

echo json_encode(["status" => 405, "message" => "Method Not Allowed"]);
exit;