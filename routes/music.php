<?php

header('Content-Type: application/json');
error_reporting(E_ALL); // Log errors in development, turn off for production
ini_set('display_errors', 1); // Disable in production

require_once("../controllers/MusicController.php");

$musicController = new MusicController();

$action = $_REQUEST['action'] ?? ''; // Get the action from the request

if ($_SERVER["REQUEST_METHOD"] == "GET") {  
        $response = $musicController->getMusics();
        
        // Decode JSON response to ensure it's valid
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
    // Collect POST data
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
