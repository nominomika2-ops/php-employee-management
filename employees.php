
<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';

$sql = "SELECT employees.id, employees.name, employees.email,
               departments.name AS department
        FROM employees
        LEFT JOIN departments
        ON employees.department_id = departments.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f6fc;
            padding: 30px;
        }

        h2 { color: #2457a7; }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background: #2457a7;
            color: white;
        }

        a {
            display: inline-block;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <h2>Employee List</h2>

    <a href="add_employee.php">+ Add Employee</a> |
    <a href="logout.php">Logout</a>

    <?php if ($result): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars((string)$row['id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['department'] ?? 'Not assigned') ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Unable to load employees. Please check the database tables and column names.</p>
    <?php endif; ?>

</body>
</html>
