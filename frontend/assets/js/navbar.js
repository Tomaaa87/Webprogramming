
    document.addEventListener('DOMContentLoaded', function() {
        const links = document.querySelectorAll('nav a');

        function setActiveLink(event) {
            links.forEach(link => link.classList.remove('active'));
            event.target.classList.add('active');
        }

        links.forEach(link => {
            link.addEventListener('click', setActiveLink);
        });

        
        if (links.length > 0) {
            links[0].classList.add('active');
        }
    });
