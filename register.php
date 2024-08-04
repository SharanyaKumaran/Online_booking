<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $date = $_POST['date'];
    $time_slot = $_POST['time_slot'];
    $address = $_POST['address'];

   
    ob_start();
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration Status</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                background-color: #f4f4f4;
            }

            .message-container {
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                text-align: center;
                width: 100%;
                max-width: 400px;
            }

            .success {
                color: #28a745;
                font-size: 18px;
                margin: 20px 0;
            }

            .error {
                color: #dc3545;
                font-size: 18px;
                margin: 20px 0;
            }

            a {
                color: #007bff;
                text-decoration: none;
                font-weight: bold;
            }

            a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="message-container">
    <?php
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $emailExists = $stmt->fetchColumn();

        if ($emailExists) {
            echo "<p class='error'>An account with this email already exists.</p>";
            $pdo->rollBack();
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE booking_date = ? AND time_slot = ?");
            $stmt->execute([$date, $time_slot]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                echo "<p class='error'>The selected slot is already booked.</p>";
                $pdo->rollBack();
            } else {
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, booking_date, address) VALUES (?, ?, ?, ?, ?)");
                if ($stmt->execute([$name, $email, $password, $date, $address])) {
                    $user_id = $pdo->lastInsertId();
                    $stmt = $pdo->prepare("INSERT INTO bookings (user_id, booking_date, time_slot) VALUES (?, ?, ?)");
                    if ($stmt->execute([$user_id, $date, $time_slot])) {
                        $pdo->commit();
                        echo "<p class='success'>Registration successful!</p>";
                    } else {
                        $pdo->rollBack();
                        echo "<p class='error'>Error during booking.</p>";
                    }
                } else {
                    $pdo->rollBack();
                    echo "<p class='error'>Error during registration.</p>";
                }
            }
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<p class='error'>Transaction failed: " . $e->getMessage() . "</p>";
    }
    ?>

            <p><a href="register.html">Go back to registration</a></p>
            <p><a href="index.html">Go to login page</a></p>
        </div>
    </body>
    </html>

    <?php
   
    ob_end_flush();
}
?>
