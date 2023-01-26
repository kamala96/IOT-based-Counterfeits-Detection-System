<?= $this->extend("templates/app") ?>

<?= $this->section("body") ?>
<?php 
function fetch_menu($data)
{
    foreach($data as $menu) :
        if(!empty($menu->sub)) : 
            ?>
<div class="card">
    <li
        class="list-group-item list-group-item-action list-group-item-light d-flex justify-content-between align-items-center">
        <div>
            <a data-toggle="collapse" href="#<?= $menu->title ?>">
                <span data-feather="folder"></span><?= $menu->title ?></a><span
                class="badge badge-primary badge-pill">14</span>
        </div>
        <div class="btn-group btn-group-sm">
            <button class="btn btn-outline-secondary" data-toggle="tooltip" data-placement="top"
                title="Download QR-Codes"
                onClick="location.href='<?= esc(base_url('dashboard/medicines/qr-codes/'.'1/'.str_replace('/', '_', $menu->qrcodelink))) ?>'">
                <span data-feather="download"></span>
            </button>
            <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#moveModal"
                data-one="<?= $menu->id ?>" data-two="<?= $menu->title ?>" title="Move This">
                <span data-feather="move"></span>
            </button>
            <button class="btn btn-outline-secondary"
                onClick="location.href='<?= esc(base_url('dashboard/medicines/delete/'.$menu->id.'/1/'.str_replace('/', '_', $menu->qrcodelink).'/1')) ?>'"
                data-toggle="tooltip" data-placement="top" title="Delete Item">
                <span data-feather="trash-2"></span>
            </button>
        </div>
    </li>

    <div id="<?= $menu->title ?>" class="panel-collapse collapse">
        <ul>
            <?php fetch_sub_menu($menu->sub) ?>
        </ul>
    </div>
</div>

<?php else : ?>
<li class="list-group-item list-group-item-light d-flex justify-content-between align-items-center">
    <?= $menu->title ?>
    <div class="btn-group btn-group-sm">
        <button class="btn btn-outline-secondary" data-toggle="tooltip" data-placement="top" title="Download QR-Codes"
            onClick="location.href='<?= esc(base_url('dashboard/medicines/qr-codes/'.'0/'.str_replace('/', '_', $menu->qrcodelink))) ?>'">
            <span data-feather="download"></span>
        </button>
        <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#moveModal"
            data-one="<?= $menu->id ?>" data-two="<?= $menu->title ?>" title="Move This">
            <span data-feather="move"></span>
        </button>
        <button class="btn btn-outline-secondary"
            onClick="location.href='<?= esc(base_url('dashboard/medicines/delete/'.$menu->id.'/1/'.str_replace('/', '_', $menu->qrcodelink).'/0')) ?>'"
            data-toggle="tooltip" data-placement="top" title="Delete Item">
            <span data-feather="trash-2"></span>
        </button>
    </div>
</li>
<?php endif ?>
<?php endforeach ?>
<?php } ?>

<?php
function fetch_sub_menu($sub_menu)
{
    foreach($sub_menu as $menu) :
        if( ! empty($menu->sub)) : ?>
<div class="card">
    <li
        class="list-group-item list-group-item-action list-group-item-light d-flex justify-content-between align-items-center">
        <div>
            <a data-toggle="collapse" href="#<?= $menu->title ?>">
                <span data-feather="folder"></span> <?= $menu->title ?>
            </a>
            <span class="badge badge-primary badge-pill">14</span>
        </div>
        <div class="btn-group btn-group-sm">
            <button class="btn btn-outline-secondary" data-toggle="tooltip" data-placement="top"
                title="Download QR-Codes"
                onClick="location.href='<?= esc(base_url('dashboard/medicines/qr-codes/'.'1/'.str_replace('/', '_', $menu->qrcodelink))) ?>'">
                <span data-feather="download"></span>
            </button>
            <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#moveModal"
                data-one="<?= $menu->id ?>" data-two="<?= $menu->title ?>" title="Move This">
                <span data-feather="move"></span>
            </button>
            <button class="btn btn-outline-secondary"
                onClick="location.href='<?= esc(base_url('dashboard/medicines/delete/'.$menu->id.'/1/'.str_replace('/', '_', $menu->qrcodelink).'/1')) ?>'"
                data-toggle="tooltip" data-placement="top" title="Delete Item">
                <span data-feather="trash-2"></span>
            </button>
        </div>
    </li>
    <div id="<?= $menu->title ?>" class="panel-collapse collapse">
        <ul>
            <?php fetch_sub_menu($menu->sub); ?>
        </ul>
    </div>
</div>
<?php else : ?>
<li class="list-group-item list-group-item-dark d-flex justify-content-between align-items-center disabled">
    <?= $menu->title ?>
    <div class="btn-group btn-group-sm">
        <button class="btn btn-outline-secondary" data-toggle="tooltip" data-placement="top" title="Download QR-Codes"
            onClick="location.href='<?= esc(base_url('dashboard/medicines/qr-codes/'.'0/'.str_replace('/', '_', $menu->qrcodelink))) ?>'">
            <span data-feather="download"></span>
        </button>
        <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#moveModal"
            data-one="<?= $menu->id ?>" data-two="<?= $menu->title ?>" title="Move This">
            <span data-feather="move"></span>
        </button>
        <button class="btn btn-outline-secondary"
            onClick="location.href='<?= esc(base_url('dashboard/medicines/delete/'.$menu->id.'/1/'.str_replace('/', '_', $menu->qrcodelink).'/0')) ?>'"
            data-toggle="tooltip" data-placement="top" title="Delete Item">
            <span data-feather="trash-2"></span>
        </button>
    </div>
</li>
<?php endif ?>
<?php endforeach ?>
<?php } ?>

