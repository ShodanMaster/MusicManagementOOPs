<?php

require_once("../models/MusicPlaylist.php");

class MusicPlaylistController extends MusicPlaylist {

    
    public function musicToPlaylist($musicId, $playlist){
        $musicPlaylist = $this->musicPlaylist($musicId, $playlist);
        return json_encode($musicPlaylist);
    }

    public function playlistMusics($playlistId){
        $playlistMusics = $this->MusicsPlaylist($playlistId);
        
        if (empty($playlistMusics)) {
            return json_encode(["status" => 404, "message" => "No music found in this playlist"]);
        }
    
        return json_encode(["status" => 200, "data" => $playlistMusics]);
    }  
    
}