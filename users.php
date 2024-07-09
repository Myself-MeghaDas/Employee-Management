<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./users.css">
    <title>User Management</title>
</head>

<body>
    <div class="container">
        <a class="btn-add" href="add_user.php">Add New</a>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Designation</th>
                    <th>Address</th>
                    <th>Gender</th>
                    <th>Delete</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require('./connection.php');
                $p = crud::selectdata();
                if (isset($_GET['id'])) {
                    $id = intval($_GET['id']);
                    $e = crud::delete($id);
                    header("Location: users.php");
                    exit;
                }
                if (count($p) > 0) {
                    foreach ($p as $user) {
                        echo '<tr>';
                        foreach ($user as $key => $value) {
                            if ($key != 'id') {
                                echo '<td>' . htmlspecialchars($value) . '</td>';
                            }
                        }
                        echo '<td><a class="an" href="javascript:void(0);" onclick="confirmDelete(' . $user['id'] . ')">Delete</a></td>';
                        echo '<td><a class="an" href="update.php?id=' . $user['id'] . '">Update</a></td>';
                        echo '</tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                window.location.href = 'users.php?id=' + id;
            }
        }
    </script>

</body>

</html>