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
function updatePrice(input) {
    const quantity = input.value;
    const basePrice = input.closest('tr').querySelector('.price').getAttribute('data-base-price');
    const totalPrice = quantity * basePrice;
    input.closest('tr').querySelector('.price').textContent = 'P' + totalPrice;
}
function toggleRecipientFields() {
    const deliveryOption = document.querySelector('input[name="delivery_option"]:checked').value;
    const recipientFields = document.getElementById('recipientFields');
    if (deliveryOption === 'another') {
        recipientFields.style.display = 'block';
    } else {
        recipientFields.style.display = 'none';
    }
}
function showOrderSuccessAlert() {
    alert("Order is successful, please check your order information on Orders page. Thank you!");
}
function confirmCancelOrder(orderId) {
    if (confirm('Do you want to cancel your order?')) {
        window.location.href = 'cancel_order.php?order_id=' + orderId;
    }
}