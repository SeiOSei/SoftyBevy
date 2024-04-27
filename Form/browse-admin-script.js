document.addEventListener('DOMContentLoaded', function() {
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
});
var productadd;
document.addEventListener('DOMContentLoaded', function() {
    productadd = document.querySelector('.admin-product-form-container.hidden');
});
function toggleADDForm(){
    productadd.classList.toggle('visible');
};