function displayError(message) {
    const errorDiv = document.getElementById('error_message');
    errorDiv.textContent = message;
    errorDiv.classList.remove('d-none');
}

// Получаем текущий URL
var urlParamsForSign = new URLSearchParams(window.location.search);

// Извлекаем значение параметра 'query'
var fromPage = urlParamsForSign.get('from') || "dashboard_user";

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('signinForm').addEventListener('submit', async function (event) {
        event.preventDefault();

        const email = document.getElementById('inputEmailAddress').value;
        const password = document.getElementById('inputChoosePassword').value;

        if (!email) {
            displayError("E-pochta manzili kiritilishi kerak");
            return;
        }

        if (!password) {
            displayError("Parol kiritilishi kerak");
            return;
        }

        try {
            const response = await fetch(`${local_url}/api/auth/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    "email": email, 
                    "password": password
                })
            });
            console.log(local_url)


            const responseData = await response.json();

            if (response.ok) {
                console.log(responseData)
                localStorage.setItem("city", responseData.city);
                localStorage.setItem("email", responseData.email);
                localStorage.setItem("first_name", responseData.first_name);
                localStorage.setItem("last_name", responseData.last_name);
                localStorage.setItem('phone_number', responseData.phone_number);
                localStorage.setItem('access_token', responseData.access_token);
                localStorage.setItem('user_id', responseData.user_id);
                window.location.href = `${encodeURIComponent(fromPage)}.php`;

            } else {
                displayError("Parol yoki Email xato " + responseData.message);
            }
        } catch (error) {
            displayError("Ошибка при выполнении запроса: " + error.message);
        }
    });
    
    const passwordInput = document.getElementById('inputChoosePassword');
    const toggleButton = document.querySelector('.password-toggle');

    toggleButton.addEventListener('click', function() {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleButton.querySelector('.bx').classList.replace('bx-hide', 'bx-show');
        } else {
            passwordInput.type = 'password';
            toggleButton.querySelector('.bx').classList.replace('bx-show', 'bx-hide');
        }
    });
})
