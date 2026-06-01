```php
<?php
/**
 * update.php - Edit Existing Employee (UPDATE operation)
 */

require_once 'db.php';

$message = '';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit();
}

/* Fetch employee */
$stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$employee = $result->fetch_assoc();

$stmt->close();

if (!$employee) {
    header('Location: index.php');
    exit();
}

/* Handle form submission */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name   = trim($_POST['employee_name'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $dept   = trim($_POST['department'] ?? '');
    $salary = floatval($_POST['salary'] ?? 0);

    if (empty($name) || empty($email) || empty($dept) || $salary <= 0) {

        $message = '<div class="alert alert-danger">
                        All fields are required and salary must be greater than 0.
                    </div>';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = '<div class="alert alert-danger">
                        Invalid email address.
                    </div>';

    } else {

        $stmt = $conn->prepare(
            "UPDATE employees
             SET employee_name = ?,
                 email = ?,
                 department = ?,
                 salary = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "sssdi",
            $name,
            $email,
            $dept,
            $salary,
            $id
        );

        if ($stmt->execute()) {

            header('Location: index.php');
            exit();

        } else {

            $message = '<div class="alert alert-danger">
                            Update failed: ' .
                            htmlspecialchars($stmt->error) .
                        '</div>';
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Employee</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5" style="max-width:600px;">

    <div class="card shadow">

        <div class="card-header bg-warning">
            <h4 class="mb-0">Edit Employee</h4>
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
                        value="<?php echo htmlspecialchars($employee['employee_name']); ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?php echo htmlspecialchars($employee['email']); ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Department</label>
                    <input
                        type="text"
                        name="department"
                        class="form-control"
                        value="<?php echo htmlspecialchars($employee['department']); ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Salary</label>
                    <input
                        type="number"
                        name="salary"
                        class="form-control"
                        min="1"
                        step="0.01"
                        value="<?php echo htmlspecialchars($employee['salary']); ?>"
                        required>
                </div>

                <a href="index.php" class="btn btn-secondary">
                    Cancel
                </a>

                <button type="submit" class="btn btn-warning">
                    Update Employee
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
