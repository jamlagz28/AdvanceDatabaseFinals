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

// Add or Update grade functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_grade'])) {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $grade = $_POST['grade'];

    // Check if a grade already exists for this student and course
    $check_query = "SELECT * FROM grades WHERE student_id = '$student_id' AND course_id = '$course_id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Update grade if already exists
        $update_query = "UPDATE grades SET grade = '$grade' WHERE student_id = '$student_id' AND course_id = '$course_id'";
        if (mysqli_query($conn, $update_query)) {
            echo "Grade updated successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // Insert new grade
        $insert_query = "INSERT INTO grades (student_id, course_id, grade) VALUES ('$student_id', '$course_id', '$grade')";
        if (mysqli_query($conn, $insert_query)) {
            echo "Grade added successfully!";
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
    <title>Student Grades Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Roboto+Slab:wght@400;700&family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-image: url('https://i.pinimg.com/originals/11/d5/37/11d53764cc9591f20dfa2aa313f8f82b.jpg'); /* Updated background image */
            background-size: cover;
            background-position: center;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background: rgba(62, 76, 89, 0.8); /* Transparent background for header */
            color: white;
            padding: 20px;
            text-align: center;
            font-family: 'Roboto Slab', serif;
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
            font-weight: 500;
            font-size: 1.1rem;
        }

        nav a:hover {
            text-decoration: underline;
        }

        h2 {
            text-align: center;
            font-size: 1.8rem;
            margin-top: 20px;
            font-family: 'Roboto Slab', serif;
            color: white; /* Changed color to white */
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
            padding: 15px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 1.1rem;
            font-family: 'Roboto', sans-serif;
        }

        th {
            background-color: #4b636e;
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
            width: calc(100% - 24px);
            padding: 12px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            font-family: 'Roboto', sans-serif;
        }

        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-weight: 600;
            font-family: 'Roboto', sans-serif;
            width: 100%;
            margin-top: 10px;
        }

        button:hover {
            background: #0056b3;
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
        <h1>Student Grade Management</h1>
        <nav>
            <a href="dashboard.php">⇐ Dashboard</a>
            <a class="logout" href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="container">
        <h2>Assign Grades to Students</h2>
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
            <input type="number" name="grade" step="0.01" placeholder="Grade" required>
            <button type="submit" name="submit_grade">Submit Grade</button>
        </form>

        <h2>View Grades</h2>
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Course Name</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch assigned grades
                $grade_query = "
                    SELECT s.name AS student_name, c.course_name, g.grade
                    FROM grades g
                    JOIN students s ON g.student_id = s.id
                    JOIN courses c ON g.course_id = c.id
                ";
                $grade_result = mysqli_query($conn, $grade_query);
                while ($grade = mysqli_fetch_assoc($grade_result)): ?>
                    <tr>
                        <td><?php echo $grade['student_name']; ?></td>
                        <td><?php echo $grade['course_name']; ?></td>
                        <td><?php echo $grade['grade']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
