<?php
include "../connect.php";
include "../Includes/functions/functions.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["payment_id"])) {
    $payment_id = test_input($_POST["payment_id"]);
    
    $stmt = $con->prepare("SELECT * FROM payments WHERE payment_id = ?");
    $stmt->execute(array($payment_id));
    $payment = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode($payment);
}
?>