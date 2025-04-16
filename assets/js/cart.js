document.addEventListener('DOMContentLoaded', function() {
    // Initialize cart from localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    updateCartCount();

    // Add to cart click handler
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            const productPrice = parseFloat(this.dataset.productPrice);

            // Add item to cart
            cart.push({
                id: productId,
                name: productName,
                price: productPrice,
                quantity: 1
            });

            // Save to localStorage
            localStorage.setItem('cart', JSON.stringify(cart));
            
            // Update cart count
            updateCartCount();

            // Show success message
            alert('Produit ajouté au panier!');
        });
    });

    function updateCartCount() {
        const count = cart.length;
        document.querySelector('.cart-count').textContent = count;
    }
});