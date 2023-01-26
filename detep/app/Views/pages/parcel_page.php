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

<!-- ORGANIZING PARCELS STARTS -->
<?php 
function organizeParcel($data)
{
    foreach($data as $row) :
        $parcel_path = explode("_", $row['parc_station_path']);
        $stillHere = false;
        $canDelete = false;
        if(end($parcel_path) == session()->get('int_station') && $row['parc_next_station'] == NULL) $stillHere = true;
        if(session()->get('isTopUser') && end($parcel_path) == session()->get('int_station') && $row['parc_next_station'] == NULL) $canDelete = true;
        
        if(!empty($row['sub'])) : ?>
<div class="card mb-1">
    <li
        class="list-group-item list-group-item-action list-group-item-dark d-flex justify-content-between align-items-center">
        <div>
            <a data-toggle="collapse" class="text-info" href="#<?= $row['parc_id'] ?>">
                <span data-feather="folder"></span><?= $row['parc_title'] ?>
            </a>
            <?= $row['parc_sold'] == 1 ? '<span>✓</span>' : '' ?>
            <span class="badge badge-info ml-2"><?= esc($row['sy_title']) ?></span>
        </div>

        <div class="responsive">
            <?php 
            $sentKey = 0;
            if($row['parc_station_path'] !== NULL) :
                $arrivals = explode("_", $row['parc_arrival_dates']); ?>
            <div class="btn-group btn-group-sm mr-2" role="group" aria-label="First group">
                <?php foreach ($parcel_path as $key=>$value) : 
                    $sentKey = $key; ?>
                <button type="button"
                    class="btn <?= session()->get('int_station') == $value ? 'btn-success' : 'btn-secondary' ?> hover"
                    data-one='<?= $arrivals[$key] ?>' id='<?= $value ?>'><?= $value ?></button>
                <?php endforeach ?>
            </div>
            <?php endif ?>
            <?php  if($row['parc_next_station'] !== NULL) :
                $next = $row['parc_next_station']; 
                $sent_dates = explode("_", $row['parc_sent_dates']);
                $last_sent_date = $sent_dates[$sentKey]  ?>
            <div class="btn-group btn-group-sm" role="group" aria-label="Second group">
                <button type="button"
                    class="btn <?= session()->get('int_station') == $next ? 'btn-success' : 'btn-warning' ?> hover"
                    data-one="<?= $last_sent_date ?>" id="<?= $next ?>"><?= $next ?></button>
            </div>
            <?php endif ?>
        </div>

        <?php if(session()->get('isTopUser') && !session()->get('read_only')) : 
       
        ?>
        <div class="btn-group btn-group-sm">
            <button class="btn btn-outline-secondary" data-toggle="tooltip" data-placement="top" title="Print QR-Codes"
                <?= $canDelete ? : 'disabled' ?>
                onclick="window.location.href='<?= esc(base_url('dashboard/print')) . '/' . $row['parc_title'] ?>'">
                <span data-feather="printer"></span>
            </button>
            <button class="btn btn-outline-secondary moveBtn" data-id="<?= $row['parc_id'] ?>" title="Move This"
                <?= $canDelete ? : 'disabled' ?>>
                <span data-feather="move"></span>
            </button>
            <button class="btn btn-outline-secondary deleteBtn" data-one="<?= $row['parc_id'] ?>"
                data-two="<?= $row['parc_qrcodelink'] ?>" data-three="<?= 1 ?>" title="Delete Item"
                <?= $canDelete ? : 'disabled' ?>>
                <span data-feather="trash-2"></span>
            </button>
            <button class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#sendModel"
                data-placement="top" data-one="<?= $row['parc_id'] ?>" data-two="<?= $row['parc_title'] ?>" title="Send"
                <?= $stillHere ? : 'disabled' ?>>
                <span data-feather="send"></span>
            </button>
        </div>
        <?php endif ?>
    </li>

    <div id="<?= $row['parc_id'] ?>" class="panel-collapse collapse">
        <ul>
            <?php SubParcel($row['sub']) ?>
        </ul>
    </div>
</div>

<?php else : ?>
<li
    class="list-group-item list-group-item-action list-group-item-dark d-flex justify-content-between align-items-center mb-1">
    <div>
        <span class="text-info">
            <?= $row['parc_parent'] == NULL ? '<span data-feather="folder"></span>' . $row['parc_title'] : $row['prod_name'] ?>
        </span>
        <?= $row['parc_sold'] == 1 ? '<span>✓</span>' : '' ?>
        <?= $row['parc_parent'] == NULL ? '<span class="badge badge-info ml-2">' . $row['sy_title'] .'</span>' : "" ?>
    </div>

    <div class="responsive">
        <?php 
    $sentKey = 0;
    if($row['parc_station_path'] !== NULL) :
        $parcel_path = explode("_", $row['parc_station_path']);
        $arrivals = explode("_", $row['parc_arrival_dates']); ?>
        <div class="btn-group btn-group-sm mr-2" role="group" aria-label="First group">
            <?php foreach ($parcel_path as $key=>$value) : 
            $sentKey = $key; ?>
            <button type="button"
                class="btn <?= session()->get('int_station') == $value ? 'btn-success' : 'btn-secondary' ?> hover"
                data-one='<?= $arrivals[$key] ?>' id='<?= $value ?>'><?= $value ?></button>
            <?php endforeach ?>
        </div>
        <?php endif ?>
        <?php  if($row['parc_next_station'] !== NULL) :
        $next = $row['parc_next_station']; 
        $sent_dates = explode("_", $row['parc_sent_dates']);
        $last_sent_date = $sent_dates[$sentKey]  ?>
        <div class="btn-group btn-group-sm" role="group" aria-label="Second group">
            <button type="button"
                class="btn <?= session()->get('int_station') == $next ? 'btn-success' : 'btn-warning' ?> hover"
                data-one="<?= $last_sent_date ?>" id="<?= $next ?>"><?= $next ?></button>
        </div>
        <?php endif ?>
    </div>

    <?php if(session()->get('isTopUser') && !session()->get('read_only') && $row['parc_parent'] == NULL) : ?>
    <div class="btn-group btn-group-sm">
        <button class="btn btn-outline-secondary deleteBtn" data-one="<?= $row['parc_id'] ?>"
            data-two="<?= $row['parc_qrcodelink'] ?>" data-three="<?= $row['parc_parent'] == NULL ? 1 : 0 ?>"
            title="Delete Item" <?= $canDelete ? : 'disabled' ?>>
            <span data-feather="trash-2"></span>
        </button>
    </div>
    <?php endif ?>
</li>
<?php endif ?>
<?php endforeach ?>
<?php } ?>

