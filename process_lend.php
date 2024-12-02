
<?php
session_start();
$conn = new mysqli("localhost", "root", "root@123", "p2p2");

// Ensure user is logged in
if (!isset($_SESSION['UserID']) || $_SESSION['role'] !== 'lender') {
    header("Location: login.html");
    exit();
}

$lender_id = $_SESSION['UserID'];
$borrower_id = $_POST['borrower_id'];
$amount = $_POST['amount'];

// Check lender’s balance
$lender_balance_check_sql = "SELECT bank_balance FROM User WHERE UserID = '$lender_id'";
$lender_balance_result = $conn->query($lender_balance_check_sql);
$lender_balance_row = $lender_balance_result->fetch_assoc();

if ($lender_balance_row && $lender_balance_row['bank_balance'] >= $amount) {
    // Deduct amount from lender’s balance and add to borrower’s balance
    $conn->query("UPDATE User SET bank_balance = bank_balance - $amount WHERE UserID = '$lender_id'");
    $conn->query("UPDATE User SET bank_balance = bank_balance + $amount WHERE UserID = '$borrower_id'");
    
    
    	$investorid_check = "select InvestorID from Investor where Investor.UserID = '$lender_id'";
	$investorid_result = $conn->query($investorid_check);
	$investor_row = $investorid_result->fetch_assoc();

    // Record the transaction
   $transaction_sql = "INSERT INTO Transaction (InvestorID, BorrowerID, Status) VALUES ('" . $investor_row["InvestorID"] . "', '$borrower_id', 'pending')";

    if ($conn->query($transaction_sql) === TRUE) {
        echo "Loan proposal successfully provided to the borrower.";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "You do not have sufficient funds to lend.";
}

$conn->close();
header("refresh:3; url=user_dashboard.php");
exit();
?>

