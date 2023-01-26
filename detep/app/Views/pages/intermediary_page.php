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
    <div class="collapse mb-2" id="addUsercollapse">
        <div class="card">
            <div class="card-header container-fluid">
                Add New Administrator
            </div>
            <div class="card-body">
                <form id="addIntermediary" method="post" action="javascript:void(0);">
                    <?= csrf_field() ?>
                    <div class="form-row">
                        <div class="col form-group">
                            <label>First name </label>
                            <input type="text" id="first_name" name="first_name" value="<?= set_value('first_name') ?>"
                                class="form-control" placeholder="">
                        </div>
                        <div class="col form-group">
                            <label>Last name</label>
                            <input type="text" id="last_name" name="last_name" value="<?= set_value('last_name') ?>"
                                class="form-control" placeholder=" ">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col form-group">
                            <label>Email address</label>
                            <input type="email" id="email" name="email" value="<?= set_value('email') ?>"
                                class="form-control" placeholder="">
                        </div>
                        <div class="col form-group">
                            <label>Mobile Phone</label>
                            <input type="tel" pattern="^\+[1-9]\d{1,14}$" id="phone" name="phone"
                                value="<?= set_value('phone') ?>" class="form-control" placeholder=" ">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Station</label>
                        <select name="station" id="station" class="form-control">
                            <option selected disabled> Choose...</option>
                            <?php foreach($stations as $row): ?>
                            <option value="<?= $row['st_id'] ?>">
                                <?= esc(ucwords($row['st_title'])).'  -('.esc($row['st_description']).')' ?>
                            </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="col form-group">
                            <label>Create password</label>
                            <input type="password" id="password" name="password" class="form-control">
                        </div>
                        <div class="col form-group">
                            <label>Confirm password</label>
                            <input type="password" id="confpassword" name="confpassword" class="form-control">
                        </div>
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
                    <h6 style="text-transform:uppercase;">STORES <?= esc($title) ?></h6>
                </div>
                <?php if($session->get('isTopUser')) : ?>
                <div class="col-md-2 float-right">
                    <button class="btn btn-sm btn-primary float-right" data-toggle="collapse"
                        data-target="#addUsercollapse">
                        <span data-feather="plus-circle"></span> Add New
                    </button>
                </div>
                <?php endif ?>
            </div>
        </div>
        <div class="card-body">
            <?php if(! empty($intermediaries)): ?>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Mail</th>
                            <th>Mobile</th>
                            <th>Level</th>
                            <th>Station</th>
                            <th>Mobile Device</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Mail</th>
                            <th>Mobile</th>
                            <th>Level</th>
                            <th>Station</th>
                            <th>Mobile Device</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach($intermediaries as $row): ?>
                        <tr>
                            <td><?= esc($row['int_fname'])."&nbsp;".esc($row['int_lname']) ?></td>
                            <td><?= esc($row['int_mail']) ?></td>
                            <td><?= esc($row['int_phone']) ?></td>
                            <td><?= $row['lv_int'] == 100 ? 'last' : $row['lv_int'] ?></td>
                            <td><?= esc($row['st_title']) ?></td>
                            <td><?= esc($row['int_device']) ?></td>
                            <td>
                                <button type="button" class="btn btn-outline-primary deleteBtn btn-sm"
                                    data-one="<?= $row['int_id'] ?>">Trash
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
        $("#addIntermediary").validate({
            rules: {
                first_name: {
                    required: true,
                    minlength: 3
                },
                last_name: {
                    required: true,
                    minlength: 3
                },
                phone: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 15,
                },
                station: "required",
                confpassword: {
                    required: true,
                    minlength: 6,
                    maxlength: 15,
                    equalTo: "#password"
                },
            },
            messages: {},

            submitHandler: function () {
                var formdata = $('#addIntermediary').serialize();
                $.ajax({
                    url: "<?= esc(base_url('dashboard/intermediaries/')) ?>",
                    type: "POST",
                    data: formdata,
                    dataType: "JSON",
                    success: function (data) {
                        if (data.status) {
                            location.reload();
                            $('#success_message').fadeIn().html('Success: Sent to ' +
                                data.data);
                            setTimeout(function () {
                                $('#success_message').fadeOut("slow");
                            }, 4000);
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
                    url: "<?= esc(base_url('dashboard/intermediaries/delete')) ?> ",
                    type: 'POST',
                    dataType: "JSON",
                    data: {
                        [csrfName]: csrfHash,
                        int_id: deleteid,
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