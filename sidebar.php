<div class="sidebar-column">
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-tint"></i> <!-- Laundry icon -->
            </div>
            <h2>Razin Laundry</h2>
        </div>
        
        <div class="sidebar-menu-container">
            <ul class="sidebar-menu">
                <li>
                    <a href="invoice.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'invoice.php') ? 'active' : '' ?>">
                        <i class="fas fa-receipt"></i> <!-- Invoice icon -->
                        <span>Transaksi Baru</span>
                    </a>
                </li>
                <li>
                    <a href="transaksi.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'transaksi.php') ? 'active' : '' ?>">
                        <i class="fas fa-list"></i> <!-- List icon -->
                        <span>Daftar Transaksi</span>
                    </a>
                </li>
                <li>
                    <a href="lacak.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'lacak.php') ? 'active' : '' ?>">
                        <i class="fas fa-search"></i> <!-- Search icon -->
                        <span>Lacak Order</span>
                    </a>
                </li>
                <?php if(isset($_SESSION['admin']) && $_SESSION['admin']): ?>
                <li>
                    <a href="check_database.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'check_database.php') ? 'active' : '' ?>">
                        <i class="fas fa-database"></i> <!-- Database icon -->
                        <span>Cek Database</span>
                    </a>
                </li>
                <?php endif; ?>
                <!-- <li>
                    <a href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> <!-- Logout icon -->
                        <!-- <span>Logout</span> -->
                    <!-- </a> -->
                <!-- </li>  -->
            </ul>
        </div>
        
        <div class="sidebar-footer">
            <p>&copy; <?php echo date('Y'); ?> Razin Laundry</p>
        </div>
    </div>
</div>

<!-- Add Font Awesome CSS in your HTML head section -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
