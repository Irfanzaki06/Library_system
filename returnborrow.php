<?php
require 'conn.php';

$toastMessage = '';
$success = false;

// Handle borrow action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'borrow') {
    $reader_id = $_POST['reader_id'];
    $book_title = $_POST['book_title'];
    $borrow_date = $_POST['borrow_date'];
    $due_date = $_POST['due_date'];
    
    // Check if book is available
    if (isBookAvailable($book_title)) {
        $result = borrowBook($reader_id, $book_title, $borrow_date, $due_date);
        
        if ($result) {
            $toastMessage = "Book borrowed successfully!";
            $success = true;
        } else {
            $toastMessage = "Failed to borrow book. Please try again.";
        }
    } else {
        $toastMessage = "This book is already borrowed by another reader!";
        $success = false;
    }
}

// Handle return action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'return') {
    $record_id = $_POST['record_id'];
    
    $result = returnBook($record_id);
    
    if ($result) {
        $toastMessage = "Book returned successfully!";
        $success = true;
    } else {
        $toastMessage = "Failed to return book. Please try again.";
    }
}

// Handle delete borrow record action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_record') {
    $record_id = $_POST['record_id'];
    
    $result = deleteBorrowRecord($record_id);
    
    if ($result) {
        $toastMessage = "Borrow record deleted successfully!";
        $success = true;
    } else {
        $toastMessage = "Failed to delete borrow record.";
    }
}

// Get all data
$books = selectAllBooks();
$readers = selectAllReaders();
$borrow_records = selectBorrowRecords();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow & Return - Library Management System</title>
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

        // Set today's date as default
        function setTodayDate() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('borrow_date').value = today;
            
            // Set due date to 14 days from today
            const dueDate = new Date();
            dueDate.setDate(dueDate.getDate() + 14);
            document.getElementById('due_date').value = dueDate.toISOString().split('T')[0];
        }

        // Delete borrow record
        function deleteBorrowRecord(recordId) {
            if (confirm('Are you sure you want to delete this borrow record? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_record">
                    <input type="hidden" name="record_id" value="${recordId}">
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
                    <div class="bg-indigo-600 p-2 rounded-lg">
                        <i class="fas fa-exchange-alt text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">Borrow & Return</h1>
                        <p class="text-sm text-gray-600">Library Management System</p>
                    </div>
                </div>
                
                <!-- Navigation Links -->
                <div class="flex items-center space-x-4">
                    <a href="Menu.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center space-x-2">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h2 class="text-4xl font-bold text-white mb-4">📚 Borrow & Return Management</h2>
            <p class="text-lg text-white">Manage book transactions and track borrowing records</p>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Borrow Book Section -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="text-center mb-6">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-hand-holding text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Borrow Book</h3>
                    <p class="text-gray-600">Issue books to readers</p>
                </div>

                <form method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="borrow">
                    
                    <!-- Reader Selection -->
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Select Reader</label>
                        <select name="reader_id" required class="w-full border border-gray-300 rounded-md p-3 focus:ring-2 focus:ring-green-400 focus:border-transparent">
                            <option value="">Choose a reader...</option>
                            <?php foreach ($readers as $reader): ?>
                                <option value="<?= $reader['reader_id'] ?>">
                                    <?= htmlspecialchars($reader['full_name']) ?> (<?= htmlspecialchars($reader['reader_id']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Book Selection -->
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Select Book</label>
                        <select name="book_title" required class="w-full border border-gray-300 rounded-md p-3 focus:ring-2 focus:ring-green-400 focus:border-transparent">
                            <option value="">Choose a book...</option>
                            <?php foreach ($books as $book): ?>
                                <?php $isAvailable = isBookAvailable($book['title']); ?>
                                <option value="<?= htmlspecialchars($book['title']) ?>" <?= !$isAvailable ? 'disabled style="color: #999; background-color: #f5f5f5;"' : '' ?>>
                                    <?= htmlspecialchars($book['title']) ?> by <?= htmlspecialchars($book['author']) ?>
                                    <?= !$isAvailable ? ' (Currently Borrowed)' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Borrow Date -->
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Borrow Date</label>
                        <input type="date" name="borrow_date" id="borrow_date" required
                               class="w-full border border-gray-300 rounded-md p-3 focus:ring-2 focus:ring-green-400 focus:border-transparent">
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Due Date</label>
                        <input type="date" name="due_date" id="due_date" required
                               class="w-full border border-gray-300 rounded-md p-3 focus:ring-2 focus:ring-green-400 focus:border-transparent">
                    </div>

                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-md transition duration-200 flex items-center justify-center space-x-2 text-lg font-medium">
                        <i class="fas fa-hand-holding"></i>
                        <span>Borrow Book</span>
                    </button>
                </form>
            </div>

            <!-- Borrow Records Section -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="text-center mb-6">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-list-alt text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Borrowing Records</h3>
                    <p class="text-gray-600">Track all book transactions</p>
                </div>

                <div class="max-h-96 overflow-y-auto">
                    <?php if (empty($borrow_records)): ?>
                        <div class="text-center py-8">
                            <i class="fas fa-book text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-500">No borrowing records found</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($borrow_records as $record): ?>
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-200">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-800"><?= htmlspecialchars($record['book_title']) ?></h4>
                                            <p class="text-sm text-gray-600">Reader: <?= htmlspecialchars($record['full_name'] ?? $record['reader_id']) ?></p>
                                            <p class="text-sm text-gray-600">Borrowed: <?= date('M d, Y', strtotime($record['borrow_date'])) ?></p>
                                            <p class="text-sm text-gray-600">Due: <?= date('M d, Y', strtotime($record['due_date'])) ?></p>
                                            
                                            <?php if ($record['status'] === 'borrowed'): ?>
                                                <?php 
                                                $today = new DateTime();
                                                $due_date = new DateTime($record['due_date']);
                                                $is_overdue = $today > $due_date;
                                                ?>
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $is_overdue ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' ?>">
                                                    <?= $is_overdue ? 'Overdue' : 'Active' ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Returned
                                                </span>
                                                <?php if ($record['return_date']): ?>
                                                    <p class="text-sm text-gray-600 mt-1">Returned: <?= date('M d, Y', strtotime($record['return_date'])) ?></p>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="ml-4 flex space-x-2">
                                            <?php if ($record['status'] === 'borrowed'): ?>
                                                <form method="POST">
                                                    <input type="hidden" name="action" value="return">
                                                    <input type="hidden" name="record_id" value="<?= $record['id'] ?>">
                                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition duration-200">
                                                        <i class="fas fa-undo"></i> Return
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            
                                            <!-- Delete Record Button -->
                                            <button onclick="deleteBorrowRecord('<?= $record['id'] ?>')" 
                                                    class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm transition duration-200">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto-set dates on page load -->
    <script>
        window.onload = function() {
            setTodayDate();
        }
    </script>

    <?php if ($toastMessage): ?>
    <script>
        showToast("<?= $toastMessage ?>", <?= $success ? 'true' : 'false' ?>);
    </script>
    <?php endif; ?>
</body>
</html>
