<?php

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json'); // Ensure correct response type
require_once(__DIR__ . "/../controllers/PlaylistController.php");

$playlistController = new PlaylistController();

$action = $_REQUEST['action'] ?? ''; 

if ($_SERVER["REQUEST_METHOD"] == "GET") {  
    $response = $playlistController->getPlaylists();

    // Check if the response is valid JSON
    $decodedResponse = json_decode($response, true);

    if ($decodedResponse === null) {
        error_log("Invalid JSON Response: " . $response); // Log error for debugging
        echo json_encode([
            "status" => 500, 
            "message" => "Invalid JSON response from Playlist Controller",
            "json_error" => json_last_error_msg(),
            "debug" => $response
        ]);
        exit;
    }

    echo json_encode($decodedResponse);
    exit;
}
?>
