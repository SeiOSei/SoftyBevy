function toggleForms() {
    var loginForm = document.querySelector('.form-container.login');
    var registrationForm = document.querySelector('.form-container.registration');

    loginForm.classList.toggle('hidden');
    loginForm.classList.toggle('visible');
    
    registrationForm.classList.toggle('hidden');
    registrationForm.classList.toggle('visible');
}
function togglePlaceholder(inputId){
      var input = document.getElementById(inputId);
      input.classList.toggle('placeholder-visible');
}
const emailElement = document.getElementById('email');
emailElement.addEventListener('focus', function() {
    this.placeholder = '';
});
emailElement.addEventListener('blur', function() {
    if (this.value ==='') {
        this.placeholder = 'Email';
    }
});
const passwordElement = document.getElementById('password');
passwordElement.addEventListener('focus', function() {
    this.placeholder = '';
});
passwordElement.addEventListener('blur', function() {
    if (this.value ==='') {
        this.placeholder = 'Password';
    }
});
const FnameElement = document.getElementById('firstname');
FnameElement.addEventListener('focus', function() {
    this.placeholder = '';
});
FnameElement.addEventListener('blur', function() {
    if (this.value ==='') {
        this.placeholder = 'First Name';
    }
});
const LnameElement = document.getElementById('lastname');
LnameElement.addEventListener('focus', function() {
    this.placeholder = '';
});
LnameElement.addEventListener('blur', function() {
    if (this.value ==='') {
        this.placeholder = 'Last Name';
    }
});
const emailrgElement = document.getElementById('email-register');
emailrgElement.addEventListener('focus', function() {
    this.placeholder = '';
});
emailrgElement.addEventListener('blur', function() {
    if (this.value ==='') {
        this.placeholder = 'Email';
    }
});
const passwordrgElement = document.getElementById('password-register');
passwordrgElement.addEventListener('focus', function() {
    this.placeholder = '';
});
passwordrgElement.addEventListener('blur', function() {
    if (this.value ==='') {
        this.placeholder = 'Password';
    }
});
document.addEventListener('DOMContentLoaded', function() {
    var inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
    
    inputs.forEach(function(input) {
        input.addEventListener('input', function() {
            var label = input.nextElementSibling;
            label.style.visibility = 'visible';
        });
    });
});
