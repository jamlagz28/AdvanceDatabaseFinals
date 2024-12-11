<?php
session_start();
include('db_connection.php');

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Link to Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    
    <style>
        /* Background Image */
        body {
            font-family: 'Quicksand', sans-serif; /* Primary font */
            margin: 0;
            padding: 0;
            background: url('https://scontent.fmnl4-2.fna.fbcdn.net/v/t1.15752-9/466617009_573540195280630_6414482871791914877_n.png?_nc_cat=105&ccb=1-7&_nc_sid=9f807c&_nc_eui2=AeEJv3FBBPD1lKY25bAKeoWi-uUgpeSf77L65SCl5J_vstsGaBW6M3JtwKggvUO7DYZw_E0LcI4GhWMDB3t8DWpB&_nc_ohc=JPfLwJp7GeYQ7kNvgFyw2KI&_nc_zt=23&_nc_ht=scontent.fmnl4-2.fna&oh=03_Q7cD1QHuS4zO7LXPJN8J3CwEh3s4G95KYueO7g6oc8_wwAXw5A&oe=677F1594') no-repeat center center fixed;
            background-size: cover;
            color: black; /* Light text color for better readability */
        }

        header {
            background: rgba(0, 0, 0, 0.7); /* Semi-transparent black for contrast */
            color: white;
            padding: 20px;
            text-align: center;
            font-family: 'Poppins', sans-serif;
        }

        h1 {
            font-size: 2.5rem; /* Larger font size */
            font-weight: 600;
        }

        nav {
            background: linear-gradient(to right, #6a11cb, #2575fc); /* Gradient background for nav */
            padding: 15px 0; /* Vertical padding for the navbar */
            text-align: center;
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Add a subtle shadow for depth */
            margin-top: 20px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 20px;
            font-size: 1.2rem;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 5px;
            display: inline-block; /* Makes the links inline-block for better styling */
            transition: background 0.3s ease, transform 0.3s ease; /* Smooth transitions */
        }

        nav a:hover {
            background-color: rgba(255, 255, 255, 0.2); /* Light background on hover */
            transform: scale(1.1); /* Slightly enlarge the link on hover */
            text-decoration: underline; /* Underline text on hover */
        }

        .logout {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 25px;
            background: #d9534f;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: background 0.3s ease; /* Smooth transition for background */
        }

        .logout:hover {
            background: #c9302c;
        }
        
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Student Management System</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="student_records.php">Student Records</a>
            <a href="student_courses.php">Student Courses</a>
            <a href="student_grades.php">Student Grades</a>
            <a href="student_attendance.php">Student Attendance</a>
            <a href="student_schedule.php">Student Schedules</a>
            <a href="student_reports.php">Student Reports</a>
            <a class="logout" href="logout.php">Logout</a>
        </nav>
    </header>
    
    <main>
        <!-- No table, only the navbar remains -->
    </main>
</body>
</html>
