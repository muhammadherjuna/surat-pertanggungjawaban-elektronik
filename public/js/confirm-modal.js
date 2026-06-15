/**
 * Global SweetAlert2 Confirmation Modal
 * 
 * Replaces native browser confirm() with professional SweetAlert2 modals.
 * 
 * Usage on forms:
 *   <form data-confirm="Pesan konfirmasi" data-confirm-title="Judul" data-confirm-type="warning|danger|info">
 * 
 * Usage on buttons (for forms referenced by ID):
 *   <button data-confirm="Pesan" data-confirm-submit="#form-id">
 */
document.addEventListener('DOMContentLoaded', function () {
    // Style config per type
    const typeConfig = {
        warning: {
            icon: 'warning',
            confirmColor: '#3085d6',
            confirmText: 'Ya, Lanjutkan',
        },
        danger: {
            icon: 'warning',
            confirmColor: '#d33',
            confirmText: 'Ya, Hapus',
        },
        success: {
            icon: 'question',
            confirmColor: '#28a745',
            confirmText: 'Ya, Setujui',
        },
        reject: {
            icon: 'warning',
            confirmColor: '#d33',
            confirmText: 'Ya, Tolak',
        },
        verify: {
            icon: 'question',
            confirmColor: '#17a2b8',
            confirmText: 'Ya, Verifikasi',
        },
        info: {
            icon: 'question',
            confirmColor: '#3085d6',
            confirmText: 'Ya, Lanjutkan',
        },
    };

    // Intercept form submissions with data-confirm
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            var message = form.getAttribute('data-confirm');
            var title = form.getAttribute('data-confirm-title') || 'Konfirmasi';
            var type = form.getAttribute('data-confirm-type') || 'warning';
            var config = typeConfig[type] || typeConfig.warning;

            Swal.fire({
                title: title,
                text: message,
                icon: config.icon,
                showCancelButton: true,
                confirmButtonColor: config.confirmColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: config.confirmText,
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup: 'shadow-lg',
                    title: 'font-weight-bold',
                },
            }).then(function (result) {
                if (result.isConfirmed) {
                    // Remove the listener temporarily to allow normal submit
                    form.removeEventListener('submit', arguments.callee);
                    form.submit();
                }
            });
        });
    });

    // Intercept buttons that submit external forms via data-confirm-submit
    document.querySelectorAll('[data-confirm-submit]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            var formSelector = btn.getAttribute('data-confirm-submit');
            var targetForm = document.querySelector(formSelector);
            if (!targetForm) return;

            var message = btn.getAttribute('data-confirm');
            var title = btn.getAttribute('data-confirm-title') || 'Konfirmasi';
            var type = btn.getAttribute('data-confirm-type') || 'warning';
            var config = typeConfig[type] || typeConfig.warning;

            Swal.fire({
                title: title,
                text: message,
                icon: config.icon,
                showCancelButton: true,
                confirmButtonColor: config.confirmColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: config.confirmText,
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup: 'shadow-lg',
                    title: 'font-weight-bold',
                },
            }).then(function (result) {
                if (result.isConfirmed) {
                    targetForm.submit();
                }
            });
        });
    });
});
