<?php
class Crud {
    public static function connect() {
        // $servername = "localhost";
        // $username = "root";
        // $password = "";
        // $dbname = "crud";
        $servername = "sql202.infinityfree.com";
        $username = "if0_36871789";
        $password = "ZVl5ZASKshaCfl5";
        $dbname = "if0_36871789_crudoperation";
        
        // Create connection
        $con = new mysqli($servername, $username, $password, $dbname);
        
        // Check connection
        if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
        }
        return $con;
    }

    public static function selectData() {
        $con = self::connect();
        $sql = "SELECT * FROM employee";
        $result = $con->query($sql);
        
        $data = array();
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        $con->close();
        return $data;
    }

    public static function delete($id) {
        $con = self::connect();
        $sql = "DELETE FROM employee WHERE id=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $con->close();
    }
}
?>
