<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['user_type'] == "new") {
        header("Location: register.html");
    } else {
        header("Location: login.html");
    }
    exit();
}
?>

