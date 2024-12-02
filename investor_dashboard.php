<?php
session_start();
$conn = new mysqli("localhost", "root", "root@123", "p2p2");

// Fetch all borrowers
$sql = "SELECT * 
	FROM Borrower 
	JOIN User ON Borrower.UserID = User.UserID
	JOIN Request ON Borrower.BorrowerID = Request.BorrowerID";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lender Dashboard</title>
</head>
<body>
    <h2>List of Borrowers</h2>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Requested Amount</th>
            <th>Requested Interest</th>
            <th>Requested EMI</th>
            <th>Requested Duration</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['fname'] . ' ' . $row['lname']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phnumber']; ?></td>
                <td><?php echo $row['Amount']; ?></td>
                <td><?php echo $row['ReqInterest']; ?></td>
                <td><?php echo $row['ReqEMI']; ?></td>
                <td><?php echo $row['ReqDuration']; ?></td>
                
                <td><a href="lend.php?borrower_id=<?php echo $row['BorrowerID']; ?>">Offer to Lend</a></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
