<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CampusFlow – Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>

<header class="top-bar">
    <div class="top-bar-left">
        <span class="logo-circle">CF</span>
        <div>
            <div class="top-title">CampusFlow</div>
            <div class="top-subtitle">Smart Student Records</div>
        </div>
    </div>
    <div class="top-bar-right">
        <span class="user-name">Hi, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a class="btn-secondary" href="logout.php">Logout</a>
    </div>
</header>

<div class="container">
    <!-- Form + Search Row -->
    <div class="grid-2">
        <!-- Student Form -->
        <div class="card">
            <h2 id="form-title">Add New Student</h2>
            <form id="studentForm">
                <input type="hidden" id="id" name="id">

                <div class="form-group">
                    <label>Student ID <span class="small-text">(Format: 25ABCD01)</span></label>
                    <input type="text" id="student_id" name="student_id" maxlength="8"
                           placeholder="YYBBBBNN e.g. 25ABCD01">
                </div>

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter student name">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter email">
                </div>

                <div class="form-group">
                    <label>Course</label>
                    <input type="text" id="course" name="course" placeholder="e.g. MCA">
                </div>

                <div class="form-group">
                    <label>Semester</label>
                    <input type="text" id="semester" name="semester" placeholder="e.g. 3rd">
                </div>

                <button type="submit" id="saveBtn" class="btn-primary">Save</button>
                <button type="button" id="resetBtn" class="btn-secondary">Reset</button>

                <p id="msg"></p>
            </form>
        </div>

        <!-- Search Box -->
        <div class="card">
            <h2>Search Students</h2>
            <div class="form-group">
                <label>Search by ID / Name / Email / Course</label>
                <input type="text" id="searchBox" placeholder="Type to search...">
            </div>
            <p class="small-text">Search results update automatically as you type.</p>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card">
        <div class="card-header-row">
            <h2>Students List</h2>
            <span id="recordCount" class="badge"></span>
        </div>
        <div class="table-wrapper">
            <table id="studentsTable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Semester</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <!-- Filled by AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>
