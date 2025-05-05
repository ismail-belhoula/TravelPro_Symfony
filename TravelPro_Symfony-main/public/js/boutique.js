document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('productSearch');
    const priceSort = document.getElementById('priceSort');
    const productsContainer = document.getElementById('productsContainer');
    let searchTimeout;

    function updateProducts() {
        // Show loading state
        if (productsContainer) {
            productsContainer.style.opacity = '0.6';
        }

        // Build URL with parameters
        const params = new URLSearchParams({
            q: searchInput.value,
            sort: priceSort.value
        });

        // Make AJAX request
        fetch(`/boutique/search?${params.toString()}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (productsContainer) {
                    productsContainer.innerHTML = data.html;
                    productsContainer.style.opacity = '1';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (productsContainer) {
                    productsContainer.style.opacity = '1';
                }
            });
    }

    // Add event listeners
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(updateProducts, 300);
        });
    }

    if (priceSort) {
        priceSort.addEventListener('change', updateProducts);
    }
});