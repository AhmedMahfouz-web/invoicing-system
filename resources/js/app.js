import './bootstrap';
import Alpine from 'alpinejs';
import jQuery from 'jquery';
import Toastify from 'toastify-js';
import 'toastify-js/src/toastify.css';
import Chart from 'chart.js/auto'; // Use 'chart.js/auto' to automatically register all charts and controllers

window.Alpine = Alpine;
window.$ = window.jQuery = jQuery;

Alpine.start();

// Define showToast globally
window.showToast = function(message) {
    Toastify({
        text: message,
        duration: 3000, // Duration in milliseconds
        gravity: "top", // `top` or `bottom`
        position: 'right', // `left`, `center` or `right`
        backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
        stopOnFocus: true, // Prevents dismissing of toast on hover
    }).showToast();
}

// Invoice functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');

    if (document.getElementById('add-item')) {
        console.log('Invoice form detected');

        let itemCount = 0;
        const itemTemplate = document.querySelector('.invoice-item');

        // Add new item row
        document.getElementById('add-item').addEventListener('click', function() {
            console.log('Add item clicked');
            itemCount++;

            // Clone the template
            let newItem = itemTemplate.cloneNode(true);

            // Update names
            newItem.querySelectorAll('[name]').forEach(function(element) {
                let oldName = element.getAttribute('name');
                let newName = oldName.replace('[0]', '[' + itemCount + ']');
                element.setAttribute('name', newName);
            });

            // Clear values
            newItem.querySelectorAll('select').forEach(el => el.value = '');
            newItem.querySelectorAll('input[type="number"]').forEach(el => el.value = '');
            newItem.querySelector('.quantity').value = 1;

            // Add to form
            document.getElementById('invoice-items').appendChild(newItem);
            console.log('New item added');

            // Reattach event listeners
            attachEventListeners(newItem);
        });

        // Event listener for removing items
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
                console.log('Remove clicked');
                if (document.querySelectorAll('.invoice-item').length > 1) {
                    e.target.closest('.invoice-item').remove();
                    calculateTotals();
                }
            }
        });

        // Attach event listeners to a row
        function attachEventListeners(row) {
            // Product selection
            row.querySelector('.product-select').addEventListener('change', function() {
                console.log('Product selected');
                let price = this.options[this.selectedIndex].dataset.price || 0;
                row.querySelector('.unit-price').value = parseFloat(price).toFixed(2);
                calculateTotals();
            });

            // Quantity change
            row.querySelector('.quantity').addEventListener('input', function() {
                console.log('Quantity changed');
                calculateTotals();
            });
        }

        // Calculate totals
        function calculateTotals() {
            console.log('Calculating totals');
            let subtotal = 0;

            document.querySelectorAll('.invoice-item').forEach(function(item) {
                let quantity = parseFloat(item.querySelector('.quantity').value) || 0;
                let price = parseFloat(item.querySelector('.unit-price').value) || 0;
                let total = quantity * price;
                item.querySelector('.item-total').value = total.toFixed(2);
                subtotal += total;
            });

            let taxRate = 14 / 100; // 14% tax rate
            let tax = subtotal * taxRate;
            let total = subtotal + tax;

            document.getElementById('subtotal').textContent = subtotal.toFixed(2) + ' ج.م';
            document.getElementById('tax').textContent = tax.toFixed(2) + ' ج.م';
            document.getElementById('total').textContent = total.toFixed(2) + ' ج.م';

            document.getElementById('subtotal-input').value = subtotal.toFixed(2);
            document.getElementById('tax-input').value = tax.toFixed(2);
            document.getElementById('total-input').value = total.toFixed(2);
        }

        // Initialize event listeners for initial row
        document.querySelectorAll('.invoice-item').forEach(attachEventListeners);
    }
});
