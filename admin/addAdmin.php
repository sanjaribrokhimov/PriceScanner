<?php include("./components/header.php"); ?>

<div id="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card alert">
                <div class="card-header">
                    <h4>All Admin</h4>
                    <div class="page-title">
                        <button id="company-add-btn" class="btn-company">+Admin</button>
                    </div>
                </div>
                <div id="main-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card alert">
                                <div class="card-header">
                                    <h4>COMPANY</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Company</th>
                                                    <th>Role</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- <tr>
                                                <td>1</td>
                                                <td>user</td>
                                                <td class="color-primary">company</td>
                                                <td>
                                                    <button class="btn btn-primary" onclick="window.location.href='companyInfo.html'">Info</button>
                                                </td>
                                            </tr> -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
<div id="pagination">
</div>
<div id="popup-wrapper" class="popup-wrapper hidden">
    <div id="popup-background" class="popup-background"></div>
    <div id="popup-content" class="popup-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card alert">
                    <div class="card-body">
                        <div class="card-header m-b-20">
                            <h4>Input Form</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="basic-form">
                                    <form id="admin-form" method="POST" action="">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input id="first_name" type="text" name="first_name" class="form-control" placeholder="Enter first name" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input id="last_name" type="text" name="last_name" class="form-control" placeholder="Enter last name" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input id="email" type="email" name="email" class="form-control" placeholder="Enter email" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Phone Number</label>
                                            <input id="phone_number" type="text" name="phone_number" class="form-control" placeholder="Enter phone number" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input id="password" type="password" name="password" class="form-control" placeholder="Enter password" required>
                                        </div>
                                        <div class="form-group">
                                            <label>City</label>
                                            <input id="city" type="text" name="city" class="form-control" placeholder="Enter city" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Category</label>
                                            <select id="category" name="category" class="form-control">
                                                <option value="rentcar">rentcar</option>
                                                <option value="tour">tour</option>
                                                <option value="hotel">hotel</option>
                                            </select>
                                        </div>
                                        <input type="hidden" id="company_id" name="company_id" value="1">

                                        <button id="save-btn" class="btn btn-default btn-lg" type="submit">Save</button>
                                        <button class="btn btn-default btn-lg" type="reset">Reset</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button id="close-popup" class="btn btn-danger">Close</button>
            </div>
        </div>
    </div>
</div>




