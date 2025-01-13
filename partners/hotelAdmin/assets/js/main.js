document.addEventListener("DOMContentLoaded", function() {


    const token = localStorage.getItem('access_token');

    // Если токен отсутствует, перенаправляем на страницу входа
    if (!token) {
        window.location.href = 'login.php';
        return; // Прерываем выполнение скрипта
    }
  

    // Обновляем имя пользователя в другом элементе
    document.getElementById('user-name').innerText = `${localStorage.getItem('first_name')} ${localStorage.getItem('last_name')}`;

    const logoutButtons = document.querySelectorAll('a[href="login.php"]');
    logoutButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            // Очищаем localStorage
            localStorage.clear();
            // Перенаправляем на страницу входа
            window.location.href = 'login.php';
        });
    });
});