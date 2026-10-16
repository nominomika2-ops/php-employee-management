
<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $department_id = (int)($_POST['department_id'] ?? 0);

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid name and email.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO employees (name, email, department_id)
             VALUES (?, ?, NULLIF(?, 0))"
        );

        $stmt->bind_param("ssi", $name, $email, $department_id);

        if ($stmt->execute()) {
            $success = "Employee added successfully!";
        } else {
            $error = "Unable to add employee.";
        }

        $stmt->close();
    }
}

$departments = $conn->query("SELECT id, name FROM departments");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eaf2f8;
            padding: 30px;
        }

        .form-box {
            background: white;
            max-width: 450px;
            margin: auto;
            padding: 25px;
            border-radius: 10px;
        }

        h2 { color: #2457a7; }

        label { display: block; margin-top: 15px; }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        button {
            margin-top: 20px;
            padding: 12px;
            width: 100%;
            background: #2457a7;
            color: white;
            border: none;
            cursor: pointer;
        }

        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Add Employee</h2>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Name</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Department</label>
        <select name="department_id">
            <option value="0">Select Department (Optional)</option>

            <?php if ($departments): ?>
                <?php while ($dept = $departments->fetch_assoc()): ?>
                    <option value="<?= (int)$dept['id'] ?>">
                        <?= htmlspecialchars($dept['name']) ?>
                    </option>
                <?php endwhile; ?>
            <?php endif; ?>
        </select>

        <button type="submit">Save Employee</button>
    </form>

    <p><a href="employees.php">Back to Employee List</a></p>
</div>

</body>
</html>
