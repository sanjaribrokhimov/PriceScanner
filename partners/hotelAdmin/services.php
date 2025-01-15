<?php include './components/header.php'; ?>
<style>
#hotels-tbody img {
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
    padding: 5px 10px;
    cursor: pointer;
    border: 1px solid #007bff;
    background-color: #f8f9fa;
    color: #007bff;
}

.pagination-btn:hover {
    background-color: #007bff;
    color: white;
}

.btn-active {
    font-weight: bold;
    background-color: #007bff;
    color: white;
}
#rooms-tbody tr:hover
{
    cursor: pointer;
    background:rgb(228, 248, 228) !important;
}
#rooms-tbody tr.active
{
    background:  #cbf5cb;
}
#rooms-tbody tr td{
    background: transparent;
    text-align: left;
}
</style>

<div id="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card alert">
                <div class="card-header">
                    <h4>HOTELS</h4>
                </div>
                <br>
                <div>
                    <button class="btn btn-primary" onclick="window.location.href='addHotel.php'">
                        <i class="bi bi-plus-circle"></i> ADD Hotel
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="hotels-table">
                            <thead>
                                <tr>
                                    <th>Hotel Name</th>
                                    <th>Image</th>
                                    <th>City</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="hotels-tbody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card alert">
                <div class="card-header">
                    <h4>Rooms</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Room type</th>
                                    <th>Bed type</th>
                                    <th>Capacity</th>
                                    <th>Features</th>
                                    <th>Price per Night</th>
                                </tr>
                            </thead>
                            <tbody id="rooms-tbody">
                                <!-- Здесь будут отображаться комнаты -->
                            </tbody>
                        </table>

                        <div id="pagination" class="pagination">
                            <!-- Кнопки пагинации -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Модальное окно для дат-->
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
let allRooms = [];
const months = [
    "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", 
    "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"
];

var hotel_id = '';
const paginationContainer = document.getElementById("pagination");
const maxVisibleButtons = 5;
let currentPage = 1;
const itemsPerPage = 10;

