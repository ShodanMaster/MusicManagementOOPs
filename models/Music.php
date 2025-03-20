<?php
session_start();
include("../config/Dbconfig.php");

class Music extends Dbconfig {

    private $userId;

    public function __construct() {
        if (isset($_SESSION['user_id'])) {
            $this->userId = $_SESSION['user_id'];
        } else {
            header('Location: authenticate.php');
            exit();
        }
    }

    protected function userMusics() {
        try {
            $conn = $this->connect();

            $draw = $_GET['draw'] ?? 1;
            $start = (int)($_GET['start'] ?? 0);
            $length = (int)($_GET['length'] ?? 10);
            $searchValue = $_GET['search']['value'] ?? '';
            
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM musics WHERE user_id = ?");
            $stmt->bind_param("i", $this->userId);
            $stmt->execute();
            $totalRecords = $stmt->get_result()->fetch_assoc()['count'];
            
            $query = "SELECT id, music, creator FROM musics WHERE user_id = ?";
            $params = [$this->userId];
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
            array_push($params, $start, $length);
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
            error_log("Error in userMusics: " . $e->getMessage());
            return json_encode([
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => "An error occurred, please try again later."
            ]);
        }
    }

    protected function musicAdd($music, $creator) {
        try {
            $conn = $this->connect();

            $sql = "INSERT INTO musics(user_id, music, creator) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $this->userId, $music, $creator);

            if ($stmt->execute()) {
                return ["status" => 200, "message" => "Music added successfully!"];
            } else {
                return ["status" => 500, "message" => "Music addition failed!"];
            }
        } catch (mysqli_sql_exception $e) {
            return ["status" => 500, "message" => "Database error: " . $e->getMessage()];
        }
    }

    protected function musicUpdate($id, $music, $creator) {
        try {
            $conn = $this->connect();
            $conn->begin_transaction();
    
            $sql = "SELECT user_id FROM musics WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                $task = $result->fetch_assoc();
    
                if ($task['user_id'] == $this->userId) {
                    $sql = "UPDATE musics SET music=?, creator=? WHERE id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssi", $music, $creator, $id);
    
                    if ($stmt->execute()) {
                        $conn->commit();
                        return ['status' => 200, 'message' => 'Music Updated Successfully'];
                    } else {
                        $conn->rollback();
                        return ["status" => 500, "message" => "Music Update Failed"];
                    }
                } else {
                    return ["status" => 403, "message" => "Unauthorized Access"];
                }
            } else {
                return ["status" => 404, "message" => "Music Not Found"];
            }
        } catch (mysqli_sql_exception $e) {
            $conn->rollback();
            return ["status" => 500, "message" => "Database Error: " . $e->getMessage()];
        }
    }

    protected function musicDelete($id){
        try {
            $conn = $this->connect();
            $conn->begin_transaction();
    
            $sql = "SELECT user_id FROM musics WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                $task = $result->fetch_assoc();
    
                if ($task['user_id'] == $this->userId) {
                    $sql = 'DELETE FROM musics WHERE id = ?';
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('i', $id);
    
                    if ($stmt->execute()) {
                        $conn->commit();
                        return ['status' => 200, 'message' => 'Music Deleted Successfully'];
                    } else {
                        $conn->rollback();
                        return ["status" => 500, "message" => "Music Delete Failed"];
                    }
                } else {
                    return ["status" => 403, "message" => "Unauthorized Access"];
                }
            } else {
                return ["status" => 404, "message" => "Music Not Found"];
            }
        } catch (mysqli_sql_exception $e) {
            $conn->rollback();
            return ["status" => 500, "message" => "Database Error: " . $e->getMessage()];
        }
    }
}
?>
