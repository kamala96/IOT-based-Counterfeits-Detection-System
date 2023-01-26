<?php $session = session(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?= esc($title) ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="<?= esc(base_url('assets/bootstrap-4.0.0/css/bootstrap.min.css')) ?>">
    <link rel="stylesheet" href="<?= esc(base_url('assets/css/dataTables.bootstrap4.css')) ?>">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">

    <!-- Default Font -->
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400&display=swap" rel="stylesheet">

    <!-- Custom styles for homepage -->
    <link href="<?= esc(base_url('assets/css/styles.css')) ?>" rel="stylesheet">

    <style type="text/css">
        
        /* Absolute Center Spinner */
.loading {
  position: fixed;
  z-index: 999;
  height: 2em;
  width: 2em;
  overflow: show;
  margin: auto;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}

/* Transparent Overlay */
.loading:before {
  content: '';
  display: block;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
    background: radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0, .8));

  background: -webkit-radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0,.8));
}

/* :not(:required) hides these rules from IE9 and below */
.loading:not(:required) {
  /* hide "loading..." text */
  font: 0/0 a;
  color: transparent;
  text-shadow: none;
  background-color: transparent;
  border: 0;
}

.loading:not(:required):after {
  content: '';
  display: block;
  font-size: 10px;
  width: 1em;
  height: 1em;
  margin-top: -0.5em;
  -webkit-animation: spinner 150ms infinite linear;
  -moz-animation: spinner 150ms infinite linear;
  -ms-animation: spinner 150ms infinite linear;
  -o-animation: spinner 150ms infinite linear;
  animation: spinner 150ms infinite linear;
  border-radius: 0.5em;
  -webkit-box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
}

/* Animation */

@-webkit-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-moz-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-o-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

        html,
        body {
            height: 100%;
            font-family: 'Ubuntu', sans-serif;
        }
    </style>
    <script>
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>';  
  </script>
</head>
<?php $level = $session->get('level_int') == 100 ? 'Last' : $session->get('level_int'); ?>

<body>
    <div id="isloading" class="loading" style="display:none;">Loading&#8230;</div>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="<?= esc(base_url('dashboard'));?>">CoDe Portal</a>
        <p class="text-light">
            <?= $session->get('system_name').'>'.$session->get('station_name').'['.$level.']>'.$session->get('full_name') ?>
        </p>
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <button type="button" class="btn btn-outline-light btn-sm" onclick="location.href='<?= esc(base_url('logout'));?>';"><span data-feather="log-out"></span>&nbsp;Sign-out</button>
            </li>
        </ul>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= service('uri')->getSegment(2) == '' ? ' active' : '';?>"
                                href="<?= esc(base_url('dashboard'));?>">
                                <span data-feather="home"></span>
                                Dashboard <span class="sr-only">(current)</span>
                            </a>
                        </li>
                    </ul>

                    <h6
                        class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Core</span>
                        <a class="d-flex align-items-center text-muted" href="#">
                            <span data-feather="corner-right-down"></span>
                        </a>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link <?= service('uri')->getSegment(2)=='parcels' ? ' active' : '';?>"
                                href="<?= esc(base_url('dashboard/parcels')) ?>">
                                <span data-feather="package"></span>
                                Parcels
                            </a>
                        </li>
                    </ul>
                    <?php if($session->get('level_int') != 100) : ?>
                    <h6
                        class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>STORES ADMINS</span>
                        <a class="d-flex align-items-center text-muted" href="#">
                            <span data-feather="corner-right-down"></span>
                        </a>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link <?= service('uri')->getSegment(2)=='intermediaries' ? ' active' : '' ?>"
                                href="<?= esc(base_url('dashboard/intermediaries'));?>">
                                <span data-feather="users"></span>
                                Administrators
                            </a>
                        </li>
                    </ul>
                    <?php endif ?>

                    <?php if($session->get('isTopUser')) : ?>
                    <h6
                        class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Settings</span>
                        <a class="d-flex align-items-center text-muted" href="#">
                            <span data-feather="corner-right-down"></span>
                        </a>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link <?= service('uri')->getSegment(2)=='levels' ? ' active' : '' ?>"
                                href="<?= esc(base_url('dashboard/levels'));?>">
                                <span data-feather="list"></span>
                                Distribution Levels
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= service('uri')->getSegment(2)=='stations' ? ' active' : '' ?>"
                                href="<?= esc(base_url('dashboard/stations'));?>">
                                <span data-feather="list"></span>
                                Distribution Stores
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= service('uri')->getSegment(2)=='products' ? ' active' : '' ?>"
                                href="<?= esc(base_url('dashboard/products'));?>">
                                <span data-feather="list"></span>
                                Distribution Products
                            </a>
                        </li>
                    </ul>
                    <?php endif ?>
                </div>
            </nav>
            <?= $this->renderSection("body") ?>

            <script src="<?= esc(base_url('assets/js/jquery.min.js')) ?>"></script>
            <script src="<?= esc(base_url('assets/js/popper.min.js')) ?>"></script>
            <script src="<?= esc(base_url('assets/bootstrap-4.0.0/js/bootstrap.min.js')) ?>"></script>
            <script src="<?= esc(base_url('assets/js/dataTables.min.js')) ?>"></script>
            <script src="<?= esc(base_url('assets/js/dataTables.bootstrap4.min.js')) ?>"></script>
            <script src="<?= esc(base_url('assets/demo/datatables-demo.js')) ?>"></script>
            <script src="<?= esc(base_url('assets/js/jquery.validate.js')) ?>"></script>
            <script src="<?= esc(base_url('assets/js/bootbox/bootbox.min.js')) ?>"></script>


            <!-- Icons -->
            <!-- <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script> -->
            <script src="<?= esc(base_url('assets/js/feather.min.js')) ?>"></script>
            <script>
                feather.replace()
            </script>

            <!-- Graphs
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    <script>
    var ctx = document.getElementById("myChart");
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
            datasets: [{
                data: [15339, 21345, 18483, 24003, 23489, 24092, 12034],
                lineTension: 0,
                backgroundColor: 'transparent',
                borderColor: '#007bff',
                borderWidth: 4,
                pointBackgroundColor: '#007bff'
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: false
                    }
                }]
            },
            legend: {
                display: false,
            }
        }
    });
    </script> -->
            <?= $this->renderSection("scripts") ?>

        </div>
    </div>
</body>

</html>