const moment = require('moment');

function getApiData() {
    let xhr = new XMLHttpRequest();
    xhr.onload = function(){
        if (xhr.status >= 200 && xhr.status < 300) {
            let res = JSON.parse(xhr.response);
            parseStatus(res.status);
            parseWeather(res.weather);
        }
    }

    xhr.open('GET', '/api.json');
    xhr.send();
}

function parseStatus(data) {
    let statusContainer = document.getElementById('statusContainer');
    let notesContainer = document.getElementById('notesContainer');

    while (statusContainer.firstChild) {
        statusContainer.removeChild(statusContainer.firstChild);
    }

    statusContainer.appendChild(function(){
        let el = document.createElement('h1');
        el.classList.add('status');
        el.textContent = data.alive;
        return el
    }());

    if (data.notes) {
        notesContainer.appendChild(function(){
            let el = document.createElement('p');
            el.classList.add('notes');
            el.textContent = data.notes;
            return el;
        }());
    }
}

function parseWeather(data) {
    const weatherIcons = {
        'sunny'        : 'wi-day-sunny',
        'clear-night'  : 'wi-night-clear',
        'cloudy-day'   : 'wi-day-cloudy',
        'cloudy-night' : 'wi-night-cloudy',
        'cloud'        : 'wi-cloud',
        'cloudy'       : 'wi-cloudy',
        'rain'         : 'wi-rain',
        'rain-day'     : 'wi-day-rain',
        'rain-night'   : 'wi-night-alt-rain',
        'thunderstorm' : 'wi-thunderstorm',
        'snow'         : 'wi-snow',
        'fog'          : 'wi-fog',
        'na'           : 'wi-na',
    }

    let weatherContainer = document.getElementById('weatherContainer');
    let weatherEl = document.createElement('p');
    let iconEl = document.createElement('i');
    let descEl = document.createElement('span');

    let icon = data.icon;
    let desc = data.asString;

    weatherEl.classList.add('weather');
    iconEl.classList.add('wi', weatherIcons[icon]);
    descEl.textContent = ' ' + desc;

    weatherEl.appendChild(iconEl);
    weatherEl.appendChild(descEl);
    weatherContainer.appendChild(weatherEl);
}

function getAge() {
    let dicksAge = moment("19310224").fromNow(true);
    console.log("Dick is " + dicksAge + " old.");
}

function init() {
    getApiData();
    getAge();
}

init();