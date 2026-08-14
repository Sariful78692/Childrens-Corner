<style>
    .gallery-nav-tabs {
        border-bottom: none;
        margin-bottom: 30px;
        display: flex;
        gap: 15px;
    }
    .gallery-nav-tabs li a {
        border: none !important;
        background: #f8fafc;
        color: #64748b;
        padding: 12px 25px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .gallery-nav-tabs li.active a {
        background: #4361ee !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
    }
    .gallery-card {
        background: #fff;
        border-radius: 20px;
        border: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    .gallery-card:hover {
        transform: translateY(-5px);
    }
    .photo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }
    .photo-item {
        position: relative;
        aspect-ratio: 1/1;
        border-radius: 15px;
        overflow: hidden;
        group: hover;
    }
    .photo-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .photo-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .photo-item:hover .photo-overlay {
        opacity: 1;
    }
    .upload-box {
        border: 2px dashed #e2e8f0;
        border-radius: 15px;
        padding: 40px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .upload-box:hover {
        border-color: #4361ee;
        background: #f8fafc;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-picture-o"></i> Gallery & Media</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <?= $this->session->flashdata('msg'); ?>
                
                <ul class="nav nav-tabs gallery-nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#photos"><i class="fa fa-camera"></i> Photos Gallery</a></li>
                    <li><a data-toggle="tab" href="#categories"><i class="fa fa-folder-open"></i> Album Categories</a></li>
                </ul>

                <div class="tab-content">
                    <!-- PHOTOS TAB -->
                    <div id="photos" class="tab-pane fade in active">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="box box-primary" style="border-radius: 20px; overflow: hidden; border: none; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
                                    <div class="box-header with-border" style="background: #f8fafc; padding: 20px;">
                                        <h3 class="box-title" style="font-weight: 700; color: #1e293b;">Upload New Photos</h3>
                                    </div>
                                    <form action="<?= site_url('admin/cms/add_photo') ?>" method="post" enctype="multipart/form-data">
                                        <?= $this->customlib->getCSRF(); ?>
                                        <div class="box-body" style="padding: 25px;">
                                            <div class="form-group">
                                                <div class="d-flex justify-content-between align-items-center" style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                                    <label style="color: #64748b; font-weight: 600; margin: 0;">Select Album Category</label>
                                                    <button type="button" class="btn btn-link btn-xs" onclick="toggleNewCategory()" style="padding: 0; color: #576885; font-weight: 600; text-decoration: none;">+ New Category</button>
                                                </div>
                                                
                                                <div id="category_select_wrapper">
                                                    <select name="category_id" id="category_id" class="form-control select2" style="border-radius: 10px; height: 45px; width: 100%;" required>
                                                        <option value="">Choose Category...</option>
                                                        <?php foreach ($categories as $cat) { ?>
                                                            <option value="<?= $cat['id'] ?>"><?= $cat['category_name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <div id="new_category_input_wrapper" style="display: none;">
                                                    <div class="input-group">
                                                        <input type="text" name="new_category_name" id="new_category_name" class="form-control" style="border-radius: 10px 0 0 10px; height: 45px;" placeholder="Type new category name...">
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-default" type="button" onclick="toggleNewCategory()" style="height: 45px; border-radius: 0 10px 10px 0;">
                                                                <i class="fa fa-times text-danger"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                    <small class="text-muted">This will create a new album automatically.</small>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label style="color: #64748b; font-weight: 600;">Photo Title</label>
                                                <input type="text" name="title" class="form-control" style="border-radius: 10px; height: 45px;" placeholder="e.g. Annual Day 2026" required>
                                            </div>
                                            <div class="form-group">
                                                <label style="color: #64748b; font-weight: 600;">Event Date</label>
                                                <input type="date" name="photo_date" class="form-control" style="border-radius: 10px; height: 45px;" value="<?= date('Y-m-d') ?>">
                                            </div>
                                            <div class="form-group">
                                                <label style="color: #64748b; font-weight: 600;">Description</label>
                                                <textarea name="description" class="form-control" style="border-radius: 10px;" rows="3" placeholder="Brief description of the event..."></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label style="color: #64748b; font-weight: 600;">Choose Photos</label>
                                                <div class="upload-box" onclick="document.getElementById('photo_input').click();">
                                                    <i class="fa fa-cloud-upload" style="font-size: 40px; color: #576885; margin-bottom: 10px;"></i>
                                                    <p style="margin: 0; color: #1e293b; font-weight: 600;">Click to select files</p>
                                                    <small class="text-muted">JPG or PNG only (Multiple supported)</small>
                                                    <input type="file" name="photos[]" id="photo_input" multiple accept="image/jpeg,image/png" style="display: none;" onchange="updatePhotoPreview(this)">
                                                </div>
                                                <div id="photo_preview_count" style="margin-top: 10px; font-weight: 600; color: #576885;"></div>
                                            </div>
                                        </div>
                                        <div class="box-footer" style="background: #fff; padding: 20px; border-top: 1px solid #f1f5f9;">
                                            <button type="submit" class="btn btn-primary btn-block" style="border-radius: 12px; height: 45px; background: #4361ee; border: none; font-weight: 600;">
                                                <i class="fa fa-upload"></i> Start Uploading
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="photo-grid">
                                    <?php foreach ($photos as $photo) { ?>
                                        <div class="photo-item gallery-card shadow-sm">
                                            <img src="<?= base_url('uploads/gallery/' . $photo['image']) ?>" alt="<?= $photo['title'] ?>">
                                            <div class="photo-overlay">
                                                <div class="text-center">
                                                    <p style="color: #fff; margin-bottom: 10px; font-weight: 600;"><?= $photo['title'] ?></p>
                                                    <a href="<?= site_url('admin/cms/delete_photo/' . $photo['id']) ?>" class="btn btn-danger btn-xs" onclick="return confirm('Delete this photo?')">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </a>
                                                </div>
                                            </div>
                                            <div style="position: absolute; bottom: 10px; left: 10px; z-index: 1;">
                                                <span class="badge bg-blue" style="font-size: 10px;"><?= $photo['category_name'] ?></span>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CATEGORIES TAB -->
                    <div id="categories" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="box box-primary" style="border-radius: 20px; overflow: hidden; border: none; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
                                    <div class="box-header with-border" style="background: #f8fafc; padding: 20px;">
                                        <h3 class="box-title" style="font-weight: 700; color: #1e293b;">Create New Category</h3>
                                    </div>
                                    <form action="<?= site_url('admin/cms/add_category') ?>" method="post" enctype="multipart/form-data">
                                        <?= $this->customlib->getCSRF(); ?>
                                        <div class="box-body" style="padding: 25px;">
                                            <input type="hidden" name="category_id" id="edit_category_id">
                                            <div class="form-group">
                                                <label style="color: #64748b; font-weight: 600;">Category Name</label>
                                                <input type="text" name="category_name" id="edit_category_name" class="form-control" style="border-radius: 10px; height: 45px;" placeholder="e.g. Annual Sports" required>
                                            </div>
                                            <div class="form-group">
                                                <label style="color: #64748b; font-weight: 600;">Album Cover Image (Optional)</label>
                                                <input type="file" name="category_image" class="form-control" accept=".png, .jpg, .jpeg" style="border-radius: 10px; height: 45px;">
                                                <small class="text-muted">Leave empty to keep current cover.</small>
                                            </div>
                                        </div>
                                        <div class="box-footer" style="background: #fff; padding: 20px; border-top: 1px solid #f1f5f9;">
                                            <button type="submit" id="cat_submit_btn" class="btn btn-primary btn-block" style="border-radius: 12px; height: 45px; background: #4361ee; border: none; font-weight: 600;">
                                                Create Album
                                            </button>
                                            <button type="button" id="cat_cancel_btn" onclick="cancelEdit()" class="btn btn-default btn-block" style="display: none; border-radius: 12px; height: 45px; font-weight: 600;">
                                                Cancel Edit
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="box box-primary" style="border-radius: 20px; overflow: hidden; border: none; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
                                    <div class="box-body" style="padding: 0;">
                                        <table class="table" style="margin: 0;">
                                            <thead style="background: #f8fafc;">
                                                <tr>
                                                    <th style="padding: 15px 25px; border: none; width: 80px;">Cover</th>
                                                    <th style="padding: 15px 25px; border: none;">Category Name</th>
                                                    <th style="padding: 15px 25px; border: none;">Created At</th>
                                                    <th class="text-right" style="padding: 15px 25px; border: none;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($categories as $cat) { ?>
                                                    <tr>
                                                        <td style="padding: 15px 25px; border-top: 1px solid #f1f5f9;">
                                                            <?php if (!empty($cat['category_image'])) { ?>
                                                                <img src="<?= base_url('uploads/gallery/categories/' . $cat['category_image']) ?>" style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover;">
                                                            <?php } else { ?>
                                                                <div style="width: 50px; height: 50px; border-radius: 8px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #cbd5e1;">
                                                                    <i class="fa fa-image"></i>
                                                                </div>
                                                            <?php } ?>
                                                        </td>
                                                        <td style="padding: 15px 25px; border-top: 1px solid #f1f5f9; font-weight: 600; color: #1e293b;"><?= $cat['category_name'] ?></td>
                                                        <td style="padding: 15px 25px; border-top: 1px solid #f1f5f9; color: #64748b;"><?= date('d M Y', strtotime($cat['created_at'])) ?></td>
                                                        <td class="text-right" style="padding: 15px 25px; border-top: 1px solid #f1f5f9;">
                                                            <button type="button" class="btn btn-default btn-xs" onclick="editCategory('<?= $cat['id'] ?>', '<?= htmlspecialchars($cat['category_name']) ?>')" style="border-radius: 8px; margin-right: 5px;">
                                                                <i class="fa fa-pencil text-info"></i>
                                                            </button>
                                                            <a href="<?= site_url('admin/cms/delete_category/' . $cat['id']) ?>" class="btn btn-default btn-xs" onclick="return confirm('Deleting a category will delete all photos in it. Continue?')" style="border-radius: 8px;">
                                                                <i class="fa fa-trash text-danger"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function editCategory(id, name) {
        document.getElementById('edit_category_id').value = id;
        document.getElementById('edit_category_name').value = name;
        document.querySelector('#categories .box-title').innerHTML = 'Edit Category';
        document.getElementById('cat_submit_btn').innerHTML = 'Update Album';
        document.getElementById('cat_cancel_btn').style.display = 'block';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function cancelEdit() {
        document.getElementById('edit_category_id').value = '';
        document.getElementById('edit_category_name').value = '';
        document.querySelector('#categories .box-title').innerHTML = 'Create New Category';
        document.getElementById('cat_submit_btn').innerHTML = 'Create Album';
        document.getElementById('cat_cancel_btn').style.display = 'none';
    }

    function updatePhotoPreview(input) {
        const count = input.files.length;
        document.getElementById('photo_preview_count').innerHTML = count > 0 ? `<i class="fa fa-check-circle"></i> ${count} photos selected` : '';
    }

    function toggleNewCategory() {
        const selectWrapper = document.getElementById('category_select_wrapper');
        const inputWrapper = document.getElementById('new_category_input_wrapper');
        const selectField = document.getElementById('category_id');
        const inputField = document.getElementById('new_category_name');

        if (inputWrapper.style.display === 'none') {
            inputWrapper.style.display = 'block';
            selectWrapper.style.display = 'none';
            selectField.required = false;
            inputField.required = true;
            inputField.focus();
        } else {
            inputWrapper.style.display = 'none';
            selectWrapper.style.display = 'block';
            selectField.required = true;
            inputField.required = false;
            inputField.value = '';
        }
    }

    $(document).ready(function() {
        if ($.fn.select2) {
            $('.select2').select2();
        }
    });
</script>
