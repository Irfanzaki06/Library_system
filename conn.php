<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbs_5";
$port = 3306; 

$conn = new mysqli($servername, $username, $password, $dbname, $port);


function addNewBook($title,$author,$date_added,$genre1,$genre2,$type,$synopsis,$book_image = '') {
   global $conn;
   $sql = "INSERT INTO booklist (title, author, published_date, genre1, genre2, type, synopsis, book_image) VALUES ('$title', '$author', '$date_added', '$genre1', '$genre2', '$type', '$synopsis', '$book_image')";
   return $conn->query($sql);
}


// Retrieve (Cari data dalam database that matched username and password)
function login($username, $password){
   global $conn;
   $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
   $result = $conn->query($sql);
   return $result->fetch_assoc();
}

function selectAllBooks() {
    global $conn;
    $sql = "SELECT * FROM booklist";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function queryBook($query) {
    global $conn;
    $sql = "SELECT * FROM booklist WHERE Title LIKE '%$query%'";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Reader management functions
function addNewReader($reader_id, $full_name, $email, $phone, $address, $date_of_birth) {
    global $conn;
    $sql = "INSERT INTO readers (reader_id, full_name, email, phone, address, date_of_birth) VALUES ('$reader_id', '$full_name', '$email', '$phone', '$address', '$date_of_birth')";
    return $conn->query($sql);
}

function selectAllReaders() {
    global $conn;
    $sql = "SELECT * FROM readers ORDER BY created_at DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


function borrowBook($reader_id, $book_title, $borrow_date, $due_date) {
    global $conn;
    $sql = "INSERT INTO borrow_return (reader_id, book_title, borrow_date, due_date, status) VALUES ('$reader_id', '$book_title', '$borrow_date', '$due_date', 'borrowed')";
    return $conn->query($sql);
}

function returnBook($id) {
    global $conn;
    $today = date('Y-m-d');
    $sql = "UPDATE borrow_return SET return_date = '$today', status = 'returned' WHERE id = '$id'";
    return $conn->query($sql);
}

function selectBorrowRecords() {
    global $conn;
    $sql = "SELECT br.*, r.full_name FROM borrow_return br LEFT JOIN readers r ON br.reader_id = r.reader_id ORDER BY br.created_at DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Update reader status
function updateReaderStatus($reader_id, $status) {
    global $conn;
    $sql = "UPDATE readers SET status = '$status' WHERE reader_id = '$reader_id'";
    return $conn->query($sql);
}

// Delete book
function deleteBook($book_id) {
    global $conn;
    $sql = "DELETE FROM booklist WHERE id = '$book_id'";
    return $conn->query($sql);
}

// Check if book is available (not currently borrowed)
function isBookAvailable($book_title) {
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM borrow_return WHERE book_title = '$book_title' AND status = 'borrowed'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'] == 0;
}

// Delete borrow record
function deleteBorrowRecord($record_id) {
    global $conn;
    $sql = "DELETE FROM borrow_return WHERE id = '$record_id'";
    return $conn->query($sql);
}

?>




