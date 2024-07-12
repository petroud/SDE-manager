<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.png" type="image/png">
    <title>SDE Manager | Index</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: white; /* Light grey background */
            padding-top: 56px; /* Adjust according to the height of the navbar */

        }
        .navbar {
            background-color: #3C5A80; /* Blue navbar */
        }
        .navbar-brand, .nav-link {
            color: white !important; /* White text in navbar */
        }
        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.1); /* Light border for the toggle button */
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3E%3Cpath stroke='rgba%28255, 255, 255, 0.5%29' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }
        .nav-link.active {
            color: #EF6C4D !important; 
        }
        .nav-link:hover {
            color: #98C1D9 !important; 
        }
        .content {
            flex: 1;
        }
        footer {
            background-color: #293241; /* Dark grey footer */
            color: white;
            text-align: center;
            height: 1.5em;
            width: 100%;
            flex-shrink: 0;
        }
        .text-center {
            text-align: center;
            word-wrap: break-word; /* Ensure long words break and wrap */
            overflow-wrap: break-word; /* Ensure overflow words break and wrap */
        }
        .navbar-brand img {
            max-height: 33px; /* Adjust the logo size */
            margin-right: 10px;
        }
        @media (max-width: 600px) {
            footer {
                height: 3em;
            }

            .text-center small {
                font-size: 0.7em;
                padding: 0 10px; /* Add padding to ensure the text is not touching the edges */
            }
        }
    </style>
     <style>
        .file-list {
            list-style-type: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .file-item {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            border: 1px solid #ccc;
            padding: 3px;
            margin: 5px;;
            height: 40px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: calc(33.333% - 20px);
            min-width: 150px; 
        }

        .file-icon {
            width: 40px;
            height: 40px;
            margin-left: 10px;
            color: #3C5A80;
            margin-top: 15px;
        }

       
        .file-name {
            font-size: 12px;
            white-space: nowrap;
            margin-left: -15px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .table th, .table td {
            text-align: center;
            vertical-align: middle;
            padding: 8px;
        }
        .dropzone {
            border: dashed 4px #ddd !important ;
            background-color: #f2f6fc;
            border-radius: 15px;
        }
    
        .dropzone .dropzone-container {
            padding: 2rem 0;
            width: 100%;
            height: 100%;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #8c96a8;
            z-index: 20;
        }
        
    
        .dropzone .file-input {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            visibility: hidden;
            cursor: pointer;
        }

        .file-input {
            display: none;
        }
      
        .hr-sect {
            display: flex;
            flex-basis: 100%;
            align-items: center;
            margin: 8px 0px;
        }
        .hr-sect:before,
        .hr-sect:after {
            content: "";
            flex-grow: 1;
            background: #ddd;
            height: 1px;
            font-size: 0px;
            line-height: 0px;
            margin: 0px 8px;
        }
        .custom-browse-btn {
            background-color: #3C5A80;
            border-color: #3C5A80;
            padding: 5px 10px;
            font-size: 14px;
        }
        .custom-browse-btn:hover {
            background-color: #1f2d40;
        }

        .custom-upload-btn {
            background-color: #EF6C4D;
            border-color: #EF6C4D;
            color: white;
            cursor:pointer;
            padding: 5px 10px;
            font-size: 14px;
        }
        .custom-upload-btn:hover {
            background-color: #a3422a;
            color: white;
        }
        .action-column {
            text-align: center;
            vertical-align: middle;
        }

        .action-column .btn {
            margin: 5px;
        }

        @media (max-width: 576px) {
            .action-column .btn {
                display: block;
                width: 100%;
                margin-bottom: 10px;
            }
        }
        /* Mobile view adjustments */
        @media (max-width: 768px) {
            .table thead {
                display: none;
            }

            .table tbody, .table tr, .table td {
                display: block;
                width: 100%;
            }

            .table tbody tr {
                margin-bottom: 1rem;
                border: 1px solid #dee2e6;
                border-radius: 0.25rem;
            }

            .table tbody td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            .table tbody td:before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 0.75rem;
                font-weight: bold;
                text-align: left;
            }

            .table tbody td.action-column {
                text-align: center;
                padding: 0.75rem;
            }
            .table tbody td[data-label="Dataset Name"] {
                white-space: nowrap;     
                overflow: hidden;         
                text-overflow: ellipsis;  
            }
            .table thead th.action-column {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>

    <nav class="navbar navbar-expand-lg fixed-top">
            <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                SDE Manager
            </a>                
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        <?php
                        $page = isset($_GET['page']) ? $_GET['page'] : 'main';
                        $pages = ['main' => 'Main', 'datasets' => 'Datasets', 'console' => 'SDE Console', 'monitoring' => 'Monitoring'];
                        foreach ($pages as $key => $value) {
                            $active = ($page == $key) ? 'active' : '';
                            echo "<li class='nav-item'><a class='nav-link $active' href='index.php?page=$key'>$value</a></li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
    </nav>


    <div class="container mt-5">
        <?php
            $page = isset($_GET['page']) ? $_GET['page'] : 'main';
            include $page . '.php';
      
        ?>
    </div>


    <?php include 'footer.php'; ?>

   
</body>
</html>