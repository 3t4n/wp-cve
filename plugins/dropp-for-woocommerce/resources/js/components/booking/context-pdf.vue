<template>
	<div class="context-pdf">
		<div class="context-pdf__overlay" v-show="loading">
			<loader></loader>
		</div>

		<ul class="context-pdf__list">
			<li v-if="error">
				Error
			</li>
			<li
				class="pdf-actions"
				v-if="! error"
				v-for="pdf, index in pdfs"
				:key="index"
			>
				<a
					class="pdf-action pdf-action--download"
					target="_blank"
					:href="download_url( pdf.barcode )"
				>{{pdf.label}}</a>
				<a
					v-if="pdf.barcode"
					class="pdf-action pdf-action--delete"
					href="#"
					@click.prevent="delete_pdf( pdf.barcode )"
				>&times;</a>
			</li>
			<li>
				<a
					class="dropp-consignment__action"
					href="#"
					v-html="i18n.extra_pdf"
					@click.prevent="add_pdf"
				></a>
			</li>
		</ul>
	</div>
</template>
<style lang="scss">
	.context-pdf {
		position: relative;
		&__overlay {
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			display: flex;
			justify-content: center;
			align-items: center;

			background-color: rgba(255,255,255,0.5);
		}
		&__list {
			margin-top: 0;
		}
	}
	.pdf-actions {
		display: flex;
		justify-content: space-between;
	}
	.context-pdf .pdf-action--delete {
		color: #900;
	}
</style>
<script>
	import Loader from './loader.vue';
	export default {
		data: function() {
			return {
				i18n: _dropp.i18n,
				pdfs: [],
				loading: false,
				error: false,
			}
		},
		props: [
			'consignment_id'
		],
		mounted: function() {
			this.get_pdfs();
		},
		methods: {
			download_url: function( barcode ) {
				let url = _dropp.ajaxurl + '?action=dropp_pdf_single&consignment_id=' + this.consignment_id;
				if ( barcode ) {
					url += '&barcode=' + barcode;
				}
				return url
			},
			get_pdfs: function() {
				if ( this.loading ) {
					return;
				}
				this.loading = true;
				jQuery.ajax( {
					url: _dropp.ajaxurl,
					method: 'get',
					data: {
						action: 'dropp_get_pdf_list',
						consignment_id: this.consignment_id,
					},
					success: this.success,
					error:   this.error_handler,
				} );
			},
			add_pdf: function() {
				if ( this.loading ) {
					return;
				}
				this.loading = true;
				jQuery.ajax( {
					url: _dropp.ajaxurl,
					method: 'get',
					data: {
						action: 'dropp_add_extra_pdf',
						consignment_id: this.consignment_id,
					},
					success: this.success,
					error:   this.error_handler,
				} );
			},
			delete_pdf: function( barcode ) {
				if ( this.loading ) {
					return;
				}
				this.loading = true;
				jQuery.ajax( {
					url: _dropp.ajaxurl,
					method: 'get',
					data: {
						action: 'dropp_delete_extra_pdf',
						consignment_id: this.consignment_id,
						barcode: barcode
					},
					success: this.success,
					error:   this.error_handler,
				} );
			},
			success: function( data ) {
				this.pdfs = data;
				this.loading = false;
			},
			error_handler: function( jqXHR, textStatus, errorThrown ) {
				console.log( jqXHR );
				console.log( textStatus );
				console.log( errorThrown );
				this.loading = false;
				this.error = true;
			},
		},
		components: {
			Loader,
		},
	};
</script>
