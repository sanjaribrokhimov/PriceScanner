<?php include './components/header.php'; ?>
<style>
    #cars-tbody img {
        border: 2px solid black;
        border-radius: 4px;
        padding: 5px;
        width: 150px;
        height: 100px;
        object-fit: cover;
    }

    .status-indicator {
        display: inline-block;
        width: 15px;
        height: 15px;
        border-radius: 50%;
    }

    .status-available {
        background-color: green;
    }

    .status-unavailable {
        background-color: red;
    }

    .pagination-btn {
        margin: 0 5px;
    }

    .btn-active {
        font-weight: bold;
        background-color: #007bff;
        color: white;
    }
</style>

<div id="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card alert">
                <div class="card-header">
                    <h4>CARS</h4>
                </div>
                <br>
                <div>
                    <button class="btn btn-primary" onclick="window.location.href='addCar.php'">
                        <i class="bi bi-plus-circle"></i> ADD Car
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="cars-table">
                            <thead>
                                <tr>
                                    <th>Model</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="cars-tbody">
                                <!-- Data will be inserted here -->
                            </tbody>
                        </table>
                    </div>
                    <div id="pagination" style="margin-top: 20px; text-align: center;">
                        <!-- Pagination controls will be inserted here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Модальное окно -->
<div class="modal" id="modal-for-date">
    <div class="modal-content">
        <h2>Выберите даты</h2>
        <div class="month-header" data-id=''>
            <button onclick="prevMonthModal()">&#8592;</button>
            <div id="month-name-modal">Месяц</div>
            <button onclick="nextMonthModal()">&#8594;</button>
        </div>
        <div class="calendar" id="calendar-modal"></div>
        <br>
        <div>
            <label for="start-date">Дата начала:</label>
            <input type="date" id="start-date" readonly>
            <label for="end-date">Дата окончания:</label>
            <input type="date" id="end-date" readonly>
            <br>
            <button class="modal-button add-date-button" onclick="addReservation()" data-set-id="">Добавить</button>
            <button class="modal-button" onclick="closeModal()">Закрыть</button>
        </div>
    </div>
</div>

