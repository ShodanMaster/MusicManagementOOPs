<?php

require_once("../models/Music.php");

class MusicController extends Music {

    public function getMusics() {
        try {
            $musicsJson = $this->userMusics();
            $musics = json_decode($musicsJson, true);

            // Check if the data is valid
            if ($musics === null || !isset($musics["data"])) {
                return json_encode([
                    "status" => 500,
                    "message" => "Invalid JSON response from userMusics()",
                    "debug" => $musicsJson
                ]);
            }

            return json_encode($musics);  // Return valid data

        } catch (Exception $e) {
            // Handle unexpected errors
            return json_encode([
                "status" => 500,
                "message" => "An error occurred while fetching musics.",
                "error" => $e->getMessage()
            ]);
        }
    }

    public function addMusic($music, $creator ,$id=null) {
        try {

            if($id!=null){
                $addMusic = $this->musicUpdate($id, $music, $creator);
                return json_encode($addMusic);
                
            }
            
            $addMusic = $this->musicAdd($music, $creator);
            return json_encode($addMusic);
        } catch (Exception $e) {
            return json_encode([
                "status" => 500,
                "message" => "Failed to add music",
                "error" => $e->getMessage()
            ]);
        }
    }

    public function deleteMusic($id){
        $deleteMusic = $this->musicDelete($id);
        return json_encode($deleteMusic);
    }
}
