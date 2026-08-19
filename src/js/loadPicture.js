// hide the picture images until they've loaded
// and set event listeners for their load events
const pictureImages = document.querySelectorAll(".picture-image");
// console.log(pictureImages)

for (const pictureImage of pictureImages) {
	// now applying the --hide class manually, otherwise there's
	// a flash of alt text in firefox
	// pictureImage.classList.add('picture-image--hide');
	loadImage(pictureImage);
}

function loadImage(img) {
	//  check complete and naturalWidth first
	//  if .complete AND naturalWidth is true, assume the image has already loaded
	if (img.complete && img.naturalWidth) {
		img.classList.add("picture-image--show");
		// console.log("image already loaded")
		return;
	}
	// otherwise add the eventlistener with
	// a callback that adds the --show class
	// and removes the --hide class
	img.addEventListener("load", () => {
		img.classList.remove("picture-image--hide");
		img.classList.add("picture-image--show");
		// console.log("image loaded!");
	});
}
