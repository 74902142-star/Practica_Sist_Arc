<footer class="bg-light text-center text-muted py-3 mt-5 border-top">
    <div class="container">
        <div class="row">
            <div class="col-md-4 text-md-start">
                <small>© 2024 Ministerio Público</small>
            </div>
            <div class="col-md-4">
                <small>Sistema de Archivo Fiscal v1.0</small>
            </div>
            <div class="col-md-4 text-md-end">
                <small>
                    <i class="bi bi-person-check"></i> 
                    Usuario: <?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Invitado'); ?>
                </small>
            </div>
        </div>
    </div>
</footer>