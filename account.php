<?php

require_once "includes/db-connect.php";
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = (int) $_SESSION["user_id"];
$details_message = "";
$pet_message = "";
$booking_message = "";


$customer_sql = "SELECT * FROM customers WHERE user_id = $user_id";
$customer_result = mysqli_query($conn, $customer_sql);
$customer = mysqli_fetch_assoc($customer_result);
$customer_id = $customer["customer_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $form_action = $_POST["form_action"];


    if ($form_action == "update_details") {
        $full_name = mysqli_real_escape_string($conn, $_POST["full_name"]);
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $phone = mysqli_real_escape_string($conn, $_POST["phone"]);

        $update_sql = "UPDATE customers SET full_name = '$full_name', email = '$email', phone = '$phone' WHERE customer_id = $customer_id";

        if (mysqli_query($conn, $update_sql)) {
            $details_message = "Your details have been updated.";
            // refresh $customer so the form shows the new values
            $customer_result = mysqli_query($conn, "SELECT * FROM customers WHERE customer_id = $customer_id");
            $customer = mysqli_fetch_assoc($customer_result);
        } else {
            $details_message = "Something went wrong: " . mysqli_error($conn);
        }
    }


    if ($form_action == "add_pet") {
        $pet_name = mysqli_real_escape_string($conn, $_POST["pet_name"]);
        $breed = mysqli_real_escape_string($conn, $_POST["breed"]);

        $insert_pet_sql = "INSERT INTO pets (customer_id, pet_name, breed) VALUES ($customer_id, '$pet_name', '$breed')";

        if (mysqli_query($conn, $insert_pet_sql)) {
            $pet_message = "Pet added.";
        } else {
            $pet_message = "Something went wrong: " . mysqli_error($conn);
        }
    }


    if ($form_action == "book_appointment") {
        $service_id = (int) $_POST["service_id"];
        $pet_id = (int) $_POST["pet_id"];
        $booking_date = mysqli_real_escape_string($conn, $_POST["booking_date"]);
        $booking_time = mysqli_real_escape_string($conn, $_POST["booking_time"]);

        $insert_booking_sql = "INSERT INTO bookings (customer_id, service_id, pet_id, booking_date, booking_time) VALUES ($customer_id, $service_id, $pet_id, '$booking_date', '$booking_time')";

        if (mysqli_query($conn, $insert_booking_sql)) {
            $booking_message = "Your appointment has been booked.";
        } else {
            $booking_message = "Something went wrong: " . mysqli_error($conn);
        }
    }
}




$pets_sql = "SELECT * FROM pets WHERE customer_id = $customer_id";
$pets_result = mysqli_query($conn, $pets_sql);


$services_sql = "SELECT * FROM services";
$services_result = mysqli_query($conn, $services_sql);

$bookings_sql = "SELECT * FROM bookings WHERE customer_id = $customer_id ORDER BY booking_date";
$bookings_result = mysqli_query($conn, $bookings_sql);

$page_title = "My Account";
include "includes/header.php";
?>

<h1>My Account</h1>

<!-- 1. My Details -->
<div class="account-section">
    <h2>My Details</h2>

    <?php if ($details_message != "") { ?>
        <div class="success-message"><?php echo $details_message; ?></div>
    <?php } ?>

    <form name="detailsForm" method="post" action="account.php" onsubmit="return validateDetailsForm()">
        <input type="hidden" name="form_action" value="update_details">

        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($customer["full_name"]); ?>">

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($customer["email"]); ?>">

        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($customer["phone"] ?? ""); ?>">

        <button type="submit" class="btn">Save Details</button>
    </form>
</div>

<!-- 2. My Pets -->
<div class="account-section">
    <h2>My Pets</h2>

    <?php if ($pet_message != "") { ?>
        <div class="success-message"><?php echo $pet_message; ?></div>
    <?php } ?>

    <table class="simple-table">
        <tr><th>Pet Name</th><th>Breed</th></tr>
        <?php while ($pet_row = mysqli_fetch_assoc($pets_result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($pet_row["pet_name"]); ?></td>
                <td><?php echo htmlspecialchars($pet_row["breed"]); ?></td>
            </tr>
        <?php } ?>
    </table>

    <form name="petForm" method="post" action="account.php" onsubmit="return validatePetForm()">
        <input type="hidden" name="form_action" value="add_pet">

        <label for="pet_name">Pet Name</label>
        <input type="text" id="pet_name" name="pet_name">

        <label for="breed">Breed</label>
        <input type="text" id="breed" name="breed">

        <button type="submit" class="btn">Add Pet</button>
    </form>
</div>

<!-- 3. Book an Appointment -->
<div class="account-section booking">
    <h2>Book an Appointment</h2>

    <?php if ($booking_message != "") { ?>
        <div class="success-message"><?php echo $booking_message; ?></div>
    <?php } ?>

    <?php
        // Re-run the pets query since the first result set was already looped through above
        $pets_result_2 = mysqli_query($conn, $pets_sql);
        if (mysqli_num_rows($pets_result_2) == 0) {
    ?>
        <p>Add a pet above before booking an appointment.</p>
    <?php } else { ?>
        <form name="bookingForm" method="post" action="account.php" onsubmit="return validateBookingForm()">
            <input type="hidden" name="form_action" value="book_appointment">

            <label for="service_id">Service</label>
            <select id="service_id" name="service_id">
                <?php while ($service_row = mysqli_fetch_assoc($services_result)) { ?>
                    <option value="<?php echo $service_row["service_id"]; ?>">
                        <?php echo htmlspecialchars($service_row["service_name"]) . " - $" . $service_row["price"]; ?>
                    </option>
                <?php } ?>
            </select>

            <label for="pet_id">Pet</label>
            <select id="pet_id" name="pet_id">
                <?php while ($pet_row2 = mysqli_fetch_assoc($pets_result_2)) { ?>
                    <option value="<?php echo $pet_row2["pet_id"]; ?>"><?php echo htmlspecialchars($pet_row2["pet_name"]); ?></option>
                <?php } ?>
            </select>

            <label for="booking_date">Date</label>
            <input type="date" id="booking_date" name="booking_date">

            <label for="booking_time">Time</label>
            <input type="time" id="booking_time" name="booking_time">

            <button type="submit" class="btn">Book Appointment</button>
        </form>
    <?php } ?>

    <h3>My Upcoming Appointments</h3>
    <table class="simple-table">
        <tr><th>Date</th><th>Time</th><th>Service</th><th>Status</th></tr>
        <?php while ($booking_row = mysqli_fetch_assoc($bookings_result)) {
            // Small second query to get the service name - kept simple, no JOIN
            $service_lookup_sql = "SELECT service_name FROM services WHERE service_id = " . (int) $booking_row["service_id"];
            $service_lookup_result = mysqli_query($conn, $service_lookup_sql);
            $service_lookup_row = mysqli_fetch_assoc($service_lookup_result);
        ?>
            <tr>
                <td><?php echo htmlspecialchars($booking_row["booking_date"]); ?></td>
                <td><?php echo htmlspecialchars($booking_row["booking_time"]); ?></td>
                <td><?php echo htmlspecialchars($service_lookup_row["service_name"]); ?></td>
                <td><?php echo htmlspecialchars($booking_row["status"]); ?></td>
            </tr>
        <?php } ?>
    </table>
</div>

<?php include "includes/footer.php"; ?>
<script src="js/validate.js"></script>
</body>
</html>
