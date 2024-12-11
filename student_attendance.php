<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch all students and courses
$student_query = "SELECT * FROM students";
$student_result = mysqli_query($conn, $student_query);

$course_query = "SELECT * FROM courses";
$course_result = mysqli_query($conn, $course_query);

// Mark attendance functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_attendance'])) {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $attendance_date = $_POST['attendance_date'];
    $status = $_POST['status'];

    // Check if attendance for the student and course already exists on that date
    $check_query = "SELECT * FROM attendance WHERE student_id = '$student_id' AND course_id = '$course_id' AND attendance_date = '$attendance_date'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Update attendance if it already exists
        $update_query = "UPDATE attendance SET status = '$status' WHERE student_id = '$student_id' AND course_id = '$course_id' AND attendance_date = '$attendance_date'";
        if (mysqli_query($conn, $update_query)) {
            echo "Attendance updated successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // Insert new attendance record
        $insert_query = "INSERT INTO attendance (student_id, course_id, attendance_date, status) VALUES ('$student_id', '$course_id', '$attendance_date', '$status')";
        if (mysqli_query($conn, $insert_query)) {
            echo "Attendance marked successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Lora:wght@400;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: url('https://getwallpapers.com/wallpaper/full/c/4/0/799269-vista-desktop-wallpaper-1920x1080-windows-10.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background: rgba(0, 51, 102, 0.8);
            color: white;
            padding: 20px;
            text-align: center;
            font-family: 'Montserrat', sans-serif;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 700;
            font-family: 'Lora', serif;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: 600;
            font-size: 1.1rem;
        }

        nav a:hover {
            text-decoration: underline;
        }

        h2 {
            text-align: center;
            font-size: 2rem;
            margin-top: 20px;
            font-family: 'Montserrat', sans-serif;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 1.1rem;
        }

        th {
            background-color: #003366;
            color: white;
            font-weight: 700;
        }

        tr:nth-child(even) {
            background-color: #f7f7f7;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        form {
            background: rgba(255, 255, 255, 0.9);
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            width: 50%;
        }

        input, select {
            width: calc(100% - 22px);
            padding: 12px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        button {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px;
            cursor: pointer;
            border-radius: 5px;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
        }

        button:hover {
            background: #218838;
        }

        .logout {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
        }

        .logout:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
    <header>
        <h1>Student's Attendance Management</h1>
        <nav>
            <a href="dashboard.php">⇐ Dashboard</a>
            <a class="logout" href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="container">
        <h2>Mark Attendance</h2>
        <form method="POST" action="">
            <select name="student_id" required>
                <option value="">Select Student</option>
                <?php while ($student = mysqli_fetch_assoc($student_result)): ?>
                    <option value="<?php echo $student['id']; ?>"><?php echo $student['name']; ?></option>
                <?php endwhile; ?>
            </select>
            <select name="course_id" required>
                <option value="">Select Course</option>
                <?php while ($course = mysqli_fetch_assoc($course_result)): ?>
                    <option value="<?php echo $course['id']; ?>"><?php echo $course['course_name']; ?></option>
                <?php endwhile; ?>
            </select>
            <input type="date" name="attendance_date" required>
            <select name="status" required>
                <option value="">Select Status</option>
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
                <option value="Late">Late</option>
            </select>
            <button type="submit" name="submit_attendance">Submit Attendance</button>
        </form>

        <h2>View Attendance</h2>
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Course Name</th>
                    <th>Attendance Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch attendance records
                $attendance_query = "
                    SELECT s.name AS student_name, c.course_name, a.attendance_date, a.status
                    FROM attendance a
                    JOIN students s ON a.student_id = s.id
                    JOIN courses c ON a.course_id = c.id
                ";
                $attendance_result = mysqli_query($conn, $attendance_query);
                while ($attendance = mysqli_fetch_assoc($attendance_result)): ?>
                    <tr>
                        <td><?php echo $attendance['student_name']; ?></td>
                        <td><?php echo $attendance['course_name']; ?></td>
                        <td><?php echo $attendance['attendance_date']; ?></td>
                        <td><?php echo $attendance['status']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
