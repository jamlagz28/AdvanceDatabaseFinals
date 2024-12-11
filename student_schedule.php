<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch all students and courses for the schedule form
$student_query = "SELECT * FROM students";
$student_result = mysqli_query($conn, $student_query);

$course_query = "SELECT * FROM courses";
$course_result = mysqli_query($conn, $course_query);

// Add schedule functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_schedule'])) {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $schedule_day = $_POST['schedule_day'];
    $schedule_time = $_POST['schedule_time'];

    // Check if a schedule for the student and course already exists
    $check_query = "SELECT * FROM schedules WHERE student_id = '$student_id' AND course_id = '$course_id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Update the existing schedule
        $update_query = "UPDATE schedules SET schedule_day = '$schedule_day', schedule_time = '$schedule_time' WHERE student_id = '$student_id' AND course_id = '$course_id'";
        if (mysqli_query($conn, $update_query)) {
            echo "Schedule updated successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // Insert new schedule
        $insert_query = "INSERT INTO schedules (student_id, course_id, schedule_day, schedule_time) VALUES ('$student_id', '$course_id', '$schedule_day', '$schedule_time')";
        if (mysqli_query($conn, $insert_query)) {
            echo "Schedule added successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// View schedule functionality
$schedule_query = "SELECT s.name AS student_name, c.course_name, sc.schedule_day, sc.schedule_time 
                   FROM schedules sc
                   JOIN students s ON sc.student_id = s.id
                   JOIN courses c ON sc.course_id = c.id";
$schedule_result = mysqli_query($conn, $schedule_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Schedules</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background: url('https://a.rgbimg.com/users/e/er/ervinbacik/600/meKUFHa.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
        }

        header {
            background: rgba(74, 144, 226, 0.8); /* Transparent header background */
            color: white;
            padding: 20px;
            text-align: center;
            font-family: 'Montserrat', sans-serif;
        }

        header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 700;
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

        .container {
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
            color: #4A90E2;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        input, select, button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            font-size: 1rem;
            border: 1px solid #ddd;
        }

        input[type="text"], input[type="time"] {
            background-color: #f9f9f9;
        }

        select {
            background-color: #f9f9f9;
        }

        button {
            background-color: #4A90E2;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }

        button:hover {
            background-color: #357ABD;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 1.1rem;
        }

        th {
            background-color: #4A90E2;
            color: white;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .logout {
            padding: 10px 20px;
            background: #E94E77;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
        }

        .logout:hover {
            background: #D83B64;
        }
    </style>
</head>
<body>
    <header>
        <h3>Student's Schedule Management</h3>
        <nav>
            <a href="dashboard.php">⇐ Dashboard</a>
            <a class="logout" href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="container">
        <!-- Add Schedule Form -->
        <h3>Add or Update Student Schedule</h3>
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
            <input type="text" name="schedule_day" placeholder="Enter Day (e.g., Monday)" required>
            <input type="time" name="schedule_time" required>
            <button type="submit" name="submit_schedule">Submit Schedule</button>
        </form>

        <!-- View Schedules -->
        <h2>Student Schedules</h2>
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Course Name</th>
                    <th>Schedule Day</th>
                    <th>Schedule Time</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($schedule = mysqli_fetch_assoc($schedule_result)): ?>
                    <tr>
                        <td><?php echo $schedule['student_name']; ?></td>
                        <td><?php echo $schedule['course_name']; ?></td>
                        <td><?php echo $schedule['schedule_day']; ?></td>
                        <td><?php echo $schedule['schedule_time']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