<script>
    let currentPage = 1;
    const perPage = 10;

    function fetchUsers(page = 1) {
        fetch(`${local_url}/api/admin/users?page=${page}&per_page=${perPage}`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem("access_token")}`
                }
            })
            .then(response => response.json())
            .then(response => {
                if (response.message) {
                    const usersList = response.users;
                    const totalPages = response.pages;
                    const currentPage = response.current_page;

                    let usersHtml = '';
                    usersList.forEach(user => {
                        // Проверяем, что роль пользователя - "admin"
                        if (user.role.toLowerCase() === 'admin' || user.role.toLowerCase() === 'partner') {
                            console.log(user)
                            usersHtml += `
                        <tr>
                            <td>${user.first_name} ${user.last_name}</td>
                            <td>${user.company || 'N/A'}</td> <!-- Add category -->
                            <td class="color-primary" style="text-transform: uppercase;">${user.role}</td>
                            <td style="display: flex;justify-content: space-between;">
                                <button id="connectButton" class="btn ${ user.status ? "btn-success" : "btn-danger"} btn-addon" data-id="${user.id}">
                                    <i id="connectIcon" class="${ user.status ? "ti-power-on" : "ti-power-off"}"></i>
                                    <span id="connectText">${ user.status ? "On" : "Off"}</span>
                                </button>
                                <button class="btn btn-primary" onclick="window.location.href='companyInfo.php?company_id=${user.company_id}&id=${user.id}'">Info</button>
                            </td>
                        </tr>
                    `;
                        }
                    });
                    console.log(usersHtml)
                    document.querySelector('tbody').innerHTML = usersHtml;

                    let paginationHtml = '';
                    for (let i = 1; i <= totalPages; i++) {
                        paginationHtml += `
                    <button onclick="fetchCompanies(${i})" class="${i === currentPage ? 'active' : ''}">
                        ${i}
                    </button>
                `;
                    }
                    document.getElementById('pagination').innerHTML = paginationHtml;
                } else {
                    document.querySelector('tbody').innerHTML = `<tr><td colspan="4">No companies found.</td></tr>`;
                }
            })
            .then(() => {
                document.querySelectorAll('#connectButton').forEach(function(button) {
                    button.addEventListener('click', function() {
                        var icon = button.querySelector('#connectIcon');
                        var text = button.querySelector('#connectText');

                        if (button.classList.contains('btn-success')) {
                            button.classList.remove('btn-success');
                            button.classList.add('btn-danger');
                            icon.classList.remove('ti-power-on');
                            icon.classList.add('ti-power-off');
                            text.textContent = 'Off';
                            fetch(`${local_url}/api/admin/users/edit_user`, {
                                    method: "POST",
                                    headers: {
                                        'Authorization': `Bearer ${localStorage.getItem("access_token")}`,
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify({
                                        user_id: button.getAttribute("data-id"),
                                        status: false,
                                    })
                                })
                                .then(resp => {
                                    if (!resp.ok) {
                                        throw new Error('Network response was not ok');
                                    }
                                    alert("User disabled successfully")
                                    return resp.json();
                                })
                                .catch(err => {
                                    alert(err);
                                    console.log(err);
                                });
                        } else {
                            button.classList.remove('btn-danger');
                            button.classList.add('btn-success');
                            icon.classList.remove('ti-power-off');
                            icon.classList.add('ti-power-on');
                            text.textContent = 'On';
                            fetch(`${local_url}/api/admin/users/edit_user`, {
                                    method: "POST",
                                    headers: {
                                        'Authorization': `Bearer ${localStorage.getItem("access_token")}`,
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify({
                                        user_id: button.getAttribute("data-id"),
                                        status: true,
                                    })
                                })
                                .then(resp => {
                                    if (!resp.ok) {
                                        throw new Error('Network response was not ok');
                                    }
                                    alert("User enabled successfully");
                                    return resp.json();
                                })
                                .catch(err => {
                                    alert(err);
                                    console.log(err);
                                });
                        }
                    });
                });
            })
            .catch(error => {
                console.error('Error fetching companies:', error);
                document.getElementById('companies-container').innerHTML = `<p>Error loading companies.</p>`;
            });
    }

    window.onload = function() {
        fetchUsers(currentPage);
    }


    document.getElementById("save-btn").addEventListener('click', async (event) => {
        event.preventDefault();

        // Собираем данные из формы
        const data = {
            first_name: document.getElementById('first_name').value,
            last_name: document.getElementById('last_name').value,
            email: document.getElementById('email').value,
            phone_number: document.getElementById('phone_number').value,
            password: document.getElementById('password').value,
            city: document.getElementById('city').value,
            category: document.getElementById('category').value,
            company_id: document.getElementById('company_id').value
        };

        try {
            // Отправляем запрос с JSON-данными
            const response = await fetch(`${local_url}/api/admin/companies/new_admin`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('access_token')}`,
                },
                body: JSON.stringify(data) // Преобразуем объект в JSON
            });

            const result = await response.json();
            if (response.ok) {
                alert('new Admin created successfully');
                window.location.href = "./addAdmin.php"; // Перенаправляем на страницу с компаниями
            } else {
                alert(`Error: ${result.error}`);
            }
        } catch (error) {
            alert('An error occurred while creating the company.');
        }
    });
</script>




<script>
    const openPopupBtn = document.getElementById('company-add-btn');
    const closePopupBtn = document.getElementById('close-popup');
    const popupWrapper = document.getElementById('popup-wrapper');

    openPopupBtn.addEventListener('click', () => {
        popupWrapper.classList.remove('hidden');
    });

    closePopupBtn.addEventListener('click', () => {
        popupWrapper.classList.add('hidden');
    });

    popupWrapper.addEventListener('click', (event) => {
        if (event.target === popupWrapper || event.target === document.getElementById('popup-background')) {
            popupWrapper.classList.add('hidden');
        }
    });
    document.querySelector('#logout-btn').addEventListener('click', function() {
        localStorage.clear();
    })

    document.addEventListener("DOMContentLoaded", function() {
        fetch(`${local_url}/api/auth/check-health`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (!data.message) {
                    window.location.href = 'login.php';
                }
            })
            .catch(error => {
                window.location.href = 'login.php';
            });
        document.querySelector('.user-avatar').textContent = localStorage.getItem("first_name")
    })
</script>

<?php include("./components/footer.php"); ?>