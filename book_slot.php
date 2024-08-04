<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $slot_id = $_POST['slot_id'];
    $user_id = $_SESSION['user_id'];

 
    $stmt = $pdo->prepare("SELECT * FROM slots WHERE id = ? AND booked_by IS NULL");
    $stmt->execute([$slot_id]);
    $slot = $stmt->fetch();

    if ($slot) {
       
        $stmt = $pdo->prepare("UPDATE slots SET booked_by = ? WHERE id = ?");
        $stmt->execute([$user_id, $slot_id]);

       
        $stmt = $pdo->prepare("INSERT INTO bookings (user_id, slot_id, booking_date) VALUES (?, ?, CURDATE())");
        $stmt->execute([$user_id, $slot_id]);

        echo "Slot booked successfully!";
    } else {
        echo "Slot is no longer available.";
    }
}
?>
