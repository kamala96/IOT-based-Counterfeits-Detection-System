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

    <!-- Form Start -->
    <div class="collapse mb-2" id="addStationcollapse">
        <div class="card">
            <div class="card-header container-fluid">
                Add New Store
            </div>
            <div class="card-body">
                <form id="addStation" method="post" action="javascript:void(0);">
                    <?= csrf_field() ?>
                    <div class="form-row">
                        <div class="col form-group">
                            <label>Title</label>
                            <input type="text" id="name" name="name" class="form-control">
                        </div>
                        <div class="col form-group">
                            <label>Level</label>
                            <select name="level" id="level" class="form-control">
                                <option selected disabled> Choose...</option>
                                <?php foreach($levels as $row): ?>
                                <option value="<?= $row['lv_id'] ?>">
                                    <?= esc($row['lv_title']) ?>
                                </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Descriptions</label>
                        <textarea id="descriptions" name="descriptions" class="form-control"
                            placeholder=""> </textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block"> Save </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Form End -->

    <!-- List Start -->
    <div class="card mb-2">
        <div class="card-header container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <h6 style="text-transform:uppercase"><?= esc($title) ?> MANAGEMENT</h6>
                </div>
                <?php if($session->get('isTopUser')) : ?>
                <div class="col-md-2 float-right">
                    <button class="btn btn-sm btn-primary float-right" data-toggle="collapse"
                        data-target="#addStationcollapse">
                        <span data-feather="plus-circle"></span> Add New
                    </button>
                </div>
                <?php endif ?>
            </div>
        </div>
        <div class="card-body">
            <?php if(! empty($stations)): ?>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Detailed Info</th>
                            <th>Level</th>
                            <th>Beacon</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Detailed Info</th>
                            <th>Level</th>
                            <th>Beacon</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach($stations as $row): ?>
                        <?php $level0 = explode('-', $row['lv_title']); $level = trim($level0[0]); ?>
                        <tr>
                            <td><?= esc($row['st_title']) ?></td>
                            <td><?= $row['st_description'] != NULL ? esc($row['st_description']) : 'NULL' ?></td>
                            <td><?= esc($level) ?></td>
                            <td><?= esc($row['st_beacon']) ?></td>
                            <td>
                                <button type="button" class="btn btn-outline-primary deleteBtn btn-sm"
                                    data-one="<?= $row['st_id'] ?>">Trash
                                </button>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <?php else : ?>
            <div class="alert alert-light" role="alert">No results found.</div>
            <?php endif ?>
        </div>
    </div>
</main>
<?= $this->endSection() ?>

<?= $this->section("scripts") ?>
<script>
    $(function () {
        $("#addStation").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3
                },
                level: {
                    required: true,
                    number: true
                },
            },
            messages: {},

            submitHandler: function () {
                var formdata = $('#addStation').serialize();
                $.ajax({
                    url: "<?= esc(base_url('dashboard/stations/')) ?>",
                    type: "POST",
                    data: formdata,
                    dataType: "JSON",
                    success: function (data) {
                        if (data.status) {
                            location.reload();
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
        // DELETE AREA -deleteBtn
    $('.deleteBtn').click(function () {
        var el = this;
        var deleteid = $(this).attr('data-one');

        bootbox.confirm("Do you really want to delete this record?", function (result) {
            if (result) {
                $("#isloading").show();
                $.ajax({
                    url: "<?= esc(base_url('dashboard/stations/delete')) ?> ",
                    type: 'POST',
                    dataType: "JSON",
                    data: {
                        [csrfName]: csrfHash,
                        station_id: deleteid,
                    },
                    success: function (response) {
                        $("#isloading").hide();
                        if (response.status) {
                            bootbox.alert(response.data.toString(), function (action) {
                                location.reload();
                            });
                        } else {
                            bootbox.alert(response.data.toString());
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $("#isloading").hide();
                        bootbox.alert(errorThrown.toString());
                    }
                });
            }
        });
    });
</script>
<?= $this->endSection() ?>