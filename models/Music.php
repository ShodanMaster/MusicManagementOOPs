<?php
session_start();
include("../config/Dbconfig.php");

class Music extends Dbconfig{

    private $userId;

    public function __construct(){
        if (isset($_SESSION['user_id'])) {
            $this->userId = $_SESSION['user_id'];
        } else {
           header('authenticate.php');
        }
    }

    protected function userMusics() {
        try {
            $conn = $this->connect();
            
            $draw = $_GET['draw'] ?? 1;
            $start = $_GET['start'] ?? 0;
            $length = $_GET['length'] ?? 10;
            $searchValue = $_GET['search']['value'] ?? '';
            
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM musics WHERE user_id = ?");
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $totalRecords = $stmt->get_result()->fetch_assoc()['count'];
            
            $query = "SELECT id, music, creator FROM musics WHERE user_id = ?";
            $params = [$_SESSION['user_id']];
            $types = "i";
            
            if (!empty($searchValue)) {
                $query .= " AND (music LIKE ? OR creator LIKE ?)";
                $searchValue = "%$searchValue%";
                array_push($params, $searchValue, $searchValue);
                $types .= "ss";
            }
            
            $filterQuery = "SELECT COUNT(*) as count FROM musics WHERE user_id = ?";
            if (!empty($searchValue)) {
                $filterQuery .= " AND (music LIKE ? OR creator LIKE ?)";
            }
    
            $stmt = $conn->prepare($filterQuery);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $recordsFiltered = $stmt->get_result()->fetch_assoc()['count'];
            
            $query .= " ORDER BY id DESC LIMIT ?, ?";
            array_push($params, (int)$start, (int)$length);
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
            return json_encode([
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $e->getMessage()
            ]);
        }
    }

    protected function musicAdd($music, $creator){
        try{
            $conn = $this->connect();
            $conn->begin_transaction();

            $sql = "INSERT INTO musics(user_id, music, creator) VALUES (?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $this->userId,$music, $creator);

            if ($stmt->execute()) {
                $conn->commit();
                return ["status" => 200, "message" => "Music Added successfully!"];
            } else {
                $conn->rollback();
                return ["status" => 500, "message" => "Music Add failed!"];
            }
        }catch (mysqli_sql_exception $e) {
            $conn->rollback();
            return ["status" => 500, "message" => "Database error: " . $e->getMessage()];
        }
    }
}