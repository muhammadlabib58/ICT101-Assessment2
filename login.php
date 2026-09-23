<?php

require_once "includes/db-connect.php";

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    // --- Lecture-style plain text check (default) ---
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        $error_message = "Something went wrong: " . mysqli_error($conn);
    } elseif (mysqli_num_rows($result) == 1) {
        $user_row = mysqli_fetch_assoc($result);

        session_start();
        $_SESSION["user_id"] = $user_row["user_id"];
        $_SESSION["username"] = $user_row["username"];

        header("Location: account.php");
        exit;
    } else {
        $error_message = "Incorrect username or password. Please try again.";
    }

    /*
        $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) == 1) {
        $user_row = mysqli_fetch_assoc($result);
        if (password_verify($password, $user_row["password"])) {
            session_start();
            $_SESSION["user_id"] = $user_row["user_id"];
            $_SESSION["username"] = $user_row["username"];
            header("Location: account.php");
            exit;
        } else {
            $error_message = "Incorrect username or password. Please try again.";
        }
    } else {
        $error_message = "Incorrect username or password. Please try again.";
    }
    */
}

$page_title = "Login";
include "includes/header.php";
?>

<div class="form-box">
    <h2>Login</h2>

    <?php if ($error_message != "") { ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php } ?>

    <form name="loginForm" method="post" action="login.php" onsubmit="return validateLoginForm()">
        <label for="username">Username</label>
        <input type="text" id="username" name="username">

        <label for="password">Password</label>
        <input type="password" id="password" name="password">

        <button type="submit" class="btn">Login</button>
    </form>

    <div class="form-links">
        <p><a href="reset-password.php">Forgot your password? Reset it here</a></p>
        <p>New here? <a href="register.php">Register an account</a></p>
    </div>
</div>

<?php include "includes/footer.php"; ?>
<script src="js/validate.js"></script>
</body>
</html>
