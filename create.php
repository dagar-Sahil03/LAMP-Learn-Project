```php
<?php
/**
 * create.php - Add New Employee (CREATE operation)
 */

require_once 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Collect and sanitize input
    $name   = trim($_POST['employee_name'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $dept   = trim($_POST['department'] ?? '');
    $salary = floatval($_POST['salary'] ?? 0);

    // Validation
    if (empty($name) || empty($email) || empty($dept) || $salary <= 0) {

        $message = '<div class="alert alert-danger">
                        All fields are required and salary must be greater than 0.
                    </div>';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = '<div class="alert alert-danger">
                        Please enter a valid email address.
                    </div>';

    } else {

        // Prepared Statement
        $stmt = $conn->prepare(
            "INSERT INTO employees
            (employee_name, email, department, salary)
            VALUES (?, ?, ?, ?)"
        );

        if ($stmt) {

            $stmt->bind_param(
                "sssd",
                $name,
                $email,
                $dept,
                $salary
            );

            if ($stmt->execute()) {

                header('Location: index.php');
                exit();

            } else {

                $message = '<div class="alert alert-danger">
                                Error: ' . htmlspecialchars($stmt->error) . '
                            </div>';
            }

            $stmt->close();

        } else {

            $message = '<div class="alert alert-danger">
                            Database Error: ' . htmlspecialchars($conn->error) . '
                        </div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Employee</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5" style="max-width: 600px;">

    <div class="card shadow">

        <div class="card-header bg-success text-white">
            <h4 class="mb-0">Add New Employee</h4>
        </div>

        <div class="card-body">

            <?php echo $message; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Employee Name</label>
                    <input
                        type="text"
                        name="employee_name"
                        class="form-control"
                        value="<?php echo htmlspecialchars($_POST['employee_name'] ?? ''); ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Department</label>

                    <select name="department" class="form-select" required>
                        <option value="">Select Department</option>

                        <?php
                        $departments = [
                            'Engineering',
                            'HR',
                            'Finance',
                            'Marketing',
                            'Operations'
                        ];

                        $selectedDept = $_POST['department'] ?? '';

                        foreach ($departments as $department) {
                            $selected = ($selectedDept === $department) ? 'selected' : '';
                            echo "<option value=\"$department\" $selected>$department</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Salary (INR)</label>
                    <input
                        type="number"
                        name="salary"
                        class="form-control"
                        min="1"
                        step="0.01"
                        value="<?php echo htmlspecialchars($_POST['salary'] ?? ''); ?>"
                        required>
                </div>

                <a href="index.php" class="btn btn-secondary">
                    Cancel
                </a>

                <button type="submit" class="btn btn-success">
                    Save Employee
                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>

<?php
$conn->close();
?>
```
