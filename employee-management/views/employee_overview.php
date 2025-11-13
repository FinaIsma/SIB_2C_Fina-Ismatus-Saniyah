<?php 
include '../views/header.php';       
require_once '../config/Database.php'; 

$database = new Database();
$db = $database->getConnection();

try {
    $query = "
        SELECT
            COUNT(*) AS total_karyawan,
            SUM(salary) AS total_gaji,
            ROUND(AVG(EXTRACT(YEAR FROM AGE(CURRENT_DATE, hire_date))), 2) AS avg_masakerja
        FROM employees;
    ";

    $stmt = $db->query($query);
    $overview = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("<div style='color:red;'>Gagal mengambil data: " . $e->getMessage() . "</div>");
}
?>

<h2>Ringkasan Data Karyawan</h2>

<p style="margin-bottom: 2rem; color: #666;">
    Data ini menggunakan fungsi PostgreSQL <code>COUNT()</code>, <code>SUM()</code>, dan <code>AVG()</code>.
</p>

<?php if ($overview): ?>

    <!-- Cards Ringkasan -->
    <div class="dashboard-cards">
        <div class="card">
            <h3>Total Karyawan</h3>
            <div class="number"><?php echo $overview['total_karyawan']; ?> orang</div>
        </div>

        <div class="card">
            <h3>Total Gaji per Bulan</h3>
            <div class="number">Rp <?php echo number_format($overview['total_gaji'], 0, ',', '.'); ?></div>
        </div>

        <div class="card">
            <h3>Rata-rata Masa Kerja</h3>
            <div class="number"><?php echo $overview['avg_masakerja']; ?> tahun</div>
        </div>
    </div>

    <!-- Visualisasi -->
    <div style="margin-top: 3rem;">
        <h3>Visualisasi Total Gaji</h3>
        <div style="background: white; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #667eea;">
            
            <div style="margin: 0.5rem 0;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                    <span>Total Gaji</span>
                    <span>Rp <?php echo number_format($overview['total_gaji'], 0, ',', '.'); ?></span>
                </div>
                <div style="background: #f0f0f0; border-radius: 4px; height: 20px;">
                    <div style="background: #667eea; width: 100%; height: 100%; border-radius: 4px;"></div>
                </div>
            </div>

        </div>
    </div>

<?php else: ?>
    <div style="padding: 2rem; background:#f8f9fa; border-radius: 8px;">
        <p>Tidak ada data karyawan.</p>
    </div>
<?php endif; ?>

<div style="margin-top: 2rem; padding: 1rem; background: #e7f3ff; border-radius: 5px;">
    <strong>Informasi:</strong>
    Perhitungan menggunakan fungsi PostgreSQL: 
    <code>SUM()</code>, <code>COUNT()</code>, <code>AVG()</code>, dan <code>AGE()</code>.
</div>

<?php include '../views/footer.php'; ?>
