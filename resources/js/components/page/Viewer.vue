<template>
	<div id="app">
		<label for="file-upload" class="custom-file-upload">
			Open Word
		</label>
		<input
			id="file-upload"
			type="file"
			@change="openDocument"
			class="btn"
		/>
		<div class="container">
			<div class="row">
				<div class="col-md-8">
					<PSPDFKitContainer :wordFile="wordFile" @loaded="handleLoaded" />

				</div>
			</div>
		</div>
		<div class="sample">

		</div>
		<!-- <v-chip> hello </v-chip> -->
	</div>
</template>

<script>
import PSPDFKitContainer from './Docviewer.vue';

export default {
	data() {
		return {
			wordFile: this.wordFile ||"data:application/pdf;base64"
		};
	},
	/**
	 * Render the `PSPDFKitContainer` component.
	 */
	components: {
		PSPDFKitContainer,
	},
	/**
	 * Our component has two methods — one to check when the document is loaded, and the other to open the document.
	 */
	methods: {
		handleLoaded(instance) {
			console.log(instance.exportPDF())
			instance.exportPDF();
			// console.log('PSPDFKit has loaded: ', instance);
			// Do something.
		},

		openDocument() {
			// console.log(event.target.files[0])
			// 	PSPDFKit.unload('.sample');
			// 	PSPDFKit.load({
			// 	container:".sample",
			// 	document: window.URL.createObjectURL(event.target.files[0],),
			// 	licenseKey: "ATi46klU5_QpWWkkfceNCcIkMuN6chgNJ5QOkbLZ7QmbfS1V2qgdRWRHyH2f3gTDcvCjTTUQPa3GqgWzEPC6DKkjTvzbZv75wRbuYAniIHLRddCgA2zP7AHnNMCGms_7Pl9WOe9A5hcbEiF_EpgJQsDRpvEHm3VxcYz2uvWJFecL-wAWzlYAGpE6_sppFu4r66dxAfn4vgEEclJ4"
			// 	})
				
			// 	.then((instance)=> {
			// 		return instance.exportPDF();
			// 		// const blob = new Blob([buffer], { type: "application/pdf" });
			// 		// const objectUrl = window.URL.createObjectURL(blob);
			// 		// // downloadPdf(objectUrl);
			// 		// window.URL.revokeObjectURL(objectUrl);
			// 		// console.log(objectUrl)
			// 	}).catch((err)=>{
			// 		console.log(err)
			// 	});
			// To access the Vue instance data properties, use `this` keyword.
			if (this.wordFile) {
				
				window.URL.revokeObjectURL(this.wordFile);
			}
			this.wordFile = window.URL.createObjectURL(
				event.target.files[0],
			);
		},
	},
};
</script>

<style>
#app {
	text-align: center;
	color: #2c3e50;
}

body {
	margin: 0;
}

input[type='file'] {
	display: none;
}

.custom-file-upload {
	border: 1px solid #ccc;
	border-radius: 4px;
	display: inline-block;
	padding: 6px 12px;
	cursor: pointer;
	background: #4a8fed;
	padding: 10px;
	color: #fff;
	font: inherit;
	font-size: 16px;
	font-weight: bold;
}

.sample {
	height: 100vh;
	/* width: 200px; */
}

</style>