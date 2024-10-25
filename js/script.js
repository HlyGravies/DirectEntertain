// Guarantee JavaScript only run after DOM structure finished loading
document.addEventListener("DOMContentLoaded", function () {
    // To show password for login and register page
    let passwordInput = document.getElementById("password");
    let togglePasswordBtn = document.getElementById("togglePassword");

    let registerPasswordInput = document.getElementById("rePassword");
    let toggleRePasswordBtn = document.getElementById("toggleRePassword");

    let confirmPasswordInput = document.getElementById("confirm_password");
    let toggleConfirmPasswordBtn = document.getElementById("toggleConfirmPassword");

    if (togglePasswordBtn) {
        togglePasswordBtn.addEventListener("click", function () {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                togglePasswordBtn.textContent = "非表示";
            } else {
                passwordInput.type = "password";
                togglePasswordBtn.textContent = "表示";
            }
        });
    }

    // To show/hidden password in register page
    if (toggleRePasswordBtn) {
        toggleRePasswordBtn.addEventListener("click", function () {
            if (registerPasswordInput.type === "password") {
                registerPasswordInput.type = "text";
                toggleRePasswordBtn.textContent = "非表示";
            } else {
                registerPasswordInput.type = "password";
                toggleRePasswordBtn.textContent = "表示";
            }
        });
    }

    // To show confirm password content (Only in register page)
    // if (confirmPasswordInput && toggleConfirmPasswordBtn) {
    //     toggleConfirmPasswordBtn.addEventListener("click", function () {
    //         if (confirmPasswordInput.type === "password") {
    //             confirmPasswordInput.type = "text";
    //             toggleConfirmPasswordBtn.textContent = "非表示";
    //         } else {
    //             confirmPasswordInput.type = "password";
    //             toggleConfirmPasswordBtn.textContent = "表示";
    //         }
    //     });
    //
    //     // Confirm password before register form being sent
    //     document.getElementById("registration-form").addEventListener("submit", function (event) {
    //         if (registerPassword.value !== confirmPasswordInput) {
    //             document.getElementById("response").innerText = "Password incorrect";
    //             document.getElementById("response").style.color = "red";
    //             event.preventDefault();
    //         }
    //     });
    // }
});