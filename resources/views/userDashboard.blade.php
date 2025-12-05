@extends('app')

@section('title', 'User Dashboard - Balagbal Store')

@section('css')
    @vite(['resources/css/user-dashboard.css'])
@endsection

@section('content')
    <div class="user-dashboard">
        <!-- User Stats Section -->
        <section class="stats-section">
            <div class="stats-header">
                <h1>Dashboard</h1>
                <div class="header-actions">
                    <button class="btn-profile" onclick="openProfileModal()">👤 Profile</button>

                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-logout">🚪 Logout</button>
                    </form>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card card-blue">
                    <div class="card-content">
                        <p class="card-label">Bought Items</p>
                        <p class="card-value">24</p>
                    </div>
                </div>

                <div class="stat-card card-green">
                    <div class="card-content">
                        <p class="card-label">Most Expensive Item</p>
                        <p class="card-value">$399.99</p>
                    </div>
                </div>

                <div class="stat-card card-orange">
                    <div class="card-content">
                        <p class="card-label">Total Expense</p>
                        <p class="card-value">$5,420.00</p>
                    </div>
                </div>

                <div class="stat-card card-purple">
                    <div class="card-content">
                        <p class="card-label">Money Amount</p>
                        <p class="card-value">$2,150.50</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Shop Section -->
        <section class="shop-section">
            <div class="shop-header">
                <h2>Shop Products</h2>
                <nav class="product-nav">
                    <button class="nav-btn active" onclick="filterProducts('all')">All Products</button>
                    @foreach ($categories as $category)
                        <button class="nav-btn"
                                onclick="filterProducts('{{ $category->name }}')">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </nav>
            </div>

            <div class="products-grid">
                @forelse ($products as $product)
                    <div class="product-card" data-category="{{ $product->category->name }}">
                        <div class="product-image">📷 {{ $product->name }}</div>

                        <div class="product-info">
                            <h3>{{ $product->name }}</h3>
                            <p class="product-desc">{{ $product->description }}</p>
                            <p class="product-price">${{ number_format($product->unit_price, 2) }}</p>

                            <button class="btn-add-cart"
                                    onclick="addToCart('{{ $product->name }}', {{ $product->unit_price }})">
                                🛒 Add to Cart
                            </button>
                        </div>
                    </div>
                @empty
                    <p>No products available right now.</p>
                @endforelse
            </div>

        </section>
    </div>

    <!-- Buy Button (Fixed Bottom Right) -->
    <button class="btn-buy-fixed" onclick="openCartModal()">🛒 Buy</button>

    <!-- Profile Modal -->
    <div id="profileModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('profileModal')">&times;</span>
            <h2>User Profile</h2>
            <form class="profile-form" action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username"
                            value="{{ auth()->user()->username }}" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input 
                            type="email" 
                            id="email"
                            name="email"
                            value="{{ auth()->user()->email }}" 
                            required
                        >
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input 
                            type="text" 
                            id="firstname"
                            name="firstname"
                            value="{{ $user->customer->first_name }}" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input 
                            type="text" 
                            id="lastname"
                            name="lastname"
                            value="{{ $user->customer->last_name }}" 
                            required
                        >
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input 
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Leave blank to keep current password"
                        >
                    </div>

                    <div class="form-group">
                        <label for="birthdate">Birth Date</label>
                        <input 
                            type="date"
                            id="birthdate"
                            name="birthdate"
                            value="{{ $user->customer->date_of_birth }}">
                        >
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input 
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ $user->customer->phone_number }}"
                        >
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <input 
                            type="text"
                            id="address"
                            name="address"
                            value="{{ $user->customer->address }}"
                        >
                    </div>
                </div>

                <div class="modal-buttons">
                    <button type="submit" class="btn-save">Save Changes</button>
                    <button type="button" class="btn-close" onclick="closeModal('profileModal')">Close</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Cart Modal -->
    <div id="cartModal" class="modal">
        <div class="modal-content cart-modal">
            <span class="close" onclick="closeModal('cartModal')">&times;</span>
            <h2>Shopping Cart</h2>
            
            <div class="cart-content">
                <div class="cart-items">
                    <h3>Items</h3>
                    <ul id="cartItemsList" class="items-list">
                        <!-- Items will be added here dynamically -->
                    </ul>
                </div>

                <div class="cart-summary">
                    <div class="summary-item">
                        <span>Subtotal:</span>
                        <span id="subtotal">$0.00</span>
                    </div>
                    <div class="summary-item">
                        <span>Tax (10%):</span>
                        <span id="tax">$0.00</span>
                    </div>
                    <div class="summary-total">
                        <span>Total:</span>
                        <span id="total">$0.00</span>
                    </div>

                    <div class="modal-buttons">
                        <button type="button" class="btn-confirm" onclick="confirmBuy()">Confirm Buy</button>
                        <button type="button" class="btn-close" onclick="closeModal('cartModal')">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('js')
        <script>
            // User Dashboard JavaScript

            // Cart management
            let cartItems = [];

            // Open Profile Modal
            function openProfileModal() {
                document.getElementById("profileModal").style.display = "block";
            }

            // Open Cart Modal
            function openCartModal() {
                if (cartItems.length === 0) {
                    alert("Your cart is empty!");
                    return;
                }
                displayCartItems();
                document.getElementById("cartModal").style.display = "block";
            }

            // Close Modal
            function closeModal(modalId) {
                document.getElementById(modalId).style.display = "none";
            }

            // Add to cart
            function addToCart(productName, price) {
                const item = {
                    id: Date.now(),
                    name: productName,
                    price: price,
                };
                cartItems.push(item);
                alert(`${productName} added to cart!`);
                updateCartButton();
            }

            // Display cart items
            function displayCartItems() {
                const cartItemsList = document.getElementById("cartItemsList");
                cartItemsList.innerHTML = "";

                if (cartItems.length === 0) {
                    cartItemsList.innerHTML =
                        '<li style="padding: 20px; text-align: center; color: #7f8c8d;">No items in cart</li>';
                } else {
                    cartItems.forEach((item, index) => {
                        const li = document.createElement("li");
                        li.innerHTML = `
                            <span>
                                <span class="item-name">${item.name}</span><br/>
                                <span class="item-price">$${item.price.toFixed(2)}</span>
                            </span>
                            <button class="remove-item" onclick="removeFromCart(${index})">✕</button>
                        `;
                        cartItemsList.appendChild(li);
                    });
                }

                updateCartTotals();
            }

            // Remove from cart
            function removeFromCart(index) {
                cartItems.splice(index, 1);
                displayCartItems();
            }

            // Update cart totals
            function updateCartTotals() {
                const subtotal = cartItems.reduce((sum, item) => sum + item.price, 0);
                const tax = subtotal * 0.1;
                const total = subtotal + tax;

                document.getElementById("subtotal").textContent = `$${subtotal.toFixed(2)}`;
                document.getElementById("tax").textContent = `$${tax.toFixed(2)}`;
                document.getElementById("total").textContent = `$${total.toFixed(2)}`;
            }

            // Update cart button visibility/text
            function updateCartButton() {
                const cartBtn = document.querySelector(".btn-buy-fixed");
                if (cartItems.length > 0) {
                    cartBtn.textContent = `🛒 Buy (${cartItems.length})`;
                }
            }

            // Confirm purchase
            function confirmBuy() {
                if (cartItems.length === 0) {
                    alert("Cart is empty!");
                    return;
                }

                fetch("/orders/confirm", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    body: JSON.stringify({
                        items: cartItems
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert("Order placed successfully!");
                        cartItems = [];
                        updateCartButton();
                        closeModal("cartModal");
                    } else {
                        alert("Something went wrong: " + data.message);
                    }
                })
                .catch(err => {
                    alert("Error: " + err);
                });
            }

            // Filter products
            function filterProducts(category) {
                // highlight active button
                document.querySelectorAll(".nav-btn").forEach(btn => btn.classList.remove("active"));
                event.target.classList.add("active");

                const cards = document.querySelectorAll(".product-card");

                cards.forEach(card => {
                    const cardCategory = card.dataset.category;

                    if (category === "all" || cardCategory === category) {
                        card.style.display = "block";
                    } else {
                        card.style.display = "none";
                    }
                });
            }


            // Save profile changes
            document.addEventListener("DOMContentLoaded", function () {
                const profileForm = document.querySelector(".profile-form");
                if (profileForm) {
                    profileForm.addEventListener("submit", function (e) {
                        // e.preventDefault();
                        // alert("Profile updated successfully!");
                        // closeModal("profileModal");
                    });
                }

                // Close modal when clicking outside
                window.onclick = function (event) {
                    if (event.target.classList.contains("modal")) {
                        event.target.style.display = "none";
                    }
                };
            });
        </script>
    @endsection
@endsection
