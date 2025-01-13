<?php include("./components/header.php"); ?>

<div id="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card alert">
                <div class="card-header">
                    <h4>COMPANY</h4>
                    <div class="page-title">
                        <button id="company-add-btn" class="btn-company">+company</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Legal name</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Switch</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>user</td>
                                    <td class="color-primary">company</td>
                                    <td>
                                        <button class="btn btn-primary" onclick="window.location.href='companyInfo.php'">Info</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                                    <form id="company-form">
                                        <div class="form-group">
                                            <label>Legal Name</label>
                                            <input id="legal_name" type="text" name="legal_name" class="form-control border-none input-default bg-ash" placeholder="Enter legal name">
                                        </div>
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input id="name" type="text" name="name" class="form-control border-none input-default bg-ash" placeholder="Enter name">
                                        </div>
                                        <div class="form-group">
                                            <label>Category</label>
                                            <select id="category" name="category" class="form-control border-none input-default bg-ash">
                                                <option value="rentcar">Rentcar</option>

                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>City</label>
                                            <input id="city" type="text" name="city" class="form-control border-none input-default bg-ash" placeholder="Enter city">
                                        </div>
                                        <div class="form-group">
                                            <label>District</label>
                                            <input id="district" type="text" name="district" class="form-control border-none input-default bg-ash" placeholder="Enter district">
                                        </div>
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input id="address" type="text" name="address" class="form-control border-none input-default bg-ash" placeholder="Enter address">
                                        </div>
                                        <div class="form-group image-type">
                                            <label>Upload Logo</label>
                                            <input id="logo" type="file" name="logo" accept="image/*">
                                        </div>
                                        <button id="save-btn" class="btn btn-default btn-lg m-b-10 bg-warning border-none m-r-5 sbmt-btn" type="button">Save</button>
                                        <button class="btn btn-default btn-lg m-b-10 m-l-5 sbmt-btn" type="reset">Reset</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button id="close-popup" class="btn btn-danger">Close</button>
    </div>
</div>


<script src="js/addCompany.js"></script>
<script src="js/listCompanies.js"></script>

<script>
    document.querySelector('#logout-btn').addEventListener('click', function() {
        localStorage.clear();
    })

    document.addEventListener("DOMContentLoaded", function() {
        fetch('/api/auth/check-health', {
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