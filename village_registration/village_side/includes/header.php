<style>
        /* Custom Styles */
        body {
            font-family: 'Arial', sans-serif;
            background: #f8f9fa;
            color: #333;
        }

        .navbar {
            background: linear-gradient(90deg, #007bff, #00a8ff);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff !important;
        }

        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }

        .navbar-nav .nav-link {
            color: #fff !important;
            font-size: 1rem;
            margin: 0 10px;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: #f8f9fa !important;
        }

        .hero {
            background: linear-gradient(90deg, #007bff, #00a8ff);
            color: #fff;
            padding: 60px 0;
            text-align: center;
        }

        .hero h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .card {
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: #007bff;
            color: #fff;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
        }

        .table {
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table thead th {
            background: #007bff;
            color: #fff;
            border: none;
        }

        .table tbody tr:hover {
            background: #f1f1f1;
        }

        footer {
            background: #333;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            margin-top: 40px;
        }

        footer a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }

        footer a:hover {
            color: #007bff;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="logo.png" alt="Logo"> Village Registration
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_newcomers.php"><i class="fas fa-users"></i> View Newcomers</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-chart-line"></i> Reports
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="reports_by_date.php">By Date</a>
                            <a class="dropdown-item" href="reports_by_status.php">By Status</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="settings.php"><i class="fas fa-cog"></i> Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
