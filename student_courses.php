<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch all courses
$course_query = "SELECT * FROM courses";
$course_result = mysqli_query($conn, $course_query);

// Fetch all students
$student_query = "SELECT * FROM students";
$student_result = mysqli_query($conn, $student_query);

// Add a new course functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_course'])) {
    $course_name = $_POST['course_name'];
    $course_code = $_POST['course_code'];

    $insert_course_query = "INSERT INTO courses (course_name, course_code) VALUES ('$course_name', '$course_code')";
    if (mysqli_query($conn, $insert_course_query)) {
        echo "New course added successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Courses</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Overall layout and background */
        body {
            font-family: 'Roboto', sans-serif;
            background-image: url('https://www.desktopbackground.org/download/1024x600/2012/04/17/375783_abstract-blue-wallpapers-cliparts-co_1600x1200_h.jpg');
            background-size: cover;
            background-position: center;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: rgba(44, 62, 80, 0.8); /* Darken header for contrast */
            color: white;
            padding: 20px 40px;
            text-align: center;
            font-size: 1.5rem; /* Adjust the header font size */
        }

        nav a {
            color: #ecf0f1;
            text-decoration: none;
            margin: 0 15px; /* Adjust space between links */
            font-weight: 500;
            font-size: 1.1rem; /* Adjust the font size for the links */
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        h2 {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        /* Forms and buttons */
        form {
            background: rgba(255, 255, 255, 0.9); /* Slight transparency for form */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }

        button {
            background-color: #27ae60;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }

        button:hover {
            background-color: #2ecc71;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
            font-size: 1.1rem;
        }

        th {
            background-color: #2c3e50;
            color: #ecf0f1;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #ecf0f1;
        }

        tr:hover {
            background-color: #dcdde1;
        }

        /* Footer and logout */
        .logout {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .logout:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <header>
        <h1>Student Course Management</h1>
        <nav>
            <a href="dashboard.php">⇐ Dashboard</a>
            <a class="logout" href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="container">
        <!-- Add New Course Form -->
        <h2>Add New Course</h2>
        <form method="POST" action="">
        <input type="text" name="student_name" placeholder="Student Name" required>
        <input type="text" name="course_name" placeholder="Course Name" required>
            <input type="text" name="course_code" placeholder="Course Code" required>
            
            <button type="submit" name="add_course">Add Course</button>
        </form>

        <!-- Assign Course to Student Form -->
        <h2>Assign Course to Student</h2>
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
            <button type="submit" name="assign_course">Assign Course</button>
        </form>

        <!-- Display Courses Assigned to Students -->
        <h2>Assigned Courses to Students</h2>
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Course Name</th>
                    <th>Course Code</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $assigned_courses_query = "
                    SELECT s.name AS student_name, c.course_name, c.course_code 
                    FROM student_courses sc
                    JOIN students s ON sc.student_id = s.id
                    JOIN courses c ON sc.course_id = c.id
                ";
                $assigned_courses_result = mysqli_query($conn, $assigned_courses_query);
                while ($assigned_course = mysqli_fetch_assoc($assigned_courses_result)): ?>
                    <tr>
                        <td><?php echo $assigned_course['student_name']; ?></td>
                        <td><?php echo $assigned_course['course_name']; ?></td>
                        <td><?php echo $assigned_course['course_code']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
