<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? "Time Table Generator Software" }} | Time Table Generator Software</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anta&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Oswald:wght@200..700&display=swap" rel="stylesheet">

    @yield('css')

    <!-- Custom CSS -->
    <style>
        /* Custom styles */
        *{
            margin: 0;
            box-sizing: border-box;
            font-family: "Oswald", sans-serif;
        }
        body {
            font-family: Arial, sans-serif;
        }

        .hero-section {
            position: relative;
            background-image: url({{ asset('img/scl_bg.jpg') }});
            background-size: cover;
            background-position: center;
            color: #fff;
            padding: 30vh 0;
            min-height: 90vh;
        }

        .overlay{
            position: absolute;
            z-index: 1;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.342);
        }

        .about-section {
            padding: 50px 0;
            background-image: url({{ asset('img/bg.jpg') }});
            background-size: cover;
            background-position: center;
        }

        .reasons-section {
            background-color: #f8f9fa;
            padding: 50px 0;
        }

        /* Green color theme */
        .green-color {
            color: #28a745;
        }

        .green-bg {
            background-color: #28a745;
        }
        .my-i-font{
            font-size: 50px
        }
        .my-why{
            border-radius: 8px;
            padding: 10px;
            background-color: white;
            box-shadow: 6px 5px 7px 2px #cbcbcb;
            transition: ease-in-out .5s;
            cursor: pointer;
            margin-bottom: 2rem
        }
        .my-why:hover{
            scale: 1.1;
            transition: ease-in-out .5s
        }
        .my-why p{
            padding: 10px;
        }
    </style>
</head>

<body>
    @yield('content')

    <!-- Footer Section -->
    <footer class="text-center py-4 green-bg mt-5">
        <div class="container">
            <p class="text-white mb-0">© {{ date('Y') }} Time Table Generator Software. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    @yield('js')
</body>

</html>
