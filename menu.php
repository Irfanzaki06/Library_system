<?php
session_start();
if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-cover bg-center bg-fixed" style="background-image: url('a.jpeg');">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo/Brand -->
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-600 p-2 rounded-lg">
                        <i class="fas fa-book text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">Library System</h1>
                        <p class="text-sm text-gray-600">Admin Dashboard</p>
                    </div>
                </div>
                
                <!-- User Info -->
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-700">Welcome, <?php echo $_SESSION['username'];?></p>
                        <p class="text-xs text-gray-500">Library Management</p>
                    </div>
                    <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center space-x-2">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Welcome Section -->
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-white mb-4">Welcome to Library Management</h2>
            <p class="text-lg text-white">Manage your library efficiently with our comprehensive system</p>
        </div>

        <!-- Menu Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Add Reader Card -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                <div class="p-6">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-user-plus text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Add Reader</h3>
                    <p class="text-gray-600 mb-6">Register new library members and manage reader information.</p>
                    <a href="add_reader.php" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>Add Reader</span>
                    </a>
                </div>
            </div>

            <!-- Reader List Card -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                <div class="p-6">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-users text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Reader Management</h3>
                    <p class="text-gray-600 mb-6">View and manage all registered library members.</p>
                    <a href="reader_list.php" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center space-x-2">
                        <i class="fas fa-list"></i>
                        <span>View Readers</span>
                    </a>
                </div>
            </div>

            <!-- Add Book Card -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                <div class="p-6">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-book-medical text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Add Book</h3>
                    <p class="text-gray-600 mb-6">Add new books to the library collection with detailed information.</p>
                    <a href="book.php" class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>Add Book</span>
                    </a>
                </div>
            </div>

            <!-- Book List Card -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                <div class="p-6">
                    <div class="bg-orange-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-books text-orange-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Book Collection</h3>
                    <p class="text-gray-600 mb-6">Browse and manage the complete library book collection.</p>
                    <a href="book_list.php" class="w-full bg-orange-600 hover:bg-orange-700 text-white py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center space-x-2">
                        <i class="fas fa-list"></i>
                        <span>View Books</span>
                    </a>
                </div>
            </div>

            <!-- Borrow & Return Card -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                <div class="p-6">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-exchange-alt text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Borrow & Return</h3>
                    <p class="text-gray-600 mb-6">Manage book borrowing and return transactions.</p>
                    <a href="returnborrow.php" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center space-x-2">
                        <i class="fas fa-handshake"></i>
                        <span>Manage Transactions</span>
                    </a>
                </div>
            </div>

        </div>

  </div>
</body>
</html>
