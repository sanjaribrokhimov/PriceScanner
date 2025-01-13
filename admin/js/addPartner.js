document.getElementById('partner-add-btn').addEventListener('click', function() {
    document.getElementById('popup-wrapper').classList.remove('hidden');
});

document.getElementById('close-popup').addEventListener('click', function() {
    document.getElementById('popup-wrapper').classList.add('hidden');
});

function submitForm() {

    const formData = {
        first_name: document.getElementById("first_name").value,
        last_name: document.getElementById("last_name").value,
        email: document.getElementById("email").value,
        phone_number: document.getElementById("phone_number").value,
        city: document.getElementById("city").value,
        company_id: new URLSearchParams(window.location.search).get('company_id'),
        password: document.getElementById("password").value,
        category: document.getElementById("category").value,
        
    };

    fetch(`${local_url}/api/admin/companies/assign_partner`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem("access_token")}` 
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(result => {
        if (result.message) {
            alert(result.message);
            document.getElementById('popup-wrapper').classList.add('hidden');
        } else if (result.error) {
            alert(result.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred.');
    });
}
