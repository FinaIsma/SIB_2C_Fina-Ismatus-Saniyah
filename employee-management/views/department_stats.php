<?php 
/**
 * FILE: views/salary_stats.php
 * FUNGSI: Menampilkan statistik gaji per departemen
 * TANPA memanggil fungsi PL/pgSQL di database, tapi query langsung di PHP.
 */

include '../views/header.php';       // header di folder views
require_once '../config/Database.php'; // koneksi database di config\

// Inisialisasi koneksi ke database
$database = new Database();
$db = $database->getConnection();

try {
    // Query langsung pakai fungsi agregat PostgreSQL
    $query = "
        SELECT 
            department,
            ROUND(AVG(salary), 2) AS avg_salary,
            MAX(salary) AS max_salary,
            MIN(salary) AS min_salary
        FROM employees
        GROUP BY department
        ORDER BY department;
    ";

    $stmt = $db->query($query);
    $salary_stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("<div style='color:red;'>Gagal mengambil data: " . $e->getMessage() . "</div>");
}
?>

<h2>Statistik Gaji per Departemen</h2>

<p style="margin-bottom: 2rem; color: #666;">
    Data ini dihitung langsung dari tabel <code>employees</code> menggunakan fungsi agregat PostgreSQL.
</p>

<?php if (!empty($salary_stats)): ?>
    <!-- Cards Ringkasan -->
    <?php
        $avg_all = array_sum(array_column($salary_stats, 'avg_salary')) / count($salary_stats);
        $max_all = max(array_column($salary_stats, 'max_salary'));
        $min_all = min(array_column($salary_stats, 'min_salary'));
    ?>
    <div class="dashboard-cards">
        <div class="card">
            <h3>Rata-rata Gaji Semua Departemen</h3>
            <div class="number">Rp <?php echo number_format($avg_all, 0, ',', '.'); ?></div>
        </div>
        <div class="card">
            <h3>Gaji Tertinggi</h3>
            <div class="number">Rp <?php echo number_format($max_all, 0, ',', '.'); ?></div>
        </div>
        <div class="card">
            <h3>Gaji Terendah</h3>
            <div class="number">Rp <?php echo number_format($min_all, 0, ',', '.'); ?></div>
        </div>
    </div>

    <!-- Tabel Statistik Detail -->
    <table class="data-table">
        <thead>
            <tr>
                <th>Departemen</th>
                <th>Gaji Rata-rata</th>
                <th>Gaji Tertinggi</th>
                <th>Gaji Terendah</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($salary_stats as $row): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($row['department']); ?></strong></td>
                <td><strong>Rp <?php echo number_format($row['avg_salary'], 0, ',', '.'); ?></strong></td>
                <td style="color:#27ae60;">Rp <?php echo number_format($row['max_salary'], 0, ',', '.'); ?></td>
                <td style="color:#c0392b;">Rp <?php echo number_format($row['min_salary'], 0, ',', '.'); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Chart Visualisasi -->
    <div style="margin-top: 3rem;">
        <h3>Visualisasi Gaji Rata-rata per Departemen</h3>
        <div style="background: white; padding: 1.5rem; border-radius: 8px; margin: 1rem 0; border-left: 4px solid #667eea;">
            <?php foreach ($salary_stats as $row): ?>
            <div style="margin: 0.5rem 0;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                    <span><?php echo htmlspecialchars($row['department']); ?></span>
                    <span>Rp <?php echo number_format($row['avg_salary'], 0, ',', '.'); ?></span>
                </div>
                <div style="background: #f0f0f0; border-radius: 4px; height: 20px;">
                    <div style="background: #667eea; height: 100%; border-radius: 4px; width: <?php echo ($row['avg_salary'] / $max_all * 100); ?>%;"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

<?php else: ?>
    <div style="text-align: center; padding: 3rem; background: #f8f9fa; border-radius: 8px;">
        <p style="font-size: 1.2rem; color: #666;">Tidak ada data gaji per departemen.</p>
        <p style="color: #999;">Pastikan tabel <code>employees</code> memiliki kolom <code>department</code> dan <code>salary</code> dengan data yang valid.</p>
        <a href="index.php?action=create" class="btn btn-primary" style="margin-top: 1rem;">Tambah Data Karyawan</a>
    </div>
<?php endif; ?>

<div style="margin-top: 2rem; padding: 1rem; background: #e7f3ff; border-radius: 5px;">
    <strong>Informasi:</strong>
    Data ini menggunakan agregat PostgreSQL:
    <code>AVG()</code>, <code>MAX()</code>, dan <code>MIN()</code> untuk menghitung statistik gaji tiap departemen.
</div>

<?php include '../views/footer.php'; ?>
