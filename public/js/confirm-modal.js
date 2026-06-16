/**
 * Global SweetAlert2 Confirmation Modal
 * 
 * Replaces native browser confirm() with professional SweetAlert2 modals.
 * All modals use a consistent visual style across the entire application.
 * 
 * Usage on forms:
 *   <form data-confirm="Pesan" data-confirm-title="Judul" data-confirm-type="warning|danger|success|reject|verify">
 *   <form data-confirm-html="<p>Rich HTML</p>" data-confirm-title="Judul" data-confirm-type="...">
 * 
 * Usage on buttons (for forms referenced by ID):
 *   <button data-confirm="Pesan" data-confirm-submit="#form-id">
 *   <button data-confirm-html="<p>HTML</p>" data-confirm-submit="#form-id">
 *
 * Usage on buttons that trigger SweetAlert2 + form submit (for Ajukan SPJ):
 *   <button data-swal-submit="#form-id" data-swal-title="..." data-swal-html="..." data-swal-type="...">
 */
document.addEventListener('DOMContentLoaded', function () {

    // ========================
    // Style config per type
    // ========================
    var typeConfig = {
        warning: {
            icon: 'warning',
            confirmColor: '#3085d6',
            confirmText: '<i class="fas fa-check mr-1"></i> Ya, Lanjutkan',
        },
        danger: {
            icon: 'warning',
            confirmColor: '#d33',
            confirmText: '<i class="fas fa-trash mr-1"></i> Ya, Hapus',
        },
        success: {
            icon: 'question',
            confirmColor: '#28a745',
            confirmText: '<i class="fas fa-check-circle mr-1"></i> Ya, Setujui',
        },
        reject: {
            icon: 'warning',
            confirmColor: '#d33',
            confirmText: '<i class="fas fa-times-circle mr-1"></i> Ya, Tolak',
        },
        verify: {
            icon: 'question',
            confirmColor: '#17a2b8',
            confirmText: '<i class="fas fa-check-double mr-1"></i> Ya, Verifikasi',
        },
        submit: {
            icon: 'question',
            confirmColor: '#28a745',
            confirmText: '<i class="fas fa-paper-plane mr-1"></i> Ya, Ajukan Sekarang',
        },
        info: {
            icon: 'question',
            confirmColor: '#3085d6',
            confirmText: '<i class="fas fa-check mr-1"></i> Ya, Lanjutkan',
        },
    };

    // ========================
    // Common SweetAlert2 options
    // ========================
    function buildSwalOptions(title, message, htmlContent, type) {
        var config = typeConfig[type] || typeConfig.warning;
        var options = {
            title: title,
            icon: config.icon,
            showCancelButton: true,
            confirmButtonColor: config.confirmColor,
            cancelButtonColor: '#6c757d',
            confirmButtonText: config.confirmText,
            cancelButtonText: '<i class="fas fa-times mr-1"></i> Batal',
            reverseButtons: true,
            focusCancel: true,
            customClass: {
                popup: 'shadow-lg',
                title: 'font-weight-bold',
                confirmButton: 'font-weight-bold',
                cancelButton: 'font-weight-bold',
            },
        };

        if (htmlContent) {
            options.html = htmlContent;
        } else if (message) {
            options.text = message;
        }

        return options;
    }

    // ========================
    // 1. Intercept form submissions with data-confirm / data-confirm-html
    // ========================
    document.querySelectorAll('form[data-confirm], form[data-confirm-html]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            var message = form.getAttribute('data-confirm') || '';
            var htmlContent = form.getAttribute('data-confirm-html') || '';
            var title = form.getAttribute('data-confirm-title') || 'Konfirmasi';
            var type = form.getAttribute('data-confirm-type') || 'warning';

            Swal.fire(buildSwalOptions(title, message, htmlContent, type)).then(function (result) {
                if (result.isConfirmed) {
                    // Temporarily remove data-confirm to allow normal submit
                    form.removeAttribute('data-confirm');
                    form.removeAttribute('data-confirm-html');
                    form.submit();
                }
            });
        });
    });

    // ========================
    // 2. Intercept buttons that submit external forms via data-confirm-submit
    // ========================
    document.querySelectorAll('[data-confirm-submit]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            var formSelector = btn.getAttribute('data-confirm-submit');
            var targetForm = document.querySelector(formSelector);
            if (!targetForm) return;

            var message = btn.getAttribute('data-confirm') || '';
            var htmlContent = btn.getAttribute('data-confirm-html') || '';
            var title = btn.getAttribute('data-confirm-title') || 'Konfirmasi';
            var type = btn.getAttribute('data-confirm-type') || 'warning';

            Swal.fire(buildSwalOptions(title, message, htmlContent, type)).then(function (result) {
                if (result.isConfirmed) {
                    targetForm.submit();
                }
            });
        });
    });

    // ========================
    // 3. Standalone SweetAlert2 buttons (data-swal-submit)
    //    Used for buttons that are NOT inside a form but need to submit one.
    //    e.g., Ajukan SPJ buttons that replace Bootstrap modals.
    // ========================
    document.querySelectorAll('[data-swal-submit]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            var formSelector = btn.getAttribute('data-swal-submit');
            var targetForm = document.querySelector(formSelector);
            if (!targetForm) return;

            var title = btn.getAttribute('data-swal-title') || 'Konfirmasi';
            var htmlContent = btn.getAttribute('data-swal-html') || '';
            var message = btn.getAttribute('data-swal-text') || '';
            var type = btn.getAttribute('data-swal-type') || 'warning';

            Swal.fire(buildSwalOptions(title, message, htmlContent, type)).then(function (result) {
                if (result.isConfirmed) {
                    targetForm.submit();
                }
            });
        });
    });
});
