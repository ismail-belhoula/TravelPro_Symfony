// Product search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('productSearch');
    
    if (searchInput) {
        // Initial setup - show all products
        const products = document.querySelectorAll('.product');
        products.forEach(product => {
            product.style.display = '';
            product.style.opacity = '1';
            product.style.transform = 'scale(1)';
        });

        // Search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            products.forEach(product => {
                const productTitle = product.querySelector('.product-title');
                if (!productTitle) return;
                
                const title = productTitle.textContent.toLowerCase();
                const matches = title.includes(searchTerm);
                
                // Set initial state before animation
                if (matches) {
                    product.style.display = '';
                    setTimeout(() => {
                        product.style.opacity = '1';
                        product.style.transform = 'scale(1)';
                    }, 10);
                } else {
                    product.style.opacity = '0';
                    product.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        product.style.display = 'none';
                    }, 300); // Match this with CSS transition duration
                }
            });
        });
    }
});

