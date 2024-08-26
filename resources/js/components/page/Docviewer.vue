<template>
	<div>
	<div class="doc-container"></div>
	
	</div>
</template>

<script>


export default {
	name: 'PSPDFKit',
	/**
	 * The component receives the `pdfFile` prop, which is of type `String` and is required.
	 */
	props: {
		wordFile: {
			type: String,
			required: true,
		},
	},
	/**
	 * We wait until the template has been rendered to load the document into the library.
	 */
	mounted() {
		this.loadPSPDFKit().then((instance) => {
			// console.log("intance :",instance)
			// instance.exportPDF({
			// 		outputFormat: {
			// 			conformance: PSPDFKit.Conformance.PDFA_4F
			// 		}
			// 		})
			this.$emit('loaded', instance);
			
		});
	},
	/**
	 * We watch for `wordFile` prop changes and trigger unloading and loading when there's a new document to load.
	 */
	watch: {
		wordFile(val) {
			if (val) {
				this.loadPSPDFKit();
			}
		},
	},
	/**
	 * Our component has the `loadPSPDFKit` method. This unloads and cleans up the component and triggers document loading.
	 */
	methods: {
		async loadPSPDFKit() {
			PSPDFKit.unload('.doc-container');
				const initialViewState = new PSPDFKit.ViewState({
					readOnly: true,
				});
			return PSPDFKit.load({
				licenseKey:'ATi46klU5_QpWWkkfceNCcIkMuN6chgNJ5QOkbLZ7QmbfS1V2qgdRWRHyH2f3gTDcvCjTTUQPa3GqgWzEPC6DKkjTvzbZv75wRbuYAniIHLRddCgA2zP7AHnNMCGms_7Pl9WOe9A5hcbEiF_EpgJQsDRpvEHm3VxcYz2uvWJFecL-wAWzlYAGpE6_sppFu4r66dxAfn4vgEEclJ4',
				// To access the `pdfFile` from props, use `this` keyword.
				document: this.wordFile,
                // disableWebAssemblyStreaming:true,
				container: '.doc-container',
				initialViewState,
				
                // baseUrl:"/public/",
                
            }).then((instance) => {
				instance.exportPDF();
			});
            // console.log(PSPDFKit)
		},
	},


	/**
	 * Clean up when the component is unmounted so it's ready to load another document (not needed in this example).
	 */
	beforeUnmount() {
		PSPDFKit.unload('.doc-container');
	},
};
</script>

<style scoped>
.doc-container {
	height: 100vh;
	/* width: 200px; */
}


</style>

