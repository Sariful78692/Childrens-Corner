<aside class="main-sidebar" id="alert2">
    <?php if ($this->rbac->hasPrivilege('student', 'can_view')) { ?>
        <form class="navbar-form navbar-left search-form2" role="search" action="<?php echo site_url('admin/admin/search'); ?>" method="POST">
            <?php echo $this->customlib->getCSRF(); ?>
            <div class="input-group ">

                <input type="text" name="search_text" class="form-control search-form" placeholder="<?php echo $this->lang->line('search_by_student_name'); ?>">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" style="padding: 3px 12px !important;border-radius: 0px 30px 30px 0px; background: #fff;" class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
    <?php } ?>
    <section class="sidebar" id="sibe-box">
        <?php $this->load->view('layout/top_sidemenu'); ?>

        <ul class="sidebar-menu verttop">

            <!-- //==================sidebar dynamic======================= -->
            <?php
            $side_list = side_menu_list(1);

            if (!empty($side_list)) {
                foreach ($side_list as $side_list_key => $side_list_value) {

                    $module_permission = access_permission_sidebar_remove_pipe($side_list_value->access_permissions);
                    $module_access     = false;
                    if (!empty($module_permission)) {
                        foreach ($module_permission as $m_permission_key => $m_permission_value) {
                            $cat_permission = access_permission_remove_comma($m_permission_value);

                            if ($this->rbac->hasPrivilege($cat_permission[0], $cat_permission[1])) {
                                $module_access = true;
                                break;
                            }
                        }
                    }
                    if ($module_access) {
                        if ($this->module_lib->hasModule($side_list_value->short_code) && $this->module_lib->hasActive($side_list_value->short_code)) {
                            //print_r($side_list_value);
            ?>

                            <li class="treeview <?php echo activate_main_menu($side_list_value->activate_menu); ?>">

                                <a href="#">
                                    <i class="<?php echo $side_list_value->icon; ?>"></i> <span><?php echo $this->lang->line($side_list_value->lang_key); ?></span> <i class="fa fa-angle-left pull-right"></i>
                                </a>

                                <?php
                                if (!empty($side_list_value->submenus)) {
                                ?>
                                    <ul class="treeview-menu">
                                        <?php
                                        foreach ($side_list_value->submenus as $submenu_key => $submenu_value) {

                                            $sidebar_permission = access_permission_sidebar_remove_pipe($submenu_value->access_permissions);
                                            $sidebar_access     = false;

                                            if (!empty($sidebar_permission)) {
                                                foreach ($sidebar_permission as $sidebar_permission_key => $sidebar_permission_value) {
                                                    $sidebar_cat_permission = access_permission_remove_comma($sidebar_permission_value);

                                                    if ($submenu_value->addon_permission != "") {
                                                        if (
                                                            $this->rbac->hasPrivilege($sidebar_cat_permission[0], $sidebar_cat_permission[1])
                                                            && $this->auth->addonchk($submenu_value->addon_permission, false)
                                                        ) {
                                                            $sidebar_access = true;
                                                            break;
                                                        }
                                                    } else {
                                                        if ($this->rbac->hasPrivilege($sidebar_cat_permission[0], $sidebar_cat_permission[1])) {
                                                            $sidebar_access = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                            }

                                            if ($sidebar_access) {
                                                if (!empty($submenu_value->permission_group_id)) {
                                                    if (!$this->module_lib->hasActive($submenu_value->short_code)) {
                                                        continue;
                                                    }
                                                }

                                        ?>

                                                <li class="<?php echo activate_submenu($submenu_value->activate_controller, explode(',', $submenu_value->activate_methods)); ?>"><a href="<?php echo site_url($submenu_value->url); ?>"><i class="fa fa-angle-double-right"></i><?php echo $this->lang->line($submenu_value->lang_key); ?>
                                                <?php if ($submenu_value->url == 'studentfee/pending_collections' && $this->pending_fees_count > 0) { ?>
                                                    <span class="pull-right-container">
                                                        <small class="label pull-right bg-yellow"><?php echo $this->pending_fees_count; ?></small>
                                                    </span>
                                                <?php } ?>
                                                </a></li>


                                        <?php
                                            }
                                        }

                                        ?>
                                    </ul>

                                <?php

                                }
                                ?>
                            </li>
            <?php
                        }
                    }
                }
            }
            ?>
            <!-- //==================sidebar dynamic======================= -->

            <li class="treeview <?php echo set_Topmenu('CMS'); ?>">
                <a href="#">
                    <i class="fa fa-book"></i> <span>CMS</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php echo set_Submenu('cms/syllabus'); ?>"><a href="<?php echo site_url('admin/cms/syllabus'); ?>"><i class="fa fa-angle-double-right"></i> Syllabus</a></li>
                    <li class="<?php echo set_Submenu('cms/holiday_prospectus'); ?>"><a href="<?php echo site_url('admin/cms/holiday_prospectus'); ?>"><i class="fa fa-angle-double-right"></i> Holiday & Prospectus</a></li>
                    <li class="<?php echo set_Submenu('admin/cms/notice_board'); ?>"><a href="<?php echo site_url('admin/cms/notice_board'); ?>"><i class="fa fa-angle-double-right"></i> Notice Board</a></li>
                    <li class="<?php echo set_Submenu('admin/cms/gallery'); ?>"><a href="<?php echo site_url('admin/cms/gallery'); ?>"><i class="fa fa-angle-double-right"></i> Gallery & Media</a></li>
                    <li class=""><a href="<?php echo site_url('admin/cms/examination_routine'); ?>"><i class="fa fa-angle-double-right"></i> Examination</a></li>
                </ul>
            </li>

        </ul>
    </section>
</aside>
