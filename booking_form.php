<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

$user_id = $_SESSION['user_id'];


$slots = $pdo->query("SELECT * FROM slots WHERE booked_by IS NULL")->fetchAll();//fetching slotes
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Slot</title>
</head>
<body>
    <h2>Available Slots</h2>
    <form action="book_slot.php" method="POST">
        <label for="slot">Choose a Slot:</label>
        <select name="slot_id" required>
            <?php foreach ($slots as $slot): ?>
                <option value="<?= $slot['id'] ?>"><?= $slot['date'] . ' ' . $slot['time_slot'] ?></option>
            <?php endforeach; ?>
        </select><br>
        <input type="submit" value="Book Slot">
    </form>
</body>
</html>
