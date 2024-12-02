<?php
session_start();
$conn = new mysqli("localhost", "root", "root@123", "p2p2");

// Ensure user is logged in
if (!isset($_SESSION['UserID'])) {
    header("Location: login.html");
    exit();
}

// Get the logged-in user's ID
$UserID = $_SESSION['UserID'];
$loan_requests_sql = 
"SELECT DISTINCT
    User.fname AS investor_fname,
    User.lname AS investor_lname, 
    Proposal.Interest, 
    Proposal.Duration, 
    Proposal.amount, 
    Transaction.Status
FROM 
    Transaction 
JOIN Investor ON Transaction.InvestorID = Investor.InvestorID
JOIN User ON Investor.UserID = User.UserID
JOIN Proposal ON Proposal.InvestorID = Investor.InvestorID
WHERE 
    Transaction.InvestorID IN (
        SELECT InvestorID 
        FROM Transaction t
        WHERE t.BorrowerID IN (
            SELECT BorrowerID 
            FROM Borrower
            WHERE UserID = $UserID
        )
        AND t.Status = 'pending'
    )";

// Fetch all loan requests where the logged-in user is a borrower
/*
$loan_requests_sql = "SELECT 
        Request.*, 
        User.fname AS borrower_fname, 
        User.lname AS borrower_lname, 
        User.email AS borrower_email, 
        Transaction.Status,
        Transaction.TransID, 
        Investor.InvestorID, 
        User2.fname AS investor_fname, 
        User2.lname AS investor_lname
    FROM 
        Request
    JOIN Borrower ON Request.BorrowerID = Borrower.BorrowerID
    JOIN User ON Borrower.UserID = User.UserID
    JOIN Transaction ON Transaction.BorrowerID = Borrower.BorrowerID
    JOIN Investor ON Investor.InvestorID = Transaction.InvestorID
    JOIN User AS User2 ON Investor.UserID = User2.UserID
    WHERE 
        Borrower.UserID = '$UserID' AND 
        Transaction.Status = 'pending'"; 
 */

   
   
    
$loan_requests_result = $conn->query($loan_requests_sql);


$loan_offers_sql = 
"SELECT Distinct
    User.fname AS borrower_fname,
    User.lname AS borrower_lname, 
    Request.ReqInterest, 
    Request.ReqDuration, 
    Request.Amount, 
    Transaction.Status
FROM 
    Transaction 
JOIN Borrower ON Transaction.BorrowerID = Borrower.BorrowerID
JOIN User ON Borrower.UserID = User.UserID
JOIN Request ON Request.BorrowerID = Borrower.BorrowerID
WHERE 
    Transaction.BorrowerID IN (
        SELECT BorrowerID 
        FROM Transaction t
        WHERE t.InvestorID IN (
            SELECT InvestorID 
            FROM Investor
            WHERE UserID = $UserID
            AND t.Status = 'pending'
        )
    )" ;

/*
$loan_offers_sql = "
    SELECT 
        Proposal.*, 
        User.fname AS borrower_fname, 
        User.lname AS borrower_lname, 
        User.email AS borrower_email, 
        Transaction.Status,
        Transaction.TransID, 
        Investor.InvestorID, 
        User2.fname AS investor_fname, 
        User2.lname AS investor_lname
    FROM 
        Proposal
    JOIN Investor ON Proposal.InvestorID = Investor.InvestorID
    JOIN User AS User2 ON Investor.UserID = User2.UserID
    JOIN Transaction ON Proposal.InvestorID = Transaction.InvestorID
    JOIN Borrower ON Transaction.BorrowerID = Borrower.BorrowerID
    JOIN User ON Borrower.UserID = User.UserID
    WHERE 
        Investor.UserID = '$UserID' AND 
        Transaction.Status = 'pending'";
    */    








// Fetch all loan offers where the logged-in user is an investor (lender)
/*$loan_offers_sql =    "SELECT 
    User.fname AS borrower_fname,
    User.lname AS borrower_lname, 
    Request.ReqInterest, 
    Request.ReqDuration, 
    Request.Amount, 
    Transaction.Status
FROM 
    Transaction 
JOIN Borrower ON Transaction.BorrowerID = Borrower.BorrowerID
JOIN User ON Borrower.UserID = User.UserID
JOIN Request ON Request.BorrowerID = Borrower.BorrowerID
WHERE 
    Transaction.BorrowerID IN (
        SELECT BorrowerID 
        FROM Transaction t
        WHERE t.InvestorID IN (
            SELECT InvestorID 
            FROM Investor
            WHERE UserID = $UserID
            AND t.Status = 'pending'
        )
    )" ;*/
        
$loan_offers_result = $conn->query($loan_offers_sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        /* Resetting margin and padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Background styling */
        body {
            font-family: Arial, sans-serif;
            background: url('wall.jpeg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
        }

        /* Dark overlay */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1;
        }

        /* Main container */
        .container {
            position: relative;
            z-index: 2;
            width: 90%;
            max-width: 900px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
        }

        h2, h3 {
            text-align: center;
            color: #00e676;
            margin-bottom: 20px;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: center;
            color: #fff;
        }

        th {
            background-color: rgba(0, 230, 118, 0.8);
            font-size: 18px;
            font-weight: bold;
        }

        td {
            background-color: rgba(0, 0, 0, 0.3);
            font-size: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Button styling */
        button {
            background-color: #00e676;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.3);
            margin: 10px auto;
            display: block;
            width: fit-content;
        }

        button:hover {
            background-color: #00c853;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Current Offers and Requests!</h2>
        
        <h3>MY LOAN OFFERS</h3>
        <table>
            <tr>
                <th>Borrower Name</th>
                <th>Offered Amount</th>
                <th>Offered Interest</th>
                <th>Duration</th>
                <th>Status</th>
            </tr>
            <?php while ($row = $loan_offers_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['borrower_fname']); ?></td>
                    <td><?php echo htmlspecialchars($row['Amount']); ?></td>
                    <td><?php echo htmlspecialchars($row['ReqInterest']); ?></td>
                    <td><?php echo htmlspecialchars($row['ReqDuration']); ?></td>
                    <td><?php echo htmlspecialchars($row['Status']); ?></td>
                </tr>
            <?php } ?>
        </table>

        <h3>MY LOAN REQUESTS</h3>
        <table>
            <tr>
                <th>Investor Name</th>
                <th>Requested Amount</th>
                <th>Requested Interest</th>
                <th>Requested Duration</th>
                <th>Status</th>
            </tr>
            <?php while ($row = $loan_requests_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['investor_fname']) . ' ' . htmlspecialchars($row['investor_lname']); ?></td>
                    <td><?php echo htmlspecialchars($row['amount']); ?></td>
                    <td><?php echo htmlspecialchars($row['Interest']); ?></td>
                    <td><?php echo htmlspecialchars($row['Duration']); ?></td>
                    <td><?php echo htmlspecialchars($row['Status']); ?></td>
                </tr>
            <?php } ?>
        </table>

        <a href="role_selection.html">
            <button>Want to Lend/Borrow Again?</button>
        </a>
    </div>
</body>
</html>

<?php $conn->close(); ?>

