<?php
session_start();
$conn = new mysqli("localhost", "root", "root@123", "p2p2");

// Ensure UserID is set in the session
if (!isset($_SESSION['UserID']) || empty($_SESSION['UserID'])) {
    echo("User is not logged in. Please log in to continue.");
    header("Location: login.html");
}

$UserID = $_SESSION['UserID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $interest = $_POST['interest'];
    $duration = $_POST['duration'];
    $emi = $_POST['emi'];
    $amount = $_POST['amount']; 

    if ($role == "lender") {
        // Check bank balance criteria
        $balance_check_query = "SELECT bank_balance FROM User WHERE UserID = '$UserID'";
        $balance_result = $conn->query($balance_check_query);
        
        if ($balance_result && $balance_row = $balance_result->fetch_assoc()) {
            if ($amount > $balance_row['bank_balance']) {
                die("Insufficient bank balance.");
            }
        } else {
            die("Error retrieving bank balance.");
        }
        
        // Insert UserID into Investor table
        $sql = "INSERT INTO Investor (UserID) VALUES ('$UserID')";
        $conn->query($sql);
        
        // Retrieve the corresponding InvestorID
        $investor_id_query = "SELECT InvestorID FROM Investor WHERE UserID = '$UserID'";
        $result = $conn->query($investor_id_query);
        $row = $result->fetch_assoc();
        $InvestorID = $row['InvestorID'];

        // Insert into Proposal table with the retrieved InvestorID
        $sql1 = "INSERT INTO Proposal (InvestorID, interest, duration, amount, EMI) VALUES ('$InvestorID', '$interest', '$duration', '$amount', '$emi')";
        $conn->query($sql1);
        
        $_SESSION['role'] = 'lender';
        header("Location: investor_dashboard.php");
    } else {
        // Insert UserID into Borrower table
        $sql = "INSERT INTO Borrower (UserID) VALUES ('$UserID')";
        $conn->query($sql);
        
        // Retrieve the corresponding BorrowerID
        $borrower_id_query = "SELECT BorrowerID FROM Borrower WHERE UserID = '$UserID'";
        $result = $conn->query($borrower_id_query);
        $row = $result->fetch_assoc();
        $BorrowerID = $row['BorrowerID'];
        
        $sql2 = "INSERT INTO Request (BorrowerID, ReqInterest, ReqDuration, amount, ReqEMI) VALUES ('$BorrowerID', '$interest', '$duration', '$amount', '$emi')";
        $conn->query($sql2);
        
        $_SESSION['role'] = 'borrower';
        header("Location: borrower_dashboard.php");
    }
    exit();
}
?>

