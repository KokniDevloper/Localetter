<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.html');
    exit;
}

$conn = mysqli_connect('localhost', 'root', '', 'newsletterpro');
$query = "SELECT * FROM list";
$result = mysqli_query($conn, $query);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['insert'])) {
        $email = $_POST['insert'];
        $prep = mysqli_prepare($conn, "INSERT INTO `list` (`emails`) VALUES (?)");
        mysqli_stmt_bind_param($prep, 's', $email);
        mysqli_stmt_execute($prep);
    }
    if (isset($_POST['delete'])) {
        $id = $_POST['delete'];
        $prep = mysqli_prepare($conn, "SELECT * FROM list WHERE id = ?");
        mysqli_stmt_bind_param($prep, 'i', $id);
        mysqli_stmt_execute($prep);
        $result = mysqli_stmt_get_result($prep);
        if (mysqli_num_rows($result) == 0) {
            $_SESSION['errorMsg'] = 'No record exists at Id ' . $id . ".<br>Check you are inserting DB ID and not the serial number.";
            header("Location: app.php");
            exit;
        }
        $prep = mysqli_prepare($conn, "DELETE FROM list WHERE id = ?");
        mysqli_stmt_bind_param($prep, 'i', $id);
        mysqli_stmt_execute($prep);
    }
    if (isset($_POST['update'])) {
        $email = $_POST['remail'];
        $id = $_POST['update'];
        $prep = mysqli_prepare($conn, "SELECT * FROM list WHERE id = ?");
        mysqli_stmt_bind_param($prep, 'i', $id);
        mysqli_stmt_execute($prep);
        $result = mysqli_stmt_get_result($prep);
        if (mysqli_num_rows($result) == 0) {
            $_SESSION['errorMsg'] = 'No record exists at Id ' . $id . ".<br>Check you are inserting DB ID and not the serial number.";
            header("Location: app.php");
            exit;
        }
        $prep = mysqli_prepare($conn, "UPDATE list SET emails = ? WHERE id = ?");
        mysqli_stmt_bind_param($prep, 'si', $email, $id);
        mysqli_stmt_execute($prep);
    }
    header("Location: app.php");
    exit;
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter Pro</title>
    <link rel="stylesheet" href="Frontend/styles/style.css">
</head>

<body>
    <div class="main">
        <header>
            <nav>
                <h3>
                    <a class="primary-text" href="index.html">Newsletter Pro</a>
                </h3>
                <a href="Backend/auth/logout.php">logout</a>
            </nav>
        </header>
        <div class="hero">
            <h1 class="primary-text">Newsletter Professional</h1>
            <h2 class="secondary-text">Welcome</h2>
            <br>
            <div class="appForms secondary-text">
                <!-- Insert -->
                <form method="POST">
                    <fieldset>
                        <legend>
                            Insert:
                        </legend>
                        <label for="insert">Email: </label><br>
                        <input type="email" name="insert" id="insert" required><br>
                        <input type="submit" value="Insert">
                    </fieldset>
                </form>
                <!-- Delete -->
                <form method="POST">
                    <fieldset>
                        <legend>
                            Delete:
                        </legend>
                        <label for="delete">ID: </label><br>
                        <input type="number" name="delete" id="delete" required min="1"><br>
                        <input type="submit" value="Delete">
                    </fieldset>
                </form>
                <!-- Update -->
                <form method="POST">
                    <fieldset>
                        <legend>
                            Update:
                        </legend>
                        <label for="remail">Replacement Email: </label><br>
                        <input type="email" name="remail" id="remail" required><br>
                        <label for="delete">ID: </label><br>
                        <input type="number" name="update" id="update" required min="1"><br>
                        <input type="submit" value="Post">
                    </fieldset>
                </form>
            </div>
            <h3>
                <?php
                if (isset($_SESSION["errorMsg"])) {
                    echo $_SESSION["errorMsg"];
                    unset($_SESSION['errorMsg']);
                }
                ?>
            </h3>
            <br>
            <div style="width:80%;height:1px;background:gray;"></div>
            <div class="list secondary-text">
                <?php
                if (mysqli_num_rows($result) == 0) {
                    echo "<h2>No Emails Inserted</h2>";
                } else {
                    echo "<h2>Emails Inserted:</h2>";
                    echo "<ol>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<li>" . htmlspecialchars($row['emails']) . " | DB ID: " . $row['id'] . "</li>";
                    }
                    echo "</ol>";
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>