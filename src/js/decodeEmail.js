// from https://andrewlock.net/simple-obfuscation-of-email-addresses-using-javascript/

// edited for es6, and to remove .hidden class once email address has been decoded
// so we don't see the big ugly encoded text

// Find all the elements on the page that use class="eml-protected"
// (leave this as getElementsByClassName - it returns a live list, querySelectorAll is static)
const allElements = document.getElementsByClassName("eml-protected");
// const allElements = document.querySelectorAll(".eml-protected");

// Loop through all the elements, and update them
for (let i = 0; i < allElements.length; i++) {
	updateAnchor(allElements[i]);
}

function updateAnchor(el) {
	// fetch the hex-encoded string
	// (why use innerHTML to get but textContent to set? consider changing)
	const encoded = el.innerHTML;

	// my new stuff
	// const encoded = el.href;
	console.log(el.href);
	// end my stuff

	// const encoded = el.textContent;

	// decode the email, using the decodeEmail() function from before
	const decoded = decodeEmail(encoded);

	// Replace the text (displayed) content
	el.textContent = decoded;

	// Set the link to be a "mailto:" link
	el.href = "mailto:" + decoded;

	// remove the .hidden class (now on the inner span)
	// can we abstract this out somehow?
	el.classList.remove("hidden");
}

function decodeEmail(encodedString) {
	// Holds the final output
	let email = "";

	// Extract the first 2 letters
	const keyInHex = encodedString.substr(0, 2);

	// Convert the hex-encoded key into decimal
	const key = parseInt(keyInHex, 16);

	// Loop through the remaining encoded characters in steps of 2
	for (let n = 2; n < encodedString.length; n += 2) {
		// Get the next pair of characters
		const charInHex = encodedString.substr(n, 2);

		// Convert hex to decimal
		const char = parseInt(charInHex, 16);

		// XOR the character with the key to get the original character
		const output = char ^ key;

		// Append the decoded character to the output
		email += String.fromCharCode(output);
	}
	return email;
}
