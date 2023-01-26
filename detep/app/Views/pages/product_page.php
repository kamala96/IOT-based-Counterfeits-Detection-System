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
    <div class="collapse mb-2" id="addProductcollapse">
        <div class="card">
            <div class="card-header container-fluid">
                Add New Product
            </div>
            <div class="card-body">
                <form id="addProduct" method="post" action="javascript:void(0);">
                    <?= csrf_field() ?>
                    <div class="form-row">
                        <div class="col form-group">
                            <label>Product Name</label>
                            <input type="text" id="name" name="name" class="form-control">
                        </div>
                        <div class="col form-group">
                            <label>Product Category</label>
                            <select name="category" id="category" class="form-control">
                                <option selected disabled> Choose...</option>
                                <?php foreach($categories as $row): ?>
                                <option value="<?= $row['cat_id'] ?>">
                                    <?= esc($row['cat_name']) ?>
                                </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"> Save </button>
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
                <?php if( $session->get('read_only') == false && $session->get('isTopUser')) : ?>
                <div class="col-md-2 float-right">
                    <button class="btn btn-sm btn-primary float-right" data-toggle="collapse"
                        data-target="#addProductcollapse">
                        <span data-feather="plus-circle"></span> Add New
                    </button>
                </div>
                <?php endif ?>
            </div>
        </div>
        <div class="card-body">
            <?php if(! empty($products)): ?>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <?= $session->get('read_only') == true ? '<th>System</th>' : '' ?>
                            <th>Reg-Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <?= $session->get('read_only') == true ? '<th>System</th>' : '' ?>
                            <th>Reg-Date</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach($products as $row): ?>
                        <tr>
                            <td><?= esc($row['prod_name']) ?></td>
                            <td><?= esc($row['cat_name']) ?></td>
                            <?= $session->get('read_only') == true ? '<td>'.esc($row['sy_title']).'</th>' : '' ?></td>
                            <td><?= date("M d Y h:i A", strtotime($row['prod_regdate'])) ?>
                            </td>
                            <td>
                                <button class="btn btn-outline-secondary deleteBtn"
                                    data-one="<?= $row['prod_id'] ?>">Trash
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
        $("#addProduct").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3
                },
                category: {
                    required: true,
                    number: true
                },
            },
            messages: {},

            submitHandler: function () {
                var formdata = $('#addProduct').serialize();
                $.ajax({
                    url: "<?= esc(base_url('dashboard/products/')) ?>",
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
                    url: "<?= esc(base_url('dashboard/products/delete')) ?> ",
                    type: 'POST',
                    dataType: "JSON",
                    data: {
                        [csrfName]: csrfHash,
                        product_id: deleteid,
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
<?= $this->endSection() ?>.