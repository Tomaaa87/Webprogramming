document.addEventListener("DOMContentLoaded", () => {
    const buttons = document.querySelectorAll('.accordion-toggle');

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-target');
            const content = document.getElementById(targetId);

            if (content.classList.contains('open')) {
                content.classList.remove('open');
                content.style.maxHeight = null; 
            } else {
              
                document.querySelectorAll('.accordion-content.open').forEach(openContent => {
                    openContent.classList.remove('open');
                    openContent.style.maxHeight = null;
                });

                
                content.classList.add('open');
                content.style.maxHeight = content.scrollHeight + 'px';
            }
        });
    });
});
