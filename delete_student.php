<?php
session_start();
include('db_connection.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // Delete query to remove the student record from the database
    $delete_query = "DELETE FROM students WHERE id = '$student_id'";

    // Execute the query
    if (mysqli_query($conn, $delete_query)) {
        echo "Student record deleted successfully!";
        header("Location: index.php");  // Redirect to the student records page (or wherever you want)
        exit();
    } else {
        echo "Error deleting student: " . mysqli_error($conn);
    }
} else {
    echo "No student ID provided!";
}
?>
