<?php
session_start();
$conn = new mysqli("localhost", "root", "root@123", "p2p2");

// Ensure user is logged in
if (!isset($_SESSION['UserID']) || $_SESSION['role'] !== 'borrower') {
    header("Location: login.html");
    exit();
}

$borrower_id = $_SESSION['UserID'];
$lender_id = $_POST['lender_id'];
$amount = $_POST['amount'];

$investorid_check = "select UserID from Investor where Investor.InvestorID = '$lender_id'";
$investorid_result = $conn->query($investorid_check);
$investor_row = $investorid_result->fetch_assoc();

// Check lender’s balance to confirm they have enough funds
$lender_balance_check_sql = "SELECT bank_balance FROM User WHERE UserID = '{$investor_row['UserID']}'";
$lender_balance_result = $conn->query($lender_balance_check_sql);
$lender_balance_row = $lender_balance_result->fetch_assoc();

if ($lender_balance_row && $lender_balance_row['bank_balance'] >= $amount) {
    // Deduct amount from lender’s balance and add to borrower’s balance
    $conn->query("UPDATE User SET bank_balance = bank_balance - $amount WHERE UserID = '{$lender_balance_row['bank_balance']}'");
    $conn->query("UPDATE User SET bank_balance = bank_balance - $amount WHERE UserID = '{$lender_balance_row['UserID']}'");

    
        $investorid_check = "select BorrowerID from Borrower where Borrower.UserID = '$borrower_id'";
	$investorid_result = $conn->query($investorid_check);
	$investor_row = $investorid_result->fetch_assoc();

    // Record the transaction with 'pending' status
    $transaction_sql = "INSERT INTO Transaction (InvestorID, BorrowerID, Status) VALUES ('$lender_id', '" . $investor_row["BorrowerID"] . "', 'pending')";
    if ($conn->query($transaction_sql) === TRUE) {
        echo "Loan request successfully created and awaiting lender approval.";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Lender does not have sufficient funds.";
}

$conn->close();
header("refresh:3; url=user_dashboard.php");
exit();
?>
