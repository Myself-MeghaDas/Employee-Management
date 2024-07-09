<?php
require('./connection.php');

$success = false;
$error_message = '';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        $con = Crud::connect();
        $sql = "SELECT * FROM employee WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $employee = $result->fetch_assoc();

        if (!$employee) {
            $error_message = 'Employee not found.';
        }

        $stmt->close();
        $con->close();
    } catch (mysqli_sql_exception $e) {
        $error_message = 'Error: ' . $e->getMessage();
    }
}

if (isset($_POST['submit'])) {
    $name = $_POST['Name'];
    $email = $_POST['Email'];
    $phonenumber = $_POST['Phone'];
    $designation = $_POST['Designation'];
    $gender = $_POST['Gender'];
    $address = $_POST['Address'];

    if (!empty($name) && !empty($email) && !empty($phonenumber) && !empty($designation) && !empty($address) && !empty($gender)) {
        try {
            $con = Crud::connect();
            $sql = "UPDATE employee SET name=?, email=?, phonenumber=?, designation=?, address=?, gender=? WHERE id=?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ssssssi", $name, $email, $phonenumber, $designation, $address, $gender, $id);
            $stmt->execute();
            $stmt->close();
            $con->close();

            header("Location: " . $_SERVER['PHP_SELF'] . "?id=$id&success=true");
            exit();
        } catch (mysqli_sql_exception $e) {
            if ($con->errno == 1062) {
                $error_message = 'Error: Duplicate entry found for email.';
            } else {
                $error_message = 'Error: ' . $e->getMessage();
            }
        }
    } else {
        $error_message = 'Please fill in all fields.';
    }
}

if (isset($_GET['success']) && $_GET['success'] == 'true') {
    $success = true;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./signUp.css">
    <title>Update Employee</title>
    <script>
        function showMessage() {
            const message = document.getElementById('success-message');
            if (message) {
                message.style.display = 'block';
                setTimeout(() => {
                    message.style.display = 'none';
                    const url = new URL(window.location);
                    url.searchParams.delete('success');
                    window.history.replaceState(null, null, url);
                }, 3000);
            }
        }
        window.onload = showMessage;
    </script>
    <style>
        #success-message {
            display: none;
            position: fixed;
            top: 10px;
            right: 10px;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 5px;
        }

        #error-message {
            display: block;
            position: fixed;
            top: 10px;
            right: 10px;
            background-color: #f44336;
            color: white;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <?php if ($success) : ?>
        <div id="success-message">Update Successfully!</div>
    <?php endif; ?>

    <?php if ($error_message) : ?>
        <div id="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <div class="form-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>Update Employee Details</h2>
            <a href="users.php" style="padding: 7px; background-color: #009879; color: white; text-decoration: none; border-radius: 22%;">
                Go to User Panel</a>
        </div>

        <form id="registrationForm" action="" method="post">
            <input type="text" name="Name" placeholder="Name" value="<?php echo htmlspecialchars($employee['name'] ?? ''); ?>">
            <input type="text" name="Email" placeholder="Email" value="<?php echo htmlspecialchars($employee['email'] ?? ''); ?>">
            <input type="text" name="Phone" placeholder="Phone Number" value="<?php echo htmlspecialchars($employee['phonenumber'] ?? ''); ?>">
            <input type="text" name="Designation" placeholder="Designation" value="<?php echo htmlspecialchars($employee['designation'] ?? ''); ?>">
            <label for="gender">Gender:</label>
            <input type="radio" name="Gender" value="Male" <?php echo (isset($employee['gender']) && $employee['gender'] == 'Male') ? 'checked' : ''; ?>> Male
            <input type="radio" name="Gender" value="Female" <?php echo (isset($employee['gender']) && $employee['gender'] == 'Female') ? 'checked' : ''; ?>> Female
            <input type="radio" name="Gender" value="Other" <?php echo (isset($employee['gender']) && $employee['gender'] == 'Other') ? 'checked' : ''; ?>> Other
            <textarea name="Address" placeholder="Address"><?php echo htmlspecialchars($employee['address'] ?? ''); ?></textarea>
            <div>
                <button type="submit" name="submit">Update</button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            const name = document.getElementsByName('Name')[0].value;
            const email = document.getElementsByName('Email')[0].value;
            const phone = document.getElementsByName('Phone')[0].value;
            const designation = document.getElementsByName('Designation')[0].value;
            const gender = document.querySelector('input[name="Gender"]:checked');
            const address = document.getElementsByName('Address')[0].value;

            if (!name || !email || !phone || !designation || !gender || !address) {
                event.preventDefault();
                alert('Please fill in all fields.');
            }
        });
    </script>
</body>

</html>
