<?php 
include '../views/header.php';       
require_once '../config/Database.php'; 

$database = new Database();
$db = $database->getConnection();

try {
    $query = "
        SELECT 
            CASE
                WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, hire_date)) < 1 THEN 'Junior'
                WHEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, hire_date)) BETWEEN 1 AND 3 THEN 'Middle'
                ELSE 'Senior'
            END AS kategori,
            COUNT(*) AS jumlah
        FROM employees
        GROUP BY kategori
        ORDER BY kategori;
    ";

    $stmt = $db->query($query);
    $tenure_stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("<div style='color:red;'>Gagal mengambil data: " . $e->getMessage() . "</div>");
}
?>

<h2>Statistik Masa Kerja Karyawan</h2>

<p style="margin-bottom: 2rem; color: #666;">
    Data dihitung menggunakan fungsi PostgreSQL <code>CASE WHEN</code>, <code>COUNT()</code>, dan <code>GROUP BY</code>.
</p>

<?php if (!empty($tenure_stats)): ?>

    <!-- Cards Ringkasan -->
    <div class="dashboard-cards">
        <?php 
        $total = array_sum(array_column($tenure_stats, 'jumlah'));
        ?>
        <div class="card">
            <h3>Total Karyawan</h3>
            <div class="number"><?php echo $total; ?> orang</div>
        </div>
        <div class="card">
            <h3>Level Masa Kerja</h3>
            <div class="number">3 Kategori</div>
        </div>
        <div class="card">
            <h3>Data Terupdate</h3>
            <div class="number"><?php echo date("d/m/Y"); ?></div>
        </div>
    </div>

    <!-- Tabel Detail -->
    <table class="data-table">
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Jumlah Karyawan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tenure_stats as $row): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($row['kategori']); ?></strong></td>
                <td><?php echo $row['jumlah']; ?> orang</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Chart Visualisasi -->
    <div style="margin-top: 3rem;">
        <h3>Visualisasi Karyawan per Kategori Masa Kerja</h3>
        <div style="background: white; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #667eea;">
            <?php foreach ($tenure_stats as $row): ?>
            <div style="margin: 0.5rem 0;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                    <span><?php echo htmlspecialchars($row['kategori']); ?></span>
                    <span><?php echo $row['jumlah']; ?> orang</span>
                </div>
                <div style="background: #f0f0f0; border-radius: 4px; height: 20px;">
                    <div style="background: #667eea; height: 100%; border-radius: 4px; width: <?php echo ($row['jumlah'] / $total * 100); ?>%;"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

<?php else: ?>
    <div style="text-align: center; padding: 3rem; background: #f8f9fa; border-radius: 8px;">
        <p style="font-size: 1.2rem; color: #666;">Tidak ada data masa kerja.</p>
        <p style="color: #999;">Pastikan tabel <code>employees</code> memiliki kolom <code>hire_date</code>.</p>
    </div>
<?php endif; ?>

<div style="margin-top: 2rem; padding: 1rem; background: #e7f3ff; border-radius: 5px;">
    <strong>Informasi:</strong>
    Perhitungan masa kerja menggunakan:
    <code>EXTRACT()</code>, <code>AGE()</code>, <code>CASE WHEN</code>, <code>COUNT()</code>.
</div>

<?php include '../views/footer.php'; ?>
