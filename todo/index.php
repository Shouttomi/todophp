<?php

$server = 'localhost';
$username = 'root';
$password = '';
$database = 'todo_master';

$conn = mysqli_connect($server, $username, $password, $database);

if ($conn->connect_errno) {
    die('Connection to MySQL failed: ' . $conn->connect_error);
}

if (isset($_POST['add'])) {
    $item = $_POST['item'];

    if (!empty($item)) {
        /*  $item = mysqli_real_escape_string($conn, $item);  */
        $query = "INSERT INTO todo (name) VALUES ('$item')";

        if (mysqli_query($conn, $query)) {
            echo '<center><div class="alert alert-success" role="alert">Item Added Successfully</div></center>';
        } else {
            echo mysqli_error($conn);
        }
    }
}

if (isset($_GET['action'])) {
    $itemid = $_GET['item'];

    if ($_GET['action'] == 'done' || $_GET['action'] == 'delete') {
        $itemid = intval($itemid);
        $action = $_GET['action'] == 'done' ? 1 : ($_GET['action'] == 'delete' ? 2 : 0);

        if ($action > 0) {
            $alertclass = "";

            if ($action == 1) {
                $query = "UPDATE todo SET status = $action WHERE id = $itemid";
                $alertclass = 'alert-info';
            } else {
                $query = "DELETE FROM todo WHERE id = $itemid";
                $alertclass = 'alert-danger';
            }

            if (mysqli_query($conn, $query)) {
                $actionText = $action == 1 ? "Marked as Done" : "Deleted Successfully";
                echo "<center><div class='alert $alertclass' role='alert'>Item $actionText</div></center>";
            } else {
                echo mysqli_error($conn);
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Todo List Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        .done {
            text-decoration: line-through;
        }
    </style>
</head>

<body>
    <main>
        <div class="container pt-5">
            <div class="row">
                <div class="col-sm-12 col-md-3"></div>
                <div class="col-sm-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <p>Todo List</p>
                        </div>
                        <div class="card-body">
                            <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="item" placeholder="Add to do item">
                                </div>
                                <input type="submit" class="btn btn-dark" name="add" value="Add Item">
                            </form>
                            <div class="mt-5 mb-5">
                                <?php
                                $query = "SELECT * FROM todo";
                                $result = mysqli_query($conn, $query);
                                if ($result->num_rows > 0) {
                                    $i = 1;
                                    while ($row = $result->fetch_assoc()) {
                                        $done = $row['status'] == 1 ? "done" : "";
                                        echo "
                                            <div class='row mt-4'>
                                                <div class='col-sm-12 col-md-1'><h5>$i</h5></div>
                                                <div class='col-sm-12 col-md-6'><h5 class='$done'>" . htmlspecialchars($row['name']) . "</h5></div>
                                                <div class='col-sm-12 col-md-5'>
                                                    <a href='?action=done&item={$row['id']}' class='btn btn-outline-dark'>Mark as Done</a>
                                                    <a href='?action=delete&item={$row['id']}' class='btn btn-outline-danger'>Delete</a>
                                                </div>
                                            </div>";
                                        $i++;
                                    }
                                } else {
                                    echo "<center><img src='folder.png' alt='Empty list' width='50px'><br><span>Your list is empty</span></center>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $(".alert").fadeTo(5000, 500).slideUp(500, function() {
                $('.alert').slideUp(500);
            });
        });
    </script>
</body>

</html>