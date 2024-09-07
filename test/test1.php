<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script src="crypto-js.min.js"></script>
  <script src="jquery-3.5.1.min.js"></script>
</head>

<body>
  <input type="text" id="encrypted" value="5rMnvCTn8pdQp/xBG8JVPKsd/op0XnLE0t+juWpvE8Q=::1Ot8kKueTMJ64+txun5vrA==">
  <button id="decrypt-btn">Decrypt</button>
  <div id="displayDecryptedText"></div>
  <div>
    <input list="browsers" name="browser" id="browser">

    <datalist id="browsers">
      <option value="Chrome">
      <option value="Firefox">
      <option value="Safari Browser">
      <option value="Edge Browser">
      <option value="Opera Mini">
    </datalist>

  </div>

  <script>
    $(document).ready(function () {
      $('#decrypt-btn').click(async function () {
        console.log("okay");
        var encryptedText = $('#encrypted').val();
        const key = "hrd@CSCRO6";

        decryptData(encryptedText, key).then(decrypted => {
          console.log(decrypted);
        }).catch(error => {
          console.error(error);
        });;

        // $("#displayDecryptedText").html(decryptedText);
        // console.log(decryptedText);
      });
    });

    // document.getElementById("decrypt-btn").addEventListener("click", function () {
    //   console.log("Okay");
    //   var encrypted = document.getElementById("encrypted").value;
    //   document.getElementById("displayDecryptedText").innerHTML = decryptText(encrypted, "hrd@CSCRO6") == "" ? "None" : decryptText(encrypted, "hrd@CSCRO6");
    // });

    function decryptData(encryptedData, password) {
      try {
        // Split encrypted data and IV
        const [encrypted, iv] = encryptedData.split('::');

        // Convert IV from base64 to Uint8Array
        const ivBuffer = Uint8Array.from(atob(iv), c => c.charCodeAt(0));

        // Derive 256-bit AES key from password using PBKDF2
        const salt = new Uint8Array(16); // 16-byte salt
        crypto.getRandomValues(salt);
        return window.crypto.subtle.importKey(
          "raw",
          new TextEncoder().encode(password),
          { name: "PBKDF2" },
          false,
          ["deriveKey"]
        ).then(passwordKey => {
          return window.crypto.subtle.deriveKey(
            {
              name: "PBKDF2",
              salt: salt,
              iterations: 1000,
              hash: "SHA-256"
            },
            passwordKey,
            { name: "AES-CBC", length: 256 },
            false,
            ["decrypt"]
          );
        }).then(cryptoKey => {
          // Decrypt
          return window.crypto.subtle.decrypt(
            { name: "AES-CBC", iv: ivBuffer },
            cryptoKey,
            Uint8Array.from(atob(encrypted), c => c.charCodeAt(0))
          ).then(decryptedBuffer => {
            const decrypted = new TextDecoder().decode(decryptedBuffer);
            return decrypted;
          }).catch(error => {
            console.error('Error during decryption:', error);
            throw error; // re-throw the error
          });
        });
      } catch (error) {
        console.error('Error in decryptData:', error);
        return "Error decrypting data";
      }
    }
  </script>
</body>

</html>