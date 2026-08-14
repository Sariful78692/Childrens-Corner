<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<style>
    .material-switch > input[type="checkbox"] {
        display: none;   
    }

    .material-switch > label {
        cursor: pointer;
        height: 0px;
        position: relative; 
        width: 40px;  
    }

    .material-switch > label::before {
        background: rgb(0, 0, 0);
        box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
        border-radius: 8px;
        content: '';
        height: 16px;
        margin-top: -8px;
        position:absolute;
        opacity: 0.3;
        transition: all 0.4s ease-in-out;
        width: 40px;
    }
    .material-switch > label::after {
        background: rgb(255, 255, 255);
        border-radius: 16px;
        box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
        content: '';
        height: 24px;
        left: -4px;
        margin-top: -8px;
        position: absolute;
        top: -4px;
        transition: all 0.3s ease-in-out;
        width: 24px;
    }
    .material-switch > input[type="checkbox"]:checked + label::before {
        background: inherit;
        opacity: 0.5;
    }
    .material-switch > input[type="checkbox"]:checked + label::after {
        background: inherit;
        left: 20px;
    }

    /* Modern Upload Box */
    .notice-upload-zone {
        background: #f8fafc;
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }

    .notice-upload-zone:hover {
        border-color: #4361ee;
        background: #f1f5f9;
    }

    .notice-upload-zone i {
        font-size: 32px;
        color: #4361ee;
        margin-bottom: 10px;
        display: block;
    }

    .notice-upload-zone span {
        font-size: 14px;
        font-weight: 600;
        color: #475569;
        display: block;
    }

    .notice-upload-zone small {
        color: #94a3b8;
    }

    .file-name-pill {
        display: none;
        margin-top: 10px;
        padding: 5px 12px;
        background: #4361ee;
        color: white;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .hidden-file-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    /* Image Hover Styles */
    .img-view-container {
        position: relative;
        width: 50px;
        height: 50px;
        border-radius: 4px;
        overflow: hidden;
        display: inline-block;
        vertical-align: middle;
        cursor: pointer;
        border: 1px solid #ddd;
    }
    .img-view-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .img-view-container .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
    }
    .img-view-container:hover .overlay {
        opacity: 1;
    }
    .img-view-container .overlay i {
        font-size: 14px;
        background: #17a2b8;
        color: #fff;
        width: 32px;
        height: 32px;
        line-height: 32px;
        text-align: center;
        border-radius: 6px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        display: inline-block;
        padding: 0;
        border: 1px solid rgba(255,255,255,0.2);
    }

    /* Centered Modal without scrolling */
    body.modal-open {
        overflow: hidden !important;
        padding-right: 0 !important;
    }
    #imagePreviewModal {
        text-align: center;
        padding: 0 !important;
        overflow: hidden !important;
    }
    #imagePreviewModal:before {
        content: '';
        display: inline-block;
        height: 100%;
        vertical-align: middle;
        margin-right: -4px;
    }
    #imagePreviewModal .modal-dialog {
        display: inline-block;
        text-align: left;
        vertical-align: middle;
        width: auto;
        max-width: 90%;
    }
    #imagePreviewModal .modal-content {
        border: none;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        border-radius: 12px;
        overflow: hidden;
    }
    #imagePreviewModal .modal-body {
        padding: 0;
        position: relative;
    }
    #imagePreviewModal .close {
        color: #000;
        opacity: 1;
        text-shadow: none;
        padding: 0;
        background: #fff;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        margin: 15px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.4);
        line-height: 30px;
        position: absolute;
        right: 0;
        top: 0;
        z-index: 2000;
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        border: 1px solid #ddd;
    }
    .zoom-controls {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(255, 255, 255, 0.9);
        padding: 5px 15px;
        border-radius: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        z-index: 100;
        display: flex;
        gap: 15px;
    }
    .zoom-btn {
        background: none;
        border: none;
        font-size: 18px;
        color: #333;
        cursor: pointer;
        padding: 5px;
        transition: color 0.3s;
    }
    .zoom-btn:hover {
        color: #4361ee;
    }
    .img-wrapper {
        overflow: auto;
        max-height: 85vh;
        width: 100%;
        max-width: 95vw;
        padding: 40px;
        background: #f0f2f5;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #modal_image {
        transition: transform 0.3s ease;
        transform-origin: center;
        max-width: 100%;
        max-height: calc(85vh - 80px);
        height: auto;
        width: auto;
        display: block;
        margin: 0 auto;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    /* Description Truncation */
    .desc-cell {
        max-width: 250px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 12px;
        color: #666;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-bullhorn"></i> Notice Board</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <?= $this->session->flashdata('msg'); ?>
            </div>
            
            <!-- Form Section -->
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo isset($notice) ? 'Edit Notice' : 'Add Notice'; ?></h3>
                        <?php if (isset($notice)) { ?>
                            <div class="box-tools pull-right">
                                <a href="<?php echo base_url(); ?>admin/cms/notice_board" class="btn btn-sm btn-primary" style="margin-top: 2px;">
                                    <i class="fa fa-arrow-left"></i> Back
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                    <form action="<?php echo site_url('admin/cms/notice_board') ?>" id="notice_form" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <input type="hidden" name="notice_id" value="<?php echo isset($notice) ? $notice['id'] : ''; ?>">
                            <div class="form-group">
                                <label>Title</label><small class="req"> *</small>
                                <input autofocus="" id="title" name="title" placeholder="" type="text" class="form-control" value="<?php echo isset($notice) ? $notice['title'] : set_value('title'); ?>" required />
                                <span class="text-danger"><?php echo form_error('title'); ?></span>
                            </div>
                            <div class="form-group">
                                <label>Description</label><small class="req"> *</small>
                                <textarea id="description" name="description" class="form-control compose-textarea summernote" required><?php echo isset($notice) ? $notice['description'] : set_value('description'); ?></textarea>
                                <span class="text-danger"><?php echo form_error('description'); ?></span>
                            </div>
                            <div class="form-group">
                                <label>Notice Image</label>
                                <div class="notice-upload-zone" onclick="document.getElementById('notice_image').click();">
                                    <i class="fa fa-picture-o"></i>
                                    <span>Click to Select Image or PDF</span>
                                    <small>Recommended: 800x400 (Max 2MB) | JPG, PNG, PDF</small>
                                    <input type="file" name="file" id="notice_image" class="hidden-file-input" accept="image/*,.pdf" onchange="updateNoticeFileName(this)">
                                    <div id="notice_file_name" class="file-name-pill"></div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo isset($notice) ? 'Update Notice' : 'Save Notice'; ?></button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- List Section -->
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Notices List</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Image</th>
                                        <th>Description</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($notices)) {
                                        foreach ($notices as $notice) { ?>
                                            <tr>
                                                <td class="mailbox-name"><?php echo $notice['title']; ?></td>
                                                <td>
                                                    <?php if ($notice['image'] != "") { ?>
                                                        <div class="img-view-container" onclick="viewNoticeImage('<?php echo base_url('uploads/notices/' . $notice['image']); ?>', '<?php echo htmlspecialchars($notice['title']); ?>')">
                                                            <img src="<?php echo base_url('uploads/notices/' . $notice['image']); ?>">
                                                            <div class="overlay">
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class="text-muted" style="font-size: 10px;">No Image</span>
                                                    <?php } ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <div class="desc-cell" title="<?php echo htmlspecialchars(strip_tags($notice['description'])); ?>">
                                                        <?php echo strip_tags($notice['description']); ?>
                                                    </div>
                                                </td>
                                                <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($notice['created_at'])); ?></td>
                                                <td>
                                                    <div class="material-switch">
                                                        <input id="notice_<?php echo $notice['id']; ?>" name="notice_status" type="checkbox" class="chk" data-id="<?php echo $notice['id']; ?>" <?php echo ($notice['status'] == 1) ? "checked" : ""; ?> />
                                                        <label for="notice_<?php echo $notice['id']; ?>" class="label-success"></label>
                                                    </div>
                                                </td>
                                                <td class="mailbox-date pull-right">
                                                    <a href="<?php echo base_url(); ?>admin/cms/edit_notice/<?php echo $notice['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="Edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="<?php echo base_url(); ?>admin/cms/delete_notice/<?php echo $notice['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete this notice?')">
                                                        <i class="fa fa-remove text-danger"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.summernote').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'hr']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        $(document).on('change', '.chk', function () {
            var id = $(this).data('id');
            var status = $(this).is(':checked') ? 1 : 0;
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>admin/cms/toggle_notice_status",
                data: {
                    'id': id,
                    'status': status,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                dataType: "json",
                success: function (data) {
                    successMsg("Status updated successfully");
                }
            });
        });
    });

    function updateNoticeFileName(input) {
        var pill = document.getElementById('notice_file_name');
        if (input.files && input.files[0]) {
            pill.innerHTML = '<i class="fa fa-check-circle"></i> ' + input.files[0].name;
            pill.style.display = 'inline-block';
        } else {
            pill.style.display = 'none';
        }
    }

    var currentZoom = 1;
    function viewNoticeImage(url, title) {
        currentZoom = 1;
        $('#modal_image').attr('src', url).css('transform', 'scale(1)');
        $('#modal_title').text(title);
        $('#imagePreviewModal').modal('show');
    }

    function zoomIn() {
        currentZoom += 0.2;
        if (currentZoom > 3) currentZoom = 3;
        $('#modal_image').css('transform', 'scale(' + currentZoom + ')');
    }

    function zoomOut() {
        currentZoom -= 0.2;
        if (currentZoom < 0.5) currentZoom = 0.5;
        $('#modal_image').css('transform', 'scale(' + currentZoom + ')');
    }
</script>

<!-- Image Preview Modal -->
<div id="imagePreviewModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <div class="modal-body">
                <div class="img-wrapper">
                    <img id="modal_image" src="">
                </div>
                <div class="zoom-controls">
                    <button class="zoom-btn" onclick="zoomOut()"><i class="fa fa-minus"></i></button>
                    <button class="zoom-btn" onclick="zoomIn()"><i class="fa fa-plus"></i></button>
                    <button class="zoom-btn" onclick="currentZoom=1; $('#modal_image').css('transform', 'scale(1)')"><i class="fa fa-refresh"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
