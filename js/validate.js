function validateLoginForm() {
    var username = document.loginForm.username.value;
    var password = document.loginForm.password.value;

    if (username == "" || password == "") {
        alert("Please enter both your username and password.");
        return false;
    }
    return true;
}

function validateRegisterForm() {
    var fullName = document.registerForm.full_name.value;
    var email = document.registerForm.email.value;
    var username = document.registerForm.username.value;
    var password = document.registerForm.password.value;
    var confirmPassword = document.registerForm.confirm_password.value;

    if (fullName == "" || email == "" || username == "" || password == "") {
        alert("Please fill in every field before registering.");
        return false;
    }

    // Very simple email check - just looks for an "@" and a "."
    if (email.indexOf("@") == -1 || email.indexOf(".") == -1) {
        alert("Please enter a valid email address.");
        return false;
    }

    if (password.length < 6) {
        alert("Your password should be at least 6 characters long.");
        return false;
    }

    if (password != confirmPassword) {
        alert("Your passwords do not match. Please try again.");
        return false;
    }

    return true;
}

function validateResetForm() {
    var username = document.resetForm.username.value;
    var email = document.resetForm.email.value;
    var newPassword = document.resetForm.new_password.value;
    var confirmPassword = document.resetForm.confirm_password.value;

    if (username == "" || email == "" || newPassword == "" || confirmPassword == "") {
        alert("Please fill in every field.");
        return false;
    }

    if (newPassword != confirmPassword) {
        alert("Your new passwords do not match.");
        return false;
    }

    return true;
}

function validateDetailsForm() {
    var fullName = document.detailsForm.full_name.value;
    var email = document.detailsForm.email.value;

    if (fullName == "" || email == "") {
        alert("Name and email cannot be left empty.");
        return false;
    }
    return true;
}

function validatePetForm() {
    var petName = document.petForm.pet_name.value;

    if (petName == "") {
        alert("Please enter your pet's name.");
        return false;
    }
    return true;
}

function validateBookingForm() {
    var serviceId = document.bookingForm.service_id.value;
    var bookingDate = document.bookingForm.booking_date.value;
    var bookingTime = document.bookingForm.booking_time.value;

    if (serviceId == "" || bookingDate == "" || bookingTime == "") {
        alert("Please choose a service, date and time for your appointment.");
        return false;
    }
    return true;
}

function validateFeedbackForm() {
    var name = document.feedbackForm.name.value;
    var comments = document.feedbackForm.comments.value;
    var rating = document.feedbackForm.rating.value;

    if (name == "" || comments == "") {
        alert("Please enter your name and a comment before submitting.");
        return false;
    }

    if (rating == "") {
        alert("Please choose a rating from 1 to 5.");
        return false;
    }

    return true;
}


function filterServices() {
    var input = document.getElementById("serviceSearch").value;
    var keyword = input.toLowerCase();
    var cards = document.getElementsByClassName("card");
    var i;

    for (i = 0; i < cards.length; i++) {
        var heading = cards[i].getElementsByTagName("h3")[0].innerHTML;
        var headingText = heading.toLowerCase();

        if (headingText.indexOf(keyword) > -1) {
            cards[i].style.display = "";
        } else {
            cards[i].style.display = "none";
        }
    }
}
