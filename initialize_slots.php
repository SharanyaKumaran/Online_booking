<?php
include 'config.php';

$slots = [
    ['2024-08-01', '09:00 - 10:00'],
    ['2024-08-01', '10:00 - 11:00'],
    ['2024-08-01', '11:00 - 12:00'],
    ['2024-08-01', '13:00 - 14:00'],
    ['2024-08-01', '14:00 - 15:00'],
];

foreach ($slots as $slot) {
    $stmt = $pdo->prepare("INSERT IGNORE INTO slots (date, time_slot) VALUES (?, ?)");
    $stmt->execute($slot);
}

echo "Slots initialized!";
?>