<?php
function SubParcel($sub_parcel)
{
    foreach($sub_parcel as $row2) :
        $parcel_path = explode("_", $row2['parc_station_path']);
        $stillHere = false;
        $canDelete = false;
        if(end($parcel_path) == session()->get('int_station') && $row2['parc_next_station'] == NULL) $stillHere = true;
        if(session()->get('isTopUser') && end($parcel_path) == session()->get('int_station') && $row2['parc_next_station'] == NULL) $canDelete = true;
        if( ! empty($row2['sub'])) : ?>
<div class="card mb-1">
    <li
        class="list-group-item list-group-item-action list-group-item-light d-flex justify-content-between align-items-center">
        <div>
            <a data-toggle="collapse" class="text-info" href="#<?= $row2['parc_id'] ?>">
                <span data-feather="folder"></span> <?= $row2['parc_title'] ?>
            </a>
            <?= $row2['parc_sold'] == 1 ? '<span>✓</span>' : '' ?>
            <span class="badge badge-info ml-2"><?= esc($row2['sy_title']) ?></span>
        </div>

        <div class="responsive">
            <?php   $sentKey = 0;
    if($row2['parc_station_path'] !== NULL) :
        $parcel_path = explode("_", $row2['parc_station_path']);
        $arrivals = explode("_", $row2['parc_arrival_dates']); ?>
            <div class="btn-group btn-group-sm mr-2" role="group" aria-label="First group">
                <?php foreach ($parcel_path as $key=>$value) : 
            $sentKey = $key; ?>
                <button type="button"
                    class="btn <?= session()->get('int_station') == $value ? 'btn-success' : 'btn-secondary' ?> hover"
                    data-one='<?= $arrivals[$key] ?>' id='<?= $value ?>'><?= $value ?></button>
                <?php endforeach ?>
            </div>
            <?php endif ?>
            <?php  if($row2['parc_next_station'] !== NULL) :
        $next = $row2['parc_next_station']; 
        $sent_dates = explode("_", $row2['parc_sent_dates']);
        $last_sent_date = $sent_dates[$sentKey]  ?>
            <div class="btn-group btn-group-sm" role="group" aria-label="Second group">
                <button type="button"
                    class="btn <?= session()->get('int_station') == $next ? 'btn-success' : 'btn-warning' ?> hover"
                    data-one="<?= $last_sent_date ?>" id="<?= $next ?>"><?= $next ?></button>
            </div>
            <?php endif ?>
        </div>

        <?php if(session()->get('isTopUser') && !session()->get('read_only')) : ?>
        <div class="btn-group btn-group-sm">
            <button class="btn btn-outline-secondary" data-toggle="tooltip" data-placement="top" title="Print QR-Codes"
                <?= $canDelete ? : 'disabled' ?>
                onclick="window.location.href='<?= esc(base_url('dashboard/print')) . '/' . $row2['parc_title'] ?>'">
                <span data-feather="printer"></span>
            </button>
            <button class="btn btn-outline-secondary moveBtn" data-id="<?= $row2['parc_id'] ?>" title="Move This"
                <?= $canDelete ? : 'disabled' ?>>
                <span data-feather="move"></span>
            </button>
            <button class="btn btn-outline-secondary deleteBtn" data-one="<?= $row2['parc_id'] ?>"
                data-two="<?= $row2['parc_qrcodelink'] ?>" data-three="<?= 1 ?>" title="Delete Item"
                <?= $canDelete ? : 'disabled' ?>>
                <span data-feather="trash-2"></span>
            </button>
            
            <button class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#sendModel"
                data-placement="top" data-one="<?= $row2['parc_id'] ?>" data-two="<?= $row2['parc_title'] ?>"
                title="Send" <?= $stillHere ? : 'disabled' ?>>
                <span data-feather="send"></span>
            </button>
        </div>
        <?php endif ?>
    </li>
    <div id="<?= $row2['parc_id'] ?>" class="panel-collapse collapse">
        <ul>
            <?php SubParcel($row2['sub']); ?>
        </ul>
    </div>
</div>
<?php else : ?>
<li
    class="list-group-item list-group-item-action list-group-item-light d-flex justify-content-between align-items-center mb-1">
    <div>
        <span class="text-info">
            <?= $row2['parc_parent'] == NULL ? '<span data-feather="folder"></span>' . $row2['parc_title'] : $row2['prod_name'] ?>
        </span>
        <?= $row2['parc_sold'] == 1 ? '<span>✓</span>' : '' ?>
        <?= $row2['parc_parent'] == NULL ? '<span class="badge badge-info ml-2">' . $row2['sy_title'] .'</span>' : "" ?>
    </div>

    <div class="responsive">
        <?php 
    $sentKey = 0;
    if($row2['parc_station_path'] !== NULL) :
        $parcel_path = explode("_", $row2['parc_station_path']);
        $arrivals = explode("_", $row2['parc_arrival_dates']); ?>
        <div class="btn-group btn-group-sm mr-2" role="group" aria-label="First group">
            <?php foreach ($parcel_path as $key=>$value) : 
            $sentKey = $key; ?>
            <button type="button"
                class="btn <?= session()->get('int_station') == $value ? 'btn-success' : 'btn-secondary' ?> hover"
                data-one='<?= $arrivals[$key] ?>' id='<?= $value ?>'><?= $value ?></button>
            <?php endforeach ?>
        </div>
        <?php endif ?>
        <?php  if($row2['parc_next_station'] !== NULL) :
        $next = $row2['parc_next_station']; 
        $sent_dates = explode("_", $row2['parc_sent_dates']);
        $last_sent_date = $sent_dates[$sentKey]  ?>
        <div class="btn-group btn-group-sm" role="group" aria-label="Second group">
            <button type="button"
                class="btn <?= session()->get('int_station') == $next ? 'btn-success' : 'btn-warning' ?> hover"
                data-one="<?= $last_sent_date ?>" id="<?= $next ?>"><?= $next ?></button>
        </div>
        <?php endif ?>
    </div>

    <?php if(session()->get('isTopUser') && !session()->get('read_only') && $row2['parc_parent'] == NULL) : ?>
    <div class="btn-group btn-group-sm">
        <button class="btn btn-outline-secondary deleteBtn" data-one="<?= $row2['parc_id'] ?>"
            data-two="<?= $row2['parc_qrcodelink'] ?>" data-three="<?= $row2['parc_parent'] == NULL ? 1 : 0 ?>"
            title="Delete Item" <?= $canDelete ? : 'disabled' ?>>
            <span data-feather="trash-2"></span>
        </button>
    </div>
    <?php endif ?>
</li>
<?php endif ?>
<?php endforeach ?>
<?php } ?>
<!-- ORGANIZING PARCELS ENDS -->

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active"><?= esc($title) ?></li>
    </ol>

    <!-- Errors area -->
    <?= isset($validation) ? $validation->listErrors('my_list') : "" ?>
    <?= isset($success_redirect) ? '<div class="alert alert-success">'.esc($success_redirect).'</div>' : "" ?>
    <?= isset($error_redirect) ? '<div class="alert alert-danger">'.esc($error_redirect).'</div>' : "" ?>

    <div id="error_message" class="ajax_response alert alert-danger" style="display:none;"></div>
    <div id="success_message" class="ajax_response alert alert-success" style="display:none;"></div>
    <!-- Errors end -->

    <!-- Add Parcel Form Start -->
    <div class="collapse mb-2" id="addProductcollapse">
        <div class="card">
            <div class="card-header container-fluid">
                Add New Parcel
            </div>
            <div class="card-body">
                <form id="addParcel" method="post" action="javascript:void(0);">
                    <?= csrf_field() ?>
                    <div class="form-row">
                        <div class="col form-group">
                            <label>Product Category</label>
                            <select name="product" id="product" class="form-control">
                                <option selected disabled> Choose from your list...</option>
                                <?php foreach($products as $row): ?>
                                <option value="<?= $row['prod_id'] . "*" . $row['prod_name'] ?>">
                                    <?= esc($row['prod_name']) ?>
                                </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col form-group">
                            <label>Number of Items</label>
                            <input type="number" id="items" name="items" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"> Create Parcel </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Parcel Form End -->


    <!-- Send Parcel Form Start -->
    <div class="modal fade" id="sendModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Moving</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="sendingParcel" method="post" action="javascript:void(0);">
                        <?= csrf_field() ?>
                        <div class="form-group parcel" style="display: none;">
                            <label for="parcel" class="col-form-label">ID:</label>
                            <input name="parcel" id="parcel" type="text" class="form-control">
                        </div>
                        <div class="form-group input-group mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect01">Target</label>
                            </div>
                            <select name="send_to" class="form-control custom-select selectpicker"
                                data-live-search="true" id="send_to">
                                <option disabled selected>Choose...</option>
                                <?php if( ! empty($stations)) :
                                    foreach($stations as $row) : ?>
                                <option value="<?= esc($row['st_id']) ?>"> <?= esc($row['st_title']) ?></option>
                                <?php endforeach ?>
                                <?php endif ?>
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Send Parcel Form End -->


    <!-- List Start -->
    <div class="card mb-2">
        <div class="card-header container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <h6>Supply Chain <?= esc($title) ?></h6>
                </div>
                <?php if($session->get('read_only') == false && $session->get('isTopUser')) : ?>
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
            <ul class="list-group">
                <?php if( ! empty($parcels)) : ?>
                <?php organizeParcel($parcels); ?>
                <?php else: ?>
                <?= '<div>No Results!</div>' ?>
                <?php endif ?>

            </ul>
        </div>
    </div>
