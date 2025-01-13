   
    function togglePasswordVisibility(inputId, toggleIconId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(toggleIconId);

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bx-hide');
            toggleIcon.classList.add('bx-show');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bx-show');
            toggleIcon.classList.add('bx-hide');
        }
    }

    function validateForm(event) {
        event.preventDefault(); 

        const password1 = document.getElementById('inputPassword1').value;
        const password2 = document.getElementById('inputPassword2').value;
        const termsAgreed = document.getElementById('flexSwitchCheckChecked').checked;

        if (password1 !== password2) {
            showMessage('Parollar bir biriga mos emas', 'error');
            return; 
        }

        if (!termsAgreed) {
            showMessage('Talab va shartlarga rozilig bildirish kerak', 'error');
            return; 
        }

        document.getElementById('registrationForm').submit();
    }

    function showMessage(message, type) {
        const messageBox = document.getElementById('messageBox');
        messageBox.textContent = message;
        messageBox.style.display = 'block';
        messageBox.classList.add(type === 'error' ? 'alert-danger' : 'alert-success');

        setTimeout(function () {
            messageBox.style.display = 'none';
            messageBox.classList.remove('alert-danger', 'alert-success');
        }, 5000);
    }

    function togglePasswordVisibility(inputId, toggleIconId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(toggleIconId);

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bx-hide');
            toggleIcon.classList.add('bx-show');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bx-show');
            toggleIcon.classList.add('bx-hide');
        }
    }

    function validateForm(event) {
        event.preventDefault(); 

        const firstName = document.getElementById('inputFirstName').value;
        const lastName = document.getElementById('inputLastName').value;
        const email = document.getElementById('inputEmailAddress').value;
        const phoneNumber = document.getElementById('inputPhoneNumber').value;
        const city = document.getElementById('inputSelectCountry').value;
        const password1 = document.getElementById('inputPassword1').value;
        const password2 = document.getElementById('inputPassword2').value;
        const termsAgreed = document.getElementById('flexSwitchCheckChecked').checked;

        if (!firstName) {
            showMessage('Ism kiritilishi kerak', 'error');
            return;
        }

        if (!lastName) {
            showMessage('Familiya kiritilishi kerak', 'error');
            return;
        }

        if (!email) {
            showMessage('Email kiritilishi kerak', 'error');
            return;
        }

        if (!phoneNumber ) {
            showMessage('Telefon raqam kiritilishi kerak', 'error');
            return;
        }

        if (!city) {
            showMessage('Shahar tanlanishi kerak', 'error');
            return;
        }

        if ( password1.length < 5 || password1.length > 10) {
            showMessage("Parol kiritilmagan yoki kamida 5 ta  ko'pi bilan 10 ta belgi bo'lishi kerak", 'error');
            return;
        }

        if (password1 !== password2) {
            showMessage('Parollar bir biriga mos emas', 'error');
            return;
        }

        if (!termsAgreed) {
            showMessage('Talab va shartlarga rozilig bildirish kerak', 'error');
            return;
        }

        document.getElementById('registrationForm').submit();
    }

    function showMessage(message, type) {
        const messageBox = document.getElementById('messageBox');
        messageBox.textContent = message;
        messageBox.style.display = 'block';
        messageBox.classList.add(type === 'error' ? 'alert-danger' : 'alert-success');

        setTimeout(function () {
            messageBox.style.display = 'none';
            messageBox.classList.remove('alert-danger', 'alert-success');
        }, 5000);
    }

    const addNewInputButton = document.getElementById('addNewInputButton');
    const newInputContainer = document.querySelector('.new-input-container');

    addNewInputButton.addEventListener('click', function () {
        if (newInputContainer.style.display === 'none') {
            newInputContainer.style.display = 'block';
        } else {
            newInputContainer.style.display = 'none';
        }
    });
    const passwordInput = document.getElementById('inputChoosePassword');
    const toggleButton = document.querySelector('.password-toggle');

    toggleButton.addEventListener('click', function () {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleButton.querySelector('.bx').classList.replace('bx-hide', 'bx-show');
        } else {
            passwordInput.type = 'password';
            toggleButton.querySelector('.bx').classList.replace('bx-show', 'bx-hide');
        }
    });