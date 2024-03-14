<?php
function irp_ui_tracking($override=FALSE) {
    global $irp;

    $track=$irp->Utils->qs('track', '');
    if($track!='') {
        $track=intval($track);
        $irp->Options->setTrackingEnable($track);
        $irp->Tracking->sendTracking(TRUE);
    }

    $uri=IRP_TAB_SETTINGS_URI.'&track=';
    if($irp->Options->isTrackingEnable()) {
        if($override) {
            $uri.='0';
            $irp->Options->pushSuccessMessage('EnableAllowTrackingNotice', $uri);
        }
    } else {
        $uri.='1';
        $irp->Options->pushWarningMessage('DisableAllowTrackingNotice', $uri);
    }
    $irp->Options->writeMessages();
}
function irp_io_first_time() {
    global $irp;
    if($irp->Options->isShowActivationNotice()) {
        $irp->Options->pushSuccessMessage('FirstTimeActivation');
        $irp->Options->writeMessages();
        $irp->Options->setShowActivationNotice(FALSE);
    }
}
function irp_ui_box_preview() {
    global $irp;

    $count=$irp->Options->getRewritePostsInBoxCount();
    $count=$irp->Utils->iqs('rewritePostsInBoxCount', $count);
    $args=array(
        'orderby'=>'rand'
        , 'numberposts'=>$count
    );

    $days=$irp->Options->getRewritePostsDays();
    $days=$irp->Utils->iqs('rewritePostsDays', $days);
    if($days>0) {
        $args['date_query'] = array(
            'column' => 'post_date'
            , 'after' => '- '.$days.' days'
        );
    }
    $posts=get_posts($args);
    $ids=array();
    foreach($posts as $p) {
        $ids[]=$p->ID;
    }
    if(count($ids)==0) {
        echo "<b>";
        echo __( "No posts available. Check settings and try again.", IRP_PLUGIN_SLUG );
        echo "</b>";
        die();
    }

    $defaults=$irp->Options->getTemplateStyle();
    $args=$irp->Utils->aqs('template-', $defaults);
    $args['includeCss']=TRUE;
    $args['preview']=TRUE;
    $box=irp_ui_get_box($ids, $args);
    echo wp_kses( $box, $irp->Utils->kses_allowed_html(), array('http', 'https', 'javascript') );
    die();
}
function irp_ui_settings() {
    global $irp;
    irp_ui_tracking(FALSE);

    $irp->Utils->load_related_box_script();

    $irp->Form->prefix='Settings';
    $irp->Form->helps = FALSE;
    $irp->Form->formStarts();

    if($irp->Check->nonce('irp_settings')) {

        $irp->Options->resetMaxExecutionTime();
        $irp->Options->setActive($irp->Utils->iqs('irpActive'));

        $defaults=array(
            'hasShadow'=>0
            , 'hasPoweredBy'=>0
        );
        $template=$irp->Utils->aqs('template-', $defaults);
        $irp->Options->setTemplateStyle($template);

        $irp->Options->setMarginTop($irp->Utils->sanitizeMargin('marginTop', '0em'));
        $irp->Options->setMarginBottom($irp->Utils->sanitizeMargin('marginBottom', '1em'));
        $irp->Options->setRewriteActive($irp->Utils->iqs('irpRewriteActive'));
        $irp->Options->setRewriteBoxesCount($irp->Utils->iqs('irpRewriteBoxesCount', 1));
        //$irp->Options->setRewritePostsInBoxCount($irp->Utils->iqs('irpRewritePostsInBoxCount', 1));
        $irp->Options->setRewritePostsDays($irp->Utils->iqs('irpRewritePostsDays', 0));
        $irp->Options->setRewriteThreshold($irp->Utils->iqs('irpRewriteThreshold', 300));
        $irp->Options->setHookPriority($irp->Utils->iqs('irpHookPriority', 99999));
        $irp->Options->setRewriteAtEnd($irp->Utils->iqs('irpRewriteAtEnd'));
        $irp->Options->setRewriteStaticLinks($irp->Utils->iqs('irpRewriteStaticLinks'));
        $irp->Options->setPlaceInsideSpanElements($irp->Utils->iqs('irpPlaceInsideSpanElements'));
        $irp->Options->setDoNotIncludeCssInBox($irp->Utils->iqs('irpDoNotIncludeCssInBox'));

        $irp->Options->setEngineSearch($irp->Utils->iqs('irpEngineSearch', IRP_ENGINE_SEARCH_CATEGORIES_TAGS));

        $options = $irp->Options->getRewritePostTypes();
        foreach ($options as $k => $template) {
            $template = intval($irp->Utils->qs('irpRewritePostType_' . $k, 0));
            $options[$k] = $template;
        }
        $irp->Options->setRewritePostTypes($options);

        $options = $irp->Options->getMetaboxPostTypes();
        foreach ($options as $k => $template) {
            $template = intval($irp->Utils->qs('metabox_' . $k, 0));
            $options[$k] = $template;
        }
        $irp->Options->setMetaboxPostTypes($options);
    }

    $c=wp_count_posts()->publish;
    $t=$irp->Options->getMaxExecutionTime();
    if($t>0) {?>
        <p style="width:auto; font-style: italic;"><?php $irp->Lang->P('PreviewSectionMaxTime', $t, $c)?></p>    
    <?php }

    $irp->Form->p('GeneralSection');
    $args=array(
        'class'=>'irp-hideShow irp-checkbox'
        , 'irp-hideIfTrue'=>'false'
        , 'irp-hideShow'=>'irp-active-box'
    );
    $irp->Form->checkbox('irpActive', $irp->Options->isActive(), 1, $args);
    $args=array(
        'id'=>'irp-active-box'
        , 'name'=>'irp-active-box'
        , 'style'=>'margin-top:10px;'
    );
    $irp->Form->divStarts($args);
    {
        $template=$irp->Options->getTemplateStyle();
        $prefix='template-';
        $irp->Form->text($prefix.'ctaText', $irp->Utils->get($template, 'ctaText'));
        $options=$irp->HtmlTemplate->getTemplatesNames();
        $irp->Form->select($prefix.'template', $irp->Utils->get($template, 'template'), $options);
        $irp->Form->number($prefix.'boxOpacity', $irp->Utils->get($template, 'boxOpacity'));
            $irp->Form->text('marginTop', $irp->Options->getMarginTop());
            $irp->Form->text('marginBottom', $irp->Options->getMarginBottom());
        //$options=array('none', 'capitalize', 'uppercase', 'lowercase');
        //$irp->Form->select($t.'textTransform', $irp->Utils->get($v, 'textTransform'), $options);
        $array=array('ctaTextColor', 'postTitleColor', 'boxColor', 'borderColor');
        $blanks=array('(Default)', '(Default)', '(Trasparent)', '(Without)');
        for($i=0; $i<count($array); $i++) {
            $k=$array[$i];
            $v=$blanks[$i];
            $colors=$irp->Options->getColors($v);

            $v=$irp->Utils->get($template, $k);
            $v=$irp->Options->getColor($v);
            $irp->Form->colorSelect($prefix.$k, $v, $colors);
        }
        $array=array('hasShadow', 'hasPoweredBy');
        foreach($array as $k) {
            $irp->Form->checkbox($prefix.$k, $irp->Utils->iget($template, $k));
        }
        ?>
        <div style="padding-left:10px; padding-right:10px; border:1px dashed #444444">
            <?php $irp->Form->p('PreviewSection'); ?>
            <p id="relatedBoxExample" style="width:auto;"></p>
        </div>
        <?php
        $irp->Form->p('RewriteSection');
        $args=array(
            'class'=>'irp-hideShow irp-checkbox'
            , 'irp-hideIfTrue'=>'false'
            , 'irp-hideShow'=>'irp-rewrite-box'
        );
        $irp->Form->checkbox('irpRewriteActive', $irp->Options->isRewriteActive(), 1, $args);
        $args=array('id'=>'irp-rewrite-box', 'name'=>'irp-rewrite-box', 'style'=>'margin-top:10px;');
        $irp->Form->divStarts($args);
        {
            $options=array();
            $options[]=array('id'=>1, 'name'=>1);
            $options[]=array('id'=>2, 'name'=>2);
            $options[]=array('id'=>3, 'name'=>3);
            $irp->Form->select('irpRewriteBoxesCount', $irp->Options->getRewriteBoxesCount(), $options);
            $irp->Form->number('irpRewritePostsDays', $irp->Options->getRewritePostsDays());
            $irp->Form->number('irpRewriteThreshold', $irp->Options->getRewriteThreshold(), $args);
            $irp->Form->number('irpHookPriority', $irp->Options->getHookPriority());
            $irp->Form->checkbox('irpRewriteAtEnd', $irp->Options->isRewriteAtEnd());
            $irp->Form->checkbox('irpRewriteStaticLinks', $irp->Options->isRewriteStaticLinks());
            $irp->Form->checkbox('irpPlaceInsideSpanElements', $irp->Options->isPlaceInsideSpanElements());
            $irp->Form->checkbox('irpDoNotIncludeCssInBox', $irp->Options->isDoNotIncludeCssInBox());

            $irp->Form->p('');

            $options=$irp->Options->getRewritePostTypes();
            $types=$irp->Utils->query(IRP_QUERY_POST_TYPES);
            foreach($types as $v) {
                $v=$v['name'];
                $irp->Form->checkbox('irpRewritePostType_'.$v, $options[$v]);
            }
        }
        $irp->Form->divEnds();

        $irp->Form->p('EngineSection');
        $irp->Form->divStarts();
        {
            $options=array(
                IRP_ENGINE_SEARCH_CATEGORIES_TAGS
                , IRP_ENGINE_SEARCH_CATEGORIES
                , IRP_ENGINE_SEARCH_TAGS
            );
            $irp->Form->select('irpEngineSearch', $irp->Options->getEngineSearch(), $options);
            $options=array();
            $options[]=array('id'=>'dofollow', 'name'=>'dofollow');
            $options[]=array('id'=>'nofollow', 'name'=>'nofollow');
            $k='linkRel';

            $irp->Form->select($prefix.$k, $irp->Utils->get($template, $k), $options);
            $options=array();
            $options[]=array('id'=>'_blank', 'name'=>'_blank');
            $options[]=array('id'=>'_self', 'name'=>'_self');
            $k='linkTarget';
            $irp->Form->select($prefix.$k, $irp->Utils->get($template, $k), $options);
        }
        $irp->Form->divEnds();

        $irp->Form->p('MetaboxSection');
        $irp->Form->divStarts();
        {
            $metaboxes=$irp->Options->getMetaboxPostTypes();
            $types=$irp->Utils->query(IRP_QUERY_POST_TYPES);
            foreach($types as $template) {
                $template=$template['name'];
                $irp->Form->checkbox('metabox_'.$template, $metaboxes[$template]);
            }
        }
        $irp->Form->divEnds();
    }
    $irp->Form->divEnds();

    $irp->Form->nonce('irp_settings');
    irp_notice_pro_features();
    $args=array('id'=>'boxDontSave', 'style'=>'display:none;');
    $irp->Form->divStarts($args);
    {
        $irp->Form->p('Notice.DontSave');
    }
    $irp->Form->divEnds();
    $irp->Form->submit('Save');

    $irp->Form->formEnds(false);
    $irp->Form->helps=FALSE;

	$args=array('id'=>'irp-sidebar', 'style'=>'float:left; max-width: 250px; margin-left:10px');
	$irp->Form->divStarts($args);

	$count=$irp->Tabs->getPluginsCount();
	$plugins=array();
	while(count($plugins)<2) {
		$id=rand(1, $count);
		if(!isset($plugins[$id])) {
			$plugins[$id]=$id;
		}
	}

	$count=$irp->Tabs->drawContactUsWidget();
	foreach($plugins as $id) {
		$count=$irp->Tabs->drawPluginWidget($id);
	}

    $irp->Form->divEnds();

}

