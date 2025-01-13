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
</style>
            
<div id="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card alert">
                <div class="card-header">
                    <h4>TOURS</h4>
                </div>
                <br>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="cars-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tour ID</th>
                                    <th>Name</th>
                                    <th>Surname</th>
                                    <th>Created at</th>
                                    <th>Phone</th>
                                    </tr>
                            </thead>
                            <tbody id="tours-tbody">
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


<script>

    const tableBody = document.getElementById("tours-tbody");
    const paginationContainer = document.getElementById("pagination");
    const token = localStorage.getItem('access_token');
    let company_id = localStorage.company_id
    if(!company_id || !token){
        localStorage.clear();
        window.location.href="/login.php"
    }
    let currentPage = 1;
    const perPage = 10;
    const apiUrl = `${local_url}/api/order/tour/items?company_id=${company_id}&per_page=${perPage}`;
    const maxVisibleButtons = 5;

    function renderTable(orders) {
        tableBody.innerHTML = "";
        orders.forEach(order => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${order.id}</td>
                <td><a href="tourInfo.php?tourId=${order.tour_id}" style="color: #2353f6">${order.tour_id}</a></td>
                <td>${order.name}</td>
                <td>${order.surname}</td>
                <td>${new Date(order.created_at).toLocaleString()}</td>
                <td><a href="tel:${order.telephone}" style="color: #2353f6">${order.telephone}</a></td>
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
                const response = await fetch(`${local_url}/api/order/tour/item/${id}/`, {
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

</script>
<?php include './components/footer.php' ?>



