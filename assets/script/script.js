// LOAD PRODUCTS
const grid = document.getElementById("products-grid");

function loadProducts(filter = "all") {
    grid.innerHTML = "";

    products
        .filter(p => filter === "all" || p.category === filter)
        .forEach(p => {
            grid.innerHTML += `
                <div class="product-card">
                    <img src="${p.img}">
                    <div class="product-info">
                        <h3 class="product-title">${p.name}</h3>
                        <p class="product-desc">${p.desc}</p>
                        <p class="product-price">₱${p.price}</p>
                        <button class="add-btn">+ Add</button>
                    </div>
                </div>
            `;
        });
}
loadProducts();

// FILTER BUTTONS
document.querySelectorAll(".filter-btn").forEach(btn => {
    btn.addEventListener("click", () => {
        document.querySelector(".filter-btn.active").classList.remove("active");
        btn.classList.add("active");
        loadProducts(btn.dataset.filter);
    });
});

// PROFILE SIDEBAR
const sidebar = document.getElementById("profile-sidebar");
document.getElementById("profile-toggle").onclick = () => {
    sidebar.classList.add("active");
};
document.querySelector(".close-profile").onclick = () => {
    sidebar.classList.remove("active");
};

// ADDRESS MODAL
const addrModal = document.getElementById("addressModal");
document.querySelector(".manage-address-btn").onclick = () => {
    addrModal.style.display = "block";
};
document.querySelector(".close-address-btn").onclick = () => {
    addrModal.style.display = "none";
};

// ADD TO CART (localStorage ready for PHP later)
let cartCount = 0;
document.addEventListener("click", e => {
    if(e.target.classList.contains("add-btn")) {
        cartCount++;
        document.getElementById("cart-count").innerText = cartCount;
    }
});
