```php
<?php
/**
 * index.php - Employee Records List (READ operation)
 * Main page displaying all employee records
 */

require_once 'db.php';

// Fetch all employees from database
$result = $conn->query("SELECT * FROM employees ORDER BY id DESC");

// Check for query errors
if (!$result) {
    die("Database Query Failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management System</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .header-bar {
            background-color: #1F3864;
            color: white;
            padding: 20px;
            margin-bottom: 30px;
        }

        .btn-action {
            margin: 0 3px;
        }
    </style>
</head>
<body>

    <div class="header-bar">
        <div class="container">
            <h2>Employee Management System</h2>
            <small>PHP LAMP Stack Application | AWS EC2 Deployment</small>
        </div>
    </div>

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Employee Records</h4>
            <a href="create.php" class="btn btn-success">
                + Add New Employee
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Salary (INR)</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($result->num_rows > 0): ?>

                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>

                                <td>
                                    <?php echo htmlspecialchars($row['employee_name']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['email']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['department']); ?>
                                </td>

                                <td>
                                    ₹<?php echo number_format($row['salary'], 2); ?>
                                </td>

                                <td>
                                    <?php
                                    echo !empty($row['created_at'])
                                        ? date('d M Y', strtotime($row['created_at']))
                                        : 'N/A';
                                    ?>
                                </td>

                                <td>
                                    <a href="update.php?id=<?php echo $row['id']; ?>"
                                       class="btn btn-warning btn-sm btn-action">
                                        Edit
                                    </a>

                                    <a href="delete.php?id=<?php echo $row['id']; ?>"
                                       class="btn btn-danger btn-sm btn-action"
                                       onclick="return confirm('Delete this employee?')">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7" class="text-center">
                                No employees found.
                            </td>
                        </tr>

                    <?php endif; ?>
                </tbody>

            </table>
        </div>

    </div>

</body>
</html>

<?php
$conn->close();
?>
```
