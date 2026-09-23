<?php

require_once "includes/db-connect.php";

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $new_password = mysqli_real_escape_string($conn, $_POST["new_password"]);
    $confirm_password = mysqli_real_escape_string($conn, $_POST["confirm_password"]);

    if ($username == "" || $email == "" || $new_password == "") {
        $error_message = "Please fill in every field.";
    } elseif ($new_password != $confirm_password) {
        $error_message = "Your new passwords do not match.";
    } else {

        // Step 1: find the user_id for that username
        $user_sql = "SELECT * FROM users WHERE username = '$username'";
        $user_result = mysqli_query($conn, $user_sql);

        if ($user_result && mysqli_num_rows($user_result) == 1) {
            $user_row = mysqli_fetch_assoc($user_result);
            $user_id = $user_row["user_id"];

            // Step 2: check that email matches this user's customer record
            $customer_sql = "SELECT * FROM customers WHERE user_id = $user_id AND email = '$email'";
            $customer_result = mysqli_query($conn, $customer_sql);

            if ($customer_result && mysqli_num_rows($customer_result) == 1) {
                $update_sql = "UPDATE users SET password = '$new_password' WHERE user_id = $user_id";
                mysqli_query($conn, $update_sql);
                $success_message = "Your password has been updated. You can now log in.";
            } else {
                $error_message = "We couldn't match that username and email.";
            }
        } else {
            $error_message = "We couldn't match that username and email.";
        }
    }
}

$page_title = "Reset Password";
include "includes/header.php";
?>

<div class="form-box">
    <h2>Reset Password</h2>

    <?php if ($error_message != "") { ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php } ?>

    <?php if ($success_message != "") { ?>
        <div class="success-message"><?php echo $success_message; ?> <a href="login.php">Go to Login</a></div>
    <?php } ?>

    <form name="resetForm" method="post" action="reset-password.php" onsubmit="return validateResetForm()">
        <label for="username">Username</label>
        <input type="text" id="username" name="username">

        <label for="email">Email on your account</label>
        <input type="email" id="email" name="email">

        <label for="new_password">New Password</label>
        <input type="password" id="new_password" name="new_password">

        <label for="confirm_password">Confirm New Password</label>
        <input type="password" id="confirm_password" name="confirm_password">

        <button type="submit" class="btn">Reset Password</button>
    </form>

    <div class="form-links">
        <p><a href="login.php">Back to Login</a></p>
    </div>
</div>

<?php include "includes/footer.php"; ?>
<script src="js/validate.js"></script>
</body>
</html>
