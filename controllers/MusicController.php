<?php

require_once("../models/Music.php");
class MusicController extends Music{

    public function getMusics(){

        $musicsJson = $this->userMusics();

        $musics = json_decode($musicsJson, true);

        if($musics === null || isset($musics["data"])){
            return json_encode([
                "status" => 500,
                "message" => "Invalid JSON response from userTasks()",
                "debug" => $musicsJson
            ]);
        }

        return json_encode($musics);
    }

    public function addMusic($music, $creator){
        $addMusic = $this->musicAdd($music, $creator);
        return json_encode($addMusic);
    }
}