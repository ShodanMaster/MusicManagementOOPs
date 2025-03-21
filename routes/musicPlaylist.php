<?php

header('Content-Type: application/json');
error_reporting(E_ALL); // Log errors in development, turn off for production
ini_set('display_errors', 1); // Disable in production

require_once("../controllers/MusicPlaylistController.php");

$musicController = new MusicPlaylistController();

$action = $_REQUEST['action'] ?? ''; // Get the action from the request

if ($_SERVER["REQUEST_METHOD"] == "POST") {  

    $id = $_POST["playlistmusicId"] ?? null;
    $music = $_POST["music"] ?? "";
    $playlist = $_POST["playlist"] ?? "";
    $playlistId = $_POST["playlistId"] ?? "";

    if ($action === 'addToPlaylist') {
        $response = $musicController->musicToPlaylist($id, $playlist);
        echo $response;
        exit;
    }

    if ($action === 'playlistmusics') {
        $response = $musicController->playlistMusics($playlistId);
        echo $response;
        exit;
    }
}