<div class="wrap">
	<div class="icon32 icon32-tsp" id="icon-options-general"></div>
	<h2>{$title}</h2>
	<div class="accordion" id="accordionExample">
        {if $pro_total > 0}
        <div class="card">
			<div class="card-header" id="headingOne">
				<h5 class="mb-0">
					<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
						Professional Plugins
					</button>
				</h5>
			</div>

			<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
				<div class="card-body">
                    {if $pro_active_count > 0}
                        <div>
                            {foreach $pro_active_plugins as $pro_active}
                                <dl style="padding-bottom:10px;">
                                    <dt><i class="fa fa-check-circle success"></i><strong>{$pro_active.title}</strong> - <em>{$pro_active.desc}</em></dt>
                                    <dd>
                                        <a href="{$pro_active.more_url}" target="_blank">Read More</a>
                                        <a href="{$pro_active.settings}">Settings</a>
                                    </dd>
                                </dl>
                            {/foreach}
                        </div>
                    {/if}
                    {if $pro_installed_count > 0}
                        <div>
                            {foreach $pro_installed_plugins as $pro_installed}
                                <dl style="padding-bottom:10px;">
                                    <dt><i class="fa fa-times-circle fail"></i><strong>{$pro_installed.title}</strong> - <em>{$pro_installed.desc}</em></dt>
                                    <dd>
                                        <a href="{$pro_installed.more_url}" target="_blank">Read More</a>
                                    </dd>
                                </dl>
                            {/foreach}
                        </div>
                    {/if}
                    {if $pro_recommend_count > 0 }
                        <div>
                            {foreach $pro_recommend_plugins as $pro_recommend }
                                <dl style="padding-bottom:10px;">
                                    <dt><i class="fa fa-thumbs-up"></i><strong>{$pro_recommend.title}</strong> - <em>{$pro_recommend.desc}</em></dt>
                                    <dd>
                                        <a href="{$pro_recommend.more_url}" target="_blank">Read More</a>
                                        <a href="{$pro_recommend.store_url}" target="_blank">Purchase</a>
                                    </dd>
                                </dl>
                            {/foreach}
                        </div>
                    {/if}
				</div>
			</div>
		</div>
        {/if}
        {if $free_total > 0}
		<div class="card">
			<div class="card-header" id="headingTwo">
				<h5 class="mb-0">
					<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
						Free Plugins
					</button>
				</h5>
			</div>
			<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
				<div class="card-body">
                    {if $free_active_count > 0}
                        <div>
                            {foreach $free_active_plugins as $free_active}
                                <dl style="padding-bottom:10px;">
                                    <dt><i class="fa fa-check-circle success"></i><strong>{$free_active.title}</strong> - <em>{$free_active.desc}</em></dt>
                                    <dd>
                                        <a href="{$free_active.more_url}" target="_blank">Read More</a>
                                        <a href="{$free_active.settings}">Settings</a>
                                    </dd>
                                </dl>
                            {/foreach}
                        </div>
                    {/if}
                    {if $free_installed_count > 0}
                        <div>
                            {foreach $free_installed_plugins as $free_installed}
                                <dl style="padding-bottom:10px;">
                                    <dt><i class="fa fa-times-circle fail"></i><strong>{$free_installed.title}</strong> - <em>{$free_installed.desc}</em></dt>
                                    <dd>
                                        <a href="{$free_installed.more_url}" target="_blank">Read More</a>
                                    </dd>
                                </dl>
                            {/foreach}
                        </div>
                    {/if}
                    {if $free_recommend_count > 0 }
                        <div>
                            {foreach $free_recommend_plugins as $free_recommend }
                                <dl style="padding-bottom:10px;">
                                    <dt><i class="fa fa-thumbs-up"></i><strong>{$free_recommend.title}</strong> - <em>{$free_recommend.desc}</em></dt>
                                    <dd>
                                        <a href="{$free_recommend.more_url}" target="_blank">Read More</a>
                                        <a href="{$free_recommend.settings}">Download</a>
                                    </dd>
                                </dl>
                            {/foreach}
                        </div>
                    {/if}
				</div>
			</div>
		</div>
        {/if}
	</div>
</div>
<span style="color: rgb(136, 136, 136); font-size: 10px;">If you have any questions, please contact us via <a target="_blank" href="{$contact_url}">{$contact_url}</a></span>