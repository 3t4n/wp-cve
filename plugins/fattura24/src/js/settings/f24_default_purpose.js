window.addEventListener('load', () => {
    let dfPurpose = document.getElementById('fatt-24-inv-default-object');
        dfPurpose.addEventListener('click', function(e) {
        document.getElementById('fatt-24-inv-object').value = 'Ordine E-commerce (N)';
    });
});