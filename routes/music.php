<?php

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../controllers/MusicController.php");

$musicController = new MusicController();

$action = $_REQUEST['action'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "GET") {  
        $response = $musicController->getMusics();
        
        $decodedResponse = json_decode($response, true);

        if ($decodedResponse === null) {
            echo json_encode([
                "status" => 500, 
                "message" => "Invalid JSON response from Music Controller",
                "debug" => $response
            ]);
            exit;
        }

        echo $response;
        exit;
    
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id = $_POST["musicId"] ?? null;
    $music = $_POST["music"] ?? "";
    $creator = $_POST["creator"] ?? "";

    if ($action === 'add') {
        $response = $musicController->addMusic($music, $creator ,$id);
        echo $response;
        exit;
    }

    if ($action === 'delete') {
        $response = $musicController->deleteMusic($id);
        echo $response;
        exit;
    }
}

echo json_encode([
    "status" => 405,
    "message" => "Method Not Allowed"
]);
exit;
