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
                                        <label for="tour_title">Tour Title:</label>
                                        <input type="text" class="form-control" id="tour_title" name="tour_title" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="fromCountry">From Country:</label>
                                        <input type="text" class="form-control" id="fromCountry" name="fromCountry" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="toCountry">To Country:</label>
                                        <input type="text" class="form-control" id="toCountry" name="toCountry" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="category">Category:</label>
                                        <select class="form-control" id="category" name="category" required>
                                            <option value="all">all</option>
                                            <option value="family">Family</option>
                                            <option value="beach">Beach</option>
                                            <option value="sunny">Sunny</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="tour_description">Tour Description:</label>
                                        <textarea class="form-control" id="tour_description" name="tour_description" required></textarea>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="departure_date">Departure Date:</label>
                                            <input type="date" class="form-control" id="departure_date" name="departure_date" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="price">Price:</label>
                                            <input type="number" class="form-control" id="price" name="price" required>
                                        </div>
                                    </div>

                                    <div id="additionalDates">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="extra_date_1">Additional Date:</label>
                                                <input type="date" class="form-control" name="extra_date[]">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="extra_price_1">Additional Price:</label>
                                                <input type="number" class="form-control" name="extra_price[]">
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-secondary" id="addDateButton">Add Date</button>
                                    <button type="button" class="btn btn-danger" id="deleteButton">Delete</button>

                                    <div class="form-group">
                                        <label for="youtube_link">YouTube Video Link:</label>
                                        <input type="url" class="form-control" id="youtube_link" name="youtube_link" placeholder="https://www.youtube.com/watch?v=...">
                                    </div>

                                    <div class="form-group">
                                        <label for="images">Images (up to 10):</label>
                                        <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*" required>
                                        <div id="imagePreview" class="mt-2"></div>
                                    </div>

                                    <button type="button" class="btn btn-primary" id="saveButton">Save</button>
                                </form>
                                <div id="messageDiv" class="mt-3" style="display:none;"></div>
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
let imageFiles = [];

document.getElementById('addDateButton').addEventListener('click', function() {
    const container = document.getElementById('additionalDates');
    const index = container.children.length + 1; // for unique names
    const newRow = document.createElement('div');
    newRow.className = 'form-row';
    newRow.innerHTML = `
        <div class="form-group col-md-6">
            <label for="extra_date_${index}">Additional Date:</label>
            <input type="date" class="form-control" name="extra_date[]">
        </div>
        <div class="form-group col-md-6">
            <label for="extra_price_${index}">Additional Price:</label>
            <input type="number" class="form-control" name="extra_price[]">
        </div>
    `;
    container.appendChild(newRow);
});

document.getElementById('deleteButton').addEventListener('click', function() {
    const container = document.getElementById('additionalDates');
    const lastRow = container.lastChild;
    if (lastRow) {
        container.removeChild(lastRow);
    }
});

document.getElementById('images').addEventListener('change', function(event) {
    const files = Array.from(event.target.files);
    if (imageFiles.length + files.length > 10) {
        showMessage("You can upload a maximum of 10 images.", "error");
        return;
    }

    files.forEach(file => {
        if (file) {
            imageFiles.push(file);
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.style.width = '100px';
            img.style.marginRight = '10px';
            document.getElementById('imagePreview').appendChild(img);
        }
    });
});

function showMessage(message, type) {
    const messageDiv = document.getElementById('messageDiv');
    messageDiv.style.display = 'block';
    messageDiv.className = type === "error" ? "alert alert-danger" : "alert alert-success";
    messageDiv.innerHTML = message;
}

document.getElementById('saveButton').addEventListener('click', async function() {
    const formData = new FormData();
    const errors = [];
    const  category = document.getElementById('category').value;
    const token = localStorage.getItem('access_token');
    const companyId = localStorage.getItem('company_id');
    formData.append('company_id', companyId);
    formData.append('title', document.getElementById('tour_title').value);
    formData.append('description', document.getElementById('tour_description').value);
    formData.append('category', category);

    const fromCountry = document.getElementById('fromCountry').value;
    const toCountry = document.getElementById('toCountry').value;
    const images = Array.from(document.getElementById('images').files);
    
    if (!fromCountry) {
        errors.push("From Country is required.");
    }
    if (!toCountry) {
        errors.push("To Country is required.");
    }

    formData.append('fromCountry', fromCountry);
    formData.append('toCountry', toCountry);
   

    const departureDate = document.getElementById('departure_date').value;
    const price = document.getElementById('price').value;

    if (!departureDate) {
        errors.push("Departure Date is required.");
    }
    if (!price) {
        errors.push("Price is required.");
    }

    if (!images.length) {
        errors.push("images is required.");
    }
    
    images.forEach(image => {
        if(image.size < maxSize){
            formData.append('images', image);
        }else{
            document.getElementById('images').value = ''; // Сбрасываем выбор файла
            
            errors.push("The size of the images is big!");
            errors.push("Max size is 500kb!");
            document.getElementById('imagePreview').innerHTML = null;
        }
    });

    if (errors.length > 0) {
        showMessage(errors.join('<br>'), "error");
        return;
    }
    

    const departure = {
        departure_date: departureDate,
        price: price
    };
    formData.append('departures', JSON.stringify(departure));

    const extraDates = document.getElementsByName('extra_date[]');
    const extraPrices = document.getElementsByName('extra_price[]');
    
    for (let i = 0; i < extraDates.length; i++) {
        if (extraDates[i].value && extraPrices[i].value) {
            const extraDeparture = {
                departure_date: extraDates[i].value,
                price: extraPrices[i].value
            };
            formData.append('departures', JSON.stringify(extraDeparture));
        }
    }

    const youtubeLink = document.getElementById('youtube_link').value;
    if (youtubeLink) {
        formData.append('video_url', youtubeLink);
    }

    if (images.length > 10) {
        showMessage("You can upload a maximum of 10 images.", "error");
        return;
    }


    console.log(formData)

    try {
        const response = await fetch(`${local_url}/api/tour/item`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`
            },
            body: formData
        });

        const result = await response.json();
        if (response.ok) {
            showMessage('Tour added successfully!', "success");
            console.log('Tour added:', result);
            window.location.href = './services.php'
        } else {
            showMessage(`Error: ${result.message}`, "error");
            console.error('Error:', result);
        }
    } catch (error) {
        showMessage('Network error: ' + error.message, "error");
        console.error('Network error:', error);
    }
});
</script>

<?php include './components/footer.php' ?>
