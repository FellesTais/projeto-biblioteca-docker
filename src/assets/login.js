let signInBtn = document.querySelector("#sign_in_btn");
let signUpBtn = document.querySelector("#sign_up_btn");
let signInForm = document.querySelector(".sign_in_form");
let body = document.querySelector("body");

signUpBtn.onclick = function () {
  body.classList.add("form_slide");
  if (body.classList.contains("form_slide")) {
    body.style.backgroundColor = '#3E2723';
  }
}

signInBtn.onclick = function () {
  body.classList.remove("form_slide");
  body.style.backgroundColor = '#003C47';
}
document.addEventListener('DOMContentLoaded', function () {
  verificarNotificacoes();
});