 <style type="text/css">
     @media print {
         .page-break {
             display: block;
             page-break-before: always;
         }

     }

     * {
         margin: 0;
         padding: 0;
     }

     /*body{ font-family: 'arial'; margin:0; padding: 0;font-size: 12px; color: #000;}*/
     .tc-container {
         width: 100%;
         position: relative;
         text-align: center;
     }

     .tcmybg {
         background: top center;
         background-size: contain;
         position: absolute;
         left: 0;
         bottom: 10px;
         width: 200px;
         height: 200px;
         margin-left: auto;
         margin-right: auto;
         right: 0;
     }

     /*begin students id card*/
     .studentmain {
         background: #efefef;
         width: 100%;
         margin-bottom: 30px;
     }

     .studenttop img {
         width: 30px;
         vertical-align: top;
     }

     .studenttop {
         background: <?php echo $idcard->header_color; ?>;
         padding: 2px;
         color: #fff;
         overflow: hidden;
         position: relative;
         z-index: 1;
     }

     .sttext1 {
         font-size: 24px;
         font-weight: bold;
         line-height: 30px;
     }

     .stgray {
         background: #efefef;
         padding-top: 5px;
         padding-bottom: 10px;
     }

     .staddress {
         margin-bottom: 0;
         padding-top: 2px;
     }

     .stdivider {
         border-bottom: 2px solid #000;
         margin-top: 5px;
         margin-bottom: 5px;
     }

     .stlist {
         padding: 0;
         margin: 0;
         list-style: none;
     }

     .stlist li {
         text-align: left;
         display: inline-block;
         width: 100%;
         padding: 0px 5px;
     }

     .stlist li span {
         width: 65%;
         float: right;
     }

     .stimg {
         width: 80px;
         height: auto;
     }

     .stimg img {
         width: 100%;
         height: auto;
         border-radius: 2px;
         display: block;
     }

     .img-circles {
         border-radius: 8px !important;
     }

     .center-block {
         display: block;
         margin-right: auto;
         margin-left: auto;
     }

     .staround {
         padding: 3px 10px 3px 0;
         position: relative;
         overflow: hidden;
     }

     .staround2 {
         position: relative;
         z-index: 9;
     }

     .stbottom {
         background: #453278;
         height: 20px;
         width: 100%;
         clear: both;
         margin-bottom: 5px;
     }

     .principal {
         margin-top: -40px;
         margin-right: 10px;
         float: right;
     }

     .stred {
         color: #000;
     }

     .spanlr {
         padding-left: 5px;
         padding-right: 5px;
     }

     .cardleft {
         width: 20%;
         float: left;
     }

     .cardright {
         width: 77%;
         float: right;
     }

     .signature {
         border: 1px solid #ddd;
         display: block;
         text-align: center;
         padding: 5px 20px;
         margin-top: 10px;
     }

     .vertlist {
         padding: 0;
         margin: 0;
         list-style: none;
     }

     .vertlist li {
         text-align: left;
         display: inline-block;
         width: 100%;
         padding-bottom: 5px;
         color: #000;
     }

     .vertlist li span {
         width: 65%;
         float: right;
     }

     .barcodeimg {
         display: block;
         margin-top: 5px;
         text-align: left;
     }
 </style>
 <?php
    if ($idcard->enable_vertical_card) {
    ?>

     <table cellpadding="0" cellspacing="0" width="100%" style="background: <?php echo $idcard->header_color; ?>;">
         <tr>
             <td valign="top">

                 <img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/background/' . $idcard->background); ?>" class="tcmybg" style="opacity: .1" />
             </td>
         </tr>
         <tr>
             <td valign="top" style="text-align: center;color: #fff;padding: 10px;min-height: 110px;display: block;">
                 <table cellpadding="0" cellspacing="0" width="100%">
                     <tr>
                         <td valign="top">
                             <div style="color: #fff;position: relative; z-index: 1;">
                                 <div class="sttext1">
                                     <img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/logo/' . $idcard->logo); ?>" width="30" height="30"> <?php echo $idcard->school_name; ?>

                                 </div>
                         </td>
                     </tr>
                     <tr>
                         <td valign="top" style="color: #fff"><?php echo $idcard->school_address; ?></td>
                     </tr>
                 </table>
             </td>
         </tr>
         <tr>
             <td valign="top" style="background: #fff">
                 <table cellpadding="0" cellspacing="0" width="100%" style="margin-top: -40px; position: relative;z-index: 1;">
                     <tr>
                         <td valign="top" style="text-align: center;">
                             <div class="stimg center-block">

                                 <img src="<?php echo $this->media_storage->getImageURL("uploads/student_images/no_image.png"); ?>" class="img-responsive img-circles block-center" style="border:3px solid <?php echo $idcard->header_color; ?>">
                             </div>
                         </td>
                     </tr>
                     <tr>
                         <td valign="top" style="text-align: center;">
                             <h4 style="margin:0; text-transform: uppercase;font-weight: bold; margin-top: 10px;">Student Name</h4>
                         </td>
                     </tr>
                 </table>
             </td>
         </tr>
         <tr>
             <td valign="top">
                 <table cellpadding="0" cellpadding="0" width="90%" align="center" style="background: #fff;padding: 20px 20px;display: block;width: 90%; margin:0 auto">
                     <tr>
                         <td valign="top">
                             <ul class="vertlist">
                                 <?php
                                    $fields = [
                                        'enable_admission_no'       => ['label' => 'admission_no',     'value' => '123456789'],
                                        'enable_student_rollno'     => ['label' => 'roll_no',          'value' => '1015',                  'class' => 'stred'],
                                        'enable_class'              => ['label' => 'class',            'value' => 'Class 6 - A (2018-19)'],
                                        'enable_student_house_name' => ['label' => 'house',            'value' => 'Red House',             'class' => 'stred'],
                                        'enable_fathers_name'       => ['label' => 'fathers_name',     'value' => 'S.Tudent Name'],
                                        'enable_mothers_name'       => ['label' => 'mothers_name',     'value' => 'S.Tudent Name'],
                                        'enable_address'            => ['label' => 'address',          'value' => 'D.No.1 Street Name Address Line 2 Address Line 3'],
                                        'enable_phone'              => ['label' => 'phone',            'value' => '1234567890'],
                                        'enable_dob'                => ['label' => 'd_o_b',            'value' => '25.06.2006'],
                                        'enable_blood_group'        => ['label' => 'blood_group',      'value' => 'A+',                    'class' => 'stred'],
                                    ];

                                    foreach ($fields as $key => $field) {
                                        if (!empty($idcard->$key)) {
                                            $liClass = !empty($field['class']) ? ' class="' . $field['class'] . '"' : '';
                                            $label = $this->lang->line($field['label']);
                                            $value = $field['value'];
                                            echo "<li{$liClass}>{$label}<span> {$value}</span></li>";
                                        }
                                    }
                                    ?>
                             </ul>
                             <div class="signature">
                                 <img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/signature/' . $idcard->sign_image); ?>" width="200" height="24" />
                             </div>

                             <?php

                                if ($idcard->enable_student_barcode) {
                                    if ($scan_code_type == "qrcode") {
                                ?>

                                     <?php if (file_exists("./uploads/staff_id_card/qrcode/default.png")) {
                                        ?>
                                         <div class="signature">
                                             <img src="<?php echo $this->media_storage->getImageURL('uploads/staff_id_card/qrcode/default.png'); ?>" style="max-width: 65px; margin: 0 auto; height:auto" />
                                         </div>
                                     <?php
                                        }
                                        ?>
                                 <?php

                                    } elseif ($scan_code_type == "barcode") {
                                    ?>

                                     <?php if (file_exists("./uploads/staff_id_card/barcodes/default.png")) {
                                        ?>
                                         <div class="signature">
                                             <img src="<?php echo $this->media_storage->getImageURL('uploads/staff_id_card/barcodes/default.png'); ?>" style="max-width: 65px; margin: 0 auto; height:auto" />
                                         </div>
                                     <?php
                                        }
                                        ?>
                             <?php

                                    }
                                }

                                ?>

                         </td>
                     </tr>
                 </table>
             </td>
         </tr>
     </table>
 <?php

    } else {
    ?>
     <table cellpadding="0" cellspacing="0" width="100%">
         <tr>
             <td valign="top" width="32%" style="padding: 3px;">
                 <table cellpadding="0" cellspacing="0" width="100%" class="tc-container" style="background: #efefef;">
                     <tr>
                         <td valign="top">

                             <img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/background/' . $idcard->background); ?>" class="tcmybg" style="opacity: .1" />
                         </td>
                     </tr>
                     <tr>
                         <td valign="top">
                             <div class="studenttop">
                                 <div class="sttext1">

                                     <img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/logo/' . $idcard->logo); ?>" width="30" height="30" />
                                     <?php echo $idcard->school_name; ?>
                                 </div>
                             </div>
                         </td>
                     </tr>
                     <tr>
                         <td valign="top" align="center" style="padding: 1px 0;">
                             <p><?php echo $idcard->school_address; ?></p>
                         </td>
                     </tr>
                     <tr>
                         <td valign="top" style="color: #fff;font-size: 16px; padding: 2px 0 0; position: relative; z-index: 1;background: <?php echo $idcard->header_color; ?>;text-transform: uppercase;"><?php echo $idcard->title; ?></td>
                     </tr>
                     <tr>
                         <td valign="top">
                             <div class="staround">
                                 <div class="cardleft">
                                     <div class="stimg">

                                         <img src="<?php echo $this->media_storage->getImageURL("uploads/student_images/no_image.png"); ?>" class="img-responsive" />
                                     </div>
                                     <?php
                                        $barcodePath = '';
                                        if ($idcard->enable_student_barcode) {
                                            if ($scan_code_type === 'qrcode') {
                                                $barcodePath = 'uploads/staff_id_card/qrcode/default.png';
                                            } elseif ($scan_code_type === 'barcode') {
                                                $barcodePath = 'uploads/staff_id_card/barcodes/default.png';
                                            }
                                        }

                                        if ($barcodePath && file_exists("./{$barcodePath}")) : ?>
                                         <div class="barcodeimg center-block" style="width: 90%; margin: 0 auto;">
                                             <img src="<?php echo $this->media_storage->getImageURL($barcodePath); ?>" style="max-width: 65px; margin: 0 auto; height: auto;" />
                                         </div>
                                     <?php endif; ?>
                                 </div><!--./cardleft-->
                                 <div class="cardright">
                                     <ul class="stlist">
                                         <?php
                                            $fields = [
                                                'enable_student_name'        => ['label' => 'student_name',     'value' => 'S.Tudent Name'],
                                                'enable_admission_no'        => ['label' => 'admission_no',     'value' => '123456789'],
                                                'enable_student_rollno'      => ['label' => 'roll_no',          'value' => '1015'],
                                                'enable_class'               => ['label' => 'class',            'value' => 'Class 6 - A (2018-19)'],
                                                'enable_student_house_name'  => ['label' => 'house',            'value' => 'Red House',     'class' => 'stred'],
                                                'enable_fathers_name'        => ['label' => 'fathers_name',     'value' => 'S.Tudent Name'],
                                                'enable_mothers_name'        => ['label' => 'mothers_name',     'value' => 'S.Tudent Name'],
                                                'enable_address'             => ['label' => 'address',          'value' => 'D.No.1 Street Name Address Line 2 Address Line 3'],
                                                'enable_phone'               => ['label' => 'phone',            'value' => '1234567890'],
                                                'enable_dob'                 => ['label' => 'd_o_b',            'value' => '25.06.2006'],
                                                'enable_blood_group'         => ['label' => 'blood_group',      'value' => 'A+',            'class' => 'stred'],
                                            ];

                                            foreach ($fields as $key => $field) {
                                                if (!empty($idcard->$key)) {
                                                    $liClass = isset($field['class']) ? ' class="' . $field['class'] . '"' : '';
                                                    $label = $this->lang->line($field['label']);
                                                    $value = $field['value'];
                                                    echo "<li{$liClass}>{$label}<span> {$value}</span></li>";
                                                }
                                            }
                                            ?>
                                     </ul>
                                 </div><!--./cardright-->
                             </div><!--./staround-->
                         </td>
                     </tr>
                     <tr>
                         <td valign="top" align="right" class="principal">
                             <img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/signature/' . $idcard->sign_image); ?>" width="66" height="40" />
                         </td>
                     </tr>

                 </table>
             </td>
         </tr>
     </table>

 <?php
    }
    ?>