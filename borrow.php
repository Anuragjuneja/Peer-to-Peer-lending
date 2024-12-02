<?php
session_start();
$conn = new mysqli("localhost", "root", "root@123", "p2p2");

// Ensure user is logged in and has the role of a borrower
if (!isset($_SESSION['UserID']) || $_SESSION['role'] !== 'borrower') {
    header("Location: login.html");
    exit();
}

$lender_id = $_GET['lender_id'];  // Get the lender ID from URL

// Fetch lender details to display on the form
$lender_query = "SELECT * FROM Investor WHERE InvestorID = '$lender_id'";
$lender_result = $conn->query($lender_query);
$lender = $lender_result->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow from Lender</title>
</head>
<body>
    <!--<h2>Request Loan from <?php echo $lender['fname'] . ' ' . $lender['lname']; ?></h2>-->
    <form action="process_borrow.php" method="POST">
        <input type="hidden" name="lender_id" value="<?php echo $lender_id; ?>">
        <label for="amount">Confirm Loan Amount:</label>
        <input type="number" name="amount" id="amount" required>
        <br><br>
        <input type="submit" value="Request Loan">
    </form>
</body>
</html>
<?php $conn->close(); ?>
