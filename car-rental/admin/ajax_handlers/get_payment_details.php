<?php
include "../connect.php";
include "../Includes/functions/functions.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["payment_id"])) {
    $payment_id = test_input($_POST["payment_id"]);
    
    $stmt = $con->prepare("
        SELECT 
            p.*,
            r.pickup_date,
            r.return_date,
            r.total_amount,
            c.full_name AS client_name,
            cars.car_name
        FROM 
            payments p
        JOIN 
            reservations r ON p.reservation_id = r.reservation_id
        JOIN 
            clients c ON r.client_id = c.client_id
        JOIN 
            cars ON r.car_id = cars.id
        WHERE 
            p.payment_id = ?
    ");
    $stmt->execute(array($payment_id));
    $payment_details = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Format dates for display
    if ($payment_details) {
        $payment_details["payment_date"] = date("Y-m-d H:i", strtotime($payment_details["payment_date"]));
        $payment_details["pickup_date"] = date("Y-m-d", strtotime($payment_details["pickup_date"]));
        $payment_details["return_date"] = date("Y-m-d", strtotime($payment_details["return_date"]));
    }
    
    echo json_encode($payment_details);
}
?>