<?php

// Konfigurasi koneksi database sumber
$sourceDbHost = 'localhost';
$sourceDbName = 'pmo';
$sourceDbUser = 'root';
$sourceDbPass = '';

// Konfigurasi koneksi database tujuan
$targetDbHost = 'localhost';
$targetDbName = 'pmo_baru';
$targetDbUser = 'root';
$targetDbPass = '';

try {
      // Koneksi ke database sumber
      $sourceDb = new PDO("mysql:host=$sourceDbHost;dbname=$sourceDbName", $sourceDbUser, $sourceDbPass);

      // Koneksi ke database tujuan
      $targetDb = new PDO("mysql:host=$targetDbHost;dbname=$targetDbName", $targetDbUser, $targetDbPass);

      // Menjalankan kueri SELECT di database sumber untuk mengambil data
      $query = "SELECT * FROM jamjalan";
      $stmt = $sourceDb->query($query);
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // var_dump($data);
      // die;
      
      // Memasukkan data ke database tujuan
      if (!empty($data)) {
            $targetDb->beginTransaction();

            foreach ($data as $row) {
                  $columns = implode(", ", array_keys($row));
                  $placeholders = ":" . implode(", :", array_keys($row));
                  $insertQuery = "INSERT INTO jamjalan ($columns) VALUES ($placeholders)";
                  $insertStmt = $targetDb->prepare($insertQuery);
                  $insertStmt->execute($row);
            }

            $targetDb->commit();
      }

      echo "Pemindahan database berhasil";
} catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
}
