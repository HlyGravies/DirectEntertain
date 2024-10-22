// Guarantee JavaScript only run after DOM structure finished loading
document.addEventListener("DOMContentLoaded", function () {
  // To show password for login and register page
  var passwordInput = document.getElementById("password");
  var togglePasswordBtn = document.getElementById("togglePassword");

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

  // To show confirm password content (Only in register page)
  var confirmPasswordInput = document.getElementById("confirm_password");
  var toggleConfirmPasswordBtn = document.getElementById("toggleConfirmPassword");

  if (confirmPasswordInput && toggleConfirmPasswordBtn) {
    toggleConfirmPasswordBtn.addEventListener("click", function () {
      if (confirmPasswordInput.type === "password") {
        confirmPasswordInput.type = "text";
        toggleConfirmPasswordBtn.textContent = "非表示";
      } else {
        confirmPasswordInput.type = "password";
        toggleConfirmPasswordBtn.textContent = "表示";
      }
    });

    // Confirm password before register form being sent
    document.getElementById("registrationi-form").addEventListener("submit", function (event) {
      if (passwordInput.value !== confirmPasswordInput) {
        document.getElementById("response").innerText = "Password incorrect";
        document.getElementById("repsonse").style.color = "red";
        event.preventDefault();
      }
    });
  }
});