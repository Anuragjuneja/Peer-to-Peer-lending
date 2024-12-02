<?php
session_start();
$conn = new mysqli("localhost", "root", "root@123", "p2p2");

// Fetch all investors
$sql = "SELECT * 
	FROM Investor 
	JOIN User ON Investor.UserID = User.UserID
	JOIN Proposal ON Investor.InvestorID = Proposal.InvestorID";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Borrower Dashboard</title>
</head>
<body>
    <h2>List of Lenders</h2>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Offered Amount</th>
            <th>Offered Interest</th>
            <th>Offered EMI</th>
            <th>Offered Duration</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['fname'] . ' ' . $row['lname']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phnumber']; ?></td>
                <td><?php echo $row['amount']; ?></td>
                <td><?php echo $row['interest']; ?></td>
                <td><?php echo $row['EMI']; ?></td>
                <td><?php echo $row['duration']; ?></td>
                $_SESSION['UserID'] = "UserID";
                $_SESSION['role'] = "borrower";
                <td><a href="borrow.php?lender_id=<?php echo $row['InvestorID']; ?>">Request To Borrow</a></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
