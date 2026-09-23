<?php

require_once "includes/db-connect.php";

$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $comments = mysqli_real_escape_string($conn, $_POST["comments"]);
    $rating = (int) $_POST["rating"];

    $insert_sql = "INSERT INTO feedback (name, comments, rating) VALUES ('$name', '$comments', $rating)";

    if (mysqli_query($conn, $insert_sql)) {
        $success_message = "Thank you for your feedback!";
    } else {
        $success_message = "Something went wrong: " . mysqli_error($conn);
    }
}

// Most recent 5 feedback entries
$feedback_sql = "SELECT * FROM feedback ORDER BY created_at DESC LIMIT 5";
$feedback_result = mysqli_query($conn, $feedback_sql);

$page_title = "Feedback";
include "includes/header.php";
?>

<div class="form-box">
    <h2>Leave Feedback</h2>

    <?php if ($success_message != "") { ?>
        <div class="success-message"><?php echo $success_message; ?></div>
    <?php } ?>

    <form name="feedbackForm" method="post" action="feedback.php" onsubmit="return validateFeedbackForm()">
        <label for="name">Name</label>
        <input type="text" id="name" name="name">

        <label for="comments">Comments</label>
        <textarea id="comments" name="comments" rows="4"></textarea>

        <label for="rating">Rating</label>
        <select id="rating" name="rating">
            <option value="">Choose a rating</option>
            <option value="5">5 - Excellent</option>
            <option value="4">4 - Good</option>
            <option value="3">3 - Average</option>
            <option value="2">2 - Below Average</option>
            <option value="1">1 - Poor</option>
        </select>

        <button type="submit" class="btn">Submit Feedback</button>
    </form>
</div>

<h2>Recent Feedback</h2>
<?php while ($row = mysqli_fetch_assoc($feedback_result)) { ?>
    <div class="feedback-item">
        <span class="feedback-name"><?php echo htmlspecialchars($row["name"]); ?></span>
        - <span class="feedback-rating"><?php echo str_repeat("*", (int) $row["rating"]); ?></span>
        <p><?php echo htmlspecialchars($row["comments"]); ?></p>
    </div>
<?php } ?>

<?php include "includes/footer.php"; ?>
<script src="js/validate.js"></script>
</body>
</html>
