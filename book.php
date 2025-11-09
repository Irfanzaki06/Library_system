<?php
require 'conn.php';

$toastMessage = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $author = $_POST['author'];
  $published_date = $_POST['date'];
  $genre1 = $_POST['genre1'];
  $genre2 = $_POST['genre2'];
  $type = $_POST['type'];
  $synopsis = $_POST['synopsis'];

  // Handle image upload
  $book_image = '';
  if (isset($_FILES['book_image']) && $_FILES['book_image']['error'] == 0) {
    $upload_dir = 'uploads/';
    if (!file_exists($upload_dir)) {
      mkdir($upload_dir, 0777, true);
    }
    
    $file_extension = pathinfo($_FILES['book_image']['name'], PATHINFO_EXTENSION);
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (in_array(strtolower($file_extension), $allowed_extensions)) {
      $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
      $upload_path = $upload_dir . $new_filename;
      
      if (move_uploaded_file($_FILES['book_image']['tmp_name'], $upload_path)) {
        $book_image = $upload_path;
      }
    }
  }

  $result = addNewBook($title, $author, $published_date, $genre1, $genre2, $type, $synopsis, $book_image);

  if ($result) {
    $toastMessage = "Book successfully added!";
    $success = true;
  } else {
    $toastMessage = "Failed to add the book. Please try again.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Book</title>
  <script src="https://cdn.tailwindcss.com"></script>
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

<body 
  class="min-h-screen bg-cover bg-center bg-fixed flex items-center justify-center p-5"
  style="background-image: url('a.jpeg');">

  <!-- Toast Notification -->
  <div id="toast" class="hidden fixed top-5 right-5 text-white px-6 py-3 rounded-lg shadow-lg z-50">
    <span id="toast-msg"></span>
  </div>

  <!-- Form Container -->
  <form method="POST" enctype="multipart/form-data" class="bg-white/90 shadow-2xl rounded-2xl p-8 w-full max-w-md backdrop-blur-sm">
    <h2 class="text-2xl font-semibold text-center mb-6 text-gray-800">📚 Add New Book</h2>

    <label class="block mb-2 font-medium text-gray-700">Title</label>
    <input type="text" name="title" class="w-full border border-gray-300 rounded-md p-2 mb-4 focus:ring-2 focus:ring-blue-400" required>

    <label class="block mb-2 font-medium text-gray-700">Author</label>
    <input type="text" name="author" class="w-full border border-gray-300 rounded-md p-2 mb-4" required>

    <label class="block mb-2 font-medium text-gray-700">Book Image</label>
    <input type="file" name="book_image" accept="image/*" class="w-full border border-gray-300 rounded-md p-2 mb-4 focus:ring-2 focus:ring-blue-400">
    <p class="text-xs text-gray-500 mb-4">Upload a book cover image (JPG, PNG, GIF)</p>

    <label class="block mb-2 font-medium text-gray-700">Published Date</label>
    <input type="date" name="date" class="w-full border border-gray-300 rounded-md p-2 mb-4">

    <label class="block mb-2 font-medium text-gray-700">Genre 1</label>
    <select name="genre1" class="w-full border border-gray-300 rounded-md p-2 mb-4">
      <option>None</option><option>Comedy</option><option>Action</option><option>Mystery</option>
      <option>Drama</option><option>Horror</option><option>Education</option><option>Motivational</option><option>Religion</option>
    </select>

    <label class="block mb-2 font-medium text-gray-700">Genre 2</label>
    <select name="genre2" class="w-full border border-gray-300 rounded-md p-2 mb-4">
      <option>None</option><option>Comedy</option><option>Action</option><option>Mystery</option>
      <option>Drama</option><option>Horror</option><option>Education</option><option>Motivational</option><option>Religion</option>
    </select>

    <label class="block mb-2 font-medium text-gray-700">Type</label>
    <div class="flex flex-wrap gap-3 mb-4">
      <label><input type="radio" name="type" value="Newspaper" required> Newspaper</label>
      <label><input type="radio" name="type" value="Comic"> Comic</label>
      <label><input type="radio" name="type" value="Magazine"> Magazine</label>
      <label><input type="radio" name="type" value="Novel"> Novel</label>
    </div>

    <label class="block mb-2 font-medium text-gray-700">Synopsis</label>
    <textarea name="synopsis" rows="4" class="w-full border border-gray-300 rounded-md p-2 mb-6"></textarea>

    <input type="submit" value="Submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition">

    <a href="menu.php" class="block text-center mt-4 text-blue-600 hover:underline">← Back to Menu</a>
  </form>

  <?php if ($toastMessage): ?>
  <script>
    showToast("<?= $toastMessage ?>", <?= $success ? 'true' : 'false' ?>);
  </script>
  <?php endif; ?>

</body>
</html>
