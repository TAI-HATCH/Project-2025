<?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
    <div class="admin-banner">
        ✅ Logged in as admin
    </div>
<?php endif; ?>