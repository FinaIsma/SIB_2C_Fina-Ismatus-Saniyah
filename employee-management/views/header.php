<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sistem Manajemen Karyawan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Tambahkan ini biar semua halaman styled -->
  <link rel="stylesheet" href="../assets/css/style.css">
  
  <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      background: #f9f9f9;
      color: #333;
      margin: 0;
      padding: 0;
    }

    header {
      background: linear-gradient(90deg, #667eea, #764ba2);
      color: white;
      padding: 1rem 2rem;
      text-align: center;
    }

    nav {
      background: #fff;
      display: flex;
      justify-content: center;
      gap: 1rem;
      padding: 0.75rem;
      border-bottom: 2px solid #eee;
    }

    nav a {
      text-decoration: none;
      color: #444;
      font-weight: 500;
    }

    nav a:hover {
      color: #667eea;
    }

    h2 {
      margin: 2rem;
      color: #333;
      border-left: 4px solid #667eea;
      padding-left: 10px;
    }

    .dashboard-cards {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      margin: 2rem;
    }

    .card {
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      padding: 1rem 1.5rem;
      flex: 1;
      min-width: 250px;
      transition: transform 0.2s ease;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card h3 {
      color: #555;
      font-size: 1rem;
      margin-bottom: 0.5rem;
    }

    .number {
      font-size: 1.3rem;
      font-weight: bold;
      color: #333;
    }

    table.data-table {
      width: 95%;
      margin: 2rem auto;
      border-collapse: collapse;
      background: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    table.data-table th, table.data-table td {
      border-bottom: 1px solid #eee;
      padding: 0.75rem 1rem;
      text-align: left;
    }

    table.data-table th {
      background: #667eea;
      color: white;
    }

    table.data-table tr:hover {
      background: #f6f8ff;
    }
  </style>
</head>

<body>
  <header>
    <h1>Sistem Manajemen Karyawan</h1>
    <p>Aplikasi CRUD Sederhana dengan PostgreSQL & PHP</p>
  </header>

  <nav>
    <a href="../index.php">Dashboard</a>
    <a href="../index.php?action=list">Data Karyawan</a>
    <a href="../index.php?action=create">Tambah Karyawan</a>
    <a href="../index.php?action=department_stats">Statistik Departemen</a>
    <!-- Tambahan halaman agregat -->
    <a href="salary_stats.php" class="nav-link">Statistik Gaji</a> 
    <a href="views/stenure_stats.php" class="nav-link">Masa Kerja</a>  
    <a href="views/semployee_overview.php" class="nav-link">Ringkasan Karyawan</a>  
</nav>
  <main style="padding: 1rem 2rem;">
