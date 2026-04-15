<footer class="footer">
    <div class="container-fluid px-4">
        <div class="row align-items-center">
            <div class="col-md-4">
                <span>© <?php echo date('Y'); ?> Ministerio Público</span>
            </div>
            <div class="col-md-4 text-center">
                <span>Sistema de Archivo Fiscal v2.0</span>
            </div>
            <div class="col-md-4 text-end">
                <span class="text-muted">
                    <i class="bi bi-shield-check"></i> Sesión: <?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Invitado'); ?>
                </span>
            </div>
        </div>
    </div>
</footer>