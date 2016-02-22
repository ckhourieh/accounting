<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Webneoo Accounting System</title>

    <!-- Bootstrap Styles-->
    <link href="/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="/css/font-awesome.css" rel="stylesheet" />
    <!-- Morris Chart Styles-->
    <link href="/js/morris/morris.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="/css/custom-styles.css" rel="stylesheet" />
    <!-- Google Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

    <!-- jQuery Js -->
    <script src="/js/jquery-1.10.2.js"></script>
</head>

<body id="app-layout">

    @include('layouts.menu')

    <div id="page-wrapper">
        <div id="page-inner">

            @yield('content')

        </div>
    </div>

    <!-- JS Scripts-->
    <!-- Bootstrap Js -->
    <script src="/js/bootstrap.min.js"></script>
     
    <!-- Metis Menu Js -->
    <script src="/js/jquery.metisMenu.js"></script>
    
    <!-- Custom Js -->
    <script src="/js/custom-scripts.js"></script>
</body>
</html>
