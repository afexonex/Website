<?php
$host = "sgp.domcloud.co";
$dbname = "ranijhansischool_urbanshoes";
$username = "ranijhansischool";
$password = "5V(_9EsBo89mu4U(Yx";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/**
 * Function to decrease credit for a user
 * @param int $userid User ID
 * @return bool True if credit was decreased, false otherwise
 */
function decreaseCredit($userid) {
    global $conn;

    if ($conn === null || $conn->connect_errno) {
        error_log("Database connection is not available.");
        return false;
    }

    try {
        $stmt = $conn->prepare("UPDATE users SET credit = credit - 1 WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("i", $userid);
        $stmt->execute();

        $success = $stmt->affected_rows > 0;

        $stmt->close();
        return $success;

    } catch (Exception $e) {
        error_log("Error in decreaseCredit: " . $e->getMessage());
        return false;
    }
}

// Register shutdown function to safely close the connection
register_shutdown_function(function() use (&$conn) {
    if ($conn !== null) {
        try {
            @$conn->close(); // suppress error if already closed
        } catch (Throwable $e) {
            // optional: log if needed
            error_log("Error closing connection on shutdown: " . $e->getMessage());
        }
        $conn = null;
    }
});
?>
