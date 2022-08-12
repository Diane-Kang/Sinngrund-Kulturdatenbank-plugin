const map = L.map('my_map', scrollWheelZoom = false, keyboard = false, zoomControl = false)
    .setView([49.79020826982288, 9.93560301310107], 6.3);



L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
	maxZoom: 18,
    minZoom: 5,
    attribution: 'Map data and Imagery &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
}).addTo(map);

