<?php
session_start();
include '../config/db.php';

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $message = "All fields are required.";
        $messageType = "error";
    } else {
        $check_sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "Username already exists.";
            $messageType = "error";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $insert_sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                $message = "Registration successful. You can now login.";
                $messageType = "success";
            } else {
                $message = "Something went wrong. Please try again.";
                $messageType = "error";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | OnlineRental</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 430px;
        }

        .card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 20px;
            padding: 35px 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.35);
            color: #fff;
        }

        .brand {
            text-align: center;
            margin-bottom: 25px;
        }

        .brand h1 {
            font-size: 30px;
            margin-bottom: 8px;
            color: #ffffff;
        }

        .brand p {
            font-size: 14px;
            color: #d6e4ea;
        }

        .form-title {
            text-align: center;
            margin-bottom: 22px;
            font-size: 24px;
            font-weight: bold;
            color: #ffffff;
        }

        .message {
            padding: 12px 14px;
            margin-bottom: 18px;
            border-radius: 10px;
            font-size: 14px;
            text-align: center;
        }

        .success {
            background: rgba(46, 204, 113, 0.18);
            border: 1px solid rgba(46, 204, 113, 0.45);
            color: #d5ffe5;
        }

        .error {
            background: rgba(231, 76, 60, 0.18);
            border: 1px solid rgba(231, 76, 60, 0.45);
            color: #ffd8d3;
        }

        .input-group {
            margin-bottom: 18px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 15px;
            color: #f2f7fa;
        }

        .input-group input {
            width: 100%;
            padding: 13px 14px;
            border: none;
            outline: none;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.9);
            font-size: 15px;
            color: #1f2d3a;
            transition: 0.3s ease;
        }

        .input-group input:focus {
            box-shadow: 0 0 0 3px rgba(0, 191, 166, 0.35);
            transform: translateY(-1px);
        }

        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #00c9a7, #00b4d8);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s ease;
            margin-top: 6px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 180, 216, 0.25);
        }

        .footer-text {
            margin-top: 18px;
            text-align: center;
            font-size: 14px;
            color: #d6e4ea;
        }

        .footer-text a {
            color: #7ee8fa;
            text-decoration: none;
            font-weight: bold;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }

        .icon {
            font-size: 38px;
            margin-bottom: 10px;
        }

        @media (max-width: 480px) {
            .card {
                padding: 28px 20px;
            }

            .brand h1 {
                font-size: 26px;
            }

            .form-title {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="brand">
                <div class="icon">🚗</div>
                <h1>OnlineRental</h1>
                <p>Create your account and start exploring rentals easily</p>
            </div>

            <div class="form-title">User Registration</div>

            <?php if (!empty($message)) { ?>
                <div class="message <?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php } ?>

            <form method="POST" action="">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" placeholder="Enter your username" required>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>
                </div>

                <button type="submit" class="btn">Create Account</button>
            </form>

            <div class="footer-text">
                Already have an account?
                <a href="login.php">Login here</a>
            </div>
        </div>
    </div>
</body>
</html>