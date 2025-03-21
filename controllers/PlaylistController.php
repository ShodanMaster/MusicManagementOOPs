<?php

require_once("../models/Playlist.php");

class PlaylistController extends Playlist{

    public function getPlaylists() {
        try {
            $playlistJson = $this->userPlaylists();
            $playlists = json_decode($playlistJson, true);

            // Check if JSON decoding was successful
            if ($playlists === null) {
                return json_encode([
                    "status" => 500,
                    "message" => "Invalid JSON response from userPlaylists()",
                    "json_error" => json_last_error_msg(),
                    "debug" => $playlistJson // Debug output
                ]);
            }

            // Check if "data" key exists
            if (!isset($playlists["data"])) {
                return json_encode([
                    "status" => 500,
                    "message" => "JSON response is missing 'data' key.",
                    "debug" => $playlists
                ]);
            }

            return json_encode($playlists);

        } catch (Exception $e) {
            return json_encode([
                "status" => 500,
                "message" => "An error occurred while fetching playlists.",
                "error" => $e->getMessage()
            ]);
        }
    }

    public function addPlaylist($playlist, $id=null){
        try{
            
            if($id!=null){
                $addPlaylist = $this->playlistUpdate($id, $playlist);
                return json_encode($addPlaylist);
            }

            $addPlaylist = $this->playlistAdd($playlist);
            return json_encode($addPlaylist);

        } catch (Exception $e) {
            return json_encode([
                "status" => 500,
                "message" => "Failed to add playlist",
                "error" => $e->getMessage()
            ]);
        }
    }

    public function deletePlaylist($id){
        $deletePlaylist = $this->playlistDelete($id);
        return json_encode($deletePlaylist);
    }

}