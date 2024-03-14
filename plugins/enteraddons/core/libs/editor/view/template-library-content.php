<div class="enteraddons-dialog-lightbox-message-inner">
	<div class="enteraddons-lightbox-message-cat-filter">
		<ul>
			<li class="single-filter" v-for="cat in set_filters">
				<span class="category-list-item" :key="cat.slug" @click="getItemsByFilter(cat.slug)" >{{cat.title}}</span>
			</li>
		</ul>
	</div>

	<div class="enteraddons-template-library-templates-container">
		<div class="enteraddons-dialog-lightbox-message-search">
			<div class="enteraddons-search-inner">
				<select :value="set_sort_search" @input="sortSearch" class="enteraddons-item-sort-by">
					<option value=""><?php esc_html_e( 'SORT BY', 'enteraddons' ); ?></option>
					<option value="lite"><?php esc_html_e( 'Sort by Free', 'enteraddons' ); ?></option>
					<option value="pro"><?php esc_html_e( 'Sort by Pro', 'enteraddons' ); ?></option>
				</select>
				<input type="text" :value="set_text_search" @input="textSearch" placeholder="Search" class="enteraddons-text-search
				elementor-template-library-filter-text" />
			</div>
		</div>
		<div class="enteraddons-template-library-items">
			<div class="enteraddons-template-library-templates-container-inner">
			<div v-for="item in get_templates" class="elementor-template-library-template elementor-template-library-template-remote elementor-template-library-template-page">

				<div class="enteraddons-lightbox-message-single-item">
					<div class="item-inner">
						<span class="ea-editor-tp-ribbon">{{ item.package == 'pro' ? 'Pro': 'Free' }}</span>
						<div class="item-overlay"><span @click="templatePreview({id:item.template_id,url:item.preview_url,package:item.package})" class="enteraddon-preview"><i class="eicon-search"></i></span></div>
						<img v-bind:src="item.preview_image" />
					</div>
					<div class="item-footer">
						<span class="enteraddons-template-title">{{item.title}}</span>
						<a class="elementor-template-library-template-action enteraddons-template-insert elementor-button" v-if="item.package == 'pro' && set_version_type == 'PRO' || item.package != 'pro'" >
							<i class="eicon-file-download" aria-hidden="true"></i>
							<span class="elementor-button-title" @click="insertTemplate(item.template_id)"><?php esc_html_e( 'Insert', 'enteraddons' ); ?></span>
						</a>
						<div v-if="item.package == 'pro' && set_version_type == 'LITE'" class="enteraddons-template-gopro">
							<a  :href="pro_url" target="_blank">
								<i class="eicon-external-link-square" aria-hidden="true"></i>
								<span class="elementor-button-title"><?php esc_html_e( "Go Pro", "enteraddons" ); ?></span>
							</a>
						</div>
					</div>
				</div>

			</div>
			</div>
		</div>
	</div>

</div>