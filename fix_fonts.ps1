$files = @(
    'C:\laragon\www\ybm-umkm\resources\views\guest\katalog.blade.php',
    'C:\laragon\www\ybm-umkm\resources\views\guest\checkout.blade.php',
    'C:\laragon\www\ybm-umkm\resources\views\guest\detail-produk.blade.php',
    'C:\laragon\www\ybm-umkm\resources\views\guest\detail-umkm.blade.php',
    'C:\laragon\www\ybm-umkm\resources\views\guest\umkm.blade.php',
    'C:\laragon\www\ybm-umkm\resources\views\guest\beranda.blade.php'
)

foreach ($f in $files) {
    $c = Get-Content $f -Raw -Encoding UTF8
    $c = $c.Replace("font-family: 'Fraunces', serif;", "font-family: 'Poppins', sans-serif;")
    $c = $c.Replace("font-family: var(--font-display);", "font-family: 'Poppins', sans-serif;")
    $c = $c.Replace("font-family: var(--font-body);", "font-family: 'Poppins', sans-serif;")
    $c = $c.Replace("font-family: 'Plus Jakarta Sans', sans-serif;", "font-family: 'Poppins', sans-serif;")
    $c = $c.Replace("font-family: 'DM Sans', system-ui, sans-serif;", "font-family: 'Poppins', sans-serif;")
    $c = $c.Replace("font-family: 'Playfair Display', Georgia, serif;", "font-family: 'Poppins', sans-serif;")
    $c = $c.Replace("--font-display: 'Playfair Display', Georgia, serif;", "--font-display: 'Poppins', sans-serif;")
    $c = $c.Replace("--font-body: 'DM Sans', system-ui, sans-serif;", "--font-body: 'Poppins', sans-serif;")
    $c = $c.Replace("@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600;700&display=swap');", "")
    Set-Content $f $c -NoNewline -Encoding UTF8
    Write-Host "Updated: $f"
}
Write-Host "Done!"
