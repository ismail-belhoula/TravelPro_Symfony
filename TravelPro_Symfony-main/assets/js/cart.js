document.addEventListener('DOMContentLoaded', function() {
    // Quantity selector functionality
    document.querySelectorAll('.quantity-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const input = this.parentElement.querySelector('.quantity-input');
            const currentValue = parseInt(input.value);
            const maxStock = parseInt(input.dataset.maxStock);
            
            if (this.dataset.action === 'increase' && currentValue < maxStock) {
                input.value = currentValue + 1;
            } else if (this.dataset.action === 'decrease' && currentValue > 1) {
                input.value = currentValue - 1;
            }
        });
    });

    // Quantity input validation
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            let value = parseInt(this.value);
            const max = parseInt(this.dataset.maxStock);
            
            if (isNaN(value) || value < 1) value = 1;
            if (value > max) value = max;
            
            this.value = value;
        });
    });

    // Add to cart functionality
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            
            const productId = this.dataset.productId;
            const quantityInput = this.closest('.product-details').querySelector('.quantity-input');
            const quantity = parseInt(quantityInput.value);

            // Disable button during request
            this.disabled = true;

            try {
                const response = await fetch('/add-to-cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        productId: productId,
                        quantity: quantity
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    // Success animation
                    this.innerHTML = '<i class="fas fa-check me-2"></i>Ajouté!';
                    this.classList.remove('btn-primary');
                    this.classList.add('btn-success');
                    
                    // Optional: Show success message
                    if (typeof Toastify === 'function') {
                        Toastify({
                            text: "Produit ajouté au panier!",
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#28a745"
                        }).showToast();
                    } else {
                        alert('Produit ajouté au panier avec succès!');
                    }

                    // Reset button after 2 seconds
                    setTimeout(() => {
                        this.innerHTML = '<i class="fas fa-shopping-cart me-2"></i>Ajouter au panier';
                        this.classList.remove('btn-success');
                        this.classList.add('btn-primary');
                        this.disabled = false;
                    }, 2000);
                } else {
                    throw new Error(data.error || 'Erreur lors de l\'ajout au panier');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Une erreur est survenue lors de l\'ajout au panier');
                
                // Reset button on error
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-shopping-cart me-2"></i>Ajouter au panier';
                this.classList.remove('btn-success');
                this.classList.add('btn-primary');
            }
        });
    });
});
