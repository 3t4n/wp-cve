<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/16/2019
 * Time: 5:47 AM
 */

namespace rednaoformpdfbuilder\ajax;



use DriveApi;
use rednaoformpdfbuilder\DTO\DocumentOptions;
use rednaoformpdfbuilder\htmlgenerator\generators\PDFGenerator;
use rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields\FieldFactory;
use rednaoformpdfbuilder\pr\Utilities\Printer\Printer;
use rednaoformpdfbuilder\pr\Utilities\Printer\PrinterToken;
use rednaoformpdfbuilder\Utils\ImportExport\Importer;

class DesignerAjax extends AjaxBase
{

    public function __construct($core, $prefix)
    {
        parent::__construct($core, $prefix, 'builder');
    }

    protected function RegisterHooks()
    {
        $this->RegisterPrivate('execute_preview','ExecutePreview');
        $this->RegisterPrivate('save_template','SaveTemplate','pdfbuilder_manage_templates');
        $this->RegisterPrivate('generate_local_template','GenerateLocalTemplate');
        $this->RegisterPrivate('get_drive_folder_list','GetDriveFolders');

        $this->RegisterPrivate('qrcode_preview','QRCodePreview');


        add_action('wp_ajax_rednao_validate_google_auth_token1',array($this,'ValidateGoogleAuthToken1'));
        add_action('wp_ajax_rednao_validate_google_auth_token2',array($this,'ValidateGoogleAuthToken2'));
        add_action('wp_ajax_rednao_validate_google_auth_token3',array($this,'ValidateGoogleAuthToken3'));
    }


    public function GetDriveFolders(){
        $config=$this->GetRequired('config');
        require_once $this->Loader->DIR. 'pr/addons/drive/DriveApi.php';
        try{
            $driveApi=new DriveApi($this->Loader,$config);
            $folders= $driveApi->GetListOfFolders();
            $this->SendSuccessMessage(array('folders'=>$folders));
        }catch(\Exception $ex)
        {
            $this->SendErrorMessage('An error ocurred while loading the Google Drive folders, please check config. Error Detail:'.$ex->getMessage());
        }

        die();
    }

    public function ValidateGoogleAuthToken1(){
        if(!isset($_GET['client_id'])||!isset($_GET['client_secret']))
        {
            echo __("Invalid client id or secret! please try again");
            return;
        }
        $token=new PrinterToken();
        $token->clientsecret=$_GET['client_secret'];
        $token->clientid=$_GET['client_id'];
        Printer::UpdateToken($token);

        $params = array(
            'response_type' => 'code',
            'client_id' => $_GET['client_id'],
            'redirect_uri' => Printer::GetPrinterRedirectUrl(),
            'scope' => 'https://www.googleapis.com/auth/cloudprint',
            'state' => 'token',
            'access_type' => 'offline',
            'approval_prompt' => 'force'
        );

        header('Location: https://accounts.google.com/o/oauth2/auth?'.http_build_query($params, null, '&'));
        die();
    }

    public function ValidateGoogleAuthToken2(){
        $token=Printer::GetToken();

        if(isset($_GET['code'])) {
            $post_vars = array(
                'code' => $_GET['code'],
                'client_id' => $token->clientid,
                'client_secret' => $token->clientsecret,
                'redirect_uri' =>  Printer::GetPrinterRedirectUrl(),
                'grant_type' => 'authorization_code'
            );

            $googleauth_request_options = array('timeout' => 25, 'method' => 'POST', 'body' => $post_vars);

            $result = wp_remote_post('https://accounts.google.com/o/oauth2/token', $googleauth_request_options);

            if (is_wp_error($result)) {
                echo __("Couldn't validate your credentials reason:").$result->get_error_message();
            } else {
                $json_values = json_decode($result['body'], true);

                if (isset($json_values['refresh_token'])) {
                    $token->refreshtoken=$json_values['refresh_token'];
                    Printer::UpdateToken($token);
                    header('Location: '.admin_url( 'admin-ajax.php' ) .'?action=wp_ajax_rednao_validate_google_auth_token3');

                } else {
                    Printer::DeleteToken();
                    echo __('No refresh token was received from Google. This often means that you entered your client secret wrongly, or that you have not yet re-authenticated (below) since correcting it. Re-check it, then follow the link to authenticate again. Finally, if that does not work, create a new Google client ID/secret, and start again.');
                }
            }
        } else {
            echo "Invalid credentials, please authenticate again";
        }

        die();
    }

