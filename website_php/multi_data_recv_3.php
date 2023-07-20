<?php
// Replace with your database credentials
$servername = "********";
$username = "********";
$password = "********";
$dbname = "********";

// set sum thresholds
$threshold = 475;

$rowCountThreshold = 10;

// Column name to delete
$column_name = "REV-0-R-3";
$status = 0;

// Create a new connection
$conn = new mysqli($servername, $username, $password, $dbname);


// Update data received from the client
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST["value"];
    $value = (int) $data;


    // Retrieve the total number of rows in the column
    $query = "SELECT `$column_name` FROM Value_table_3";
    $result = $conn->query($query);

    if ($result) {
        $row_count = $result->num_rows;
        echo "Number of rows in column '$column_name': " . $row_count;
    } else {
        echo "Error executing query: " . $conn->error;
    }

    // Check if the number of rows reaches the threshold
    if ($row_count >= $rowCountThreshold) {
        $sqlSum = "SELECT SUM(`$column_name`) AS sum_value FROM Value_table_3";
        $resultSum = $conn->query($sqlSum);
        $row = $resultSum->fetch_assoc();
        $sumValue = $row['sum_value'];
        echo "Sum of $column_name: $sumValue";
        if ($sumValue >= $threshold){
            $status = 0;
            $sql_status = "UPDATE Restrooms SET `Occupancy status` = '" . $status . "' WHERE ID = '" . $column_name . "'";
            // Responding to the client for the status updation
            if ($conn->query($sql_status) === true) {
                echo "Status updated successfully";
            } else {
                echo "Error updating status: " . $conn->error;
            }
        }
        else{
            $status = 1;
            $sql_status = "UPDATE Restrooms SET `Occupancy status` = '" . $status . "' WHERE ID = '" . $column_name . "'";
            // Responding to the client for the status updation
            if ($conn->query($sql_status) === true) {
                echo "Status updated successfully";
            } else {
                echo "Error updating status: " . $conn->error;
            }
        }
            

       // Delete the column
        $sqlDelete = "DELETE FROM `Value_table_3`";
        if ($conn->query($sqlDelete) === true) {
            // Update successful
            echo "Database deleted successfully ";
        } else {
            // Update failed
            echo "Error deleting database: " . $conn->error;
        }
    } else {
        echo "No column overwriting needed";
        $sql = "INSERT INTO Value_table_3 (`$column_name`) VALUES ('$value')";
        // Execute the update query
        if ($conn->query($sql) === true) {
            // Update successful
            echo "Database updated successfully ";
        } else {
            // Update failed
            echo "Error updating database: " . $conn->error;
        }
    }
    
}

// Close the second database connection
$conn->close();


