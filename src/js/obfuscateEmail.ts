// from: https://andrewlock.net/simple-obfuscation-of-email-addresses-using-javascript/
// edited for typescript compatibility and es6 variables

function encodeEmail(email: string, key: number) {
	// Hex encode the key
	const encodedKey = key.toString(16);

	// ensure it is two digits long
	let encodedString = make2DigitsLong(encodedKey);

	// loop through every character in the email
	for (let n = 0; n < email.length; n++) {
		// Get the code (in decimal) for the nth character
		const charCode = email.charCodeAt(n);

		// XOR the character with the key
		const encoded = charCode ^ key;

		// Hex encode the result, and append to the output string
		const value = encoded.toString(16);
		encodedString += make2DigitsLong(value);
	}
	return encodedString;
}

function make2DigitsLong(value: string) {
	return value.length === 1 ? "0" + value : value;
}

export default encodeEmail;
