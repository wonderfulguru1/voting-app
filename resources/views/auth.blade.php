<!DOCTYPE html>
<html>

<head lang="en">
    <meta charset="UTF-8">
    <title>Voting System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"
        integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/app.css">
    <style>
        body {
            font-family: "Google Sans", roboto, "Noto Sans Myanmar UI", arial, sans-serif;
            background-color: #F5F7FA;
            margin: 0 0 0 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin-top: 30px;
            background-color: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #286DA8;
            color: white;
            padding: 15px;
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
        }

        .section {
            margin-bottom: 40px;
        }

        .section h2 {
            font-size: 20px;
            color: #286DA8;
            margin-bottom: 10px;
        }

        .candidate-list {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .candidate {
            text-align: center;
        }

        .candidate img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .candidate span {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .chart {
            width: 100%;
            background-color: #E4E8EB;
            height: 25px;
            margin-bottom: 10px;
            position: relative;
        }

        .chart span {
            display: block;
            height: 100%;
            background-color: #286DA8;
            color: white;
            text-align: right;
            padding-right: 10px;
            box-sizing: border-box;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #999;
            margin-top: 40px;
        }


    </style>
</head>

<body>

    <div id="app">

        <router-view></router-view>


    </div>

    <div id="error"></div>
    <script>
        window.config = {
            "API": "/api/v1/", //URL OF YOUR API LOCATED
            "baseURL": "/", //URL OF YOUR WEBSITE
            "storageURL": "{{ config('app.cloudinary_enabled') ? '' : '/storage/' }}", //URL WHERE YOUR IMAGES and OTHER FILEs stored
            "debug": {{ env('APP_DEBUG') }}
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"
        integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"
        integrity="sha512-bZS47S7sPOxkjU/4Bt0zrhEtWx0y0CRkhEp8IckzK+ltifIIE9EMIMTuT/mEzoIMewUINruDBIR/jJnbguonqQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-notify@3.1.3/bootstrap-notify.min.js"
        integrity="sha256-DRllCE/8rrevSAnSMWB4XO3zpr+3WaSuqUSNLD5NAzg=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-form@4.3.0/dist/jquery.form.min.js"
        integrity="sha256-3TKcZElR88BBIA6CeePJAGOsW1yIYf4lP8pI333YuZw=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-flot@0.8.3/jquery.flot.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-flot@0.8.3/jquery.flot.pie.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-router@3.4.9/dist/vue-router.min.js"></script>
    <script src="/js/app.js"></script>
</body>

</html>
