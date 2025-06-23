<?php
// Load koneksi database
$mysqli = new mysqli("localhost", "root", "", "spk_moora_ci_3");

// Periksa koneksi
if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}

// Ambil semua user
$result = $mysqli->query("SELECT id_user FROM user");

while ($row = $result->fetch_assoc()) {
    $id_user = $row['id_user'];

    // Gunakan password default
    $password_md5 = md5('password123');

    // Update password ke MD5
    $mysqli->query("UPDATE user SET password = '$password_md5' WHERE id_user = $id_user");
    
    echo "Password untuk ID $id_user telah dikembalikan ke MD5.<br>";
}

echo "Proses selesai!";
$mysqli->close();
?>
