async function app(path, id) {
    try {
        const response = await fetch(path);
        html = await response.text();
        document.getElementById(id).innerHTML = html;
    } catch (error) {
        console.error('Error fetching the file:', error);
    }
}