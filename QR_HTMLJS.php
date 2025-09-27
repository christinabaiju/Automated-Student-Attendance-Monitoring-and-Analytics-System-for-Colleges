<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Attendance QR Scanner</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #000000, #4b2e05, #f5f5dc);
      color: white;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      height: 100vh;
      justify-content: center;
    }
    h1 {
      margin-bottom: 1rem;
      font-size: 2.5rem;
    }
    .role-selector {
      margin-bottom: 1rem;
    }
    .role-selector label {
      margin-right: 2rem;
      font-size: 1.2rem;
      cursor: pointer;
    }
    #qr-reader {
      width: 320px;
      max-width: 80vw;
      margin: auto;
      border-radius: 8px;
      box-shadow: 0 0 15px rgba(0,0,0,0.5);
      background: #ffffff30;
      padding: 10px;
    }
    #qr-result {
      margin-top: 1rem;
      font-size: 1.1rem;
      min-height: 2rem;
      text-align: center;
      word-wrap: break-word;
    }
  </style>
  <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
</head>
<body>
  <h1>Attendance QR Scanner</h1>
  <div class="role-selector">
    <label><input type="radio" name="role" value="student" checked /> Student</label>
    <label><input type="radio" name="role" value="faculty" /> Faculty (Admin Access)</label>
  </div>
  <div id="qr-reader"></div>
  <div id="qr-result">Scan a QR code to mark attendance or get admin access.</div>

  <script>
    const resultContainer = document.getElementById('qr-result');
    const qrScanner = new Html5Qrcode("qr-reader");

    const studentRole = document.querySelector('input[name="role"][value="student"]');
    const facultyRole = document.querySelector('input[name="role"][value="faculty"]');

    // Options for scanner box size
    const config = { fps: 10, qrbox: { width: 250, height: 250 } };

  function onScanSuccess(decodedText, decodedResult) {
  try {
    // Validate if decodedText is a URL
    const url = new URL(decodedText);
    // Redirect browser to the scanned URL on the same page
    window.location.href = url.href;
  } catch (e) {
    // If not a valid URL, just show message (optional)
    resultContainer.textContent = `Scanned data: ${decodedText} (not a valid URL)`;
  }
  // Stop scanning after a successful scan
  qrScanner.stop().catch(err => console.log('Failed to stop scanner:', err));
 }


    function onScanFailure(error) {
      // Scan failure feedback ignored for brevity
    }

    function startScanner() {
      qrScanner.start(
        { facingMode: "environment" }, 
        config, 
        onScanSuccess, 
        onScanFailure
      ).catch(err => {
        resultContainer.textContent = `Unable to start scanning: ${err}`;
      });
    }

    // Restart the scanner on role change
    document.querySelectorAll('input[name="role"]').forEach(radio => {
      radio.addEventListener('change', () => {
        resultContainer.textContent = 'Scan a QR code to mark attendance or get admin access.';
        qrScanner.stop().then(() => startScanner()).catch(() => startScanner());
      });
    });

    startScanner();
  </script>
</body>
</html>
