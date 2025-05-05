// Product search and sort functionality
document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("productSearch");
  const priceSort = document.getElementById("priceSort");
  const productsContainer = document.getElementById("productsContainer");
  let searchTimeout;

  function updateProducts() {
    const searchTerm = searchInput.value;
    const sortValue = priceSort.value;

    // Show loading state
    if (productsContainer) {
      productsContainer.style.opacity = "0.6";
    }

    // Build URL with parameters
    const params = new URLSearchParams({
      q: searchTerm,
      sort: sortValue,
    });

    // Make AJAX request
    fetch(`/boutique/search?${params.toString()}`)
      .then((response) => response.json())
      .then((data) => {
        if (productsContainer) {
          productsContainer.innerHTML = data.html;
          productsContainer.style.opacity = "1";

          // Reinitialize quantity controls and cart buttons
          initializeQuantityControls();
          initializeCartButtons();
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        if (productsContainer) {
          productsContainer.style.opacity = "1";
        }
      });
  }

  // Initialize quantity controls
  function initializeQuantityControls() {
    document.querySelectorAll(".quantity-btn").forEach((button) => {
      button.addEventListener("click", function (e) {
        e.preventDefault();
        const input = this.parentElement.querySelector(".quantity-input");
        const currentValue = parseInt(input.value);
        const maxStock = parseInt(input.dataset.maxStock);

        if (this.dataset.action === "increase" && currentValue < maxStock) {
          input.value = currentValue + 1;
        } else if (this.dataset.action === "decrease" && currentValue > 1) {
          input.value = currentValue - 1;
        }
      });
    });

    document.querySelectorAll(".quantity-input").forEach((input) => {
      input.addEventListener("change", function () {
        let value = parseInt(this.value);
        const max = parseInt(this.dataset.maxStock);

        if (isNaN(value) || value < 1) value = 1;
        if (value > max) value = max;

        this.value = value;
      });
    });
  }

  // Initialize cart buttons
  function initializeCartButtons() {
    document.querySelectorAll(".add-to-cart").forEach((button) => {
      button.addEventListener("click", function (e) {
        // Your existing cart functionality
      });
    });
  }

  // Debounced search
  if (searchInput) {
    searchInput.addEventListener("input", function () {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(updateProducts, 300);
    });
  }

  // Price sorting
  if (priceSort) {
    priceSort.addEventListener("change", updateProducts);
  }
});
