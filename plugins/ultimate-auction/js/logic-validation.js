jQuery(document).ready(
                       function()
                       {
                            jQuery("#auction-settings-form").submit(
                            function(){
                            var pm = jQuery("#auction-settings-form #wdm_paypal_id").val();
                            var wm = jQuery("#auction-settings-form #wdm_wire_transfer_id").val();
                            var mm = jQuery("#auction-settings-form #wdm_mailing_id").val();
                            var cash=jQuery("#auction-settings-form #wdm_cash").val();
                            
                            if(pm == '' && wm == '' && mm == '' && cash == '')
                            {
                                alert(wdm_ua_obj_l10nv.pmt);
                                return false;
                            }
                                return true;
                            }
                            );
                            jQuery("#wdm-add-auction-form").submit(
                            function(){
                                
                            var bn = new Number;
                            var ob = new Number;
                            var lb = new Number;
                            var inc = new Number;
                            var tl,ds,edt;
                            
                            tl = jQuery("#wdm-add-auction-form #auction_title").val();
                            //ds = jQuery("#wdm-add-auction-form #auction_description").val();
                            bn = jQuery("#wdm-add-auction-form #buy_it_now_price").val();
                            ob = jQuery("#wdm-add-auction-form #opening_bid").val();
                            lb = jQuery("#wdm-add-auction-form #lowest_bid").val();
                            inc = jQuery("#wdm-add-auction-form #incremental_value").val();
                            edt = jQuery("#wdm-add-auction-form #end_date").val();
                            
                            //var pd = jQuery("#payment_method #wdm_method_paypal").attr("disabled");
                            
                            if(!tl)
                            {
                                    alert(wdm_ua_obj_l10nv.ttl);
                                    return false; 
                            }
                            
                            //if(!ds)
                            //{
                            //        alert("Please enter Product Description.");
                            //        return false; 
                            //}
                            
                            if(!edt)
                            {
                                    alert(wdm_ua_obj_l10nv.et);
                                    return false; 
                            }
                            
                            //if(pd == 'disabled')
                            //{
                            //    if(bn && Number(bn) > 0)
                            //    {
                            //        alert(wdm_ua_obj_l10nv.set);
                            //        jQuery("#wdm-add-auction-form #buy_it_now_price").val("");
                            //        return false;
                            //    }
                            //    
                            //    if(!ob || 0 >= Number(ob))
                            //    {
                            //        alert(wdm_ua_obj_l10nv.opb);
                            //        return false;
                            //    }
                            //    
                            //    if(!lb || Number(lb) < Number(ob))
                            //    {
                            //        alert(wdm_ua_obj_l10nv.rp);
                            //        return false;
                            //    }
                            //    
                            //}
                            //else
                            //{
                                if((!ob || 0 >= Number(ob)) && (!bn || 0 >= Number(bn)))
                                {
                                    alert(wdm_ua_obj_l10nv.ob);
                                    return false;
                                }
                                
                                if((ob && Number(ob) > 0) && (!lb || 0 >= Number(lb)))
                                {
                                    alert(wdm_ua_obj_l10nv.olp);
                                    return false;
                                }
                                
                                if((ob && Number(ob) > 0) && (!inc || 0 >= Number(inc)))
                                {
                                   alert(wdm_ua_obj_l10nv.oinc);
                                   return false;
                                }
                                
                                if((lb && Number(lb) > 0) && (!ob || 0 >= Number(ob)))
                                {
                                    alert(wdm_ua_obj_l10nv.lpo);
                                    return false;
                                }
                                
                                if((inc && Number(inc) > 0) && (!ob || 0 >= Number(ob)))
                                {
                                    alert(wdm_ua_obj_l10nv.iop);
                                    return false;
                                }
                            //}
                                if(Number(lb) < Number(ob))
                                {
                                    alert(wdm_ua_obj_l10nv.rpo);
                                    return false;
                                }
                                
                                if(bn && Number(bn) > 0)
                                {
                                   if(Number(bn) < Number(lb))
                                   {
                                        alert(wdm_ua_obj_l10nv.bnl);
                                        return false;
                                   }
                                }
                                return true;
                            }
                            );
                            
                       }
                       );