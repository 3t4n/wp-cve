<?php

if (!defined('ABSPATH'))
    die('Restricted Access');
if (jsjobs::$_error_flag == null) {
    require JSJOBS_PLUGIN_PATH . 'modules/resume/tmpl/tfpdf.php';

    class PDF_HTML extends tFPDF {

        var $B = 0;
        var $I = 0;
        var $U = 0;
        var $HREF = '';
        var $ALIGN = '';

        function WriteHTML($html) {
            //HTML parser
            $html = jsjobslib::jsjobs_str_replace("\n", ' ', $html);
            $a = jsjobslib::jsjobs_preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
            foreach ($a as $i => $e) {
                if ($i % 2 == 0) {
                    //Text
                    if ($this->HREF)
                        $this->PutLink($this->HREF, $e);
                    elseif ($this->ALIGN == 'center')
                        $this->Cell(0, 5, $e, 0, 1, 'C');
                    else
                        $this->Write(5, $e);
                }
                else {
                    //Tag
                    if ($e[0] == '/')
                        $this->CloseTag(jsjobslib::jsjobs_strtoupper(jsjobslib::jsjobs_substr($e, 1)));
                    else {
                        //Extract properties
                        $a2 = jsjobslib::jsjobs_explode(' ', $e);
                        $tag = jsjobslib::jsjobs_strtoupper(array_shift($a2));
                        $prop = array();
                        foreach ($a2 as $v) {
                            if (jsjobslib::jsjobs_preg_match('/([^=]*)=["\']?([^"\']*)/', $v, $a3))
                                $prop[jsjobslib::jsjobs_strtoupper($a3[1])] = $a3[2];
                        }
                        $this->OpenTag($tag, $prop);
                    }
                }
            }
        }

        function OpenTag($tag, $prop) {
            //Opening tag
            if ($tag == 'B' || $tag == 'I' || $tag == 'U')
                $this->SetStyle($tag, true);
            if ($tag == 'A')
                $this->HREF = $prop['HREF'];
            if ($tag == 'BR')
                $this->Ln(5);
            if ($tag == 'P')
                $this->ALIGN = isset($prop['ALIGN']) ? $prop['ALIGN'] : '';
            if ($tag == 'HR') {
                if (!empty($prop['WIDTH']))
                    $Width = $prop['WIDTH'];
                else
                    $Width = $this->w - $this->lMargin - $this->rMargin;
                $this->Ln(2);
                $x = $this->GetX();
                $y = $this->GetY();
                $this->SetLineWidth(0.4);
                $this->Line($x, $y, $x + $Width, $y);
                $this->SetLineWidth(0.2);
                $this->Ln(2);
            }
        }

        function CloseTag($tag) {
            //Closing tag
            if ($tag == 'B' || $tag == 'I' || $tag == 'U')
                $this->SetStyle($tag, false);
            if ($tag == 'A')
                $this->HREF = '';
            if ($tag == 'P')
                $this->ALIGN = '';
        }

        function SetStyle($tag, $enable) {
            //Modify style and select corresponding font
            $this->$tag+=($enable ? 1 : -1);
            $style = '';
            foreach (array('B', 'I', 'U') as $s)
                if ($this->$s > 0)
                    $style.=$s;
            $this->SetFont('', $style);
        }

        function PutLink($URL, $txt) {
            //Put a hyperlink
            $this->SetTextColor(0, 0, 255);
            $this->SetStyle('U', true);
            $this->Write(5, $txt, $URL);
            $this->SetStyle('U', false);
            $this->SetTextColor(0);
        }

    }

    function addSection($title, &$y, &$pdf, $published) {
        if ($published == 1) {
            $pdf->SetDrawColor(223, 223, 223);
            $pdf->SetFillColor(246, 247, 248);
            $pdf->SetTextColor(60, 60, 60);
            $pdf->SetLineWidth(1);
            $pdf->SetFont('DejaVu', '', 13);
            $pdf->SetLineWidth(0.1);
            $pdf->MultiCell(190, 8, $title, 1, 'J', true);
            $y = $pdf->GetY();
        }
        return;
    }

    function addSubSection($title, &$y, &$pdf, $published) {
        if ($published == 1) {
            $pdf->SetDrawColor(223, 223, 223);
            $pdf->SetFillColor(246, 247, 248);
            $pdf->SetTextColor(60, 60, 60);
            $pdf->SetLineWidth(1);
            $pdf->SetFont('DejaVu', '', 13);
            $pdf->SetLineWidth(0.1);
            $pdf->SetY($y + 2);
            $pdf->SetX(30);
            $pdf->MultiCell(150, 8, $title, 1, 'J', true);
            $y = $pdf->GetY();
        }
        return;
    }

    function addRow($title, $value, &$y, &$pdf, $published) {
        if ($published == 1) {
            $pdf->SetFont("DejaVu", "", 11);
            $pdf->SetY($y);
            $pdf->MultiCell(60, 8, $title, 0, 'R');
            $oldy1 = $pdf->GetY();
            if ($y > (270))
                $y = 10;
            $pdf->SetY($y);
            $pdf->SetX(70);
            $pdf->SetFont("DejaVu", "", 10);
            $pdf->MultiCell(130, 8, $value, 0, 'J');
            $oldy2 = $pdf->GetY();
            $y = ($oldy1 >= $oldy2) ? $oldy1 : $oldy2;
        }
    }

    if (isset(jsjobs::$_data['socialprofilepdf']) && jsjobs::$_data['socialprofilepdf'] == true) {
        JSJOBSincluder::getObjectClass('socialmedia')->pdf();
    }

    $pdf = new PDF_HTML();
    $pdf->AddPage();
    $pdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);
    $pdf->SetFont('DejaVu', '', 14);

    $section_personal = 1;
    $section_addresses = isset(jsjobs::$_data[0]['address_section']) ? 1 : 0;
    $section_educations = isset(jsjobs::$_data[0]['institute_section']) ? 1 : 0;
    $section_employers = isset(jsjobs::$_data[0]['employer_section']) ? 1 : 0;
    $section_skills = (jsjobs::$_data[0]['personal_section']->skills != "") ? 1 : 0;
    $section_resume = (jsjobs::$_data[0]['personal_section']->resume != "") ? 1 : 0;
    $section_references = isset(jsjobs::$_data[0]['reference_section']) ? 1 : 0;
    $section_languages = isset(jsjobs::$_data[0]['language_section']) ? 1 : 0;

    $fieldsordering = JSJOBSincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(3); // resume fields
    jsjobs::$_data[2] = array();
    foreach ($fieldsordering AS $field) {
        jsjobs::$_data['fieldtitles'][$field->field] = $field->fieldtitle;
        jsjobs::$_data[2][$field->section][$field->field] = $field->required;
    }
    $ff = jsjobs::$_data[2];
    $fieldsordering = array();
    foreach ($ff AS $section => $fields) {
        foreach ($fields AS $key => $value) {
            $fieldsordering[$section][$key] = 1; // all fields were published it is maintained in model
        }
    }

    $pdf_output = '';


    $y = 0; // init for the resume pdf vars
    if (isset(jsjobs::$_data[0]['personal_section'])) {
        foreach ($fieldsordering[1] as $key => $value) {
            switch ($key) {
                case 'section_personal':
                    $pdf->SetFont("DejaVu", "", 15);
                    $pdf->SetFillColor(68, 68, 66);
                    $pdf->SetTextColor(253, 253, 253);
                    $resumetitle = '';
                    $resumetitle .= jsjobs::$_data[0]['personal_section']->first_name;
                    $resumetitle .= ' ' . jsjobs::$_data[0]['personal_section']->last_name;
                    $pdf->MultiCell(190, 8, __('Resume', 'js-jobs') . ':  ' . $resumetitle, 0, 'J', true);
                    $pdf->WriteHTML('<hr>', true);
                    addSection(__('Personal Information', 'js-jobs'), $y, $pdf, 1);
                    break;
                case "application_title":
                    jsjobs::$_data[0]['personal_section']->application_title = (jsjobs::$_data[0]['personal_section']->application_title != '') ? jsjobs::$_data[0]['personal_section']->application_title : __('N/A', 'js-jobs');
                    addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), jsjobs::$_data[0]['personal_section']->application_title, $y, $pdf, $value);
                    break;
                case "first_name":
                    jsjobs::$_data[0]['personal_section']->first_name = (jsjobs::$_data[0]['personal_section']->first_name != '') ? jsjobs::$_data[0]['personal_section']->first_name : __('N/A', 'js-jobs');
                    addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), jsjobs::$_data[0]['personal_section']->first_name, $y, $pdf, $value);
                    break;
                case "middle_name":
                    jsjobs::$_data[0]['personal_section']->middle_name = (jsjobs::$_data[0]['personal_section']->middle_name != '') ? jsjobs::$_data[0]['personal_section']->middle_name : __('N/A', 'js-jobs');
                    addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), jsjobs::$_data[0]['personal_section']->middle_name, $y, $pdf, $value);
                    break;
                case "last_name":
                    jsjobs::$_data[0]['personal_section']->last_name = (jsjobs::$_data[0]['personal_section']->last_name != '') ? jsjobs::$_data[0]['personal_section']->last_name : __('N/A', 'js-jobs');
                    addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), jsjobs::$_data[0]['personal_section']->last_name, $y, $pdf, $value);
                    break;
                case "email_address":
                    addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), jsjobs::$_data[0]['personal_section']->email_address, $y, $pdf, $value);
                    break;
                case "nationality":
                    jsjobs::$_data[0]['personal_section']->nationality = (jsjobs::$_data[0]['personal_section']->nationality != '') ? jsjobs::$_data[0]['personal_section']->nationality : __('N/A', 'js-jobs');
                    addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), jsjobs::$_data[0]['personal_section']->nationality, $y, $pdf, $value);
                    break;
                case "date_of_birth":
                    jsjobs::$_data[0]['personal_section']->date_of_birth = (jsjobs::$_data[0]['personal_section']->date_of_birth != '0000-00-00 00:00:00') ? jsjobs::$_data[0]['personal_section']->date_of_birth : '';
                    addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), jsjobs::$_data[0]['personal_section']->date_of_birth, $y, $pdf, $value);
                    break;
                case "gender":
                    jsjobs::$_data[0]['personal_section']->gender = (jsjobs::$_data[0]['personal_section']->gender != '') ? jsjobs::$_data[0]['personal_section']->gender : __('N/A', 'js-jobs');
                    if (jsjobs::$_data[0]['personal_section']->gender == 1) {
                        jsjobs::$_data[0]['personal_section']->gender = __('Male', 'js-jobs');
                    } elseif (jsjobs::$_data[0]['personal_section']->gender == 2) {
                        jsjobs::$_data[0]['personal_section']->gender = __('Female', 'js-jobs');
                    } else {
                        jsjobs::$_data[0]['personal_section']->gender = '';
                    }
                    addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), jsjobs::$_data[0]['personal_section']->gender, $y, $pdf, $value);
                    break;
                case "iamavailable":
                    jsjobs::$_data[0]['personal_section']->iamavailable = (jsjobs::$_data[0]['personal_section']->iamavailable != '') ? jsjobs::$_data[0]['personal_section']->iamavailable : __('N/A', 'js-jobs');
                    if (jsjobs::$_data[0]['personal_section']->iamavailable == 1) {
                        jsjobs::$_data[0]['personal_section']->iamavailable = __('Yes', 'js-jobs');
                    } else {
                        jsjobs::$_data[0]['personal_section']->iamavailable = __('No', 'js-jobs');
                    }
                    addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), jsjobs::$_data[0]['personal_section']->iamavailable, $y, $pdf, $value);
                    break;
                case "searchable":
                    jsjobs::$_data[0]['personal_section']->searchable = (jsjobs::$_data[0]['personal_section']->searchable != '') ? jsjobs::$_data[0]['personal_section']->searchable : __('N/A', 'js-jobs');
                    $resumevalue = (jsjobs::$_data[0]['personal_section']->searchable == 1) ? __('Yes', 'js-jobs') : __('No', 'js-jobs');
                    addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $resumevalue, $y, $pdf, $value);
                    break;
                case "home_phone":
                    jsjobs::$_data[0]['personal_section']->home_phone = (jsjobs::$_data[0]['personal_section']->home_phone != '') ? jsjobs::$_data[0]['personal_section']->home_phone : __('N/A', 'js-jobs');
                    addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), jsjobs::$_data[0]['personal_section']->home_phone, $y, $pdf, $value);
                    break;
                case "work_phone":
                    jsjobs::$_data[0]['personal_section']->work_phone = (jsjobs::$_data[0]['personal_section']->work_phone != '') ? jsjobs::$_data[0]['personal_section']->work_phone : __('N/A', 'js-jobs');
                    addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), jsjobs::$_data[0]['personal_section']->work_phone, $y, $pdf, $value);
                    break;
                case "job_category":
                    jsjobs::$_data[0]['personal_section']->categorytitle = (jsjobs::$_data[0]['personal_section']->categorytitle != '') ? jsjobs::$_data[0]['personal_section']->categorytitle : __('N/A', 'js-jobs');
                    addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), jsjobs::$_data[0]['personal_section']->categorytitle, $y, $pdf, $value);
                    break;
                case "salary":
                    $resumevalue = JSJOBSincluder::getJSModel('common')->getSalaryRangeView(jsjobs::$_data[0]['personal_section']->symbol, jsjobs::$_data[0]['personal_section']->rangestart, jsjobs::$_data[0]['personal_section']->rangeend, jsjobs::$_data[0]['personal_section']->rangetype);
                    addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $resumevalue, $y, $pdf, $value);
                    break;
                case "jobtype":
                    jsjobs::$_data[0]['personal_section']->jobtypetitle = (jsjobs::$_data[0]['personal_section']->jobtypetitle != '') ? jsjobs::$_data[0]['personal_section']->jobtypetitle : __('N/A', 'js-jobs');
                    addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), jsjobs::$_data[0]['personal_section']->jobtypetitle, $y, $pdf, $value);
                    break;
                case "heighestfinisheducation":
                    jsjobs::$_data[0]['personal_section']->highestfinisheducation = (jsjobs::$_data[0]['personal_section']->highestfinisheducation != '') ? jsjobs::$_data[0]['personal_section']->highestfinisheducation : __('N/A', 'js-jobs');
                    addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), jsjobs::$_data[0]['personal_section']->highestfinisheducation, $y, $pdf, $value);
                    break;
                case "date_start":
                    jsjobs::$_data[0]['personal_section']->date_start = (jsjobs::$_data[0]['personal_section']->date_start != '0000-00-00 00:00:00') ? jsjobs::$_data[0]['personal_section']->date_start : '';
                    addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), jsjobs::$_data[0]['personal_section']->date_start, $y, $pdf, $value);
                    break;
                case "total_experience":
                    jsjobs::$_data[0]['personal_section']->total_experience = (jsjobs::$_data[0]['personal_section']->total_experience != '') ? jsjobs::$_data[0]['personal_section']->total_experience : __('N/A', 'js-jobs');
                    addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), jsjobs::$_data[0]['personal_section']->total_experience, $y, $pdf, $value);
                    break;
                case "driving_license":
                    if(jsjobs::$_data[0]['personal_section']->driving_license == 1){
                        jsjobs::$_data[0]['personal_section']->driving_license = __('Yes','js-jobs');
                    }else{
                        jsjobs::$_data[0]['personal_section']->driving_license = __('No','js-jobs');
                    }
                    addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), jsjobs::$_data[0]['personal_section']->driving_license, $y, $pdf, $value);
                    break;
                case "license_no":
                    addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), jsjobs::$_data[0]['personal_section']->license_no, $y, $pdf, $value);
                    break;
                case "license_country":
                    addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), jsjobs::$_data[0]['personal_section']->licensecountryname, $y, $pdf, $value);
                    break;
                default:
                    $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($key, 11, jsjobs::$_data[0]['personal_section']->params); // 11 view resume
                    if (is_array($array))
                        addRow(__($array['title'], 'js-jobs'), $array['value'], $y, $pdf, $value);
                    break;
            }
        }
    }
    
    if (count(jsjobs::$_data[0]['address_section']) != 0){
        addSection(__('Address', 'js-jobs'), $y, $pdf, $section_addresses);
    }

    if ($section_addresses == 1) {
        $i = 0;
        foreach (jsjobs::$_data[0]['address_section'] as $address) {
            if (!($address instanceof Object)) {
                $address = (Object) $address;
            }
            $i++;
            foreach ($fieldsordering[2] as $key => $value) {
                switch ($key) {
                    case 'section_address':
                        addSubSection(__('Address', 'js-jobs'), $y, $pdf, $value);
                        break;
                    case 'address_city':
                        if ($address->cityname == '') {
                            $address->cityname = "N/A";
                        }
                        if ($address->statename == '') {
                            $address->statename = "N/A";
                        }
                        if ($address->countryname == '') {
                            $address->countryname = "N/A";
                        }

                        addRow(__('City', 'js-jobs'), $address->cityname, $y, $pdf, $value);
                        addRow(__('State', 'js-jobs'), $address->statename, $y, $pdf, $value);
                        addRow(__('Country', 'js-jobs'), $address->countryname, $y, $pdf, $value);
                        break;
                    case "address_zipcode":
                        if ($address->address_zipcode == '') {
                            $address->address_zipcode = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $address->address_zipcode, $y, $pdf, $value);
                        break;
                    case "address":
                        if ($address->address == '') {
                            $address->address = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $address->address, $y, $pdf, $value);
                        break;
                    default:
                        $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($key, 11, $address->params); // 11 view resume
                        if (is_array($array))
                            addRow(__($array['title'], 'js-jobs'), $array['value'], $y, $pdf, $value);
                        break;
                }
            }
        }
    }
    if (!count(jsjobs::$_data[0]['institute_section']) == 0)
        addSection(__('Institutes', 'js-jobs'), $y, $pdf, $section_educations);

    if ($section_educations == 1) {
        $i = 0;
        foreach (jsjobs::$_data[0]['institute_section'] as $institute) {
            if (!($institute instanceof Object)) {
                $institute = (Object) $institute;
            }
            $i++;
            foreach ($fieldsordering[3] as $key => $value) {
                switch ($key) {
                    case 'section_education':
                        addSubSection(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $y, $pdf, $value);
                        break;
                    case "institute":
                        if ($institute->institute == '') {
                            $institute->institute = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $institute->institute, $y, $pdf, $value);
                        break;
                    case 'institute_city':
                        if ($institute->cityname == '') {
                            $institute->cityname = "N/A";
                        }
                        if ($institute->statename == '') {
                            $institute->statename = "N/A";
                        }
                        if ($institute->countryname == '') {
                            $institute->countryname = "N/A";
                        }

                        addRow(__('City', 'js-jobs'), $institute->cityname, $y, $pdf, $value);
                        addRow(__('State', 'js-jobs'), $institute->statename, $y, $pdf, $value);
                        addRow(__('Country', 'js-jobs'), $institute->countryname, $y, $pdf, $value);
                        break;
                    case "institute_address":
                        if ($institute->institute_address == '') {
                            $institute->institute_address = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $institute->institute_address, $y, $pdf, $value);
                        break;
                    case "institute_certificate_name":
                        if ($institute->institute_certificate_name == '') {
                            $institute->institute_certificate_name = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $institute->institute_certificate_name, $y, $pdf, $value);
                        break;
                    case "institute_study_area":
                        if ($institute->institute_study_area == '') {
                            $institute->institute_study_area = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $institute->institute_study_area, $y, $pdf, $value);
                        break;
                    case "institute_date_from":
                        if ($institute->fromdate == '') {
                            $institute->fromdate = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $institute->institute_study_area, $y, $pdf, $value);
                        break;
                    case "institute_date_to":
                        if ($institute->todate == '') {
                            $institute->todate = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $institute->institute_study_area, $y, $pdf, $value);
                        break;
                    default:
                        $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($key, 11, $institute->params); // 11 view resume
                        if (is_array($array))
                            addRow(__($array['title'], 'js-jobs'), $array['value'], $y, $pdf, $value);
                        break;
                }
            }
        }
    }
    if (!count(jsjobs::$_data[0]['employer_section']) == 0)
        addSection(__('Employers', 'js-jobs'), $y, $pdf, $section_employers);

    if ($section_employers == 1) {
        $i = 0;
        foreach (jsjobs::$_data[0]['employer_section'] as $employer) {
            if (!($employer instanceof Object)) {
                $employer = (Object) $employer;
            }
            $i++;
            foreach ($fieldsordering[4] as $key => $value) {
                switch ($key) {
                    case 'section_employer':
                        addSubSection(__('Employer', 'js-jobs'), $y, $pdf, $value);
                        break;
                    case "employer":
                        if ($employer->employer == '') {
                            $employer->employer = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $employer->employer, $y, $pdf, $value);
                        break;
                    case "employer_position":
                        if ($employer->employer == '') {
                            $employer->employer = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $employer->employer_position, $y, $pdf, $value);
                        break;
                    case "employer_resp":
                        if ($employer->employer_resp == '') {
                            $employer->employer_resp = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $employer->employer_resp, $y, $pdf, $value);
                        break;
                    case "employer_pay_upon_leaving":
                        if ($employer->employer_pay_upon_leaving == '') {
                            $employer->employer_pay_upon_leaving = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $employer->employer_pay_upon_leaving, $y, $pdf, $value);
                        break;
                    case "employer_supervisor":
                        if ($employer->employer_supervisor == '') {
                            $employer->employer_supervisor = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $employer->employer_supervisor, $y, $pdf, $value);
                        break;
                    case "employer_from_date":
                        if ($employer->employer_from_date == '') {
                            $employer->employer_from_date = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $employer->employer_from_date, $y, $pdf, $value);
                        break;
                    case "employer_to_date":
                        if ($employer->employer_to_date == '') {
                            $employer->employer_to_date = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $employer->employer_to_date, $y, $pdf, $value);
                        break;
                    case "employer_leave_reason":
                        if ($employer->employer_leave_reason == '') {
                            $employer->employer_leave_reason = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $employer->employer_leave_reason, $y, $pdf, $value);
                        break;
                    case "employer_city":
                        if ($employer->cityname == "") {
                            $employer->cityname = "N/A";
                        }
                        if ($employer->statename == "") {
                            $employer->statename = "N/A";
                        }
                        if ($employer->countryname == "") {
                            $employer->countryname = "N/A";
                        }

                        addRow(__('City', 'js-jobs'), $employer->cityname, $y, $pdf, $value);
                        addRow(__('State', 'js-jobs'), $employer->statename, $y, $pdf, $value);
                        addRow(__('Country', 'js-jobs'), $employer->countryname, $y, $pdf, $value);
                        break;
                    case "employer_zip":
                        if ($employer->employer_zip == '') {
                            $employer->employer_zip = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $employer->employer_zip, $y, $pdf, $value);
                        break;
                    case "employer_phone":
                        if ($employer->employer_phone == '') {
                            $employer->employer_phone = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $employer->employer_phone, $y, $pdf, $value);
                        break;
                    case "employer_address":
                        if ($employer->employer_address == '') {
                            $employer->employer_address = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $employer->employer_address, $y, $pdf, $value);
                        break;
                    default:
                        $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($key, 11, $employer->params); // 11 view resume
                        if (is_array($array))
                            addRow(__($array['title'], 'js-jobs'), $array['value'], $y, $pdf, $value);
                        break;
                }
            }
        }
    }

    // section skills
    if (isset(jsjobs::$_data[0]['personal_section']->skills) && jsjobs::$_data[0]['personal_section']->skills != '') {
        if ($section_skills == 1) {
            foreach ($fieldsordering[5] as $key => $value) {
                switch ($key) {
                    case 'section_skills':
                        addSection(__('Skills', 'js-jobs'), $y, $pdf, $value);
                        break;
                    case "skills":
                        if (jsjobs::$_data[0]['personal_section']->skills == '') {
                            jsjobs::$_data[0]['personal_section']->skills = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), jsjobs::$_data[0]['personal_section']->skills, $y, $pdf, $value);
                        break;
                    default:
                        $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($key, 11, jsjobs::$_data[0]['personal_section']->params); // 11 view resume
                        if (is_array($array))
                            addRow(__($array['title'], 'js-jobs'), $array['value'], $y, $pdf, $value);
                        break;
                }
            }
        }
    }

    // section resume
    if (isset(jsjobs::$_data[0]['personal_section']->resume) && jsjobs::$_data[0]['personal_section']->resume != '') {
        if ($section_resume == 1) {
            foreach ($fieldsordering[6] as $key => $value) {
                switch ($key) {
                    case 'section_resume':
                        $pdf->SetDrawColor(223, 223, 223);
                        $pdf->SetFillColor(246, 247, 248);
                        $pdf->SetTextColor(60, 60, 60);
                        $pdf->SetLineWidth(1);
                        $pdf->SetFont('DejaVu', '', 13);
                        $pdf->SetLineWidth(0.1);
                        $pdf->MultiCell(190, 8, __('Resume', 'js-jobs'), 1, 'J', true);
                        $y = $pdf->GetY();
                        break;
                    case "resume":
                        if (jsjobs::$_data[0]['personal_section']->resume == '') {
                            jsjobs::$_data[0]['personal_section']->resume = "N/A";
                        }
                        $pdf->SetFont("DejaVu", "", 11);
                        $pdf->SetY($y);
                        $pdf->WriteHTML(html_entity_decode(jsjobs::$_data[0]['personal_section']->resume));
                        $pdf->Ln();
                        $oldy1 = $pdf->GetY();
                        $y = $oldy1;
                        break;
                    default:
                        $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($key, 11, jsjobs::$_data[0]['personal_section']->params); // 11 view resume
                        if (is_array($array))
                            addRow(__($array['title'], 'js-jobs'), $array['value'], $y, $pdf, $value);
                        break;
                }
            }
        }
    }

    if (!count(jsjobs::$_data[0]['reference_section']) == 0)
        addSection(__('References', 'js-jobs'), $y, $pdf, $section_references);

    if ($section_references == 1) {
        $i = 0;
        foreach (jsjobs::$_data[0]['reference_section'] as $reference) {
            if (!($reference instanceof Object)) {
                $reference = (Object) $reference;
            }
            $i++;
            foreach ($fieldsordering[7] as $key => $value) {
                switch ($key) {
                    case 'section_reference':
                        addSubSection(__('Reference', 'js-jobs'), $y, $pdf, $value);
                        break;
                    case "reference":
                        if ($reference->reference_zipcode == '') {
                            $reference->reference = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $reference->reference, $y, $pdf, $value);
                        break;
                    case 'reference_city':
                        if ($reference->cityname == '') {
                            $reference->cityname = "N/A";
                        }
                        if ($reference->statename == '') {
                            $reference->statename = "N/A";
                        }
                        if ($reference->countryname == '') {
                            $reference->countryname = "N/A";
                        }

                        addRow(__('City', 'js-jobs'), $reference->cityname, $y, $pdf, $value);
                        addRow(__('State', 'js-jobs'), $reference->statename, $y, $pdf, $value);
                        addRow(__('Country', 'js-jobs'), $reference->countryname, $y, $pdf, $value);
                        break;
                    case "reference_name":
                        if ($reference->reference_name == '') {
                            $reference->reference_name = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $reference->reference_name, $y, $pdf, $value);
                        break;
                    case "reference_zipcode":
                        if ($reference->reference_zipcode == '') {
                            $reference->reference_zipcode = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $reference->reference_zipcode, $y, $pdf, $value);
                        break;
                    case "reference_address":
                        if ($reference->reference_address == '') {
                            $reference->reference_address = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $reference->reference_address, $y, $pdf, $value);
                        break;
                    case "reference_phone":
                        if ($reference->reference_phone == '') {
                            $reference->reference_phone = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $reference->reference_phone, $y, $pdf, $value);
                        break;
                    case "reference_relation":
                        if ($reference->reference_relation == '') {
                            $reference->reference_relation = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $reference->reference_relation, $y, $pdf, $value);
                        break;
                    case "reference_years":
                        if ($reference->reference_years == '') {
                            $reference->reference_years = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $reference->reference_years, $y, $pdf, $value);
                        break;
                    default:
                        $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($key, 11, $reference->params); // 11 view resume
                        if (is_array($array))
                            addRow(__($array['title'], 'js-jobs'), $array['value'], $y, $pdf, $value);
                        break;
                }
            }
        }
    }

    if (!count(jsjobs::$_data[0]['language_section']) == 0)
        addSection(__('Languages', 'js-jobs'), $y, $pdf, $section_languages);

    if ($section_languages == 1) {
        $i = 0;
        foreach (jsjobs::$_data[0]['language_section'] as $language) {
            if (!($language instanceof Object)) {
                $language = (Object) $language;
            }
            $i++;
            foreach ($fieldsordering[8] as $key => $value) {
                switch ($key) {
                    case 'section_language':
                        addSubSection(__('Language', 'js-jobs'), $y, $pdf, $value);
                        break;
                    case "language":
                        if ($language->language == '') {
                            $language->language = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $language->language, $y, $pdf, $value);
                        break;
                    case "language_reading":
                        if ($language->language_reading == '') {
                            $language->language_reading = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $language->language_reading, $y, $pdf, $value);
                        break;
                    case "language_writing":
                        if ($language->language_writing == '') {
                            $language->language_writing = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $language->language_writing, $y, $pdf, $value);
                        break;
                    case "language_address":
                        if ($language->language_address == '') {
                            $language->language_address = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $language->language_address, $y, $pdf, $value);
                        break;
                    case "language_understanding":
                        if ($language->language_understanding == '') {
                            $language->language_understanding = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $language->language_understanding, $y, $pdf, $value);
                        break;
                    case "language_relation":
                        if ($language->language_relation == '') {
                            $language->language_relation = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $language->language_relation, $y, $pdf, $value);
                        break;
                    case "language_where_learned":
                        if ($language->language_where_learned == '') {
                            $language->language_where_learned = "N/A";
                        }
                        addRow(__(jsjobs::$_data['fieldtitles'][$key], 'js-jobs'), $language->language_where_learned, $y, $pdf, $value);
                        break;
                    default:
                        $array = JSJOBSincluder::getObjectClass('customfields')->showCustomFields($key, 11, $language->params); // 11 view resume
                        if (is_array($array))
                            addRow(__($array['title'], 'js-jobs'), $array['value'], $y, $pdf, $value);
                        break;
                }
            }
        }
    }

    //$filename = jsjobs::$_data[0]['personal_section']->first_name . '-' . jsjobs::$_data[0]['personal_section']->last_name . '-resume.pdf';
    $filename = 'resume.pdf';
    $filename = jsjobslib::jsjobs_preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $filename);
    $filename = jsjobslib::jsjobs_str_replace(' ', '-', $filename);
    $pdf->Output($filename, 'I');
    die();
}
?>
