document.addEventListener('DOMContentLoaded', () => {
    const darkModeToggle = document.getElementById('darkModeToggle');
    const body = document.body;
    
    const isDarkMode = localStorage.getItem('darkMode') === 'enabled';
    
    if (isDarkMode) {
        enableDarkMode();
    }
    
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', () => {
            if (body.classList.contains('dark-mode')) {
                disableDarkMode();
            } else {
                enableDarkMode();
            }
        });
    }
    
    function enableDarkMode() {
        body.classList.add('dark-mode');
        localStorage.setItem('darkMode', 'enabled');
        if (darkModeToggle) {
            darkModeToggle.querySelector('i').classList.remove('uil-moon');
            darkModeToggle.querySelector('i').classList.add('uil-sun');
        }
    }
    
    function disableDarkMode() {
        body.classList.remove('dark-mode');
        localStorage.setItem('darkMode', 'disabled');
        if (darkModeToggle) {
            darkModeToggle.querySelector('i').classList.remove('uil-sun');
            darkModeToggle.querySelector('i').classList.add('uil-moon');
        }
    }
}); 