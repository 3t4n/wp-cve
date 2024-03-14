<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 2/21/2018
 * Time: 11:55 AM
 */

class ReviewHelper
{
    private $thresholds=array(5,10,15);
    private $stages=array(
        array(
            'Threshold'=>50,
            'content'=>"Hello!, you have generated invoices <strong>%s times</strong>, that's Great! Could you do me a BIG favor and give it a
                                                5-star rating on WordPress? Good reviews really help to spread the word and keep me motivated to continuing maintaining and improving the plugin =)",
            'Reviewlink'=>'Sure, keep up the good work',
            'Remindmelink'=>'Nope, maybe later',
            'DontShowAgain'=>'I already did'

        ),
        array(
            'Threshold'=>100,
            'content'=>"Hello! Its me again =), you have generated invoices <strong>%s times</strong>, so i was wondering, would you have time to 5 star review the plugin really quick? I know you are busy and i don't like to bother you but reviewing the plugin is really important
and ensure the continuation of its development.",
            'Reviewlink'=>'Sure, i will review the plugin really quick',
            'Remindmelink'=>'Sorry but i am not ready yet, maybe later',
            'DontShowAgain'=>'I already did'
        ),
        array(
            'Threshold'=>200,
            'content'=>"Hello! sorry to bother you again (this is the last time i do it), i just wanted to tell you that you have generated invoices <strong>%s times</strong> which is amazing. Could you please help me and 5-star review the plugin? it will take you less than 1 minutes and will 
greatly help me promote and keep growing this plugin that i love and i hope it has been useful for you.",
            'Reviewlink'=>'Alright, i will review the plugin quickly',
            'DontShowAgain'=>'I don\'t want to review it =('
        )
    );
    private $stageProperty='wopdfinv_stage';
    private $currentStage;
    private $count=0;
    public function Start()
    {
        $this->currentStage=$this->GetCurrentStage();
        if($this->currentStage==null)
            return;

        if(!$this->ShouldPrintNotice())
            return;

        $this->PrintNotice();

    }

    private function GetCurrentStage()
    {
        $stageNumber= get_option($this->stageProperty,0);
        if($stageNumber>=count($this->stages))
            return null;

        return $this->stages[$stageNumber];
    }

    private function ShouldPrintNotice()
    {
        $this->count= get_option('woopdfinvoicecount',0);
        return $this->count>=$this->currentStage['Threshold'];
    }

    private function GetContent()
    {
        return sprintf($this->currentStage['content'],$this->count);


    }

    private function PrintNotice()
    {
        ?>
        <style type="text/css">
            .sfReviewButton{
                display: inline-block;
                padding: 6px 12px;
                margin-bottom: 0;
                font-size: 14px;
                font-weight: 400;
                line-height: 1.42857143;
                text-align: center;
                white-space: nowrap;
                vertical-align: middle;
                -ms-touch-action: manipulation;
                touch-action: manipulation;
                cursor: pointer;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
                background-image: none;
                border: 1px solid transparent;
                border-radius: 4px;
                color: #fff;
                background-color: #5bc0de;
                border-color: #46b8da;
                text-decoration: none;
            }

            .sfReviewButton:hover{
                color: #fff;
                background-color: #31b0d5;
                border-color: #269abc;
            }
        </style>
        <div class="notice notice-info sfReviewNotice" style="clear:both; padding-bottom:0;">
            <div style="padding-top: 5px;">


                <table >
                    <tbody  style="width:calc(100% - 135px);">
                    <tr>
                        <td>
                            <img style="display: inline-block;width:128px;vertical-align: top;" src="<?php echo RednaoWooCommercePDFInvoice::$URL?>images/icon.jpg">
                        </td>
                        <td>
                            <div style="display: inline-block; vertical-align: top;margin-left: 5px;"><span style="font-size: 16px;font-family: Verdana"><p style="padding-bottom: 1px;margin-bottom: 0;"><?php echo $this->GetContent()?></p>
                                            <p style="font-size: 13px;padding-top:0;margin-top:0;font-style: italic;">-Edgar Rojas</p>
                                            <ul style="list-style: circle;margin-left: 30px;">
                                                <li><a target="_blank" style="display: block" href="https://wordpress.org/support/plugin/woo-pdf-invoice-builder/reviews/?filter=5"><?php echo $this->currentStage['Reviewlink']?></a></li>
                                                <?php if(isset($this->currentStage['Remindmelink'])){?>
                                                <li><a id="wopdfinvoicerml" style="display: block" href="https://wordpress.org/support/plugin/woo-pdf-invoice-builder/reviews/?filter=5"><?php echo $this->currentStage['Remindmelink']?></a></li>
                                                <?php } ?>
                                                <li><a id="wopdfinvoicedsa" style="display: block" href="https://wordpress.org/support/plugin/woo-pdf-invoice-builder/reviews/?filter=5"><?php echo $this->currentStage['DontShowAgain']?></a></li>
                                            </ul>
                            </div>
                        </td>

                    </tr>

                    </tbody>
                </table>
                <div style="clear: both;"></div>
            </div>
        </div>

        <script type="text/javascript">
            jQuery(document).ready( function($) {
                jQuery('#wopdfinvoicerml').click(function(e){
                    e.preventDefault();
                    $.post( ajaxurl, {
                        action: 'rednao_wcpdfinv_remind_me'
                    });
                    jQuery('.sfReviewNotice').remove();
                });

                jQuery('#wopdfinvoicedsa').click(function(e){
                    e.preventDefault();
                    $.post( ajaxurl, {
                        action: 'rednao_wcpdfinv_dont_show_again'
                    });
                    jQuery('.sfReviewNotice').remove();
                });
            });
        </script> <?php
    }
}