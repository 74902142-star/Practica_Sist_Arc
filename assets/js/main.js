// Sistema de Archivo Fiscal - Funciones JavaScript

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Sistema de Archivo Fiscal - Ministerio Público');
    
    // Inicializar tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Auto-ocultar alertas después de 5 segundos
    setTimeout(function() {
        document.querySelectorAll('.alert-dismissible').forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

// Mostrar mensaje toast
function mostrarToast(mensaje, tipo = 'success') {
    const toastContainer = document.createElement('div');
    toastContainer.style.position = 'fixed';
    toastContainer.style.top = '20px';
    toastContainer.style.right = '20px';
    toastContainer.style.zIndex = '9999';
    toastContainer.style.minWidth = '300px';
    
    const bgClass = tipo === 'success' ? 'bg-success' : 
                    tipo === 'error' ? 'bg-danger' : 
                    tipo === 'warning' ? 'bg-warning' : 'bg-info';
    
    toastContainer.innerHTML = `
        <div class="toast show ${bgClass} text-white" role="alert">
            <div class="toast-body d-flex justify-content-between align-items-center">
                <span>${mensaje}</span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    document.body.appendChild(toastContainer);
    
    setTimeout(() => toastContainer.remove(), 4000);
}

// Confirmar acción
function confirmar(mensaje) {
    return confirm(mensaje || '¿Está seguro de realizar esta acción?');
}

// Formatear número de carpeta
function formatearNumeroCarpeta(input) {
    let valor = input.value.toUpperCase();
    valor = valor.replace(/[^A-Z0-9\-]/g, '');
    input.value = valor;
}

// Validar formulario de búsqueda
function validarBusqueda() {
    const numero = document.getElementById('numero_carpeta');
    if (!numero.value.trim()) {
        mostrarToast('Ingrese un número de carpeta', 'warning');
        return false;
    }
    return true;
}

// Buscar con tecla Enter
document.addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && e.target.matches('#numero_carpeta, #busqueda_ajax')) {
        e.preventDefault();
        const btn = e.target.closest('form')?.querySelector('button[type="submit"]');
        if (btn) btn.click();
    }
});