    public function ValidateGoogleAuthToken3(){
        echo __('<p>Authentication success!, you can now use your printers!.</p> <p>This screen will be closed automatically in 5 seconds</p>','rniotg');
        echo '<script type="text/javascript">setTimeout(function(){window.close();},5000);</script>';
        die();
    }



    public function GenerateLocalTemplate(){
        $id=$this->GetRequired('Id');
        $isPR=$this->GetRequired('IsPR');
        $url='';

        if(\file_exists($this->Loader->DIR."templates/$id/export.json"))
        {
            $code=\json_decode(\file_get_contents($this->Loader->DIR."templates/$id/export.json"));
            $this->SendSuccessMessage(array('data'=>$code));
        }

        if($isPR)
        {
            $url='';
            $url=\apply_filters($this->Loader->Prefix.'_download_pr_template',$url,$id);
            if($url=='')
                $this->SendErrorMessage('Could not download the template files, please make sure your site can connect with pdfbuilder.rednao.com');
        }else{
            if(!\file_exists($this->Loader->DIR."templates/$id/data.zip"))
            {
                $this->SendErrorMessage('Invalid template');
            }
            $url=$this->Loader->DIR."templates/$id/data.zip";
        }

        $zipArchive=new \ZipArchive();
        if($zipArchive->open($url)!==true)
            $this->SendErrorMessage('Could not open template file');

        $importer = new Importer($this->Loader, $zipArchive);
        $code = $importer->GetTemplateDocumentOptions();

        $this->SendSuccessMessage(array('data'=>$code));

    }

    public function QRCodePreview(){
        $data=$this->GetRequired('options');
        /** @var PDFQRCode $field */
        $field=FieldFactory::GetField($this->Loader,null, $data,null);
        $this->SendSuccessMessage($field->GetImage());
    }




    public function ExecutePreview(){

        $generator=(new PDFGenerator($this->Loader,$this->GetRequired('PageOptions'),null));
        $generator->GeneratePreview();
    }

    public function SaveTemplate(){
        /** @var DocumentOptions $data */
        $data=$this->GetData();
        global $wpdb;
        $id=0;
        $result=false;

        if(trim($data->Name)=='')
            $this->SendErrorMessage('Template name is mandatory');
        if($data->Id==0)
        {
            $count=$wpdb->get_var($wpdb->prepare('select count(*) from '.$this->Loader->TEMPLATES_TABLE.' where name=%s',$data->Name));
            if($count>0)
                $this->SendErrorMessage('Template name already in use, please define another.');
            $result=$wpdb->insert($this->Loader->TEMPLATES_TABLE,array(
               'form_id'=>$data->FormId,
               'pages'=>\json_encode($data->Pages),
                'styles'=>$data->Styles,
                'document_settings'=>\json_encode($data->DocumentSettings),
                'name'=>$data->Name
            ));
            $id=$wpdb->insert_id;

        }else{
            $count=$wpdb->get_var($wpdb->prepare('select count(*) from '.$this->Loader->TEMPLATES_TABLE.' where name=%s',$data->Name));
            if($count>1)
                $this->SendErrorMessage('Template name already in use, please define another.');

            $result=$wpdb->update($this->Loader->TEMPLATES_TABLE,array(
                'form_id'=>$data->FormId,
                'pages'=>\json_encode($data->Pages),
                'styles'=>$data->Styles,
                'document_settings'=>\json_encode($data->DocumentSettings),
                'name'=>$data->Name
            ),array(
                'id'=>$data->Id,
            ));

            $id=$data->Id;
        }

        if($result===false)
            $this->SendErrorMessage('An error ocurred, please try again');

        $this->SendSuccessMessage(array('id'=>$id));




    }
}