<?php
session_start();
include("../config/Dbconfig.php");

class MusicPlaylist extends Dbconfig {

    private $userId;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: authenticate.php');
            exit();
        }
    }
    
    protected function musicPlaylist($musicId, $playlist){
        try {
            $conn = $this->connect();
            $conn->begin_transaction();
            
            if ($this->exists($musicId, $playlist)) {
                return ["status" => 409, "message" => "Music is already in this playlist!"];
            }
            
            $sql = "INSERT INTO playlist_music (playlist_id, music_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $playlist, $musicId);
    
            if ($stmt->execute()) {
                $conn->commit();
                return ["status" => 200, "message" => "Added to Playlist successfully!"];
            } else {
                $conn->rollback();
                return ["status" => 500, "message" => "Music Playlist addition failed!"];
            }
        } catch (mysqli_sql_exception $e) {
            $conn->rollback();
            return ["status" => 500, "message" => "Database error: " . $e->getMessage()];
        }
    }
    
    private function exists($musicId, $playlist) {
        $conn = $this->connect();
        $sql = "SELECT COUNT(*) FROM playlist_music WHERE playlist_id = ? AND music_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $playlist, $musicId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
    
        return $count > 0;
    }

    protected function MusicsPlaylist($playlistId) {
        $conn = $this->connect();
        $userId = $_SESSION['user_id'];
    
        $sql = "SELECT m.id, m.music, m.creator 
                FROM playlist_music pm
                INNER JOIN musics m ON pm.music_id = m.id
                INNER JOIN playlists p ON pm.playlist_id = p.id
                WHERE pm.playlist_id = ? AND p.user_id = ?";
    
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $playlistId, $userId);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $musics = [];
    
        while ($row = $result->fetch_assoc()) {
            $musics[] = $row;
        }
    
        $stmt->close();
        return $musics;
    }
    
}