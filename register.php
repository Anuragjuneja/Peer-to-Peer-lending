<?php
$conn = new mysqli("localhost", "root", "root@123", "p2p2");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phnumber = $_POST['phnumber'];
    $bank_balance = $_POST['bank_balance'];
    $address = $_POST['address'];

    $sql = "INSERT INTO User (fname, lname, email, password, phnumber, bank_balance, address) VALUES ('$fname', '$lname', '$email', '$password', '$phnumber', '$bank_balance', '$address')";
    if ($conn->query($sql) === TRUE) {
        header("Location: login.html");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
