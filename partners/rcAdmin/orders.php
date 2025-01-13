<?php include './components/header.php' ?>

<style>
    #pagination
    {
        position: relative;
        margin: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #pagination span 
    {
        user-select: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        margin: 0 10px;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: .2s;
    }
    #pagination span:hover
    {
        background: #03a9f555;
    }
    #pagination span:nth-child(1){
        position: absolute;
        left: 0;
    }
    #pagination span:nth-last-child(1){
        position: absolute;
        right: 0;
    }
    #pagination span.active
    {
        background: #03a9f5;
        color: #fff;
    }
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

    .not-seen td:nth-child(1)
    {
        background-color: #f004;
    }
    td:nth-child(1)
    {
        background-color: transparent;
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
                    <h4>CARS</h4>
                </div>
                <br>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="cars-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Car ID</th>
                                    <th>Name</th>
                                    <th>Surname</th>
                                    <th>Created at</th>
                                    <th>Phone</th>
                                    <th>Comments</th>
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

    const tableBody = document.getElementById("cars-tbody");
    const paginationContainer = document.getElementById("pagination");
    const token = localStorage.getItem('access_token');
    let company_id = localStorage.company_id
    var a = '';
    if(!company_id || !token){
        localStorage.clear();
        window.location.href="/login.php"
    }
    let currentPage = 1;
    const perPage = 10;
    const apiUrl = `${local_url}/api/order/rco-by-company?company_id=${company_id}&per_page=${perPage}`;
    const maxVisibleButtons = 5;

    function renderTable(orders) {
        tableBody.innerHTML = "";
        orders.forEach(order => {

            let dates = order.comment ? order.comment.split('').splice(-21).join('') : '';
            let comment = order.comment ? order.comment.slice(0, -21) : '';
            // console.log(dates)
            // console.log(comment)

            const row = document.createElement("tr");
            row.dataset.produtId = `${order.id}`
            row.innerHTML = `
                <td onclick="hasSeen(${order.id})">${order.id}</td>
                <td><a href="carInfo.php?car_id=${order.car_id}" style="color: #2353f6">${order.car_id}</a></td>
                <td>${order.name}</td>
                <td>${order.surname}</td>
                <td>${new Date(order.created_at).toLocaleString()}</td>
                <td><a href="tel:${order.telephone}" style="color: #2353f6">${order.telephone}</a></td>
                <td>
                    ${dates}
                    <br>
                    ${comment}
                </td>
                <td onclick="changeStatus(${order.id})">${order.status}</td>
                <td>
                    <button class="btn btn-primary btn-danger" onclick="deleteOrder(${order.id})" data-order-id="${order.id}" type="button">
                        <i class="ti-trash"></i>
                    </button>
                </td>
            `;
            tableBody.appendChild(row);

            // Now let's send the request to the API for each order
            

        });
    }
    function hasSeen(order_id){
        const apiURL = `${local_url}/api/order/has-seen/${order_id}`; // Replace with your actual API URL
        const orderType = 'hotel'; // Assuming you have an order_type field in the order object

        // Send the request
        fetch(`${apiURL}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({
                order_type: orderType,
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Request was successful:', data);
            // You can handle the response here (e.g., update the UI based on the API response)
        })
        .catch(error => {
            console.error('Error with the request:', error);
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
    var globalData = '';
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
            // console.log(data)
            if(globalData == data) console.log('danniye te je')
            else{
                globalData = data;
                orders = data.orders;
                renderTable(data.orders);
                renderPagination(data.pages);
            }
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
                const response = await fetch(`${local_url}/api/order/rco/item/${id}`, {
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
                const response = await fetch(`${local_url}/api/order/rco/item`, {
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



