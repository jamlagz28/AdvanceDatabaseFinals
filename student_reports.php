<?php
session_start();
include('db_connection.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch all students from the database and store them in a hash map for fast lookups
$students_query = "SELECT id, name FROM students";
$students_result = mysqli_query($conn, $students_query);

// Create a hash map (associative array) for student lookups
$students_hash_map = [];
while ($student = mysqli_fetch_assoc($students_result)) {
    $students_hash_map[$student['id']] = $student['name'];
}

// Fetch all grades for students from the database
$grades_query = "SELECT student_id, grade FROM grades";
$grades_result = mysqli_query($conn, $grades_query);

// Calculate the average grade for all students
$average_grade_query = "SELECT AVG(grade) AS average_grade FROM grades";
$average_grade_result = mysqli_query($conn, $average_grade_query);
$average_grade_row = mysqli_fetch_assoc($average_grade_result);
$average_grade = $average_grade_row['average_grade'];

// Display the report and student information
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Reports</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('https://i.pinimg.com/originals/9d/4d/07/9d4d07ca7d591bb3cd3d9f4df2624bbf.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            color: #b0b0b0; /* Lightened text color */
        }

        header {
            background: rgba(44, 62, 80, 0.8);
            color: #ecf0f1; /* Lightened header text */
            padding: 20px;
            text-align: center;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 600;
        }

        nav a {
            color: #ecf0f1; /* Lightened nav text */
            text-decoration: none;
            margin: 0 20px;
            font-weight: 500;
            font-size: 1rem;
        }

        nav a:hover {
            color: #f39c12;
        }

        .average-grade {
            text-align: center;
            margin-top: 30px;
            font-size: 1.4rem;
            color: #b0b0b0; /* Lightened text color */
        }

        .logout {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
        }

        .logout:hover {
            background: #c0392b;
        }

        table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        th, td {
            padding: 14px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 1.1rem;
            color: #b0b0b0; /* Lightened table text color */
        }

        th {
            background-color: #34495e;
            color: white;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #ecf0f1;
        }

        .container {
            padding: 40px;
        }

        h2 {
            font-size: 2rem;
            text-align: center;
            color: #b0b0b0; /* Lightened heading text */
            font-weight: 600;
        }
    </style>
</head>
<body>
    <header>
        <h1>Student Reports</h1>
        <nav>
            <a href="dashboard.php">⇐ Dashboard</a>
            <a class="logout" href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="container">
        <!-- Average Grade -->
        <div class="average-grade">
            <p><strong>Average Grade of All Students:</strong> <?php echo number_format($average_grade, 2); ?></p>
        </div>

        <!-- Student Grades Table -->
        <h2>Student Grades</h2>
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display grades for all students
                while ($grade = mysqli_fetch_assoc($grades_result)) {
                    $student_name = $students_hash_map[$grade['student_id']]; // Use hash map for fast lookup
                    echo "<tr>
                            <td>{$student_name}</td>
                            <td>{$grade['grade']}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
