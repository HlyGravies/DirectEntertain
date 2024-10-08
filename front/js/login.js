//To show password
document.getElementById("btn_view").addEventListener("click", function(event){
  event.preventDefault();
  var passwordInput = document.getElementById("pass");
  if (passwordInput.type === "password"){
    passwordInput.type = "text";
    this.textContent = "非表示";
  } else {
    passwordInput.type = "password";
        this.textContent = "表示"; 
  }
})

// To confirm the password
document.getElementById("registration-form").addEventListener("submit", function(event){
  var password = document.getElementById("password").ariaValueMax;
  var confirmPassword = document.getElementById("confirm_password").ariaValueMax;

  if (password !== confirmPassword){
    document.getElementById("response").innerText = "Incorrect";
    document.getElementById("response").style.color = "red";
  }
})