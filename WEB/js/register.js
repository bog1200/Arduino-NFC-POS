function checkUsername() {

    let username = document.getElementById("username").value;
    let usernameChecker = document.getElementByClassName("username-checker");
    let usernameLengthError = document.getElementById("username-length-text");
    let usernameSpecialError = document.getElementById("username-special-text");
    if (username.length > 0) {
        for (const element of usernameChecker) {
            element.style.display = "block";
        }
    }
    if (username.length < 3) {
        usernameLengthError.style.color = "orange";
    }
    else {
        usernameLengthError.style.color = "lime";
    }
    if (username.search(/[^a-zA-Z0-9]/) < 0) {
        usernameSpecialError.style.color = "orange";
    }
    else {
        usernameSpecialError.style.color = "lime";
    }
}
function showPassword() {
    $("#password-checker").show();
}
function checkPassword() {
    let password = $("#password").val();
    let passwordLengthError = $("#password-length-text");
    let passwordUppercaseError = $("#password-uppercase-text");
    let passwordLowercaseError = $("#password-lowercase-text");
    let passwordNumberError = $("#password-number-text");
    let passwordSpecialError = $("#password-special-text");

    password.length < 8 ? passwordLengthError.css("color", "orange") : passwordLengthError.css("color", "lime");
    password.search(/[a-z]/) < 0 ? passwordLowercaseError.css("color", "orange") : passwordLowercaseError.css("color", "lime");
    password.search(/[A-Z]/) < 0 ? passwordUppercaseError.css("color", "orange") : passwordUppercaseError.css("color", "lime");
    password.search(/[0-9]/) < 0 ? passwordNumberError.css("color", "orange") : passwordNumberError.css("color", "lime");
    password.search(/[^a-zA-Z0-9]/) < 0 ? passwordSpecialError.css("color", "orange") : passwordSpecialError.css("color", "lime");


}
function hideUsername() {
    $(".username-checker").hide();
}
function checkPasswordConfirm() {
    let password = $("#password").val();
    let passwordConfirm = $("#confirm-password").val();
    let passwordConfirmChecker = $("#confirm-password-text");
    if (passwordConfirm !== password && passwordConfirm.length > 0) {
        passwordConfirmChecker.show();
    }
    else {
        passwordConfirmChecker.hide();
    }
}
function hidePasswordConfirm() {
    $("#password-confirm-text").hide();
}
$(document).ready(function () {
    $("#password").keyup(function () {
        $(".password-checker").show();
        checkPassword();
        checkPasswordConfirm();
    });
    // $("#password").change(checkPassword);
    $("#password").focusout(function () {
        $(".password-checker").hide();
    });
    $("#username").keyup(checkUsername);
    $("#username").keyup(checkUsername);
    //$("#username").focusout(hideUsername);
    $("#confirm-password").keyup(checkPasswordConfirm);

    $("login-button").click(function () {
        window.location.href("login.php");
    });
});



//# sourceMappingURL=register.js.map