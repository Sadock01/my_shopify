<!DOCTYPE html>
<html>
<head>
    <title>Test Upload</title>
</head>
<body>
    <h1>Test Upload Simple</h1>
    
    <form action="/test-upload-simple" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="payment_proof" required>
        <button type="submit">Upload Test</button>
    </form>
    
    <hr>
    
    <h2>Diagnostic</h2>
    <a href="/test-upload-debug" target="_blank">Voir les informations de diagnostic</a>
</body>
</html>
