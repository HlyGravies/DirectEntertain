// Guarantee JavaScript only run after DOM structure finished loading
document.addEventListener("DOMContentLoaded", function () {
    // To show password for login and register page
    let passwordInput = document.getElementById("password");
    let togglePasswordBtn = document.getElementById("togglePassword");

    let registerPasswordInput = document.getElementById("rePassword");
    let toggleRePasswordBtn = document.getElementById("toggleRePassword");

    // let confirmPasswordInput = document.getElementById("confirm_password");
    // let toggleConfirmPasswordBtn = document.getElementById("toggleConfirmPassword");

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

    // To transform HTML content of Login.html into JSON
    document.querySelector('form').addEventListener('submit', function (event) {
        event.preventDefault(); // To prevent form for using default setting so that fetch can be use

        // Extract data from the form
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;


        // Send data under Json to LoginAuth.php
        fetch('../API/loginAuth.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                username: username,
                password: password
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.result === 'success') {
                    alert("login successful");
                    // Redirect
                    window.location.href = "../Front/home.php"
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error("Error", error);
            })
    })

    // To transform HTML of register.html content into JSON
    document.getElementById('registration-form').addEventListener('submit', function (event) {
        event.preventDefault();
        console.log("Form submitted");

        const username = document.getElementById("username").value;
        const email = document.getElementById("email").value;
        const password = document.getElementById("rePassword").value;

        // Send data to Register.php through fetch
        fetch("../API/register.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({username, email, password})
        })
            .then(response => response.json())
            .then(data => {
                if (data.result === "success") {
                    alert("Register successfully");
                    // Redirect
                    window.location.href = "../Front/login.html";
                } else {
                    alert(data.message || "Registration failed");
                }

            })
            .catch(error => {
                console.error("Error", error);
            })
    })



});
