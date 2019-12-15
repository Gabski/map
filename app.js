$(document).ready(function () {
	function toRadian(degree) {
		return degree * Math.PI / 180;
	}

	const startPoint = [52.234178262806296, 21.030257227939515];

	//[52.234178262806296, 21.030257227939515];

	function getDistance(origin, destination) {
		// return distance in meters
		var lon1 = toRadian(origin[1]),
			lat1 = toRadian(origin[0]),
			lon2 = toRadian(destination[1]),
			lat2 = toRadian(destination[0]);

		var deltaLat = lat2 - lat1;
		var deltaLon = lon2 - lon1;

		var a =
			Math.pow(Math.sin(deltaLat / 2), 2) + Math.cos(lat1) * Math.cos(lat2) * Math.pow(Math.sin(deltaLon / 2), 2);
		var c = 2 * Math.asin(Math.sqrt(a));
		var EARTH_RADIUS = 6371;
		return c * EARTH_RADIUS * 1000;
	}

	var map = L.map('map', {
		center: [startPoint],
		scrollWheelZoom: true,
		inertia: true,
		inertiaDeceleration: 2000
	});
	map.setView(startPoint, 15);

	L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
		attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://mapbox.com">Mapbox</a>',
		// maxZoom: 15,
		id: 'superpikar.n28afi10',
		accessToken: 'pk.eyJ1Ijoic3VwZXJwaWthciIsImEiOiI0MGE3NGQ2OWNkMzkyMzFlMzE4OWU5Yjk0ZmYzMGMwOCJ9.3bGFHjoSXB8yVA3KeQoOIw'
	}).addTo(map);

	$('#locate-position').on('click', function () {
		map.locate({
			setView: true,
			maxZoom: 15
		});
	});



	function selectArea(locationPoint, bus) {
		var local;
		var near = 1;
		var myColor;
		var busesLayer = L.layerGroup().addTo(map);

		$.getJSON('index.php?bus=' + bus, function (data) {


			for (var busss in data.result) {

				let lastbus = null;
				let counter = 0;
				let number = data.result[busss].length;

				if (Array.isArray(data.result[busss])) {

					data.result[busss].forEach((element) => {

						console.log(element);

						var local = [element.Lat, element.Lon];

						var km = getDistance(locationPoint, local) / 1000;

						myColor = 'blue';
						if (km <= near) {
							myColor = 'red';
						} else if (km <= near * 3) {
							myColor = 'green';
						}

						if (lastbus) {
							lastBusLocation = [lastbus.Lat, lastbus.Lon];

							var polygon = L.polygon([local, lastBusLocation], {
								color: myColor
							}).addTo(busesLayer);
						}

						lastbus = element;
						counter++;


						let styleClass = "bus-node";
						if (counter === number) {
							styleClass = `bus bus-${element.Lines}`
						}


						if (km < near * 3) {
							// var polygon = L.polygon([local, locationPoint], {
							// 	color: myColor
							// }).addTo(busesLayer);

							// var popup = L.popup()
							//     .setLatLng(local)
							//     //.setContent(local[0] + " | " + local[1] + " km: " + km)
							//     .setContent(element.Lines + " " + element.Time)
							//     .addTo(map);
						}

						var myIcon = L.divIcon({
							className: styleClass
						});

						var bus = L.marker(local, {
							icon: myIcon
						}).addTo(busesLayer);
					});


				}
			}
		});

		return busesLayer;
	}



	var radius = 1000 * 1 * 3; //e.accuracy * 10 * 8;
	//L.circle(startPoint, radius).addTo(map);
	L.circle(startPoint, 4).addTo(map);


	var buses = [];

	buses.push(selectArea(startPoint, 108));
	buses.push(selectArea(startPoint, 162));
	buses.push(selectArea(startPoint, 167));

	var tid = setTimeout(mycode, 10000);

	function mycode() {

		let a = selectArea(startPoint, 108);
		let b = selectArea(startPoint, 162);
		let c = selectArea(startPoint, 167);

		buses.map(function (value, index, arr) {
			if (value instanceof Object) {
				map.removeLayer(value);
			}
		});

		buses.push(a);
		buses.push(b);
		buses.push(c);

		tid = setTimeout(mycode, 10000); // repeat myself
	}










});