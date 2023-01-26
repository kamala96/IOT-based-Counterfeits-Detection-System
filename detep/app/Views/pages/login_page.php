<!doctype html>
<html>

<head>
  <meta charset='utf-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('assets/images/sda4.png'); ?>">
  <title><?= esc($title) ?></title>
  <link rel="stylesheet" href="<?= esc(base_url('assets/bootstrap-4.0.0/css/bootstrap.min.css')) ?>">
  <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet'>
  <style>
    body {
      color: #000;
      overflow-x: hidden;
      height: 100%;
      background-color: #B0BEC5;
      background-repeat: no-repeat
    }

    .card0 {
      box-shadow: 0px 4px 8px 0px #757575;
      border-radius: 0px
    }

    .card2 {
      margin: 0px 40px
    }

    .logo {
      width: 200px;
      height: 100px;
      margin-top: 20px;
      margin-left: 35px
    }

    .image {
      width: 360px;
      height: 280px
    }

    .border-line {
      border-right: 1px solid #EEEEEE
    }

    .text-sm {
      font-size: 14px !important
    }

    ::placeholder {
      color: #BDBDBD;
      opacity: 1;
      font-weight: 300
    }

    :-ms-input-placeholder {
      color: #BDBDBD;
      font-weight: 300
    }

    ::-ms-input-placeholder {
      color: #BDBDBD;
      font-weight: 300
    }

    input,
    textarea {
      padding: 10px 12px 10px 12px;
      border: 1px solid lightgrey;
      border-radius: 2px;
      margin-bottom: 5px;
      margin-top: 2px;
      width: 100%;
      box-sizing: border-box;
      color: #2C3E50;
      font-size: 14px;
      letter-spacing: 1px
    }

    input:focus,
    textarea:focus {
      -moz-box-shadow: none !important;
      -webkit-box-shadow: none !important;
      box-shadow: none !important;
      border: 1px solid #304FFE;
      outline-width: 0
    }

    button:focus {
      -moz-box-shadow: none !important;
      -webkit-box-shadow: none !important;
      box-shadow: none !important;
      outline-width: 0
    }

    a {
      color: inherit;
      cursor: pointer
    }

    .btn-blue {
      background-color: #1A237E;
      width: 150px;
      color: #fff;
      border-radius: 2px
    }

    .btn-blue:hover {
      background-color: #000;
      cursor: pointer
    }

    .bg-blue {
      color: #fff;
      background-color: #1A237E
    }

    @media screen and (max-width: 991px) {
      .logo {
        margin-left: 0px
      }

      .image {
        width: 300px;
        height: 220px
      }

      .border-line {
        border-right: none
      }

      .card2 {
        border-top: 1px solid #EEEEEE !important;
        margin: 0px 15px
      }
    }
  </style>
  <script src="<?= esc(base_url('assets/js/jquery.min.js')) ?>"></script>
  <script src="<?= esc(base_url('assets/js/popper.min.js')) ?>"></script>
  <script src="<?= esc(base_url('assets/bootstrap-4.0.0/js/bootstrap.min.js')) ?>"></script>
  <script type='text/javascript'></script>
</head>

<body oncontextmenu='return false' class='snippet-body'>
  <div class="container-fluid px-1 px-md-5 px-lg-1 px-xl-5 py-5 mx-auto">
    <div class="card card0 border-0">
      <div class="row px-3 mt-3">
        <h3 class="h3 ml-3 ml-sm-5 mb-2">COUNTERFEITS DETECTION SYSTEM</h3>
      </div>
      <div class="row d-flex">
        <div class="col-lg-6">
          <div class="card1 pb-5 mt-5">
            <div class="row pt-3 pb-3">
            </div>
            <div class="row px-3 justify-content-center mt-5 mb-5 border-line">
              <div class="mt-4" style="margin-top: 5em;">
                <p>Android user?</p>
                <button type="button" onclick="location.href='<?= esc(base_url('app')) ?>';"
                  class="btn btn-blue text-center">Download App</button>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card2 card border-0 px-4 py-5 mt-5">
            <form class="form-signin" action="/login" method="post">
              <?= csrf_field() ?>
              <div class="row px-3 mb-2">
              </div>
              <div class="row px-3">
                <label class="mb-1">
                  <h6 class="mb-0 text-sm">Email Address</h6>
                </label>
                <input id="inputText"
                  class="mb-4 form-control <?= (isset($validation) && $validation->hasError('email')) ? ' is-invalid' : '' ?>"
                  type="email" name="email" placeholder="Enter a valid email address"
                  value="<?php echo set_value('email');?>" required>
                <?= (isset($validation) && $validation->hasError('email')) ? '<div class="invalid-feedback">'.$validation->getError('email').'</div>' : '' ?>
              </div>
              <div class="row px-3">
                <label class="mb-1">
                  <h6 class="mb-0 text-sm">Password</h6>
                </label>
                <input type="password" id="inputPassword"
                  class="form-control <?= (isset($validation) && $validation->hasError('password')) ? ' is-invalid' : '' ?>"
                  name="password" placeholder="Enter password" value="<?php echo set_value('password');?>" required>
                <?= (isset($validation) && $validation->hasError('password')) ? '<div class="invalid-feedback">'.$validation->getError('password').'</div>'  : '' ?>
              </div>
              <div class="row px-3 mb-4">
              </div>
              <div class="row mb-3 px-3">
                <button type="submit" class="btn btn-blue text-center">Login</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="bg-blue py-4">
        <div class="row px-3"> <small class="ml-4 ml-sm-5 mb-2">Copyright &copy; CoDe
            <?= '2020' . ' - ' . date("Y");?>. All rights reserved.</small>
          <div class="social-contact ml-4 ml-sm-auto">
            <a href="mailto:jovinkamalajohn@gmailcom"><span class="fa fa-envelope mr-4 text-sm"></span> </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>