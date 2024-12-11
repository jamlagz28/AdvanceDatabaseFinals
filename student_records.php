<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch student records from the database
$query = "SELECT * FROM students";  // Assuming your student table is called 'students'
$result = mysqli_query($conn, $query);

// Add Student functionality (optional: you would create a form to input student data)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_student'])) {
    $student_name = $_POST['student_name'];
    $student_email = $_POST['student_email'];
    $student_phone = $_POST['student_phone'];
    
    // Insert new student record into the database
    $insert_query = "INSERT INTO students (name, email, phone) VALUES ('$student_name', '$student_email', '$student_phone')";
    if (mysqli_query($conn, $insert_query)) {
        echo "New student added successfully!";
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
    <title>Student Records</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
            background-image: url('https://th.bing.com/th/id/OIP.yxVUP9HylYq4fOJAvzO6DwHaEK?rs=1&pid=ImgDetMain');
            background-size: cover;
            background-position: center;
            color: #333;
            margin: 0;
            padding: 0;
            background-attachment: fixed;
        }

        header {
            background-color: rgba(30, 42, 58, 0.9); /* Darker header with transparency */
            color: #fff;
            padding: 25px 0;
            text-align: center;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 700;
        }

        nav a {
            color: #d1d8e0;
            text-decoration: none;
            margin: 0 15px;
            font-weight: 500;
            font-size: 1.1rem;
        }

        nav a:hover {
            color: #ffb81c;
            text-decoration: underline;
        }

        h2 {
            text-align: center;
            font-size: 2rem;
            margin-top: 40px;
            font-weight: 600;
            color: #1e2a3a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        th, td {
            padding: 16px;
            border: 1px solid #e2e8f0;
            text-align: left;
            font-size: 1rem;
        }

        th {
            background-color: #2d3e50;
            color: #fff;
            font-weight: 700;
        }

        tr:nth-child(even) {
            background-color: #f3f4f6;
        }

        tr:hover {
            background-color: #f9fafb;
        }

        form {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 50%;
            margin: 30px auto;
        }

        input, select {
            width: 100%;
            padding: 14px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 15px;
            cursor: pointer;
            border-radius: 8px;
            font-weight: 500;
            width: 100%;
            font-size: 1.1rem;
        }

        button:hover {
            background-color: #0056b3;
        }

        .logout {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1.1rem;
        }

        .logout:hover {
            background-color: #c0392b;
        }

        .container {
            margin-top: 30px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Student's Record Management</h1>
        <nav>
            <a href="dashboard.php">⇐ Dashboard</a>
            <a class="logout" href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="container">
        <!-- Add New Student Form -->
        <h2>Add New Student</h2>
        <form method="POST" action="">
            <input type="text" name="student_name" placeholder="Student Name" required>
            <input type="email" name="student_email" placeholder="Student Email" required>
            <input type="text" name="student_phone" placeholder="Student Phone" required>
            <button type="submit" name="add_student">Add Student</button>
        </form>

        <!-- Student Records Table -->
        <h2>Manage Student Records</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td>
                            <a href="edit_student.php?id=<?php echo $row['id']; ?>">Edit</a> |
                            <a href="delete_student.php?id=<?php echo $row['id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
