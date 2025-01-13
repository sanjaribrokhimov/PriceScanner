<?php include("./components/header.php"); ?>


<div class="content-wrap">
    <div class="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3">
                    <div class="card">
                        <div class="stat-widget-eight">
                            <div class="stat-header">
                                <div class="header-title pull-left">Users</div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="stat-content">
                                <div class="pull-left">
                                    <i class="ti-arrow-up color-success"></i>
                                    <span class="stat-digit" id="user-count">0</span>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-primary w-70" role="progressbar"
                                    aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">
                                    <span class="sr-only">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card">
                        <div class="stat-widget-eight">
                            <div class="stat-header">
                                <div class="header-title pull-left">Company</div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="stat-content">
                                <div class="pull-left">
                                    <i class="ti-arrow-up color-success"></i>
                                    <span class="stat-digit" id="company-count">0</span>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-primary w-70" role="progressbar"
                                    aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">
                                    <span class="sr-only">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card alert">
            <div class="card-header">
                <h4>Calender</h4>
                <div class="card-header-right-icon">
                    <ul>
                        <li class="card-close" data-dismiss="alert"><i class="ti-close"></i>
                        </li>
                        <li class="doc-link"><a href="#"><i class="ti-link"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="year-calendar"></div>
            </div>
        </div>
        <!-- /# card -->
    </div>
    <!-- /# column -->
</div>
<!-- /# row -->



<script>
    const logoutButtons = document.querySelectorAll('a[href="login.php"]');
    logoutButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            localStorage.clear();
            window.location.href = 'login.php';
        });
    });

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
        document.querySelector('#greeting-header').textContent = "Hello " + localStorage.getItem("first_name")
    })

    const apiEndpoint = `${local_url}/api/admin/dashboard`;

    async function fetchData() {
        try {
            const response = await fetch(apiEndpoint, {
                method: "GET",
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
                    'Accept': 'application/json'
                },
            });
            const data = await response.json();

            document.getElementById('user-count').textContent = data.users;
            document.getElementById('company-count').textContent = data.companies;
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', fetchData);

    setInterval(fetchData, 600000);
</script>

<?php include("./components/footer.php"); ?>