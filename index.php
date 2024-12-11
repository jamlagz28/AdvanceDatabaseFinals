<?php
session_start();
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM register WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid credentials!";
        }
    } else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System - Login</title>
    <!-- Custom Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Global Styles */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('https://scontent.fcgy2-1.fna.fbcdn.net/v/t1.15752-9/462581970_587365833776199_103426830845846112_n.png?_nc_cat=102&ccb=1-7&_nc_sid=9f807c&_nc_eui2=AeFsscJWo-TrFsT8Yjqw7if_d1ffD6Dfmfp3V98PoN-Z-j78UiZhUBWEe3XnJpbzOaM_fpHADRWYJTZGHmRpvK1F&_nc_ohc=82DkImqLF0EQ7kNvgGZWDiy&_nc_zt=23&_nc_ht=scontent.fcgy2-1.fna&oh=03_Q7cD1QFByARefrlvOSHCGFqFblBPzmLE5ixw9XDwu6Bu8wILmQ&oe=677ED748') no-repeat center center fixed;
            background-size: cover;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.9); /* Slightly opaque background */
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            font-family: 'Montserrat', sans-serif;
            color: #2C3E50;
            font-weight: 600;
            margin-bottom: 25px;
            font-size: 28px;
            letter-spacing: 1px;
        }

        .input-field {
            width: 100%;
            padding: 15px;
            margin: 12px 0;
            border: 2px solid #BDC3C7;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .input-field:focus {
            border-color: #3498DB;
            outline: none;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.8);
        }

        .button {
            width: 100%;
            padding: 15px;
            background-color: #3498DB;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .button:hover {
            background-color: #2980B9;
            transform: scale(1.05);
        }

        .error-message {
            color: #E74C3C;
            font-size: 15px;
            font-weight: 500;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            margin-top: 25px;
            color: #7F8C8D;
        }

        a {
            color: #3498DB;
            text-decoration: none;
            font-weight: 600;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media screen and (max-width: 480px) {
            .login-container {
                padding: 30px;
            }
            .input-field, .button {
                font-size: 15px;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Student Management System</h2>
    <p class="subheading">Login to your account</p>
    
    <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>

    <form method="POST" action="">
        <input type="text" name="username" class="input-field" placeholder="Username" required>
        <input type="password" name="password" class="input-field" placeholder="Password" required>
        <button type="submit" class="button">Login</button>
    </form>

    <p class="signup-link">Don't have an account? <a href="register.php">Register here</a>.</p>
</div>

</body>
</html>
