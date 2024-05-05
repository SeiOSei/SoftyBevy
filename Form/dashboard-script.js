document.addEventListener('DOMContentLoaded', () => {
    const updateButtons = document.querySelectorAll('.update-btn');

    updateButtons.forEach(button => {
        button.addEventListener('click', () => {
            const row = button.closest('tr');
            const quantityInput = row.querySelector('.quantity-input');
            const priceElement = row.querySelector('.price');
            const productId = quantityInput.dataset.id;
            const newQuantity = parseInt(quantityInput.value);
            const pricePerItem = parseFloat(priceElement.innerText.replace('P', ''));
            const totalPrice = newQuantity * pricePerItem;
            priceElement.innerText = 'P' + totalPrice.toFixed(2);
            // You can update the total price in the database here if needed
        });
    });
});
