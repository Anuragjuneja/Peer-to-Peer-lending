<?php
session_start();
$conn = new mysqli("localhost", "root", "root@123", "p2p2");

if (isset($_GET['transaction_id'])) {
    $transaction_id = $_GET['transaction_id'];

    // Make sure to sanitize input to prevent SQL injection
    $transaction_id = $conn->real_escape_string($transaction_id);

    // Update the status of the loan request to 'approved'
    $update_status_sql = "UPDATE Transaction SET Status = 'approved' WHERE TransID = '$transaction_id'";

    if ($conn->query($update_status_sql)) {
        // Redirect back to the dashboard or show a success message
        header("Location: user_dashboard.php"); // Redirect to the user dashboard
    } else {
        echo "Error approving loan request: " . $conn->error;
    }
}
?>

