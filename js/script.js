document.addEventListener('DOMContentLoaded', function() {
    const confirmLinks = document.querySelectorAll('.confirm-link');

    confirmLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const url = this.href;
            const userConfirmed = confirm('¿Está seguro de cerrar sesión?');

            if (userConfirmed) {
                window.location.href = url;
            }
        });
    });
});