</main>
<?= $this->endSection() ?>

<?= $this->section("scripts") ?>


<script>
    $(function () {


        $("#addParcel").validate({
            rules: {
                product: {
                    required: true,
                },
                items: {
                    required: true,
                    number: true,
                },
            },
            messages: {},

            submitHandler: function () {
                var formdata = $('#addParcel').serialize();
                //alert(formdata);
                $("#isloading").show();
                $.ajax({
                    url: "<?= esc(base_url('dashboard/parcels/')) ?>",
                    type: "POST",
                    data: formdata,
                    dataType: "JSON",
                    success: function (data) {
                        $("#isloading").hide();
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
                        $("#isloading").hide();
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

    // POPOVER AREA
    // $(document).ready(function () {
    $(function () {

        $('.hover').popover({
            title: fetchData,
            html: true,
            placement: 'bottom',
            trigger: 'hover'
        });

        function fetchData() {
            var fetch_data = '';
            var element = $(this);
            var id = element.attr("id");
            var action_date = element.attr("data-one");
            $("#isloading").show();
            $.ajax({
                url: "<?= esc(base_url('dashboard/parcels/parcel-path')) ?>",
                method: "POST",
                async: false,
                data: {
                    id: id,
                    action_date: action_date
                },
                success: function (data) {
                    $("#isloading").hide();
                    fetch_data = data;
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $("#isloading").hide();
                }
            });
            return fetch_data;
        }
    });

    //######## SEND PERCEL MODAL START
    $('#sendModel').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var id = button.data('one')
        var name = button.data('two') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)
        modal.find('.modal-title').text('Sending ' + name)
        // modal.find('.modal-body input').val(recipient)
        modal.find('.parcel input').val(id)
    });
    //######## SEND PERCEL MODAL END


    //######## SEND PERCEL START
    $(function () {
        $("#sendingParcel").validate({
            rules: {
                parcel: "required",
                send_to: "required",
            },
            messages: {},

            submitHandler: function () {
                var formdata = $('#sendingParcel').serialize();
                $("#isloading").show();
                $.ajax({
                    url: "<?= esc(base_url('dashboard/parcels/send')) ?>",
                    type: "POST",
                    data: formdata,
                    dataType: "JSON",
                    success: function (data) {
                        $('#sendModel').modal('hide');
                        $("#isloading").hide();
                        if (data.status) {
                            bootbox.alert(data.data.toString());
                            location.reload();
                        } else {
                            // $('#error_message').fadeIn().html(data.data);
                            // setTimeout(function () {
                            //     $('#error_message').fadeOut("slow");
                            // }, 4000);
                            bootbox.alert(data.data.toString());
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $('#sendModel').modal('hide');
                        $("#isloading").hide();
                        $('#error_message').fadeIn().html('Error at add data');
                        setTimeout(function () {
                            $('#error_message').fadeOut("slow");
                        }, 2000);
                    }
                });
            }
        });
    });

    // DELETE AREA -deleteBtn
    $('.deleteBtn').click(function () {
        var el = this;
        var deleteid = $(this).attr('data-one');
        var qrcode = $(this).attr('data-two');
        var isDir = $(this).attr('data-three');

        bootbox.confirm("Do you really want to delete this record?", function (result) {
            if (result) {
                $("#isloading").show();
                $.ajax({
                    url: "<?= esc(base_url('dashboard/parcels/delete')) ?> ",
                    type: 'POST',
                    dataType: "JSON",
                    data: {
                        [csrfName]: csrfHash,
                        id: deleteid,
                        qrcode: qrcode,
                        isDir: isDir,
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

    // MOVING AREA - moveBtn
    var options = <?= json_encode($readyParcels) ?> ;
    $('.moveBtn').click(function () {
        var el = this;
        var id = $(this).attr('data-id');
        bootbox.prompt({
            title: "Where are you sending this?",
            inputType: "select",
            inputOptions: options,
            callback: function (result) {
                showResult(result, id);
            }
        });
    });

    function showResult(result, id) {
        if (typeof result !== "undefined" && result !== null) {
            if (result != id) {
                $("#isloading").show();
                $.ajax({
                    url: "<?= esc(base_url('dashboard/parcels/move')) ?>",
                    type: 'POST',
                    dataType: "JSON",
                    data: {
                        [csrfName]: csrfHash,
                        id: id,
                        moveTo: result,
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
            } else {
                bootbox.alert("No changes selected");
            }
        }
    }
</script>
<?= $this->endSection() ?>