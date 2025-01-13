<?php include './components/header.php' ?>


<div id="main-content">
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
                                <form id="carForm">
                                    <div class="form-group">
                                        <label for="model">Model:</label>
                                        <input type="text" class="form-control" id="model" name="model">
                                    </div>

                                    <div class="form-group">
                                        <label for="price">Price:</label>
                                        <input type="number" class="form-control" id="price" name="price">
                                    </div>

                                    <div class="form-group">
                                        <label for="comment">Comment:</label>
                                        <textarea class="form-control" id="comment" name="comment"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="color">Color:</label>
                                        <input type="text" class="form-control" id="color" name="color">
                                    </div>

                                    <div class="form-group">
                                        <label for="seats">Seats:</label>
                                        <input type="number" class="form-control" id="seats" name="seats">
                                    </div>

                                    <div class="form-group">
                                        <label for="fuel_type">Fuel Type:</label>
                                        <select class="form-control" id="fuel_type" name="fuel_type">
                                            <option value="">Select fuel type</option>
                                            <option value="Petrol">Petrol</option>
                                            <option value="Diesel">Diesel</option>
                                            <option value="Electric">Electric</option>
                                            <option value="Hybrid">Hybrid</option>
                                            <option value="Ethanol">Ethanol</option>
                                            <option value="Biodiesel">Biodiesel</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="insurance">Insurance:</label>
                                        <select class="form-control" id="insurance" name="insurance">
                                            <option value="">Select insurance</option>
                                            <option value="Basic">Basic</option>
                                            <option value="Premium">Premium</option>
                                            <option value="Full">Full</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="transmission">Transmission:</label>
                                        <select class="form-control" id="transmission" name="transmission">
                                            <option value="">Select transmission</option>
                                            <option value="Manual">Manual</option>
                                            <option value="Automatic">Automatic</option>
                                            <option value="Semi-Automatic">Semi-Automatic</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="deposit">Deposit:</label>
                                        <input type="number" class="form-control" id="deposit" name="deposit">
                                    </div>

                                    <div class="form-group">
                                        <label for="year">Year:</label>
                                        <input type="number" class="form-control" id="year" name="year">
                                    </div>

                                    <div class="form-group">
                                        <label for="climate">Climate:</label>
                                        <select class="form-control" id="climate" name="climate">
                                            <option value="">Select climate</option>
                                            <option value="Air Conditioning">Air Conditioning</option>
                                            <option value="Climate Control">Climate Control</option>
                                            <option value="Dual Zone Climate Control">Dual Zone Climate Control</option>
                                            <option value="Multi Zone Climate Control">Multi Zone Climate Control</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="category">category:</label>
                                        <select class="form-control" id="category" name="category">
                                            <option value="">Select category</option>
                                            <option value="Start">start</option>
                                            <option value="Comfort">comfort</option>
                                            <option value="Electro">electro</option>
                                            <option value="Premium">Premium</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="image">Image:</label>
                                        <input type="file" class="form-control" id="image" name="image">
                                    </div>

                                    <button type="button" class="btn btn-primary" id="saveButton">Save</button>
                                </form>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    const maxSize = 500 * 1024; // 500 KB в байтах
    
    document.addEventListener('DOMContentLoaded', function() {
        const saveButton = document.getElementById('saveButton');

        saveButton.addEventListener('click', async function() {
            // Валидация формы
            const model = document.getElementById('model').value;
            const price = parseInt(document.getElementById('price').value, 10);
            const comment = document.getElementById('comment').value;
            const color = document.getElementById('color').value;
            const seats = parseInt(document.getElementById('seats').value, 10);
            const fuel_type = document.getElementById('fuel_type').value;
            const insurance = document.getElementById('insurance').value;
            const transmission = document.getElementById('transmission').value;
            const deposit = parseInt(document.getElementById('deposit').value, 10);
            const year = parseInt(document.getElementById('year').value, 10);
            const climate = document.getElementById('climate').value;
            const category = document.getElementById('category').value;
            const imageFile = document.getElementById('image').files[0];

            if (!model) {
                alert("Please select a model.");
                return;
            }
            if (isNaN(price)) {
                alert("Please enter a valid price.");
                return;
            }
            if (!comment) {
                alert("Please enter a comment.");
                return;
            }
            if (!color) {
                alert("Please enter a color.");
                return;
            }
            if (isNaN(seats) || seats > 100) {
                alert("Please enter a valid number of seats (maximum 100).");
                return;
            }
            if (!fuel_type) {
                alert("Please select a fuel type.");
                return;
            }
            if (!insurance) {
                alert("Please select an insurance option.");
                return;
            }
            if (!transmission) {
                alert("Please select a transmission option.");
                return;
            }
            if (isNaN(deposit)) {
                alert("Please enter a valid deposit.");
                return;
            }
            if (isNaN(year)) {
                alert("Please enter a valid year.");
                return;
            }
            if (!climate) {
                alert("Please select a climate option.");
                return;
            }
            if (!category) {
                alert("Please enter a category.");
                return;
            }
            if (!imageFile) {
                alert("Please add an image.");
                return;
            }
            if (imageFile) {
                if (imageFile.size > maxSize) {
                    document.getElementById('image').value = ''; // Сбрасываем выбор файла
                    alert("Файл слишком большой! Пожалуйста, выберите файл меньше 500 KB.");
                    return;
                }
            }

            // Создание FormData
            const formData = new FormData();
            formData.append("model", model);
            formData.append("price", price);
            formData.append("comment", comment);
            formData.append("color", color);
            formData.append("seats", seats);
            formData.append("fuel_type", fuel_type);
            formData.append("insurance", insurance);
            formData.append("transmission", transmission);
            formData.append("deposit", deposit);
            formData.append("year", year);
            formData.append("climate", climate);
            formData.append("category", category);
            formData.append('image', imageFile);
            formData.append('status', 1);

            const companyId = localStorage.getItem('company_id');

            try {
                const response = await fetch(`${local_url}/api/rentcar/companies/${companyId}/cars`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('access_token')}`
                    },
                    body: formData
                });

                const data = await response.json();
                if (response.ok) {
                    alert('Car added successfully!');
                    document.getElementById('carForm').reset();
                    window.location.href = './services.php'
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while adding the car.');
            }
        });
    });
</script>

<?php include './components/footer.php' ?>