<script>
    
    var unavailableDates = [];

    let currentMonth = new Date().getMonth();  // Текущий месяц
    let currentYear = new Date().getFullYear(); // Текущий год
    let selectedStartDate = null;
    let selectedEndDate = null;
    let allCars = [];  // Массив для всех машин

    const months = [
        "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", 
        "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"
    ];

    async function changeStatus(id, i){
        var button = document.querySelectorAll('.connectButton')[i];
        var carId = button.getAttribute("data-id")
        var icon = button.querySelector('#connectIcon');
        var text = button.querySelector('#connectText');
        let company_id = localStorage.getItem('company_id');

        const url = `${local_url}/api/rentcar/companies/${company_id}/cars/${carId}`;

        if (button.classList.contains('btn-success')) {
            
            let formData = new FormData();
            formData.append("status", 0);

            button.classList.remove('btn-success');
            button.classList.add('btn-danger');
            icon.classList.remove('ti-power-on');
            icon.classList.add('ti-power-off');
            text.textContent = 'Off';
            
            try {
                console.log(carId)

                const response = await fetch(url, {
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

                alert('Car updated successfully!');
                // window.location.href = 'services.php';
            } catch (error) {
                console.error('Error updating tour:', error);
                alert('Failed to update tour. Please try again later.');
            }
            
        } else {
            let formData = new FormData();
            formData.append("status", 1);

            button.classList.remove('btn-danger');
            button.classList.add('btn-success');
            icon.classList.remove('ti-power-off');
            icon.classList.add('ti-power-on');
            text.textContent = 'On';
            try {
                const response = await fetch(url, {
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


    document.addEventListener("DOMContentLoaded", function() {
        const token = localStorage.getItem('access_token');
        const companyId = localStorage.getItem('company_id');
        const perPage = 10;  // Количество машин на странице
        let currentPage = 1;

        if (!token || !companyId) {
            window.location.href = 'login.php';
            return;
        }

        // Функция для загрузки всех машин
        function fetchAllCars() {
            fetch(`${local_url}/api/rentcar/companies/${companyId}/cars`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message === false) {
                        window.location.href = 'login.php';
                    } else {
                        allCars = data.cars;  // Сохраняем все машины
                        displayCars(currentPage);  // Отображаем машины для первой страницы
                        setupPagination(allCars.length, perPage);  // Настраиваем пагинацию
                    }
                })
                .catch(error => {
                    console.error('Ошибка при загрузке данных:', error);
                    // window.location.href = 'login.php';
                });
        }

        // Функция для отображения машин на текущей странице
        function displayCars(page) {
            console.log(allCars)
            const carsTableBody = document.querySelector('#cars-tbody');
            carsTableBody.innerHTML = '';  // Очищаем таблицу
            const start = (page - 1) * perPage;  // Индекс первой машины на текущей странице
            const end = start + perPage;  // Индекс последней машины на текущей странице
            const carsToShow = allCars.slice(start, end);  // Машины, которые нужно показать
            

            carsToShow.forEach((car, i) => {
                unavailableDates.push(car.availability);

                let statusClass = car.availability === [] ? 'status-unavailable' : 'status-available';
                var calendar = `
                    <div class="cont-cal">
                        <div class="calendar-container">
                            <div class="month-header">
                                <button onclick="prevMonth(${i})">&#8592;</button>
                                <div class="month-name month-name-for-date">Месяц</div>
                                <button onclick="nextMonth(${i})">&#8594;</button>
                            </div>
                            <div class="calendar calendar-for-date"></div>
                        </div>
                        
                        <div class="controls">
                            <button onclick="showModal(${car.id}, ${i}, ${false})">Добавить заказ</button>
                            <button onclick="showModal(${car.id}, ${i}, ${true})">Удалить заказ</button>
                        </div>
                    </div>
                `
                let addDates = `
                    <div class="controls">
                        <button onclick="showModal(${car.id}, ${i}, ${false})">Добавить заказ</button>
                    </div>
                `
                let statusText = car.availability === [] ? addDates : calendar;

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${car.model}</td>
                    <td><img src="data:image/jpeg;base64,${car.image}" alt="${car.model}"></td>
                    <td>${statusText}</td>
                    <td>
                    
                        <button id="connectButton" onclick="changeStatus(${car.id}, ${i})" class=" connectButton btn ${ car.status ? "btn-success" : "btn-danger"} btn-addon" data-id="${car.id}">
                            <i id="connectIcon" class="${ car.status === "active" ? "ti-power-on" : "ti-power-off"}"></i>
                            <span id="connectText">${ car.status === "active" ? "On" : "Off"}</span>
                        </button>
                        <button class="btn btn-primary" onclick="window.location.href='carInfo.php?car_id=${car.id}'">Info</button>
                        <button class="btn btn-danger delete-btn" data-car-id="${car.id}">Delete</button>
                    </td>
                `;
                carsTableBody.appendChild(row);
                car.availability === [] ? null : renderCalendar(currentMonth, currentYear, false, i);

            });

            // Привязываем обработчик события удаления для каждой кнопки Delete
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const carId = this.getAttribute('data-car-id');
                    confirmDelete(carId);
                });
            });
        }


        // Функция для настройки пагинации
        function setupPagination(totalCars, carsPerPage) {
            const paginationContainer = document.getElementById('pagination');
            paginationContainer.innerHTML = '';  // Очищаем контейнер

            const totalPages = Math.ceil(totalCars / carsPerPage);  // Количество страниц

            for (let page = 1; page <= totalPages; page++) {
                const pageItem = document.createElement('button');
                pageItem.textContent = page;
                pageItem.classList.add('btn', 'pagination-btn');

                if (page === currentPage) {
                    pageItem.classList.add('btn-primary', 'btn-active');  // Подсветка текущей страницы
                } else {
                    pageItem.classList.add('btn-secondary');
                }

                pageItem.addEventListener('click', function() {
                    currentPage = page;
                    displayCars(currentPage);  // Обновляем отображаемые машины
                    updatePagination(currentPage, totalPages);  // Обновляем пагинацию
                });

                paginationContainer.appendChild(pageItem);
            }
        }

        // Функция для обновления кнопок пагинации при смене страницы
        function updatePagination(currentPage, totalPages) {
            const paginationButtons = document.querySelectorAll('.pagination-btn');

            paginationButtons.forEach((button, index) => {
                if (index + 1 === currentPage) {
                    button.classList.add('btn-primary', 'btn-active');
                    button.classList.remove('btn-secondary');
                } else {
                    button.classList.add('btn-secondary');
                    button.classList.remove('btn-primary', 'btn-active');
                }
            });
        }

        // Функция для удаления машины
        function confirmDelete(carId) {
            if (confirm("Delete car?")) {
                deleteCar(carId);  // Если пользователь подтвердил удаление, вызываем функцию удаления
            }
        }

        // Функция для отправки запроса на удаление машины
        function deleteCar(carId) {
            fetch(`${local_url}/api/rentcar/companies/${companyId}/cars/${carId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message === 'Car successfully deleted') {
                        alert('Car deleted successfully!');
                        fetchAllCars();  // Обновляем список машин после удаления
                    } else {
                        alert('Failed to delete car');
                    }
                })
                .catch(error => {
                    console.error('Ошибка при удалении машины:', error);
                    alert('An error occurred. Please try again.');
                });
        }

        // Изначально загружаем все машины и первую страницу данных
        fetchAllCars();
    });

    // Функция для генерации календаря
    function renderCalendar(month, year, isModal = false, index = 0) {
        const calendar = isModal ? document.getElementById('calendar-modal') : document.querySelectorAll('.calendar-for-date')[index];
        const monthName = isModal ? document.getElementById('month-name-modal') : document.querySelectorAll('.month-name-for-date')[index];

        calendar.innerHTML = '';  // Очищаем старый календарь
        monthName.textContent = `${months[month]} ${year}`; // Обновляем название месяца

        const firstDayOfMonth = new Date(year, month, 1);
        const lastDayOfMonth = new Date(year, month + 1, 0);
        const daysInMonth = lastDayOfMonth.getDate();
        const startDay = firstDayOfMonth.getDay(); // День недели для первого числа месяца

        const daysOfWeek = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];
        daysOfWeek.forEach(day => {
            const dayElement = document.createElement('div');
            dayElement.textContent = day;
            calendar.appendChild(dayElement);
        });

        for (let i = 0; i < startDay; i++) {
            calendar.appendChild(document.createElement('div'));
        }

        for (let i = 1; i <= daysInMonth; i++) {
            const dayElement = document.createElement('div');
            dayElement.classList.add('day');
            dayElement.textContent = i;

            const currentDate = `${year}-${(month + 1).toString().padStart(2, '0')}-${i.toString().padStart(2, '0')}`;
            if (isUnavailable(currentDate, index)) {
                dayElement.classList.add('unavailable');
            }

            if (selectedStartDate && selectedEndDate) {
                if (currentDate === selectedStartDate) {
                    dayElement.classList.add('selected-start');
                } else if (currentDate === selectedEndDate) {
                    dayElement.classList.add('selected-end');
                } else if (currentDate > selectedStartDate && currentDate < selectedEndDate) {
                    dayElement.classList.add('in-range');
                }
            }

            dayElement.onclick = () => handleDateSelection(currentDate, index);
            calendar.appendChild(dayElement);
        }
    }

    function isUnavailable(date, i) {
        return unavailableDates[i].some(range => {
            const start = range.start_date;
            const end = range.end_date;
            return date >= start && date <= end;
        });
    }

    function handleDateSelection(date, index) {
        if (!selectedStartDate) {
            selectedStartDate = date;
            document.getElementById('start-date').value = selectedStartDate;
            document.getElementById('end-date').value = ''; // Сбросить end_date
        } else if (!selectedEndDate) {
            if (date >= selectedStartDate) {
                selectedEndDate = date;
                document.getElementById('end-date').value = selectedEndDate;
            } else {
                selectedEndDate = selectedStartDate;
                selectedStartDate = date;
                document.getElementById('start-date').value = selectedStartDate;
                document.getElementById('end-date').value = selectedEndDate;
            }
        } else {
            // Если обе даты выбраны, снова установить только start_date
            selectedStartDate = date;
            selectedEndDate = null;
            document.getElementById('start-date').value = selectedStartDate;
            document.getElementById('end-date').value = '';
        }

        renderCalendar(currentMonth, currentYear, true, index); // Обновляем календарь в модальном окне
    }

    function showModal(car_id, index, isDelete) {
        document.querySelector('.month-header').dataset.id = index;
        document.querySelector('.month-header').dataset.isDelete = isDelete;
        document.querySelector('.add-date-button').textContent = isDelete ? 'Удалить' : 'Добавить'
        document.querySelector('.add-date-button').dataset.isDelete = isDelete;
        document.querySelector('.add-date-button').dataset.id = car_id;
        document.getElementById('modal-for-date').classList.add('show')
        renderCalendar(currentMonth, currentYear, true, index);
    }

    function closeModal() {
        document.getElementById('modal-for-date').classList.remove('show')
    }

    function addReservation() {
        let carId = document.querySelector('.add-date-button').dataset.id;
        let isDelete = document.querySelector('.add-date-button').dataset.isDelete;
        isDelete = isDelete === "true" ? true : false;

        const startDate = selectedStartDate;
        const endDate = selectedEndDate;

        if (startDate && endDate) {
            let myHeaders = new Headers();
            myHeaders.append("Authorization", `Bearer ${localStorage.access_token}`);

            if (isDelete) {
                let availability = allCars.find(item => +item.id === +carId);

                console.log(carId, allCars);
                console.log(availability);

                if (!availability) {
                    alert("Автомобиль с указанным ID не найден.");
                    return;
                }

                availability = availability.availability;
                console.log(availability);

                // Проверяем, совпадает ли выбранный диапазон с уже существующим
                let exactMatch = availability.find(item => {
                    return new Date(item.start_date).getTime() === new Date(startDate).getTime() &&
                        new Date(item.end_date).getTime() === new Date(endDate).getTime();
                });

                if (exactMatch) {
                    // Если диапазон полностью совпадает, отправляем запрос с is_available: 1
                    const formData = new FormData();
                    formData.append("start_date", startDate);
                    formData.append("end_date", endDate);
                    formData.append("is_available", "1");

                    fetch(`${local_url}/api/rentcar/availability/cars/${carId}/${exactMatch.id}`, {
                        method: "PUT",
                        headers: myHeaders,
                        body: formData,
                        redirect: "follow"
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`Ошибка обновления: ${response.status}`);
                        }
                        return response.text();
                    })
                    .then(result => {
                        console.log("Обновление доступности выполнено успешно:", result);
                        window.location.reload();
                        // alert("Выбранный диапазон теперь доступен.");
                    })
                    .catch(error => {
                        console.error("Ошибка при обновлении доступности:", error);
                        alert("Произошла ошибка при обновлении.");
                    });
                } else {
                    // Логика разделения диапазона, если есть пересечения, но полного совпадения нет
                    let overlappingAvailability = availability.find(item => {
                        return (new Date(item.start_date) <= new Date(endDate)) &&
                            (new Date(item.end_date) >= new Date(startDate));
                    });

                    if (overlappingAvailability) {
                        let newAvailability = [];

                        if (new Date(overlappingAvailability.start_date) < new Date(startDate)) {
                            let adjustedEndDate = new Date(startDate);
                            adjustedEndDate.setDate(adjustedEndDate.getDate() - 1);
                            newAvailability.push({
                                start_date: overlappingAvailability.start_date,
                                end_date: adjustedEndDate.toISOString().split('T')[0],
                                is_available: 0
                            });
                        }

                        if (new Date(overlappingAvailability.end_date) > new Date(endDate)) {
                            let adjustedStartDate = new Date(endDate);
                            adjustedStartDate.setDate(adjustedStartDate.getDate() + 1);
                            newAvailability.push({
                                start_date: adjustedStartDate.toISOString().split('T')[0],
                                end_date: overlappingAvailability.end_date,
                                is_available: 0
                            });
                        }

                        // Обновляем доступность на сервер
                        Promise.all(newAvailability.map(item => {
                            const formData = new FormData();
                            formData.append("start_date", item.start_date);
                            formData.append("end_date", item.end_date);
                            formData.append("is_available", "0");

                            return fetch(`${local_url}/api/rentcar/availability/cars/${carId}/${overlappingAvailability.id}`, {
                                method: "PUT",
                                headers: myHeaders,
                                body: formData,
                                redirect: "follow"
                            });
                        }))
                        .then(responses => {
                            return Promise.all(responses.map(response => {
                                if (!response.ok) {
                                    throw new Error(`Ошибка обновления диапазона: ${response.status}`);
                                }
                                return response.text();
                            }));
                        })
                        .then(results => {
                            console.log("Обновление успешно выполнено:", results);
                            window.location.reload()
                            // alert("Доступность успешно обновлена.");
                        })
                        .catch(error => {
                            console.error("Ошибка обновления:", error);
                            alert("Произошла ошибка при обновлении доступности.");
                        });
                    } else {
                        alert("Диапазон дат не найден в доступности.");
                    }
                }


            } else {
                // Добавляем новую запись, если isDelete = false
                const formdata = new FormData();
                formdata.append("start_date", `${startDate}`);
                formdata.append("end_date", `${endDate}`);
                formdata.append("is_available", "0");

                fetch(`${local_url}/api/rentcar/availability/cars/${carId}/`, {
                    method: "POST",
                    headers: myHeaders,
                    body: formdata,
                    redirect: "follow"
                })
                .then(response => response.text())
                .then(result => {
                    console.log(result);
                    window.location.reload();
                })
                .catch(error => console.error(error));
            }

            closeModal();
        } else {
            alert("Пожалуйста, выберите даты.");
        }
    }



    function nextMonthModal() {
        let index = document.querySelector('.month-header').dataset.id;

        if (currentMonth === 11) {
            currentMonth = 0;
            currentYear++;
        } else {
            currentMonth++;
        }
        renderCalendar(currentMonth, currentYear, true, index);
    }

    function prevMonthModal() {
        let index = document.querySelector('.month-header').dataset.id;
        if (currentMonth === 0) {
            currentMonth = 11;
            currentYear--;
        } else {
            currentMonth--;
        }
        renderCalendar(currentMonth, currentYear, true, index);
    }

    function nextMonth(i) {
        if (currentMonth === 11) {
            currentMonth = 0;
            currentYear++;
        } else {
            currentMonth++;
        }
        renderCalendar(currentMonth, currentYear, false, i);
    }

    function prevMonth(i) {
        if (currentMonth === 0) {
            currentMonth = 11;
            currentYear--;
        } else {
            currentMonth--;
        }
        renderCalendar(currentMonth, currentYear, false, i);
    }


</script>

<?php include './components/footer.php'; ?>
