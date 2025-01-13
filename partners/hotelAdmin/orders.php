<?php include './components/header.php' ?>

<style>
    
    .card-body td
    {
        text-align: left !important;
    }
    table tbody td:nth-last-child(1){
        /* width: max-content; */
        display: flex;
        justify-content: flex-end;
    }
    table tbody tr
    {
        cursor: pointer;
    }
    table tbody tr:nth-child(odd)
    {
        background: #03a9f511;
    }
    table tbody tr:hover
    {
        background: #03a9f533;
    }
    .orderData
    {
        display: flex;
        justify-content: space-between;
        padding: 10px 5px;
    }
    .orderData:nth-child(odd){
        background-color: #ccc;
    }

    label
    {
        margin: 25px 0 0 0;
    }
    #changeOrderStatus
    {
        background-color: #03a9f5;
        color: #fff;
    }
    #changeOrderStatus:hover
    {
        background-color: #27bbff;
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
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="cars-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Hotel ID</th>
                                    <th>Name</th>
                                    <th>Surname</th>
                                    <th>Created at</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    </tr>
                            </thead>
                            <tbody id="hotels-tbody">
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


<!-- Modal change order -->
<div id="order-status-edit" class="modal fade">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Change status</h2>
        <form id="changeStatus">
            <div class="form-group">

                <div class="orderData">
                    <div>Name</div>
                    <div id="clientName"></div>
                </div>
                <div class="orderData">
                    <div>Surname</div>
                    <div id="clientSurname"></div>
                </div>
                <div class="orderData">
                    <div>Phone</div>
                    <div id="clientPhone"></div>
                </div>

                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status">
                    <option value="">IN PROGRESS</option>
                    <option value="Viewed">Viewed</option>
                    <option value="Closed">Closed</option>
                </select>
            </div>
            <button type="submit" id="changeOrderStatus" class="btn">Change status</button>
        </form>
    </div>
</div>







<script>

    const tableBody = document.getElementById("hotels-tbody");
    const paginationContainer = document.getElementById("pagination");
    const token = localStorage.getItem('access_token');
    let company_id = localStorage.company_id
    if(!company_id || !token){
        localStorage.clear();
        window.location.href="/login.php"
    }
    let currentPage = 1;
    const perPage = 10;

    const apiUrl = `${local_url}/api/order/hotel/${company_id}?per_page=${perPage}`;
    const maxVisibleButtons = 5;

    function renderTable(orders) {
        console.log(orders)
        tableBody.innerHTML = "";
        orders.forEach(order => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${order.id}</td>
                <td><a href="hotelInfo.php?hotel_id=${order.hotel_id}" style="color: #2353f6">${order.hotel_id}</a></td>
                <td>${order.name}</td>
                <td>${order.surname}</td>
                <td>${new Date(order.created_at).toLocaleString()}</td>
                <td><a href="tel:${order.telephone}" style="color: #2353f6">${order.telephone}</a></td>
                <td onclick="changeStatus(${order.id})">${order.status}</td>
                <td>
                    <button class="btn btn-primary btn-danger" onclick="deleteOrder(${order.id})" data-order-id="${order.id}" type="button">
                        <i class="ti-trash"></i>
                    </button>
                </td>
            `;
            tableBody.appendChild(row);
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
                    fetchData();
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

    async function fetchData() {
        try {
            const response = await fetch(`${apiUrl}&page=${currentPage}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            });
            const data = await response.json();
                
            orders = data.orders
            renderTable(data.orders);
            renderPagination(data.pages);
        } catch (error) {
            console.error("Error fetching data:", error);
        }
    }
    fetchData();
    setInterval(function(){
        fetchData()
    }, 5000);       
    
    async function deleteOrder(id) {
        var bol = confirm('Точно хотите удалить?')
        if(bol){
            try {
                const response = await fetch(`${local_url}/api/order/hotel/orders/${id}/`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (response.ok) {
                    console.log(`Record with ID ${id} deleted successfully.`);
                    fetchData(); // Функция для обновления таблицы
                } else {
                    const errorData = await response.json();
                    console.error(`Failed to delete record: ${errorData.message}`);
                }
            } catch (error) {
                console.error("Error deleting record:", error);
            }
        }
    }
    
    const orderModal = document.getElementById('order-status-edit');
    const closeBtn = orderModal.querySelector('.close');
    const changeStatusButton = document.getElementById('changeOrderStatus');

    function successModal(){
        var success = document.getElementById('success');
        success.classList.add()
    }
    function openModal(order_id) {
        orderModal.classList.add('show');
        var foundProduct = orders.find(item => item.id === +order_id);
        document.getElementById('clientName').innerText = foundProduct.name
        document.getElementById('clientSurname').innerText = foundProduct.surname
        document.getElementById('clientPhone').innerHTML = `<a href="tel:${foundProduct.telephone}" style="color: #2353f6">${foundProduct.telephone}</a>`
        changeStatusButton.dataset.orderId = order_id;
    }
    // Close Modal
    function closeModal() {
        orderModal.classList.remove('show');
    }
    // Close on clicking close button or outside modal content
    closeBtn.addEventListener('click', closeModal);
    window.addEventListener('click', (event) => {
        if (event.target === orderModal) {
            closeModal();
        }
    });
    function changeStatus(order_id){
        openModal(order_id);
    }
    changeStatusButton.addEventListener('click', async function(event){
        var order_id = event.target.dataset.orderId;
        var status = document.getElementById('status').value;
        if(status){
            try {
                // Ожидаем завершения fetch
                const response = await fetch(`${local_url}/api/order/hotel/${order_id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`,
                    },
                    body: JSON.stringify({
                        order_id: order_id,
                        status: status,
                    })
                });

                // Парсим ответ как JSON
                const responseData = await response.json();

                // Если запрос прошел успешно, выводим сообщение
                if (response.ok) {
                    alert(`Заказ с ID ${order_id} был успешно обновлен.`);
                    document.getElementById('status').value = '';
                    closeModal()
                } else {
                    console.error(`Не удалось обновить статус заказа: ${responseData.message}`);
                }
            } catch (error) {
                console.error("Ошибка при отправке запроса:", error);
            }

        }
    })

</script>



<?php include './components/footer.php' ?>