function irp_notice_pro_features() {
    global $irp;

    ?>
    <br/>
    <div class="message updated below-h2" style="max-width:600px;">
        <div style="height:10px;"></div>
        <?php
        $i=1;
        while($irp->Lang->H('Notice.ProHeader'.$i)) {
            $irp->Lang->P('Notice.ProHeader'.$i);
            echo '<br/>';
            ++$i;
        }
        $i=1;
        ?>
        <br/>
        <?php
        /*
        $options = array('public' => TRUE, '_builtin' => FALSE);
        $q=get_post_types($options, 'names');
        if(is_array($q) && count($q)>0) {
            sort($q);
            $q=implode(', ', $q);
            $q='(<b>'.$q.'</b>)';
        } else {
            $q='';
        }*/

        while($irp->Lang->H('Notice.ProFeature'.$i)) { ?>
            <div style="clear:both; margin-top: 2px;"></div>
            <div style="float:left; vertical-align:middle; height:24px; margin-right:5px;">
                <img src="<?php echo esc_url(IRP_PLUGIN_IMAGES)?>tick.png" />
            </div>
            <div style="float:left; vertical-align:middle; height:24px;">
                <?php $irp->Lang->P('Notice.ProFeature'.$i)?>
            </div>
            <?php ++$i;
        }
        ?>
        <div style="clear:both;"></div>
        <div style="height:10px;"></div>
        <div style="float:right;">
            <?php
            $url=IRP_INTELLYWP_SITE.IRP_PLUGIN_SLUG.'/?utm_source=free-users&utm_medium=irp-cta&utm_campaign=IRP';
            ?>
            <a href="<?php echo esc_url($url)?>" target="_blank">
                <b><?php $irp->Lang->P('Notice.ProCTA')?></b>
            </a>
        </div>
        <div style="height:10px; clear:both;"></div>
    </div>
    <br/>
<?php }
