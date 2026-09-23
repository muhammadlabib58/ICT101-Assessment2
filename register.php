<?php

require_once "includes/db-connect.php";

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = mysqli_real_escape_string($conn, $_POST["full_name"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $confirm_password = mysqli_real_escape_string($conn, $_POST["confirm_password"]);

    if ($full_name == "" || $email == "" || $username == "" || $password == "") {
        $error_message = "Please fill in every field.";
    } elseif ($password != $confirm_password) {
        $error_message = "Your passwords do not match.";
    } else {

        // Check the username isn't already taken
        $check_sql = "SELECT * FROM users WHERE username = '$username'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            $error_message = "That username is already taken. Please choose another.";
        } else {

            // Insert into users (login credentials)
            $insert_user_sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

            if (mysqli_query($conn, $insert_user_sql)) {
                $new_user_id = mysqli_insert_id($conn);

                // Insert into customers (profile details), linked by user_id
                $insert_customer_sql = "INSERT INTO customers (user_id, full_name, email) VALUES ($new_user_id, '$full_name', '$email')";
                mysqli_query($conn, $insert_customer_sql);

                $success_message = "Your account has been created. You can now log in.";
            } else {
                $error_message = "Something went wrong: " . mysqli_error($conn);
            }
        }
    }
}

$page_title = "Register";
include "includes/header.php";
?>

<div class="form-box">
    <h2>Register</h2>

    <?php if ($error_message != "") { ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php } ?>

    <?php if ($success_message != "") { ?>
        <div class="success-message"><?php echo $success_message; ?> <a href="login.php">Go to Login</a></div>
    <?php } ?>

    <form name="registerForm" method="post" action="register.php" onsubmit="return validateRegisterForm()">
        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name">

        <label for="email">Email</label>
        <input type="email" id="email" name="email">

        <label for="username">Username</label>
        <input type="text" id="username" name="username">

        <label for="password">Password</label>
        <input type="password" id="password" name="password">

        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password">

        <button type="submit" class="btn">Register</button>
    </form>

    <div class="form-links">
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</div>

<?php include "includes/footer.php"; ?>
<script src="js/validate.js"></script>
</body>
</html>
