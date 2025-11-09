<?php
require 'conn.php';

$toastMessage = '';
$success = false;

// Handle reader status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $reader_id = $_POST['reader_id'];
    $new_status = $_POST['new_status'];
    
    $result = updateReaderStatus($reader_id, $new_status);
    
    if ($result) {
        $toastMessage = "Reader status updated successfully!";
        $success = true;
    } else {
        $toastMessage = "Failed to update reader status.";
    }
}

// Get all readers from database
$readers = selectAllReaders();

// Handle search
$search_results = [];
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = $_GET['search'];
    // Simple search in PHP
    $search_results = array_filter($readers, function($reader) use ($search_query) {
        return stripos($reader['full_name'], $search_query) !== false || 
               stripos($reader['reader_id'], $search_query) !== false ||
               stripos($reader['email'], $search_query) !== false;
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reader List - Library Management System</title>
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

        // Update reader status
        function updateReaderStatus(readerId, newStatus) {
            if (confirm(`Are you sure you want to change this reader's status to ${newStatus}?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="update_status">
                    <input type="hidden" name="reader_id" value="${readerId}">
                    <input type="hidden" name="new_status" value="${newStatus}">
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
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">Reader Management</h1>
                        <p class="text-sm text-gray-600">Library Management System</p>
                    </div>
                </div>
                
                <!-- Navigation Links -->
                <div class="flex items-center space-x-4">
                    <a href="Menu.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center space-x-2">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                    <a href="add_reader.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>Add Reader</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h2 class="text-4xl font-bold text-white mb-4">👥 Reader Management</h2>
            <p class="text-lg text-white">Manage your library members</p>
        </div>

        <!-- Search Bar -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form method="GET" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" placeholder="Search readers by name, ID, or email..." 
                           value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center space-x-2">
                    <i class="fas fa-search"></i>
                    <span>Search</span>
                </button>
                <?php if (isset($_GET['search'])): ?>
                <a href="reader_list.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center space-x-2">
                    <i class="fas fa-times"></i>
                    <span>Clear</span>
                </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Readers Table -->
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
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No readers found</h3>
                    <p class="text-gray-500">Try searching with different keywords</p>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reader</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membership</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($search_results as $reader): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                        <i class="fas fa-user text-blue-600"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($reader['full_name']) ?></div>
                                                    <div class="text-sm text-gray-500">ID: <?= htmlspecialchars($reader['reader_id']) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?= htmlspecialchars($reader['email']) ?></div>
                                            <div class="text-sm text-gray-500"><?= htmlspecialchars($reader['phone']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= date('M d, Y', strtotime($reader['membership_date'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?= $reader['status'] === 'active' ? 'bg-green-100 text-green-800' : 
                                                   ($reader['status'] === 'inactive' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') ?>">
                                                <?= ucfirst($reader['status']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <?php if ($reader['status'] === 'active'): ?>
                                                <button onclick="updateReaderStatus('<?= $reader['reader_id'] ?>', 'inactive')" 
                                                        class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded text-sm transition duration-200 mr-2">
                                                    <i class="fas fa-pause"></i> Deactivate
                                                </button>
                                            <?php else: ?>
                                                <button onclick="updateReaderStatus('<?= $reader['reader_id'] ?>', 'active')" 
                                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition duration-200 mr-2">
                                                    <i class="fas fa-play"></i> Activate
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <!-- All Readers -->
            <div class="mb-6">
                <h3 class="text-2xl font-semibold text-white mb-4">
                    All Readers
                    <span class="text-lg text-white">(<?= count($readers) ?> total)</span>
                </h3>
            </div>
            
            <?php if (empty($readers)): ?>
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <i class="fas fa-users text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No readers registered</h3>
                    <p class="text-gray-500 mb-4">Start by adding your first reader to the library</p>
                    <a href="add_reader.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition duration-200 inline-flex items-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>Add First Reader</span>
                    </a>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reader</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date of Birth</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membership</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($readers as $reader): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                        <i class="fas fa-user text-blue-600"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($reader['full_name']) ?></div>
                                                    <div class="text-sm text-gray-500">ID: <?= htmlspecialchars($reader['reader_id']) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?= htmlspecialchars($reader['email']) ?></div>
                                            <div class="text-sm text-gray-500"><?= htmlspecialchars($reader['phone']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= !empty($reader['date_of_birth']) ? date('M d, Y', strtotime($reader['date_of_birth'])) : 'Not specified' ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= date('M d, Y', strtotime($reader['membership_date'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?= $reader['status'] === 'active' ? 'bg-green-100 text-green-800' : 
                                                   ($reader['status'] === 'inactive' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') ?>">
                                                <?= ucfirst($reader['status']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <?php if ($reader['status'] === 'active'): ?>
                                                <button onclick="updateReaderStatus('<?= $reader['reader_id'] ?>', 'inactive')" 
                                                        class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded text-sm transition duration-200 mr-2">
                                                    <i class="fas fa-pause"></i> Deactivate
                                                </button>
                                            <?php else: ?>
                                                <button onclick="updateReaderStatus('<?= $reader['reader_id'] ?>', 'active')" 
                                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition duration-200 mr-2">
                                                    <i class="fas fa-play"></i> Activate
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php if ($toastMessage): ?>
    <script>
        showToast("<?= $toastMessage ?>", <?= $success ? 'true' : 'false' ?>);
    </script>
    <?php endif; ?>
</body>
</html>
