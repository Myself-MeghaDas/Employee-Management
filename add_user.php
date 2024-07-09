<?php
require('./connection.php');

$success = false;
$error_message = '';

if (isset($_POST['submit'])) {
    $name = $_POST['Name'];
    $email = $_POST['Email'];
    $phonenumber = $_POST['Phone'];
    $designation = $_POST['Designation'];
    $gender = $_POST['Gender'];
    $address = $_POST['Address'];

    if (!empty($name) && !empty($email) && !empty($phonenumber) && !empty($designation) && !empty($address) && !empty($gender)) {
        try {
            $p = crud::concet()->prepare('INSERT INTO employee (name, email, phonenumber, designation, gender, address) VALUES (:n, :e, :p, :d,:g, :a)');
            $p->bindValue(':n', $name);
            $p->bindValue(':e', $email);
            $p->bindValue(':p', $phonenumber);
            $p->bindValue(':d', $designation);
            $p->bindValue(':g', $gender);
            $p->bindValue(':a', $address);
            $p->execute();
            $p = crud::concet()->prepare('null');
            header("Location: " . $_SERVER['PHP_SELF'] . "?success=true");
            
            exit();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
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
    <title>Add User</title>
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
                }, 3000); // Hide message after 3 seconds and clear the success parameter
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
        <div id="success-message">Successfully submitted!</div>
    <?php endif; ?>

    <?php if ($error_message) : ?>
        <div id="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <div class="form-container">
       <div style="display: flex; justify-content: space-between; align-items: center;">
       <h2>Add New User</h2>
       <a href="users.php" 
       style="padding: 9px; background-color: #009879; color: white; text-decoration: none; border-radius: 10px;">
        Go to User Panel</a>
       </div>
        <form id="registrationForm" action="" method="post">
            <input type="text" name="Name" placeholder="Name">
            <input type="text" name="Email" placeholder="Email">
            <input type="text" name="Phone" maxlength="10" minlength="10" placeholder="Phone Number">
            <input type="text" name="Designation" placeholder="Designation">
            <label for="gender">Gender:</label>
            <input type="radio" name="Gender" value="Male"> Male
            <input type="radio" name="Gender" value="Female"> Female
            <input type="radio" name="Gender" value="Other"> Other
            <textarea name="Address" placeholder="Address"></textarea>
            <div>
                <button type="submit" name="submit">Add</button>
                <button type="button" onclick="resetForm()">Cancel</button>
            </div>
        </form>
    </div>
    <script>
        function resetForm() {
            document.getElementById('registrationForm').reset();
        }

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
