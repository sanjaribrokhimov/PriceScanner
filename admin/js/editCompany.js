document.getElementById('partner-edit-company-btn').addEventListener('click', function() {
    document.getElementById('popup-wrapper-ec').classList.remove('hidden');
});

document.getElementById('close-popup-ec').addEventListener('click', function() {
  document.getElementById('popup-wrapper-ec').classList.add('hidden');
});

document.getElementById('partner-edit-tg-chat').addEventListener('click', function() {
    document.getElementById('popup-wrapper-tg').classList.remove('hidden');
});
document.getElementById('close-popup-tg').addEventListener('click', function() {
  document.getElementById('popup-wrapper-tg').classList.add('hidden');
});


document.getElementById("save-btn-ec").addEventListener('click', async (event) => {
    event.preventDefault();
  
    const formData = new FormData();
    formData.append('legal_name', document.getElementById('legal-name').value);
    formData.append('name', document.getElementById('company-name').value);
    formData.append('category', document.getElementById('company-category').value);
    formData.append('city', document.getElementById('company-city').value);
    formData.append('district', document.getElementById('company-district').value);
    formData.append('address', document.getElementById('company-address').value);
  
    const logoFile = document.getElementById('logo').files[0];
    if (logoFile) {
      formData.append('logo', logoFile);
    }
  
    try {
      const response = await fetch(`${local_url}/api/admin/company/${new URLSearchParams(window.location.search).get('id')}`, {
        method: 'PUT',
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('access_token')}`,
        },
        body: formData
      });
  
      const result = await response.json();
      if (response.ok) {
        alert('Company updated successfully');
      } else {
        alert(`Error: ${result.error}`);
      }
    } catch (error) {
      console.error('Error:', error);
      alert('An error occurred while creating the company.');
    }
  });


  
document.getElementById("save-btn-tg").addEventListener('click', async (event) => {
  event.preventDefault();
  const myHeaders = new Headers();
  myHeaders.append("Content-Type", "application/json");
  myHeaders.append("Authorization", `Bearer ${localStorage.access_token}`);

  const raw = JSON.stringify({
      "chat_id": `${document.getElementById("tg-chat-id").value}`,
      "thread_id": `${document.getElementById("thread-id").value}`,
      // "company_id": document.getElementById("save-btn-tg").dataset.userId
      "company_id": 1
  });
  console.log(raw)

  const requestOptions = {
      method: "POST",
      headers: myHeaders,
      body: raw,
      redirect: "follow"
  };

  fetch(`${local_url}/api/admin/chat_id`, requestOptions)
      .then((response) => response.text())
      .then((result) => {
        console.log(result)
        if(JSON.parse(result).message === 'New ChatId added successfully' || JSON.parse(result).message === 'ChatId entry updated successfully'){
          alert('New ChatId added successfully')
          window.location.reload();
        }
      })
      .catch((error) => console.error(error));
});

  
  