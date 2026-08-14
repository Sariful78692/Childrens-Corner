<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cms extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Customlib');
        $this->load->library('media_storage');
        $this->config->load('app-config');
        $this->load->library('form_validation');
        $this->load->library("datatables");
    }

    public function syllabus()
    {
         $this->session->set_userdata('top_menu', 'CMS');
    $this->session->set_userdata('sub_menu', 'cms/syllabus');

    $data['title'] = 'Syllabus';

    $this->load->model('class_model');
    $data['classlist'] = $this->class_model->get();

    $this->load->model('cms_model');
    $data['syllabuslist'] = $this->cms_model->getSyllabusList();


        // Form Validation Rules
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('syllabus_title', 'Syllabus Title', 'trim|required|xss_clean');
        
        if ($this->form_validation->run()) {
            $syllabus_id = $this->input->post('syllabus_id');
            $data = array(
                'class_name' => $this->input->post('class_id'),
                'syllabus_title' => $this->input->post('syllabus_title'),
                'status' => $this->input->post('status'),
            );

            if (!empty($syllabus_id)) {
                $data['id'] = $syllabus_id;
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
            }

            if (isset($_FILES["documents"]) && !empty($_FILES['documents']['name'])) {
                $fileInfo = pathinfo($_FILES["documents"]["name"]);
                $extension = strtolower($fileInfo['extension']);

                if ($extension !== 'pdf') {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Only PDF files are allowed!</div>');
                    redirect('admin/cms/syllabus');
                }

                $upload_path = FCPATH . 'uploads/syllabus/';
                if (!is_dir($upload_path) && !mkdir($upload_path, 0755, true)) {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Failed to create upload directory. Check server permissions.</div>');
                    redirect('admin/cms/syllabus');
                }
                $document = time() . '.' . $fileInfo['extension'];
                if (!move_uploaded_file($_FILES["documents"]["tmp_name"], $upload_path . $document)) {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Failed to upload file. Check server permissions on uploads/syllabus/ directory.</div>');
                    redirect('admin/cms/syllabus');
                }
                $data['documents'] = $document;
            }

            $this->cms_model->addSyllabus($data);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Syllabus saved successfully!</div>');
            redirect('admin/cms/syllabus');
        }

        $this->load->view('layout/header', $data);
        $this->load->view('cms/syllabus', $data);
        $this->load->view('layout/footer', $data);
    }

    public function edit_syllabus($id)
    {
        $this->session->set_userdata('top_menu', 'CMS');
        $this->session->set_userdata('sub_menu', 'cms/syllabus');

        $data['title'] = 'Edit Syllabus';

        $this->load->model('class_model');
        $data['classlist'] = $this->class_model->get();

        $this->load->model('cms_model');
        $data['syllabuslist'] = $this->cms_model->getSyllabusList();
        $data['syllabus'] = $this->cms_model->getSyllabus($id);

        $this->load->view('layout/header', $data);
        $this->load->view('cms/syllabus', $data);
        $this->load->view('layout/footer', $data);
    }

    public function download_syllabus($id)
    {
        $this->load->model('cms_model');
        $syllabus = $this->cms_model->getSyllabus($id);

        if (!$syllabus || empty($syllabus['documents'])) {
            show_404();
        }

        $file_path = FCPATH . 'uploads/syllabus/' . basename($syllabus['documents']);

        if (!is_file($file_path)) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">File not found on server. Please re-upload the syllabus.</div>');
            redirect('admin/cms/syllabus');
        }

        $download_name = preg_replace('/[^A-Za-z0-9._-]+/', '-', pathinfo($syllabus['syllabus_title'], PATHINFO_FILENAME));
        $download_name = trim($download_name, '-_.') ?: pathinfo($syllabus['documents'], PATHINFO_FILENAME);
        $download_name .= '.pdf';

        while (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $download_name . '"');
        header('Content-Length: ' . filesize($file_path));
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        readfile($file_path);
        exit;
    }

    public function delete_syllabus($id)
    {
        $this->load->model('cms_model');
        $this->cms_model->deleteSyllabus($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Syllabus deleted successfully!</div>');
        redirect('admin/cms/syllabus');
    }

    public function holiday_prospectus()
    {
        $this->session->set_userdata('top_menu', 'CMS');
        $this->session->set_userdata('sub_menu', 'cms/holiday_prospectus');
        $data['title'] = 'Holiday & Prospectus';
        $this->load->model('cms_model');
        $this->load->helper('form');

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $file_type = $this->input->post('file_type');
            $field_name = ($file_type == 'holiday_list') ? 'holiday_file' : 'prospectus_file';
            
            if (isset($_FILES[$field_name]) && !empty($_FILES[$field_name]['name'])) {
                $config['upload_path']   = FCPATH . 'uploads/cms/';
                if ($file_type == 'prospectus') {
                    $config['allowed_types'] = 'pdf';
                } else {
                    $config['allowed_types'] = 'pdf|jpg|jpeg|png';
                }
                $config['file_name']     = $file_type;
                $config['overwrite']     = TRUE;
                $config['max_size']      = '5120'; // 5MB in KB

                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, true);
                }

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if (!$this->upload->do_upload($field_name)) {
                    $error = $this->upload->display_errors('', '');
                    $error = str_replace('in your PHP configuration file.', '', $error);
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">' . $error . '</div>');
                    redirect('admin/cms/holiday_prospectus');
                } else {
                    $upload_data = $this->upload->data();
                    $filename = $upload_data['file_name'];

                    // Remove other extensions of the same file type
                    $allowed_extensions = array('pdf', 'jpg', 'jpeg', 'png');
                    foreach ($allowed_extensions as $ext) {
                        if ($ext != ltrim($upload_data['file_ext'], '.')) {
                            $old_file = FCPATH . "uploads/cms/" . $file_type . "." . $ext;
                            if (file_exists($old_file)) {
                                @unlink($old_file);
                            }
                        }
                    }

                    $this->cms_model->updateCmsFile($file_type, $filename);
                    $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">File updated successfully!</div>');
                    redirect('admin/cms/holiday_prospectus');
                }
            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Please select a file to upload.</div>');
                redirect('admin/cms/holiday_prospectus');
            }
        }

        $data['cms_files'] = $this->cms_model->getCmsFiles();
        $this->load->view('layout/header', $data);
        $this->load->view('cms/holiday_prospectus', $data);
        $this->load->view('layout/footer', $data);
    }

    public function notice_board()
    {
        $this->session->set_userdata('top_menu', 'CMS');
        $this->session->set_userdata('sub_menu', 'admin/cms/notice_board');
        $data['title'] = 'Notice Board';
        $this->load->model('notice_model');

        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $data['notices'] = $this->notice_model->get();
            $this->load->view('layout/header', $data);
            $this->load->view('cms/notice_board', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $notice_id = $this->input->post('notice_id');
            $data_insert = array(
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'status' => 1
            );

            if ($notice_id) {
                $data_insert['id'] = $notice_id;
            }

            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $config['upload_path']   = FCPATH . 'uploads/notices/';
                $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                $config['file_name']     = time() . '_' . $_FILES['file']['name'];

                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, true);
                }

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')) {
                    $upload_data = $this->upload->data();
                    $data_insert['image'] = $upload_data['file_name'];
                } else {
                    $error = $this->upload->display_errors('', '');
                    if (strpos($error, 'exceeds the maximum allowed size') !== false) {
                        $error = 'The uploaded file exceeds the maximum allowed size';
                        $this->session->set_flashdata('msg', '<div class="alert alert-danger">' . $error . '</div>');
                        redirect('admin/cms/notice_board');
                    }
                }
            }

            if ($notice_id) {
                $this->notice_model->add($data_insert);
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Notice updated successfully!</div>');
            } else {
                $this->notice_model->add($data_insert);
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Notice saved successfully!</div>');
            }
            redirect('admin/cms/notice_board');
        }
    }

    public function edit_notice($id)
    {
        $this->session->set_userdata('top_menu', 'CMS');
        $this->session->set_userdata('sub_menu', 'admin/cms/notice_board');
        $data['title'] = 'Edit Notice';
        $this->load->model('notice_model');
        $data['notices'] = $this->notice_model->get();
        $data['notice'] = $this->notice_model->get($id);

        $this->load->view('layout/header', $data);
        $this->load->view('cms/notice_board', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete_notice($id)
    {
        $this->load->model('notice_model');
        $notice = $this->notice_model->get($id);
        if ($notice['image'] != '') {
            @unlink(FCPATH . 'uploads/notices/' . $notice['image']);
        }
        $this->notice_model->remove($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success">Notice deleted successfully</div>');
        redirect('admin/cms/notice_board');
    }

    public function toggle_notice_status()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $this->load->model('notice_model');
        $this->notice_model->changeStatus($id, $status);
        echo json_encode(array('status' => 'success'));
    }

    /* GALLERY & MEDIA SECTION */
    public function gallery()
    {
        $this->session->set_userdata('top_menu', 'CMS');
        $this->session->set_userdata('sub_menu', 'admin/cms/gallery');
        $data['title'] = 'Gallery & Media Management';
        
        $this->load->model('Gallery_model');
        $data['categories'] = $this->Gallery_model->getCategories();
        $data['photos'] = $this->Gallery_model->getPhotos();

        $this->load->view('layout/header', $data);
        $this->load->view('cms/gallery', $data);
        $this->load->view('layout/footer', $data);
    }

    public function add_category()
    {
        $this->load->model('Gallery_model');
        $data = array(
            'category_name' => $this->input->post('category_name'),
            'status' => 1
        );

        if (isset($_FILES["category_image"]) && !empty($_FILES['category_image']['name'])) {
            $fileInfo = pathinfo($_FILES["category_image"]["name"]);
            $extension = strtolower($fileInfo['extension']);
            $allowed = array('jpg', 'jpeg', 'png');

            if (!in_array($extension, $allowed)) {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Only JPG, JPEG, and PNG files are allowed for category images!</div>');
                redirect('admin/cms/gallery');
            }

            $config['upload_path']   = FCPATH . 'uploads/gallery/categories/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['file_name']     = time() . '_' . $_FILES['category_image']['name'];

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('category_image')) {
                $uploadData = $this->upload->data();
                $data['category_image'] = $uploadData['file_name'];
            }
        }

        if ($this->input->post('category_id')) {
            $data['id'] = $this->input->post('category_id');
        }
        $this->Gallery_model->addCategory($data);
        $this->session->set_flashdata('msg', '<div class="alert alert-success">Category saved successfully</div>');
        redirect('admin/cms/gallery');
    }

    public function delete_category($id)
    {
        $this->load->model('Gallery_model');
        $this->Gallery_model->deleteCategory($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success">Category deleted successfully</div>');
        redirect('admin/cms/gallery');
    }

    public function add_photo()
    {
        $this->load->model('Gallery_model');
        $category_id = $this->input->post('category_id');
        $new_category_name = $this->input->post('new_category_name');
        $title = $this->input->post('title');
        $photo_date = $this->input->post('photo_date');
        $description = $this->input->post('description');

        // Handle Quick Add Category
        if (!empty($new_category_name)) {
            $cat_data = array(
                'category_name' => $new_category_name,
                'status' => 1
            );
            $this->Gallery_model->addCategory($cat_data);
            $category_id = $this->db->insert_id();
        }

        if (isset($_FILES['photos']) && !empty($_FILES['photos']['name'][0])) {
            $filesCount = count($_FILES['photos']['name']);
            for ($i = 0; $i < $filesCount; $i++) {
                $_FILES['file']['name']     = $_FILES['photos']['name'][$i];
                $_FILES['file']['type']     = $_FILES['photos']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['photos']['tmp_name'][$i];
                $_FILES['file']['error']     = $_FILES['photos']['error'][$i];
                $_FILES['file']['size']     = $_FILES['photos']['size'][$i];

                $config['upload_path']   = FCPATH . 'uploads/gallery/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['file_name']     = time() . '_' . $_FILES['file']['name'];

                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, true);
                }

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')) {
                    $uploadData = $this->upload->data();
                    $data = array(
                        'category_id' => $category_id,
                        'title' => $title,
                        'image' => $uploadData['file_name'],
                        'photo_date' => $photo_date,
                        'description' => $description,
                        'status' => 1
                    );
                    $this->Gallery_model->addPhoto($data);
                }
            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Photos uploaded successfully</div>');
        } else {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Please select photos to upload</div>');
        }
        redirect('admin/cms/gallery');
    }

    public function delete_photo($id)
    {
        $this->load->model('Gallery_model');
        $photo = $this->Gallery_model->getPhoto($id);
        if ($photo) {
            @unlink(FCPATH . 'uploads/gallery/' . $photo['image']);
            $this->Gallery_model->deletePhoto($id);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Photo deleted successfully</div>');
        }
        redirect('admin/cms/gallery');
    }

    public function examination_routine()
    {
        $this->session->set_userdata('top_menu', 'CMS');
        $this->session->set_userdata('sub_menu', 'cms/examination_routine');

        $data['title'] = 'Examination Routine';

        $this->load->model('cms_model');
        $data['routine_list'] = $this->cms_model->getExaminationRoutineList();
        $data['rule_list'] = $this->cms_model->getExaminationRuleList();
        $data['routine'] = null;
        $data['rule'] = $this->session->flashdata('rule_edit');
        $data['rule_msg'] = $this->session->flashdata('rule_msg');

        $routine_id = $this->input->post('routine_id');
        if (!empty($routine_id)) {
            $data['routine'] = $this->cms_model->getExaminationRoutine($routine_id);
        }

        $this->form_validation->set_rules('routine_class', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('routine_title', 'Title', 'trim|required|xss_clean');

        if ($this->input->method() === 'post' && $this->form_validation->run() == true) {
            $existing_routine = $data['routine'];
            $routine_status = $this->input->post('routine_status');

            $routine_data = array(
                'class_name' => $this->input->post('routine_class'),
                'routine_title' => $this->input->post('routine_title'),
                'status' => !empty($routine_status) ? (int) $routine_status : 0,
            );

            if (!empty($routine_id)) {
                $routine_data['id'] = $routine_id;
            } else {
                $routine_data['created_at'] = date('Y-m-d H:i:s');
                if ($routine_status === null || $routine_status === '') {
                    $routine_data['status'] = 1;
                }
            }

            $upload_path = FCPATH . 'uploads/examination_routine/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            if (isset($_FILES['routine_file']) && !empty($_FILES['routine_file']['name'])) {
                $file_info = pathinfo($_FILES['routine_file']['name']);
                $extension = strtolower($file_info['extension'] ?? '');

                if ($extension !== 'pdf') {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Only PDF files are allowed!</div>');
                    redirect('admin/cms/examination_routine');
                }

                $routine_file = time() . '_' . mt_rand(1000, 9999) . '.pdf';
                move_uploaded_file($_FILES['routine_file']['tmp_name'], $upload_path . $routine_file);
                $routine_data['routine_file'] = $routine_file;

                if (!empty($existing_routine) && !empty($existing_routine['routine_file'])) {
                    $old_file = $upload_path . $existing_routine['routine_file'];
                    if (is_file($old_file)) {
                        unlink($old_file);
                    }
                }
            } elseif (empty($routine_id)) {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Please upload a PDF file.</div>');
                redirect('admin/cms/examination_routine');
            } elseif (!empty($existing_routine) && !empty($existing_routine['routine_file'])) {
                $routine_data['routine_file'] = $existing_routine['routine_file'];
            }

            $this->cms_model->addExaminationRoutine($routine_data);

            $message = !empty($routine_id) ? 'Examination Routine updated successfully!' : 'Examination Routine saved successfully!';
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $message . '</div>');
            redirect('admin/cms/examination_routine');
        } elseif ($this->input->method() === 'post') {
            $data['error_message'] = validation_errors();
        }

        $this->load->view('layout/header', $data);
        $this->load->view('cms/examination_routine', $data);
        $this->load->view('layout/footer', $data);
    }

    public function edit_examination_routine($id)
    {
        $this->session->set_userdata('top_menu', 'CMS');
        $this->session->set_userdata('sub_menu', 'cms/examination_routine');

        $data['title'] = 'Edit Examination Routine';

        $this->load->model('cms_model');
        $data['routine_list'] = $this->cms_model->getExaminationRoutineList();
        $data['routine'] = $this->cms_model->getExaminationRoutine($id);

        $this->load->view('layout/header', $data);
        $this->load->view('cms/examination_routine', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete_examination_routine($id)
    {
        $this->load->model('cms_model');
        $routine = $this->cms_model->getExaminationRoutine($id);

        if (!empty($routine) && !empty($routine['routine_file'])) {
            $file_path = FCPATH . 'uploads/examination_routine/' . $routine['routine_file'];
            if (is_file($file_path)) {
                unlink($file_path);
            }
        }

        $this->cms_model->deleteExaminationRoutine($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Examination Routine deleted successfully!</div>');
        redirect('admin/cms/examination_routine');
    }

    public function save_examination_rule()
    {
        $this->session->set_userdata('top_menu', 'CMS');
        $this->session->set_userdata('sub_menu', 'cms/examination_routine');

        $this->form_validation->set_rules('rule_content', 'Rule Content', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('rule_msg', '<div class="alert alert-danger text-left">' . validation_errors() . '</div>');
            redirect('admin/cms/examination_routine');
        }

        $this->load->model('cms_model');
        $rule_id = $this->input->post('rule_id');

        $data = array(
            'rule_content' => $this->input->post('rule_content', false),
        );

        if (!empty($rule_id)) {
            $data['id'] = $rule_id;
            $data['updated_at'] = date('Y-m-d H:i:s');
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        $this->cms_model->addExaminationRule($data);
        $message = !empty($rule_id) ? 'Rules updated successfully!' : 'Rules added successfully!';
        $this->session->set_flashdata('rule_msg', '<div class="alert alert-success text-left">' . $message . '</div>');
        redirect('admin/cms/examination_routine');
    }

    public function edit_examination_rule($id)
    {
        $this->session->set_userdata('top_menu', 'CMS');
        $this->session->set_userdata('sub_menu', 'cms/examination_routine');

        $this->load->model('cms_model');
        $rule = $this->cms_model->getExaminationRule($id);
        if (empty($rule)) {
            show_404();
        }

        $this->session->set_flashdata('rule_edit', $rule);
        redirect('admin/cms/examination_routine');
    }

    public function delete_examination_rule($id)
    {
        $this->load->model('cms_model');
        $this->cms_model->deleteExaminationRule($id);
        $this->session->set_flashdata('rule_msg', '<div class="alert alert-success text-left">Rules deleted successfully!</div>');
        redirect('admin/cms/examination_routine');
    }
}
