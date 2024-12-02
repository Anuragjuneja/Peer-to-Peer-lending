<?php
session_start();
$conn = new mysqli("localhost", "root", "root@123", "p2p2");


// Ensure user is logged in and has the role of a lender
if (!isset($_SESSION['UserID']) || $_SESSION['role'] !== 'lender') {
    header("Location: login.html");
    exit();
}

if (!isset($_GET['borrower_id'])) {
    die("Invalid request. Borrower ID is missing.");
}
$borrower_id = $_GET['borrower_id'];

$borrower_query = "SELECT * FROM Borrower JOIN User ON Borrower.UserID = User.UserID WHERE BorrowerID = '$borrower_id'";
$borrower_result = $conn->query($borrower_query);
$borrower = $borrower_result->fetch_assoc();

if (!$borrower) {
    die("Borrower not found.");
}

// Fetch borrower details to display on the form
//$borrower_query = "SELECT * FROM Borrower WHERE BorrowerID = '$borrower_id'";
//$borrower_result = $conn->query($borrower_query);
//$borrower = $borrower_result->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lend to Borrower</title>
</head>
<body>
    <h2>Provide Loan to <?php echo $borrower['fname'] . ' ' . $borrower['lname']; ?></h2>
    <form action="process_lend.php" method="POST">
        <input type="hidden" name="borrower_id" value="<?php echo $borrower_id; ?>">
        <label for="amount">Confirm Loan Amount:</label>
        <input type="number" name="amount" id="amount" required>
        <br><br>
        <input type="submit" value="Lend Money">
    </form>
</body>
</html>
<?php $conn->close(); ?>
