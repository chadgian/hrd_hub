// Create the Html5QrcodeScanner instance
const scanner = new Html5QrcodeScanner("reader", {
  fps: 10,
  qrbox: 250,
  supportedScanTypes: [ Html5QrcodeScanType.SCAN_TYPE_CAMERA ],
  rememberLastUsedCamera: true,
  // aspectRatio: 1.7777778
});

// Get the results element and again button
const resultsElement = document.getElementById("results");
const againButton = document.getElementById("again-btn");

// Define the onScanSuccess function
function onScanSuccess(decodedText, decodedResult) {
  // Create a new paragraph element to display the result
  const newResult = document.createElement("p");
  newResult.innerHTML = decodedText;

  // Add the new result to the results element
  resultsElement.appendChild(newResult);

  // Clear the scanner and show the again button
  scanner.pause();
  againButton.style.display = "block";
}

// Define the scanAgain function
function scanAgain() {
  // Scan again and hide the again button
  scanner.resume();
  againButton.style.display = "none";
}

// Render the scanner with the onScanSuccess function
scanner.render(onScanSuccess);