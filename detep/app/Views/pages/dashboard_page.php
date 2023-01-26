<?= $this->extend("templates/app") ?>

<?= $this->section("body") ?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active"><?= esc($title) ?></li>
    </ol>

    <!-- Errors section start -->
    <?= isset($validation) ? $validation->listErrors('my_list') : "" ?>
    <?= isset($success_redirect) ? '<div class="alert alert-success">'.esc($success_redirect).'</div>' : "" ?>
    <?= isset($error_redirect) ? '<div class="alert alert-danger">'.esc($error_redirect).'</div>' : "" ?>
    <!-- Error section end -->

    <div class="card mb-2">
        <!-- <canvas class="my-4" id="myChart" width="900" height="380"></canvas> -->
        <?php $name = '39666264326136313639666236316561653933633632613537653830363262646261656131373730393664633364326232306536656464306333326639383261393732353062633664653335633263303531633433366432646362303233626339323534396430626136666232363437653561633361666333306431346136323439653031623666336430363063623162353230373861313739383265333130613064313430643232396437396238666230653538613364623235363762326332353364613862336337363131623865353866313264663164363134623138636533353061306331';
        $final = str_replace(['\/', '\n'], ['/', ''], $name); 
        ?>

        <!--<form action="" method="post">-->
            <!--    -->
            <!--    <input type="text" name="beacon" value="E9:98:49:5A:AC:E6">-->
            <!--    <input type="text" name="device" value="9fa19b0c-4e2c-4969-aedd-bf52123a9116">-->
            <!--    <input type="text" name="code"-->
            <!--        value="">-->
            <!--    <input type="text" name="receive" value="0">-->
            <!--    <input type="text" name="send" value="0">-->
            <!--    <input type="text" name="normal" value="1">-->
            <!--    <input type="text" name="recipient" value="0">-->
            <!--    <button type="submit">Submit</button>-->
            <!--</form>-->

            <div class="mt-2 mb-2 ml-2 mr-2">
                <form method="post" action="<?= esc(base_url('dashboard/reset')) ?>">
                <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Please, use this section if you are aware of it, otherwise, you are destroying your distribution system.</label>
                        <input type="text" class="form-control" name="value" placeholder="Enter value">
                    </div>
                    <button type="submit" class="btn btn-primary">Confirm activity</button>
                </form>
            </div>
        </main>

        <?= $this->endSection() ?>