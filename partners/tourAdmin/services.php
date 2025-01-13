<?php include './components/header.php' ?>

<div id="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card alert">
                <div class="card-header">
                    <h4>TOUR</h4>
                </div>
                <br>
                <div>
                    <button class="btn btn-primary" onclick="window.location.href='addTour.php'">
                        <i class="bi bi-plus-circle"></i> ADD Tour
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="tours-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="tours-tbody">
                                <!-- Данные будут вставлены здесь -->
                            </tbody>
                        </table>
                    </div>
                    <div id="pagination">
                        <!-- Контролы пагинации будут вставлены здесь -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS для стилизации пагинации -->
<style>
    #pagination {
        margin-top: 20px;
        text-align: center;
    }

    .pagination-button {
        margin: 0 5px;
        padding: 5px 10px;
        border: 1px solid #007bff;
        border-radius: 5px;
        background-color: dodgerblue;
        color: white;
        cursor: pointer;
    }

    .pagination-button.active {
        background-color: #0056b3;
    }

    .pagination-button:hover {
        background-color: #0056b3;
    }
</style>

<!-- JavaScript для загрузки данных о турах и пагинации -->
<script>
    let currentPage = 1;
    const toursPerPage = 10; // Количество туров на странице
    let allTours = [];

    async function fetchTours() {
        const companyId = localStorage.getItem('company_id'); // Получаем ID компании из localStorage
        try {
            const response = await fetch(`${local_url}/api/tour/items/tours/company/${companyId}`);
            if (!response.ok) throw new Error('Не удалось получить данные о турах');

            const data = await response.json();
            allTours = data.tours;

            if (allTours.length === 0) {
                document.getElementById('tours-tbody').innerHTML = '<tr><td colspan="3">Нет туров для этой компании.</td></tr>';
                return;
            }

            displayTours();
        } catch (error) {
            console.error('Ошибка при получении туров:', error);
            document.getElementById('tours-tbody').innerHTML = `<tr><td colspan="3">${error.message}</td></tr>`;
        }
    }

    function displayTours() {
    const toursContainer = document.getElementById('tours-tbody');
    toursContainer.innerHTML = '';

    const start = (currentPage - 1) * toursPerPage;
    const end = start + toursPerPage;
    const paginatedTours = allTours.slice(start, end);

    paginatedTours.forEach((tour, i) => {
        const tourRow = document.createElement('tr');
        
        // Определяем цвет статуса: зеленый для 'active', красный для остальных
        const statusColor = tour.status === 'active' ? 'green' : 'red';

        // URL изображения
        const imageUrl = tour.images.length > 0 ? `${local_url}/api/tour/${tour.images[0]}` : 'path/to/default/image.jpg';

        // HTML-разметка строки таблицы
        tourRow.innerHTML = `
            <td>${tour.title}</td>
            <td>
                <img src="${imageUrl}" alt="Изображение тура" style="width: 100px;">
            </td>
            <td>
                <button id="connectButton" onclick="changeStatus(${tour.id}, ${i})" class=" connectButton btn ${ tour.status === "active" ? "btn-success" : "btn-danger"} btn-addon" data-id="${tour.id}">
                    <i id="connectIcon" class="${ tour.status === "active" ? "ti-power-on" : "ti-power-off"}"></i>
                    <span id="connectText">${ tour.status === "active" ? "On" : "Off"}</span>
                </button>
            </td>
            <td>
                <button class="btn btn-danger" onclick="deleteTour(${tour.id})">Delete</button>
                <button class="btn btn-info" onclick="viewTourInfo(${tour.id})">Info</button>
            </td>
        `;
        toursContainer.appendChild(tourRow);
        console.log(tour.status)
    });

    setupPagination();
}

async function changeStatus(id, i){
    var button = document.querySelectorAll('.connectButton')[i];
    var tourId = button.getAttribute("data-id")
    var icon = button.querySelector('#connectIcon');
    var text = button.querySelector('#connectText');

    if (button.classList.contains('btn-success')) {
        
        let formData = new FormData();
        formData.append("status", "inactive");

        button.classList.remove('btn-success');
        button.classList.add('btn-danger');
        icon.classList.remove('ti-power-on');
        icon.classList.add('ti-power-off');
        text.textContent = 'Off';
        
        try {
            console.log(tourId)

            const response = await fetch(`${local_url}/api/tour/item/${tourId}/status`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem("access_token")}`
                },
                body:  formData
            });

            if (!response.ok) {
                const errorResponse = await response.json();
                alert(`Error: ${errorResponse.message || 'Unknown error'}`);
                return;
            }

            alert('Tour updated successfully!');
            // window.location.href = 'services.php';
        } catch (error) {
            console.error('Error updating tour:', error);
            alert('Failed to update tour. Please try again later.');
        }
        
    } else {
        let formData = new FormData();
        formData.append("status", "active");

        button.classList.remove('btn-danger');
        button.classList.add('btn-success');
        icon.classList.remove('ti-power-off');
        icon.classList.add('ti-power-on');
        text.textContent = 'On';
        try {
            const response = await fetch(`${local_url}/api/tour/item/${tourId}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem("access_token")}`
                },
                body:  formData
            });

            if (!response.ok) {
                const errorResponse = await response.json();
                alert(`Error: ${errorResponse.message || 'Unknown error'}`);
                return;
            }

            alert('Tour updated successfully!');
            // window.location.href = 'services.php';
        } catch (error) {
            console.error('Error updating tour:', error);
            alert('Failed to update tour. Please try again later.');
        }
    }
}
// Функция для обработки нажатия кнопки Info
function viewTourInfo(tourId) {
    // Логика для просмотра информации о туре
    window.location.href = `tourInfo.php?tourId=${tourId}`;
}

function deleteTour(tourId) {
    // Запрашиваем подтверждение у пользователя
    const confirmation = confirm('Вы уверены, что хотите удалить этот тур?');

    if (confirmation) {
        // Если пользователь подтвердил удаление, отправляем DELETE-запрос
        fetch(`${local_url}/api/tour/item/${tourId}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('access_token')}` // Устанавливаем токен для авторизации
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Tour and related images deleted successfully') {
                alert('Тур и связанные изображения успешно удалены.');
                fetchTours(); // Перезагружаем список туров после удаления
            } else {
                alert(`Ошибка: ${data.message}`);
            }
        })
        .catch(error => {
            console.error('Ошибка при удалении тура:', error);
            alert('Произошла ошибка при удалении тура.');
        });
    } else {
        // Если пользователь отменил действие, ничего не делаем
        alert('Удаление тура отменено.');
    }
}




    function setupPagination() {
        const paginationControls = document.getElementById('pagination');
        paginationControls.innerHTML = '';

        const totalTours = allTours.length;
        const totalPages = Math.ceil(totalTours / toursPerPage);

        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement('button');
            pageButton.innerText = i;
            pageButton.className = 'pagination-button';
            pageButton.onclick = () => {
                currentPage = i;
                displayTours();
            };

            if (i === currentPage) {
                pageButton.classList.add('active');
            }

            paginationControls.appendChild(pageButton);
        }
    }

    // Загружаем туры при загрузке страницы
    fetchTours();
</script>

<?php include './components/footer.php' ?>