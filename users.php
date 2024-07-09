<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./users.css">
    <title>Document</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>email</th>
                <th>phonenumber</th>
                <th>designation</th>
                <th>address</th>
                <th>gender</th>
                <th>Delete</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require('./connection.php');
            $p = crud::selectdata();
            if(isset($_GET['id'])){
                $id=$_GET['id'];
                $e=crud::delete($id);
            }
            if (count($p) > 0) {
                for ($i = 0; $i < count($p); $i++) {
                    echo '<tr>';
                    foreach ($p[$i] as $key => $value) {
                        if ($key != 'id') {
                            echo '<td>' . $value . '</td>';
                        }
                    }
                    ?>
                    <td><a class="an" href="users.php?id=<?php echo $p[$i]['id'] ?>">Delete</a></td>
                    <td><a class="an" href="update.php">Edit</a></td>
                    <?php
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
</body>

</html>