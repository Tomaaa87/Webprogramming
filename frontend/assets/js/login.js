document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById("loginForm");
    const loginButton = document.getElementById("loginButton");

  
    const validUsername = "admin";
    const validPassword = "password123";

    fetch('./assets/json/users.json')
        .then(response => response.json())
        .then(users => {
            loginForm.addEventListener("submit", function (e) {
                const username = document.getElementById("username").value;
                const password = document.getElementById("password").value;

                const user = users.find(user => user.username === username && user.password === password);

                if (!user && (username !== validUsername || password !== validPassword)) {
                    e.preventDefault(); 
                    alert("Invalid username or password. Please try again.");
                    localStorage.setItem('isLoggedIn', false);
                } else {
                    localStorage.setItem('isLoggedIn', true);
                    localStorage.setItem('username', username);
                    window.location.href = 'home.html';
                }
            });
        })
        .catch(error => console.error('Error fetching user data:', error));

    
    if (window.location.pathname.endsWith('home.html')) {
        const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
        const username = localStorage.getItem('username');

        if (!isLoggedIn) {
            window.location.href = 'login.html';
        } else {
            document.getElementById('welcomeMessage').textContent = `Hello, ${username}`;
        }
    }

    loginButton.addEventListener("click", function () {
        loginForm.dispatchEvent(new Event('submit'));
    });
});