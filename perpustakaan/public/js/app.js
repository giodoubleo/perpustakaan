document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form[data-konfirmasi]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!window.confirm(form.dataset.konfirmasi)) {
                event.preventDefault();
            }
        });
    });
});