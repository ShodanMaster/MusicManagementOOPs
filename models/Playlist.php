<?php
session_start();

include("../config/Dbconfig.php");

class Playlist extends Dbconfig{

    private $userId;

    public function __construct() {
        if (isset($_SESSION['user_id'])) {
            $this->userId = $_SESSION['user_id'];
        } else {
            header('Location: authenticate.php');
            exit();
        }
    }

    protected function userPlaylists() {
        try {
            $conn = $this->connect();
    
            $draw = $_GET['draw'] ?? 1;
            $start = (int)($_GET['start'] ?? 0);
            $length = (int)($_GET['length'] ?? 10);
            $searchValue = $_GET['search']['value'] ?? '';
    
            // Get total records count
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM playlists WHERE user_id = ?");
            $stmt->bind_param("i", $this->userId);
            $stmt->execute();
            $totalRecords = $stmt->get_result()->fetch_assoc()['count'];
    
            // Query for filtered results
            $query = "SELECT id, playlist FROM playlists WHERE user_id = ?";
            $params = [$this->userId];
            $types = "i";
    
            if (!empty($searchValue)) {
                $query .= " AND (playlist LIKE ?)";
                $searchValue = "%$searchValue%";
                array_push($params, $searchValue);
                $types .= "s";
            }
    
            // Get count of filtered records
            $filterQuery = str_replace("SELECT id, playlist", "SELECT COUNT(*) as count", $query);
            $stmt = $conn->prepare($filterQuery);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $recordsFiltered = $stmt->get_result()->fetch_assoc()['count'];
    
            // Add ORDER BY and LIMIT
            $query .= " ORDER BY id DESC LIMIT ?, ?";
            array_push($params, intval($start), intval($length));
            $types .= "ii";
    
            $stmt = $conn->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
    
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
    
            return json_encode([
                "draw" => intval($draw),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $recordsFiltered,
                "data" => $data
            ]);
    
        } catch (Exception $e) {
            error_log("Error in userPlaylists: " . $e->getMessage());
            return json_encode([
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => "An error occurred, please try again later."
            ]);
        }
    }
    
    protected function playlistAdd($playlist){
        try {
            $conn = $this->connect();

            $sql = "INSERT INTO playlists(user_id, playlist) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $this->userId, $playlist);

            if ($stmt->execute()) {
                return ["status" => 200, "message" => "Playlist added successfully!"];
            } else {
                return ["status" => 500, "message" => "Playlist addition failed!"];
            }
        } catch (mysqli_sql_exception $e) {
            return ["status" => 500, "message" => "Database error: " . $e->getMessage()];
        }
    }

}