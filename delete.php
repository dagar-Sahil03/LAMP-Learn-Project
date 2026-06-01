```php
<?php
/**
 * delete.php - Delete Employee Record
 */

require_once 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {

    $stmt = $conn->prepare(
        "DELETE FROM employees WHERE id = ?"
    );

    if ($stmt) {

        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            error_log(
                "Delete failed: " . $stmt->error
            );
        }

        $stmt->close();
    }
}

$conn->close();

header('Location: index.php');
exit();
?>
```
