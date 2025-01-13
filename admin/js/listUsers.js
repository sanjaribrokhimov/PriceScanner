
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
                    usersHtml += `
                        <tr>
                            <td>${user.first_name} ${user.last_name}</td>
                            <td>${user.company || 'N/A'}</td> <!-- Add category -->
                            <td class="color-primary" style="text-transform: uppercase;">${user.role}</td>
                            <td>
                                <button class="btn btn-primary" onclick="window.location.href='companyInfo.php?id=${user.company_id}'">Info</button>
                                <button id="connectButton" class="btn ${ user.status ? "btn-success" : "btn-danger"} btn-addon" data-id="${user.id}">
                                    <i id="connectIcon" class="${ user.status ? "ti-power-on" : "ti-power-off"}"></i>
                                    <span id="connectText">${ user.status ? "On" : "Off"}</span>
                                </button>
                            </td>
                        </tr>
                    `;
                });
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
        .then(e => {
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
                            if(!resp.ok) {
                                throw new Error('Network response was not ok');
                            }
                            alert("User disabled successfully")
                            return resp.json()
                        })
                        .catch(err => {
                            alert(err)
                            console.log(err)
                        })
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
                            if(!resp.ok) {
                                throw new Error('Network response was not ok');
                            }
                            alert("User enabled successfully")
                            return resp.json()
                        })
                        .catch(err => {
                            alert(err)
                            console.log(err)
                        })
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


