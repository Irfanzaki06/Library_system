<?php
require 'conn.php';

$toastMessage = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reader_id = $_POST['reader_id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $date_of_birth = $_POST['date_of_birth'];

    $result = addNewReader($reader_id, $full_name, $email, $phone, $address, $date_of_birth);

    if ($result) {
        $toastMessage = "Reader successfully registered!";
        $success = true;
    } else {
        $toastMessage = "Failed to register the reader. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Reader - Library Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        // Toast + Redirect
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

            // Hide and redirect if successful
            setTimeout(() => {
                toast.classList.remove('animate-slide-in');
                toast.classList.add('animate-fade-out');
            }, 2500);

            if (success) {
                setTimeout(() => {
                    window.location.href = "Menu.php";
                }, 3000);
            }
        }

        // Generate Reader ID
        function generateReaderId() {
            const timestamp = Date.now().toString().slice(-6);
            const random = Math.floor(Math.random() * 100).toString().padStart(2, '0');
            document.getElementById('reader_id').value = 'R' + timestamp + random;
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

<body class="min-h-screen bg-cover bg-center bg-fixed flex items-center justify-center p-5" style="background-image: url('a.jpeg');">
    <!-- Toast Notification -->
    <div id="toast" class="hidden fixed top-5 right-5 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <span id="toast-msg"></span>
    </div>

    <!-- Form Container -->
    <div class="bg-white/90 shadow-2xl rounded-2xl p-8 w-full max-w-lg backdrop-blur-sm">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user-plus text-green-600 text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Add New Reader</h2>
            <p class="text-gray-600">Register a new library member</p>
        </div>

        <form method="POST" class="space-y-6">
            <!-- Reader ID -->
            <div>
                <label class="block mb-2 font-medium text-gray-700">Reader ID</label>
                <div class="flex flex-col sm:flex-row gap-2">
                    <input type="text" id="reader_id" name="reader_id" 
                           class="flex-1 border border-gray-300 rounded-md p-3 focus:ring-2 focus:ring-green-400 focus:border-transparent" 
                           required readonly>
                    <button type="button" onclick="generateReaderId()" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-3 rounded-md transition duration-200 flex items-center justify-center gap-2 whitespace-nowrap shadow-md hover:shadow-lg">
                        <i class="fas fa-sync-alt"></i>
                        <span class="hidden sm:inline">Generate</span>
                        <span class="sm:hidden">Gen</span>
                    </button>
                </div>
            </div>

            <!-- Full Name -->
            <div>
                <label class="block mb-2 font-medium text-gray-700">Full Name</label>
                <input type="text" name="full_name" 
                       class="w-full border border-gray-300 rounded-md p-3 focus:ring-2 focus:ring-green-400 focus:border-transparent" 
                       placeholder="Enter full name" required>
            </div>

            <!-- Email -->
            <div>
                <label class="block mb-2 font-medium text-gray-700">Email Address</label>
                <input type="email" name="email" 
                       class="w-full border border-gray-300 rounded-md p-3 focus:ring-2 focus:ring-green-400 focus:border-transparent" 
                       placeholder="Enter email address" required>
            </div>

            <!-- Phone -->
            <div>
                <label class="block mb-2 font-medium text-gray-700">Phone Number</label>
                <input type="tel" name="phone" 
                       class="w-full border border-gray-300 rounded-md p-3 focus:ring-2 focus:ring-green-400 focus:border-transparent" 
                       placeholder="Enter phone number">
            </div>

            <!-- Address -->
            <div>
                <label class="block mb-2 font-medium text-gray-700">Address</label>
                <textarea name="address" rows="3" 
                          class="w-full border border-gray-300 rounded-md p-3 focus:ring-2 focus:ring-green-400 focus:border-transparent" 
                          placeholder="Enter full address"></textarea>
            </div>

            <!-- Date of Birth -->
            <div>
                <label class="block mb-2 font-medium text-gray-700">Date of Birth</label>
                <input type="date" name="date_of_birth" 
                       class="w-full border border-gray-300 rounded-md p-3 focus:ring-2 focus:ring-green-400 focus:border-transparent">
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-md transition duration-200 flex items-center justify-center space-x-2 text-lg font-medium">
                <i class="fas fa-user-plus"></i>
                <span>Register Reader</span>
            </button>

            <!-- Back to Menu -->
            <a href="Menu.php" 
               class="block text-center mt-4 text-blue-600 hover:text-blue-800 transition duration-200 flex items-center justify-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Menu</span>
            </a>
        </form>
    </div>

    <?php if ($toastMessage): ?>
    <script>
        showToast("<?= $toastMessage ?>", <?= $success ? 'true' : 'false' ?>);
    </script>
    <?php endif; ?>

    <!-- Auto-generate Reader ID on page load -->
    <script>
        window.onload = function() {
            generateReaderId();
        }
    </script>
</body>
</html>
