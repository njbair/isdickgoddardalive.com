const moment = require('moment');

function getStatus() {
    let xhr = new XMLHttpRequest();
    xhr.onload = function(){
        if (xhr.status >= 200 && xhr.status < 300) {
            let res = JSON.parse(xhr.response);
            let statusContainer = document.getElementById('statusContainer');
            let notesContainer = document.getElementById('notesContainer');

            while (statusContainer.firstChild) {
                statusContainer.removeChild(statusContainer.firstChild);
            }

            statusContainer.appendChild(function(){
                let el = document.createElement('h1');
                el.classList.add('status');
                el.textContent = res.alive;
                return el
            }());

            if (res.notes) {
                notesContainer.appendChild(function(){
                    let el = document.createElement('p');
                    el.classList.add('notes');
                    el.textContent = res.notes;
                    return el;
                }());
            }
        }
    }

    xhr.open('GET', '/api.json');
    xhr.send();
}

function getWeather() {
    const weatherIcons = {
        '01d': 'wi-day-sunny',
        '01n': 'wi-night-clear',
        '02d': 'wi-day-cloudy',
        '02n': 'wi-day-cloudy',
        '03d': 'wi-cloud',
        '03n': 'wi-cloud',
        '04d': 'wi-cloudy',
        '04n': 'wi-cloudy',
        '09d': 'wi-rain',
        '09n': 'wi-rain',
        '10d': 'wi-day-rain',
        '10n': 'wi-night-alt-rain',
        '11d': 'wi-thunderstorm',
        '11n': 'wi-thunderstorm',
        '13d': 'wi-snow',
        '13n': 'wi-snow',
        '50d': 'wi-fog',
        '50n': 'wi-fog',
    }

    let xhr = new XMLHttpRequest();
    xhr.onload = function(){
        if (xhr.status >= 200 && xhr.status < 300) {
            let res = JSON.parse(xhr.response);
            let weatherContainer = document.getElementById('weatherContainer');
            let weatherEl = document.createElement('p');
            let iconEl = document.createElement('i');
            let descEl = document.createElement('span');

            let icon = res.weather[0].icon;
            let temp = res.main.temp.toFixed();
            let desc = res.weather[0].description;
            let loc  = res.name;

            weatherEl.classList.add('weather');
            iconEl.classList.add('wi', weatherIcons[icon]);
            descEl.textContent = ' ' + temp + '\xB0, ' + desc + ' in ' + loc;

            weatherEl.appendChild(iconEl);
            weatherEl.appendChild(descEl);
            weatherContainer.appendChild(weatherEl);
        }
    }

    xhr.open('GET', '/weather.json');
    xhr.send();
}

function getAge() {
    let dicksAge = moment("19310224").fromNow(true);
    console.log("Dick is " + dicksAge + " old.");
}

function init() {
    getStatus();
    getWeather();
    getAge();
}

init();