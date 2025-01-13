<?php include("./components/header.php"); ?>

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
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div id="pagination">
</div>



<script>
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