<?php function fetch_menu_with_child($data)
{
    foreach($data as $menu) :
        if(!empty($menu->sub)) : 
            ?>
<option value="<?= $menu->id.'*'.substr($menu->qrcodelink, 0, strrpos($menu->qrcodelink, '/') + 1) ?>"
    name="selectParent"> <?= $menu->title ?></option>
<?php fetch_sub_menu_with_child($menu->sub); ?>
<?php endif ?>
<?php endforeach ?>
<?php 
}?>


<?php function fetch_sub_menu_with_child($sub_menu)
{
    foreach($sub_menu as $menu) :
    if(!empty($menu->sub)) : 
    ?>
<option value="<?= $menu->id.'*'.substr($menu->qrcodelink, 0, strrpos($menu->qrcodelink, '/') + 1) ?>"
    name="selectParent"> <?= $menu->title ?></option>
<?php fetch_sub_menu_with_child($menu->sub); ?>
<?php endif ?>
<?php endforeach ?>
<?php } ?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('dashboard/medicines') ?>">Parcels</a></li>
        <li class="breadcrumb-item active"><?= esc($title) ?></li>
    </ol>

    <!-- Errors section start -->
    <?= isset($validation) ? $validation->listErrors('my_list') : "" ?>
    <?= isset($success_redirect) ? '<div class="alert alert-success">'.esc($success_redirect).'</div>' : "" ?>
    <?= isset($error_redirect) ? '<div class="alert alert-danger">'.esc($error_redirect).'</div>' : "" ?>
    <!-- Error section end -->

    <!-- Form Start -->
    <div class="collapse mb-2" id="addPercelcollapse">
        <div class="card card-body">
            <form action="<?= esc(base_url('dashboard/medicines/'.$title."/".$percel)) ?>" method="post">
                <?= csrf_field() ?>
                <div class="form-group row">
                    <label for="inputTitle" class="col-sm-2 col-form-label">Parcel</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputTitle" value="<?= $title ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="selectParent" class="col-sm-2 col-form-label">Parent</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="selectParent" name="selectParent">
                            <option value="" disabled selected>Choose...</option>
                            <option value="<?= $percel.'*'.$title."/" ?>"> <?= $title ?></option>
                            <?php fetch_menu_with_child($percel_tree); ?>
                        </select>
                    </div>
                </div>
                <fieldset class="form-group">
                    <div class="row">
                        <legend class="col-form-label col-sm-2 pt-0">Item Form</legend>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="type1" value="1"
                                    <?= set_radio('type', 1) ?> checked>
                                <label class="form-check-label" for="type1">
                                    Package
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="type2" value="2"
                                    <?= set_radio('type2', 2) ?>>
                                <label class="form-check-label" for="type2">
                                    Single Item
                                </label>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="form-group row">
                    <label for="inputCount" class="col-sm-2 col-form-label">Count</label>
                    <div class="col-sm-10">
                        <input type="number" name="count" class="form-control" id="inputCount"
                            placeholder="Enter number of items to add to a selected item form"
                            onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57"
                            <?= set_value('count') ?> required>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Form End -->


    <!-- List Start -->
    <div class="card mb-2">
        <div class="card-header container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <h5 class="h5">
                        <small class="text-muted"><?= esc($title) ?></small> Items
                    </h5>
                </div>
                <div class="col-md-2 float-right">
                    <button class="btn btn-sm btn-primary float-right" data-toggle="collapse"
                        data-target="#addPercelcollapse">
                        <span data-feather="plus-circle"></span> Add Item
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <ul class="list-group">
                <?php fetch_menu($percel_tree); ?>
            </ul>
        </div>
    </div>
    <!-- List End -->
</main>
</script>
<?= $this->endSection() ?>