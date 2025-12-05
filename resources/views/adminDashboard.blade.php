@extends('app')

@section('title', 'Admin Dashboard - Balagbal Store')

@section('css')
    @vite(['resources/css/admin-dashboard.css'])
    {{-- <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}"> --}}
@endsection

@section('content')
    <div class="admin-dashboard">
        <!-- Admin Sidebar Navigation -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2>Admin Panel</h2>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="#" class="nav-link active" data-section="users">👥 User Lists</a></li>
                    <li><a href="#" class="nav-link" data-section="products">📦 Product Lists</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="admin-content">
            <!-- Users Section -->
            <section id="users" class="content-section active">
                <div class="section-header">
                    <h1>User Management</h1>
                    <p>Manage all registered users</p>
                </div>

                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                                <tr>
                                    <td>{{ $customer->id }}</td>
                                    <td>{{ $customer->user->username ?? 'N/A' }}</td>
                                    <td>{{ $customer->first_name }}</td>
                                    <td>{{ $customer->last_name }}</td>
                                    <td>{{ $customer->user->email ?? 'N/A' }}</td>
                                    <td>{{ $customer->phone_number ?? 'N/A' }}</td>
                                    <td>
                                        <button class="btn-action btn-edit" onclick="openEditModal({{ $customer->id }}, '{{ $customer->first_name }}', '{{ $customer->last_name }}', '{{ $customer->username }}', '{{ $customer->email }}')">✏️ Edit</button>
                                        <button class="btn-action btn-history" onclick="openHistoryModal({{ $customer->id }}, '{{ $customer->first_name }} {{ $customer->last_name }}', {{ $customer->orders->count() }}, {{ $customer->orders->sum('total_amount') }})">📊 History</button>
                                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" onclick="return confirm('Delete this customer?')">🗑️ Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 20px;">No customers found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Products Section -->
            <section id="products" class="content-section">
                <div class="section-header">
                    <h1>Product Management</h1>
                    <button class="btn-primary" onclick="openAddProductModal()">➕ Add Product</button>
                </div>

                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ Str::limit($product->description, 50) }}</td>
                                    <td>${{ number_format($product->unit_price, 2) }}</td>
                                    <td><span class="badge-image">📷</span></td>
                                    <td>
                                        <button class="btn-action btn-edit" onclick="openEditProductModal({{ $product->id }}, '{{ $product->name }}', '{{ $product->description }}', {{ $product->unit_price }}, {{ $product->cost_price }}, {{ $product->category_id }})">✏️ Edit</button>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" onclick="return confirm('Delete this product?')">🗑️ Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 20px;">No products found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editUserModal')">&times;</span>
            <h2>Edit Customer</h2>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="editFirstName">First Name</label>
                    <input type="text" id="editFirstName" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="editLastName">Last Name</label>
                    <input type="text" id="editLastName" name="last_name" required>
                </div>
                <div class="form-group">
                    <label for="editUsername">Username</label>
                    <input type="text" id="editUsername" name="username" required>
                </div>
                <div class="form-group">
                    <label for="editEmail">Email</label>
                    <input type="email" id="editEmail" name="email" required>
                </div>
                <div class="modal-buttons">
                    <button type="submit" class="btn-submit">Save Changes</button>
                    <button type="button" class="btn-cancel" onclick="closeModal('editUserModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- History Modal -->
    <div id="historyModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('historyModal')">&times;</span>
            <h2>Purchase History</h2>
            <div class="history-info">
                <p><strong>Customer Name:</strong> <span id="historyName"></span></p>
                <p><strong>Total Purchases:</strong> <span id="historyPurchases"></span></p>
                <p><strong>Total Spent:</strong> $<span id="historyTotal"></span></p>
            </div>
            <h3>Bought Items</h3>
            <ul id="historyItems" class="history-items">
                <li>Wireless Headphones - $79.99</li>
                <li>USB-C Cable - $12.99</li>
                <li>Mechanical Keyboard - $149.99</li>
            </ul>
            <div class="modal-buttons">
                <button type="button" class="btn-cancel" onclick="closeModal('historyModal')">Close</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('deleteModal')">&times;</span>
            <h2>Confirm Delete</h2>
            <p>Are you sure you want to delete this <span id="deleteType"></span>? This action cannot be undone.</p>
            <div class="modal-buttons">
                <button type="button" class="btn-danger" id="confirmDeleteBtn">Delete</button>
                <button type="button" class="btn-cancel" onclick="closeModal('deleteModal')">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Add/Edit Product Modal -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('productModal')">&times;</span>
            <h2 id="productModalTitle">Add Product</h2>
            <form id="productForm" method="POST" action="{{ route('products.store') }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="productName">Product Name</label>
                    <input type="text" id="productName" name="name" placeholder="Enter product name" required>
                </div>
                <div class="form-group">
                    <label for="productDescription">Description</label>
                    <textarea id="productDescription" name="description" rows="4" placeholder="Enter product description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="productCategory">Category</label>
                    <select id="productCategory" name="category_id" required>
                        <option value="">Select a category</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="productCostPrice">Cost Price ($)</label>
                    <input type="number" id="productCostPrice" name="cost_price" step="0.01" placeholder="Enter cost price" required>
                </div>
                <div class="form-group">
                    <label for="productPrice">Unit Price ($)</label>
                    <input type="number" id="productPrice" name="unit_price" step="0.01" placeholder="Enter selling price" required>
                </div>
                <div class="modal-buttons">
                    <button type="submit" class="btn-submit" id="productSubmitBtn">Add Product</button>
                    <button type="button" class="btn-cancel" onclick="closeModal('productModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    @section('js')
        <script>
            // Navigation switching
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const section = this.getAttribute('data-section');
                    
                    // Hide all sections
                    document.querySelectorAll('.content-section').forEach(s => {
                        s.classList.remove('active');
                    });
                    
                    // Remove active class from all links
                    document.querySelectorAll('.nav-link').forEach(l => {
                        l.classList.remove('active');
                    });
                    
                    // Show selected section
                    document.getElementById(section).classList.add('active');
                    this.classList.add('active');
                });
            });

            // Modal functions
            function openModal(modalId) {
                document.getElementById(modalId).style.display = 'block';
            }

            function closeModal(modalId) {
                document.getElementById(modalId).style.display = 'none';
            }

            function openEditModal(id, firstName, lastName, username, email) {
                document.getElementById('editFirstName').value = firstName;
                document.getElementById('editLastName').value = lastName;
                document.getElementById('editUsername').value = username;
                document.getElementById('editEmail').value = email;
                
                // Set form action to update route with the customer ID
                const form = document.getElementById('editUserForm');
                form.action = `/customers/${id}`;
                
                openModal('editUserModal');
            }

            function openHistoryModal(id, name, purchases, total) {
                document.getElementById('historyName').textContent = name;
                document.getElementById('historyPurchases').textContent = purchases;
                document.getElementById('historyTotal').textContent = total.toFixed(2);

                // Load REAL items via AJAX
                fetch(`/customers/${id}/history`)
                    .then(res => res.json())
                    .then(data => {
                        const historyList = document.getElementById("historyItems");
                        historyList.innerHTML = "";

                        if (data.orders.length === 0) {
                            historyList.innerHTML = "<li>No past purchases.</li>";
                            return;
                        }

                        data.orders.forEach(order => {
                            order.order_details.forEach(detail => {
                                const li = document.createElement("li");
                                li.textContent = `${detail.product.name} - $${detail.unit_price}`;
                                historyList.appendChild(li);
                            });
                        });
                    });

                openModal('historyModal');
            }

            function openAddProductModal() {
                document.getElementById('productModalTitle').textContent = 'Add Product';
                document.getElementById('productSubmitBtn').textContent = 'Add Product';

                const form = document.getElementById('productForm');
                form.action = '{{ route("products.store") }}';
                form.method = 'POST'; // should ALWAYS be POST for store

                form.reset();

                // remove _method if exists
                const methodInput = form.querySelector('input[name="_method"]');
                if (methodInput) methodInput.remove();

                openModal('productModal');
            }

            function openEditProductModal(id, name, description, price, costPrice, categoryId) {
                document.getElementById('productModalTitle').textContent = 'Edit Product';
                document.getElementById('productSubmitBtn').textContent = 'Update Product';

                document.getElementById('productName').value = name;
                document.getElementById('productDescription').value = description;
                document.getElementById('productPrice').value = price;
                document.getElementById('productCostPrice').value = costPrice;
                document.getElementById('productCategory').value = categoryId;

                const form = document.getElementById('productForm');
                form.action = `/products/${id}`;
                form.method = "POST"; // POST + method spoof

                // add PUT method spoof
                let methodInput = form.querySelector('input[name="_method"]');
                if (!methodInput) {
                    methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    form.appendChild(methodInput);
                }
                methodInput.value = 'PUT';

                openModal('productModal');
            }

            // Close modal when clicking outside
            window.onclick = function(event) {
                if (event.target.classList.contains('modal')) {
                    event.target.style.display = 'none';
                }
            };
        </script>
    @endsection
@endsection
