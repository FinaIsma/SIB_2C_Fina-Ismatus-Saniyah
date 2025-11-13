<?php 
include '../views/header.php';
require_once '../config/Database.php';

// Inisialisasi koneksi ke database
$database = new Database();
$db = $database->getConnection();

try {
    // Ambil data dari function PostgreSQL
    $query = $db->query("SELECT * FROM get_salary_stats()");
    $salary_stats = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("<div style='color:red;'>Gagal mengambil data: " . $e->getMessage() . "</div>");
}
?>

<h2>Statistik Gaji per Departemen</h2>

<p style="margin-bottom: 2rem; color: #666;">
    Data diambil dari function PostgreSQL <code>get_salary_stats()</code>.
</p>

<?php if (!empty($salary_stats)): ?>
    <!-- Cards Ringkasan -->
    <div class="dashboard-cards">
        <?php
        $avg_all = array_sum(array_column($salary_stats, 'avg_salary')) / count($salary_stats);
        $max_all = max(array_column($salary_stats, 'max_salary'));
        $min_all = min(array_column($salary_stats, 'min_salary'));
        ?>
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

    <!-- Tabel Detail -->
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
                <td>Rp <?php echo number_format($row['avg_salary'], 0, ',', '.'); ?></td>
                <td style="color:#27ae60;">Rp <?php echo number_format($row['max_salary'], 0, ',', '.'); ?></td>
                <td style="color:#c0392b;">Rp <?php echo number_format($row['min_salary'], 0, ',', '.'); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Chart Gaji Rata-rata -->
    <div style="margin-top: 3rem;">
        <h3>Visualisasi Gaji Rata-rata</h3>
        <div style="background: white; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #667eea;">
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
        <p style="color: #999;">Pastikan tabel <code>employees</code> berisi data gaji dan function <code>get_salary_stats()</code> sudah dibuat di database.</p>
    </div>
<?php endif; ?>

<div style="margin-top: 2rem; padding: 1rem; background: #e7f3ff; border-radius: 5px;">
    <strong>Informasi:</strong>
    Function ini menggunakan agregat PostgreSQL:
    <code>AVG()</code>, <code>MAX()</code>, dan <code>MIN()</code> untuk menghitung statistik gaji per departemen.
</div>

<?php include '../views/footer.php'; ?>
