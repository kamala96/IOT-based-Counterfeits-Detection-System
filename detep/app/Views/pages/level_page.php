<?= $this->extend("templates/app") ?>

<?= $this->section("body") ?>
<?php $session = session(); ?>
<style>
    .error {
        color: #a94442;
        background-color: #f2dede;
        border-color: #ebccd1;
        padding: 1px 20px 1px 20px;
    }
</style>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a>
        </li>
        <li class="breadcrumb-item active"><?= esc($title) ?></li>
    </ol>

    <!-- Errors area -->
    <?= isset($validation) ? $validation->listErrors('my_list') : "" ?>
    <?= isset($success_redirect) ? '<div class="alert alert-success">'.esc($success_redirect).'</div>' : "" ?>
    <?= isset($error_redirect) ? '<div class="alert alert-danger">'.esc($error_redirect).'</div>' : "" ?>

    <div id="error_message" class="ajax_response alert alert-danger" style="display:none;"></div>
    <div id="success_message" class="ajax_response alert alert-success" style="display:none;"></div>
    <!-- Errors end -->

    <!-- List Start -->
    <div class="card mb-2">
        <div class="card-body">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10">
                        <?php if(isset($levels) && $levels != false) : ?>
                        <div class="alert alert-primary" role="alert">
                        <?= 'Currently, your products are operating with a <span class="badge bg-dark text-light levels">'.$levels.'</span> levels distribution system' ?>
                        </div>
                        <?= 'Currently: The system is operating with the pre-defined settings collected from you.<br />' ?>
                        <?= 'On any changes: The system assumes that you are aware of what you are doing, otherwise contact us!' ?>
                        <?php else : ?>
                        No results found.
                        <?php endif ?>
                    </div>
                    <div class="col-lg-2">
                        <button class="btn btn-sm btn-outline-primary" data-toggle="collapse"
                            data-target="#modifyLevelCollapse" title="Update">
                            <span data-feather="edit"></span>
                        </button>
                    </div>
                </div>
                <hr />
                <!-- Form Start -->
                <div class="collapse" id="modifyLevelCollapse">
                    <div class="card">
                        <div class="card-body">
                            <form id="changeLevels" method="post" action="javascript:void(0);">
                                <?= csrf_field() ?>
                                <div class="form-group">
                                    <input id="count" name="count" class="form-control"
                                        placeholder="Enter new number of levels">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-outline-primary"> Update </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Form End -->
            </div>
        </div>
    </div>
</main>
<?= $this->endSection() ?>

<?= $this->section("scripts") ?>
<script>
    $(function () {
        $("#changeLevels").validate({
            rules: {
                count: {
                    required: true,
                    number: true,
                },
            },
            messages: {},

            submitHandler: function () {
                var formdata = $('#changeLevels').serialize();
                $.ajax({
                    url: "<?= esc(base_url('dashboard/levels/')) ?>",
                    type: "POST",
                    data: formdata,
                    dataType: "JSON",
                    success: function (data) {
                        if (data.status) {
                            // location.reload();
                            $('.levels').html(data.data);
                            $("#changeLevels")[0].reset();
                            $(".collapse").collapse('hide');
                        } else {
                            $('#error_message').fadeIn().html(data.data);
                            setTimeout(function () {
                                $('#error_message').fadeOut("slow");
                            }, 4000);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $('#error_message').fadeIn().html(
                            'An error occured, request not sent' + textStatus);
                        setTimeout(function () {
                            $('#error_message').fadeOut("slow");
                        }, 3000);
                    }
                });
            }
        });
    });
</script>
<?= $this->endSection() ?>