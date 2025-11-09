<?php
require 'conn.php';

$toastMessage = '';
$success = false;

// Handle book deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_book') {
    $book_id = $_POST['book_id'];
    
    $result = deleteBook($book_id);
    
    if ($result) {
        $toastMessage = "Book deleted successfully!";
        $success = true;
    } else {
        $toastMessage = "Failed to delete book.";
    }
}

// Get all books from database
$books = selectAllBooks();

// Handle search
$search_results = [];
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = $_GET['search'];
    $search_results = queryBook($search_query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book List - Library Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        // Toast notification
        function showToast(message, success) {
            const toast = document.getElementById('toast');
            const msg = document.getElementById('toast-msg');
            msg.innerText = message;

            if (success) {
                toast.classList.add('bg-green-600');
            } else {
                toast.classList.add('bg-red-600');
            }

            toast.classList.remove('hidden');
            toast.classList.add('flex', 'animate-slide-in');

            setTimeout(() => {
                toast.classList.remove('animate-slide-in');
                toast.classList.add('animate-fade-out');
            }, 2500);
        }

        // Delete book
        function deleteBook(bookId, bookTitle) {
            if (confirm(`Are you sure you want to delete "${bookTitle}"? This action cannot be undone.`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_book">
                    <input type="hidden" name="book_id" value="${bookId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

    <style>
        @keyframes slide-in {
            from {transform: translateY(-20px); opacity: 0;}
            to {transform: translateY(0); opacity: 1;}
        }
        @keyframes fade-out {
            from {opacity: 1;}
            to {opacity: 0;}
        }
        .animate-slide-in {
            animation: slide-in 0.4s ease-out forwards;
        }
        .animate-fade-out {
            animation: fade-out 0.5s ease-in forwards;
        }
    </style>
</head>
<body class="min-h-screen bg-cover bg-center bg-fixed" style="background-image: url('a.jpeg');">
    <!-- Toast Notification -->
    <div id="toast" class="hidden fixed top-5 right-5 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <span id="toast-msg"></span>
    </div>

    <!-- Navigation Bar -->
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo/Brand -->
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-600 p-2 rounded-lg">
                        <i class="fas fa-books text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">Book Collection</h1>
                        <p class="text-sm text-gray-600">Library Management System</p>
                    </div>
                </div>
                
                <!-- Navigation Links -->
                <div class="flex items-center space-x-4">
                    <a href="Menu.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center space-x-2">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                    <a href="book.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>Add Book</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h2 class="text-4xl font-bold text-white mb-4">📚 Book Collection</h2>
            <p class="text-lg text-white">Browse and manage your library books</p>
        </div>

        <!-- Search Bar -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form method="GET" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" placeholder="Search books by title..." 
                           value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center space-x-2">
                    <i class="fas fa-search"></i>
                    <span>Search</span>
                </button>
                <?php if (isset($_GET['search'])): ?>
                <a href="book_list.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center space-x-2">
                    <i class="fas fa-times"></i>
                    <span>Clear</span>
                </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Books Grid -->
        <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
            <!-- Search Results -->
            <div class="mb-6">
                <h3 class="text-2xl font-semibold text-white mb-4">
                    Search Results for "<?= htmlspecialchars($_GET['search']) ?>"
                    <span class="text-lg text-white">(<?= count($search_results) ?> found)</span>
                </h3>
            </div>
            <?php if (empty($search_results)): ?>
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No books found</h3>
                    <p class="text-gray-500">Try searching with different keywords</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php foreach ($search_results as $book): ?>
                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                            <div class="p-6">
                                <!-- Book Image -->
                                <div class="mb-4 text-center">
                                    <?php if (!empty($book['book_image']) && file_exists($book['book_image'])): ?>
                                        <img src="<?= $book['book_image'] ?>" alt="<?= htmlspecialchars($book['title']) ?>" 
                                             class="w-32 h-40 object-cover mx-auto rounded-lg shadow-md">
                                    <?php else: ?>
                                        <div class="w-32 h-40 bg-gray-200 rounded-lg mx-auto flex items-center justify-center">
                                            <i class="fas fa-book text-gray-400 text-4xl"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Book Info -->
                                <h4 class="font-semibold text-lg text-gray-800 mb-2 line-clamp-2"><?= htmlspecialchars($book['title']) ?></h4>
                                <p class="text-gray-600 mb-2"><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
                                <p class="text-gray-600 mb-2"><strong>Type:</strong> <?= htmlspecialchars($book['type']) ?></p>
                                <p class="text-gray-600 mb-2"><strong>Genre:</strong> <?= htmlspecialchars($book['genre1']) ?></p>
                                <?php if (!empty($book['published_date'])): ?>
                                    <p class="text-gray-600 mb-2"><strong>Published:</strong> <?= date('M d, Y', strtotime($book['published_date'])) ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($book['synopsis'])): ?>
                                    <p class="text-gray-600 text-sm line-clamp-3"><?= htmlspecialchars(substr($book['synopsis'], 0, 100)) ?>...</p>
                                <?php endif; ?>
                                
                                <!-- Delete Button -->
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <button onclick="deleteBook('<?= $book['id'] ?>', '<?= htmlspecialchars($book['title']) ?>')" 
                                            class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center space-x-2">
                                        <i class="fas fa-trash"></i>
                                        <span>Delete Book</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <!-- All Books -->
            <div class="mb-6">
                <h3 class="text-2xl font-semibold text-white mb-4">
                    All Books
                    <span class="text-lg text-white">(<?= count($books) ?> total)</span>
                </h3>
            </div>
            
            <?php if (empty($books)): ?>
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <i class="fas fa-book text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No books in library</h3>
                    <p class="text-gray-500 mb-4">Start by adding your first book to the collection</p>
                    <a href="book.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition duration-200 inline-flex items-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>Add First Book</span>
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php foreach ($books as $book): ?>
                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                            <div class="p-6">
                                <!-- Book Image -->
                                <div class="mb-4 text-center">
                                    <?php if (!empty($book['book_image']) && file_exists($book['book_image'])): ?>
                                        <img src="<?= $book['book_image'] ?>" alt="<?= htmlspecialchars($book['title']) ?>" 
                                             class="w-32 h-40 object-cover mx-auto rounded-lg shadow-md">
                                    <?php else: ?>
                                        <div class="w-32 h-40 bg-gray-200 rounded-lg mx-auto flex items-center justify-center">
                                            <i class="fas fa-book text-gray-400 text-4xl"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Book Info -->
                                <h4 class="font-semibold text-lg text-gray-800 mb-2 line-clamp-2"><?= htmlspecialchars($book['title']) ?></h4>
                                <p class="text-gray-600 mb-2"><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
                                <p class="text-gray-600 mb-2"><strong>Type:</strong> <?= htmlspecialchars($book['type']) ?></p>
                                <p class="text-gray-600 mb-2"><strong>Genre:</strong> <?= htmlspecialchars($book['genre1']) ?></p>
                                <?php if (!empty($book['published_date'])): ?>
                                    <p class="text-gray-600 mb-2"><strong>Published:</strong> <?= date('M d, Y', strtotime($book['published_date'])) ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($book['synopsis'])): ?>
                                    <p class="text-gray-600 text-sm line-clamp-3"><?= htmlspecialchars(substr($book['synopsis'], 0, 100)) ?>...</p>
                                <?php endif; ?>
                                
                                <!-- Delete Button -->
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <button onclick="deleteBook('<?= $book['id'] ?>', '<?= htmlspecialchars($book['title']) ?>')" 
                                            class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center space-x-2">
                                        <i class="fas fa-trash"></i>
                                        <span>Delete Book</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <?php if ($toastMessage): ?>
    <script>
        showToast("<?= $toastMessage ?>", <?= $success ? 'true' : 'false' ?>);
    </script>
    <?php endif; ?>
</body>
</html>