document.addEventListener('DOMContentLoaded', function() {
    const companyId = localStorage.getItem('company_id');
    const apiUrl = `${local_url}/api/hotel/items/company/${companyId}`;
    const imageRenderUrl = `${local_url}/api/hotel`;
    const deleteUrl = `${local_url}/api/hotel/items`; // URL для удаления

    function loadHotels(page) {
        fetch(apiUrl).then(response => response.json())
            .then(data => {
                console.log(data);
                hotel_id = data.hotels[0].hotel_id
                if (data.hotels) {
                    document.querySelectorAll('.card div')[1].style.display = 'none';
                    // Логирование для проверки данных
                    // console.log('Before sorting:', data.hotels);

                    // Сортировка отелей по дате создания (по убыванию)
                    // data.hotels.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

                    // Логирование после сортировки
                    // console.log('After sorting:', data.hotels);

                    populateTable(data.hotels, page);
                    // setupPagination(data.hotels.length);
                    loadRooms()
                } else {
                    alert('No hotels found for this company.');
                }
            })
            .catch(error => {
                console.error('Error fetching hotels:', error);
            });
    }






    

    function populateRooms(rooms) {
        const tbody = document.getElementById('rooms-tbody');
        tbody.innerHTML = '';

        // rooms.sort((a, b) => b.id - a.id)
        console.log(rooms)
        rooms.forEach((room, i) => {
            
            unavailableDates.push(room.availability);
            const row = document.createElement('tr');
            

            // Room ID
            const idCell = document.createElement('td');
            idCell.innerHTML = room.id;
            row.appendChild(idCell);
            idCell.addEventListener('click', function () {
                window.location.href = `room.php?id=${room.id}`;
            });

            // Room Type
            const typeCell = document.createElement('td');
            typeCell.textContent = room.room_type;
            row.appendChild(typeCell);
            
            // Bed Type
            const bedCell = document.createElement('td');
            bedCell.textContent = room.bed_type;
            row.appendChild(bedCell);

            // Capacity
            const capacityCell = document.createElement('td');
            capacityCell.textContent = room.capacity;
            row.appendChild(capacityCell);

            
            // Calendar
            let statusClass = room.availability === [] ? 'status-unavailable' : 'status-available';
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
                        <button onclick="showModal(${room.id}, ${i}, ${false})">Добавить заказ</button>
                        <button onclick="showModal(${room.id}, ${i}, ${true})">Удалить заказ</button>
                    </div>
                </div>
            `
            let addDates = `
                <div class="controls">
                    <button onclick="showModal(${room.id}, ${i}, ${false})">Добавить заказ</button>
                </div>
            `
            const calendarTd = document.createElement('td')
            console.log(room.availability)
            calendarTd.innerHTML = room.availability.length === [] ? addDates : calendar;
            row.appendChild(calendarTd);
            
            // Features
            const featuresCell = document.createElement('td');
            featuresCell.textContent = room.features || 'N/A';
            row.appendChild(featuresCell);

            // Price
            const priceCell = document.createElement('td');
            priceCell.textContent = `${room.price_per_night} sum`;
            row.appendChild(priceCell);

            // Availability
            row.className = room.is_available ? 'active' : 'unactive'; 

            tbody.appendChild(row);
            room.availability === [] ? null : renderCalendar(currentMonth, currentYear, false, i);
        });

        
    }

    function renderPagination(totalPages) {
        paginationContainer.innerHTML = "";

        const createButton = (text, page, disabled = false) => {
            const button = document.createElement("span");
            button.textContent = text;
            button.disabled = disabled;
            button.className = disabled ? "disabled" : page === currentPage ? "active" : "";
            button.addEventListener("click", () => {
                if (!disabled && page !== currentPage) {
                    currentPage = page;
                    loadRooms();
                }
            });
            return button;
        };

        // Кнопка "Назад"
        const prevButton = createButton("←", currentPage - 1, currentPage === 1);
        paginationContainer.appendChild(prevButton);

        // Центральные кнопки
        const startPage = Math.max(1, currentPage - Math.floor(maxVisibleButtons / 2));
        const endPage = Math.min(totalPages, startPage + maxVisibleButtons - 1);

        for (let i = startPage; i <= endPage; i++) {
            const pageButton = createButton(i, i);
            paginationContainer.appendChild(pageButton);
        }

        // Кнопка "Вперёд"
        const nextButton = createButton("→", currentPage + 1, currentPage === totalPages);
        paginationContainer.appendChild(nextButton);
    }
    
    function loadRooms() {
        const fetchUrl = `${local_url}/api/hotel/hotel/${hotel_id}/rooms?page=${currentPage}&per_page=${itemsPerPage}`;
        fetch(fetchUrl, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${localStorage.access_token}`, // Добавляем заголовок с токеном
                'Content-Type': 'application/json',
            },
        })
            .then(response => response.json())
            .then(data => {
                if (data.rooms && data.rooms.length > 0) {
                    allRooms = data.rooms;
                    populateRooms(data.rooms);
                    renderPagination(data.pages)
                } else {
                    alert('No rooms found for this hotel.');
                }
            })
            .catch(error => {
                console.error('Error fetching rooms:', error);
            });
    }

    loadRooms();
    
    
    
    
    


    function populateTable(hotels, page) {
        const tbody = document.getElementById('hotels-tbody');
        tbody.innerHTML = '';

        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedHotels = hotels.slice(start, end);

        paginatedHotels.forEach(hotel => {
            const row = document.createElement('tr');

            // Hotel Name
            const nameCell = document.createElement('td');
            nameCell.textContent = hotel.name;
            row.appendChild(nameCell);

            // Image
            const imageCell = document.createElement('td');
            const img = document.createElement('img');
            img.src = hotel.images.length > 0 ? `${imageRenderUrl}${hotel.images[0]}` :
                'default-image.jpg';
            img.alt = hotel.name;
            imageCell.appendChild(img);
            row.appendChild(imageCell);

            // City
            const cityCell = document.createElement('td');
            cityCell.textContent = hotel.city;
            row.appendChild(cityCell);

            // Status
            const statusCell = document.createElement('td');
            const statusIndicator = document.createElement('span');
            statusIndicator.classList.add('status-indicator');
            if (hotel.status == true || hotel.status == "true" || hotel.status == "True") {
                statusIndicator.classList.add('status-available');
                statusCell.textContent = 'Available';
            } else {
                statusIndicator.classList.add('status-unavailable');
                statusCell.textContent = 'Unavailable';
            }
            statusCell.insertBefore(statusIndicator, statusCell.firstChild);
            row.appendChild(statusCell);

            // Info Button
            const infoCell = document.createElement('td');
            const infoButton = document.createElement('button');
            infoButton.textContent = 'Info';
            infoButton.classList.add('btn', 'btn-info');
            infoButton.addEventListener('click', function() {
                window.location.href = `hotelInfo.php?hotel_id=${hotel.hotel_id}`;
            });
            infoCell.appendChild(infoButton);
            row.appendChild(infoCell);

            // Add Room Button
            const roomCell = document.createElement('td');
            const roomButton = document.createElement('button');
            roomButton.textContent = 'Add Room';
            roomButton.classList.add('btn', 'btn-success', 'add-room');

            roomButton.addEventListener('click', function() {
                // Открытие модального окна
                openModal();
            });

            roomCell.appendChild(roomButton);
            row.appendChild(roomCell);

            // // Delete Button
            // const deleteCell = document.createElement('td');
            // const deleteButton = document.createElement('button');
            // deleteButton.textContent = 'Delete';
            // deleteButton.classList.add('btn', 'btn-danger');
            // deleteButton.addEventListener('click', function() {
            //     if (confirm(`Are you sure you want to delete hotel "${hotel.name}"?`)) {
            //         deleteHotel(hotel.hotel_id);
            //     }
            // });
            // deleteCell.appendChild(deleteButton);
            // row.appendChild(deleteCell);

            tbody.appendChild(row);
        });
    }

    function deleteHotel(hotelId) {
        const accessToken = localStorage.getItem('access_token'); // Получаем токен из localStorage

        if (!hotelId) {
            console.error('Hotel ID is undefined');
            return;
        }

        const deleteUrl = `${local_url}/api/hotel/items/${hotelId}`;
        fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${accessToken}`, // Добавляем заголовок с токеном
                    'Content-Type': 'application/json',
                },
            })
            .then(response => {
                if (response.ok) {
                    alert('Hotel deleted successfully!');
                    window.location.reload()
                    // loadHotels(currentPage);  // Обновляем список отелей
                } else {
                    console.error('Failed to delete hotel, status:', response.status);
                    alert('Error deleting hotel. Status: ' + response.status);
                }
            })
            .catch(error => {
                console.error('Error deleting hotel:', error);
                alert('Error deleting hotel: ' + error.message);
            });
    }


    loadHotels(currentPage);

    // Get modal and elements
    const modal = document.getElementById('customModal');
    const closeBtn = modal.querySelector('.close');
    const saveRoomButton = document.getElementById('saveRoomButton');
    const roomButton = document.createElement('button');
    function openModal() {
        modal.classList.add('show');
    }

    // Close Modal
    function closeModal() {
        modal.classList.remove('show');
    }

    // Close on clicking close button or outside modal content
    closeBtn.addEventListener('click', closeModal);
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });

    const form = document.getElementById('addRoomForm');
    // Handle form submission
    form.addEventListener('submit', (event) => {
        var apiForRoom = `${local_url}/api/hotel/rooms/bulk`;
     
        const num_adults = document.getElementById('num_adults').value.trim();
        const capacity = document.getElementById('capacity').value.trim();
        if(+num_adults > +capacity){
            alert("Hona sig'imi kattalar sonidan katta yoki teng bo'lishi kerak!");
            document.getElementById('num_adults').style.borderColor = "#f00";
            document.getElementById('capacity').style.borderColor = "#f00";
            return;
        }

        const form = document.getElementById('addRoomForm');
        const formData = new FormData(form);
        formData.append('hotel_id', hotel_id);

        const quantity = document.getElementById('quantity');
        // console.log('Room Data:', Object.fromEntries(formData.entries()));
        if(+quantity.value == 1){
            apiForRoom = `${local_url}/api/hotel/room`;
            formData.delete('quantity')
        }
        // console.log(apiForRoom);
        // console.log('Room Data:', Object.fromEntries(formData.entries()));
        const data = {};
        for (let [key, value] of formData.entries()) {
            // Если ключ ожидает числовое значение, преобразуем его
            if (key === 'num_adults' || key === 'price_per_night' || key === "hotel_id" || key === "capacity" || key === "quantity") {
                data[key] = parseFloat(value); // Преобразуем в число с плавающей точкой
            }else if(key === "is_available"){
                data[key] = value === "true" ? true : false;
            }else {
                data[key] = value; // Сохраняем как есть
            }
        }
        // console.log(data)


        // Отправка данных на сервер
        fetch(apiForRoom, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.access_token}`
                },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(data => {
                // console.log(formData);
                alert('Комната успешно добавлена!');
                // Reset form and close modal
                form.reset();
                closeModal();
                if(confirm("Обновить страницу?")){
                    window.location.reload();
                }

            })
            .catch(error => {
                console.error('Ошибка:', error);
                alert('Произошла ошибка при добавлении гостиницы.');
            });
    });

    
});



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
    
    selectedStartDate = null;
    selectedEndDate = null;
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
    selectedStartDate = null;
    selectedEndDate = null;
}

function addReservation() {
    let roomId = document.querySelector('.add-date-button').dataset.id;
    let isDelete = document.querySelector('.add-date-button').dataset.isDelete;
    isDelete = isDelete === "true" ? true : false;

    const startDate = selectedStartDate;
    const endDate = selectedEndDate;

    if (startDate && endDate) {
        let myHeaders = new Headers();
        myHeaders.append("Authorization", `Bearer ${localStorage.access_token}`);

        if (isDelete) {
            let availability = allRooms.find(item => +item.id === +roomId);

            console.log(roomId, allRooms);
            console.log(availability);

            if (!availability) {
                alert("Комната с указанным ID не найден.");
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
                console.log(`${local_url}api/hotel/room/${roomId}/availability/${exactMatch.id}`)


                fetch(`${local_url}/api/hotel/room/${roomId}/availability/${exactMatch.id}`, {
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
                    // alert("Выбранный диапазон теперь доступен.");
                    window.location.reload();

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

                        return fetch(`${local_url}/api/hotel/room/${roomId}/availability/${overlappingAvailability.id}`, {
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
                        // alert("Доступность успешно обновлена.");
                        window.location.reload();
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

            fetch(`${local_url}/api/hotel/room/${roomId}/availability/`, {
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