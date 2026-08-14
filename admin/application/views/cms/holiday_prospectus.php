<style>
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

    .preview-box {
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 8px;
        background: #fff;
        margin-bottom: 15px;
        min-height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .preview-box:hover {
        border-color: #4361ee;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .preview-box a {
        display: block;
        width: 100%;
        text-align: center;
        cursor: pointer;
    }

    .preview-box img {
        max-width: 100%;
        max-height: 200px;
        border-radius: 4px;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-calendar"></i> Holiday & Prospectus</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <?= $this->session->flashdata('msg'); ?>
            </div>

            <!-- Holiday List Section -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Holiday</h3>
                    </div>
                    <form action="<?= site_url('admin/cms/holiday_prospectus') ?>" method="post" enctype="multipart/form-data">
                        <div class="box-body">
                            <?= $this->customlib->getCSRF(); ?>
                            <input type="hidden" name="file_type" value="holiday_list">
                            
                            <label>Current Holiday</label>
                            <div class="preview-box">
                                <?php if (isset($cms_files['holiday_list'])) { 
                                    $ext = pathinfo($cms_files['holiday_list'], PATHINFO_EXTENSION);
                                    $file_url = base_url('uploads/cms/' . $cms_files['holiday_list']);
                                    if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])) { ?>
                                        <a href="<?= $file_url ?>" target="_blank">
                                            <img src="<?= $file_url ?>" alt="Holiday List">
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?= $file_url ?>" target="_blank" class="text-center">
                                            <i class="fa fa-file-pdf-o" style="font-size: 48px; color: #ef4444;"></i>
                                            <p style="margin-top:10px; color: #333;">PDF Document</p>
                                            <span class="btn btn-xs btn-default">View Document</span>
                                        </a>
                                    <?php } ?>
                                <?php } else { ?>
                                    <span class="text-muted">No document uploaded</span>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label>Upload New</label>
                                <div class="notice-upload-zone" onclick="document.getElementById('holiday_input').click();">
                                    <i class="fa fa-cloud-upload"></i>
                                    <span>Select Image or PDF</span>
                                    <small>JPG, PNG, PDF (Max 5MB)</small>
                                    <input type="file" name="holiday_file" id="holiday_input" class="hidden-file-input" accept=".pdf,.jpg,.jpeg,.png" required onchange="updateFileName(this, 'hpill')">
                                    <div id="hpill" class="file-name-pill"></div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right">Update Holiday</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Prospectus Section -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">School Prospectus</h3>
                    </div>
                    <form action="<?= site_url('admin/cms/holiday_prospectus') ?>" method="post" enctype="multipart/form-data">
                        <div class="box-body">
                            <?= $this->customlib->getCSRF(); ?>
                            <input type="hidden" name="file_type" value="prospectus">
                            
                            <label>Current Prospectus</label>
                            <div class="preview-box">
                                <?php if (isset($cms_files['prospectus'])) { 
                                    $ext = pathinfo($cms_files['prospectus'], PATHINFO_EXTENSION);
                                    $file_url = base_url('uploads/cms/' . $cms_files['prospectus']);
                                    if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])) { ?>
                                        <a href="<?= $file_url ?>" target="_blank">
                                            <img src="<?= $file_url ?>" alt="Prospectus">
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?= $file_url ?>" target="_blank" class="text-center">
                                            <i class="fa fa-file-pdf-o" style="font-size: 48px; color: #ef4444;"></i>
                                            <p style="margin-top:10px; color: #333;">PDF Document</p>
                                            <span class="btn btn-xs btn-default">View Document</span>
                                        </a>
                                    <?php } ?>
                                <?php } else { ?>
                                    <span class="text-muted">No document uploaded</span>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label>Upload New</label>
                                <div class="notice-upload-zone" onclick="document.getElementById('prospectus_input').click();">
                                    <i class="fa fa-file-pdf-o"></i>
                                    <span>Select PDF Only</span>
                                    <small>PDF (Max 5MB)</small>
                                    <input type="file" name="prospectus_file" id="prospectus_input" class="hidden-file-input" accept=".pdf" required onchange="updateFileName(this, 'ppill')">
                                    <div id="ppill" class="file-name-pill"></div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right">Update Prospectus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function updateFileName(input, pillId) {
    var pill = document.getElementById(pillId);
    if (input.files && input.files[0]) {
        pill.innerHTML = '<i class="fa fa-check-circle"></i> ' + input.files[0].name;
        pill.style.display = 'inline-block';
    } else {
        pill.style.display = 'none';
    }
}
</script>
