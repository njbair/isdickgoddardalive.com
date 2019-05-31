<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Is Dick Goddard Alive?</title>
    <link href="https://fonts.googleapis.com/css?family=Vollkorn:600,900&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 5vw;
            box-sizing: border-box;
            background: #000;
            background-image: url('https://f.njbai.rs/dick-goddard.jpg');
            background-position: 60% center;
            background-attachment: fixed;
            background-size: cover;
            color: #fff;
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: left;
            justify-content: flex-end;
            font-family: 'Vollkorn', serif;
            font-weight: 600;
        }

        .status {
            margin: 0;
            padding: 0;
            font-size: 30vw;
            line-height: 1;
            text-align: left;
            text-shadow: 0 .5vw 0 rgba(0,0,0,.6);
            font-weight: 900;
        }
        .notes {
            margin: 0;
            padding: 0;
            font-size: 24px;
            text-align: left;
            text-shadow: 0 2px 0 rgba(0,0,0,.6);
        }

        @media (min-width: 50em) {
            body {
                background-position: 40% center;
                justify-content: center;
            }
        }
        @media (min-width: 80em) {
            body {
                background-position: 20% center;
            }
        }
        @media (min-width: 90em) {
            .status {
                font-size: 25vw;
            }
        }
    </style>
</head>

<body>
    <div id="statusContainer">&hellip;</div>
    <div id="notesContainer"></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script>
        (function(){
            var xhr = new XMLHttpRequest();
            xhr.onload = function(){
                if (xhr.status >= 200 && xhr.status < 300) {
                    var res = JSON.parse(xhr.response);
                    var statusContainer = document.getElementById('statusContainer');
                    var notesContainer = document.getElementById('notesContainer');

                    while (statusContainer.firstChild) {
                        statusContainer.removeChild(statusContainer.firstChild);
                    }

                    statusContainer.appendChild(function(){
                        var el = document.createElement('h1');
                        el.classList.add('status');
                        el.textContent = res.alive;
                        return el
                    }());

                    if (res.notes) {
                        notesContainer.appendChild(function(){
                            var el = document.createElement('p');
                            el.classList.add('notes');
                            el.textContent = res.notes;
                            return el;
                        }());
                    }
                }
            }

            xhr.open('GET', '/api.json');
            xhr.send();
        }());

        (function(){
            var dicksAge = moment("19310224").fromNow(true);
            console.log("Dick is " + dicksAge + " old.");
        }());
    </script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-27242731-5"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());

        gtag('config', 'UA-27242731-5');
    </script>
</body>

</html>