1. Ada beberapa kerentanan keamanan umum di aplikasi web, dan bagaimana Anda dapat menguranginya dalam proyek CodeIgniter atau Laravel Anda? 
*Jawaban:*
Pertama memastikan dulu apa yang menjadi celah dalam project Laravel tersebut, kemudian menganalisa cara kerja celah tersebut bekerja. Jika celahnya dari PHP itu sendiri maka perlu dilakukan update pada PHP seperti case baru-baru ini PHPShell. Jika celahnya dari pengkodean maka diperlukan pembenahan pada code, contohnya seperti SQL Injection solusi memastikan query tidak menggunakan rawQuery, bruteforce pada password ini solusi memberi jeda/cooldown akun user yang terdampak, DDOS dengan hit API GET secara berlebihan ditangani dengan cara Rate Limit.

2. Jelaskan pentingnya caching dalam pengembangan web. Bagaimana Anda dapat mengimplementasikan caching dalam aplikasi CodeIgniter dan Laravel?
*Jawaban:*
Caching bertujuan untuk mengurangi beban database agar data yang diberikan lebih cepat dan tidak membuat database down. Caching ini cara kerjanya dengan menyimpan ke memori server sementara dengan aturan waktu sesuai yang dikodekan, contohnya 5 menit. Ketika ada user yang hit API /api/product dan dia yang pertama maka api akan meminta data ke database, lalu sisi server akan menyimpannya ke memory lalu user kedua yang hit akan dapat data langsung tanpa perlu memanggil database lagi.
Berikut adalah code untuk implementasinya
```
Cache::remember('product_list_all', 60*5, function () {
    return Product::with('category')->where('is_deleted', false)->get()->toArray();
});
```
Pada kode tersebut menjelaskan bahwa data yang diambil akan disimpan ke Cache dengan nama 'product_list_all' selama 60x5 atau 5 menit. Tetapi Cache saling terkait dengan perubahan data, jika pada post data tidak ada triger untuk menghapus cache tersebut, maka data tidak akan update meskipun ada data terbaru.
```
cache()->forget('product_list_all');
```
Maka perlu memberikan kode forget('nama_cache') di setiap setelah store, update, delete, atau patch.

3. Bagaimana Anda mengoptimalkan query database untuk performa yang lebih baik dalam aplikasi CodeIgniter atau Laravel? 
*Jawab*
Untuk mengoptimalkan query database performa lebih baik maka sudah dipastikan tidak tereksekusi N+1, dimana 1 Fungsi pemanggilan data yang hanya perlu memanggil 1 query menjadi beranak. Contohnya user punya lebih dari 2 Role dan 1 Role punya banyak Permission. Disini jika perlu mengetahui pemanfaatan pivoting yang dijoinkan agar lebih mudah di panggil dalam 1 query.
``` 
$permissions = DB::table('user')
    ->join('role_user', 'user.id', '=', 'role_user.user_id')
    ->join('role_permission', 'role_user.role_id', '=', 'role_permission.role_id')
    ->join('permission', 'role_permission.permission_id', '=', 'permission.id')
    ->where('user.email', $request->email)
    ->select('permission.code')
    ->distinct()
    ->pluck('code') 
    ->map(fn($code) => strtoupper($code))
    ->toArray();
```

4. Jelaskan perbedaan antara HTTP dan HTTPS, dan mengapa menggunakan HTTPS direkomendasikan untuk aplikasi web.
*Jawab*
Perbedaannya berada pada data metode HTTP tidak terenkripsi ketika data keluar / diambil, sedangkan HTTPS (secure) data sebelum keluar / diambil dibungkus dulu dengan cara encrypt data. Sangat direkomendasikan karena agar data aplikasi yang diambil / keluar sampai ke user dengan sesuai, yang dimana tidak ada middleman yang merubah data tersebut sebelum sampai ke tujuan. 

5. Apa itu RESTful API, dan bagaimana Anda dapat mengimplementasikan RESTful API di Laravel atau CodeIgniter?
Restful API adalah metode komunikasi yang menggunakan HTTP GET, POST, PUT, PATCH, DELETE yang berisikan method pengolahan data CRUD (Create, Read, Update, Delete). 
Cara mengimplementasikannya pada Laravel bisa membuat Route Api dulu, kemudian disetiap endpoin/route memiliki metode controller bisa dibuat dengan cara: 
```
php artisan make:controller Api/ProductController
``` 
pada controller tersebut berisikan method Create Data, Read Data, Update Data, Delete data.  

6. Bagaimana Anda menangani validasi formulir di CodeIgniter dan Laravel? Dapatkah Anda menjelaskan skenario di mana Anda mungkin menggunakan aturan validasi kustom? 
Untuk menangani validasi formulir pada Laravel maka diperlukan validate request data sebelum atau setelah masuk ke controller. berikut cara membuat validation agar sebelum masuk controller :  
```
php artisan make:request StoreProductRequest
```
Kemudian membuat Rule custom dengan berikut:
```
php artisan make:rule BadWordCheck
```
Contoh sekenario badword pada data deskripsi Product Ecommerce. 
saya akan menyesuaikan Controller API Product data store POST Product menjadi (StoreProduct $Request)
kemudian pada validasi file StoreProductRequest menambahkan kolom tabel request dan pada validasi deskripsi ditambahkan new BadWordCheck(). Lalu pada rule dilakukan penyesuaian logic seperti berikut :
```
public function validate(string $attribute, mixed $value, Closure $fail): void
{
    if (stripos($value, 'bajakan') !== false) {
        $fail("Barang bajakan tidak boleh dijual di sistem ini.");
    }
    if (stripos($value, 'palsu') !== false) {
        $fail("Barang palsu tidak boleh dijual di sistem ini.");
    }
    if (stripos($value, 'kw') !== false) {
        $fail("Barang kw tidak boleh dijual di sistem ini.");
    }
}
```
Ketika kata terdeteksi maka POST data Product akan tertolah dengan detail yang berada di $fail

7. Apa peran Composer dalam pengembangan PHP, dan bagaimana itu digunakan dalam proyek CodeIgniter 4 atau Laravel? 
*Jawab*
Peran composer adalah sebagai management dependency dimana untuk mengolah library, package, dan script dari thrid party. Pengunaannya berada di file yang bernama composer.json, dan memiliki composer.lock yang bertujuan untuk mengunci versi dependency agar mempermudah pengerjaan pengkodean php dalam satu team dengan dependency yang sama. 

8. Jelaskan middleware dalam Laravel dan bagaimana penggunaannya dalam proses penanganan HTTP requests. 
 *Jawab*
 Middleware bisa dibilang satpam komplek program yang bertugas untuk mengecek kurir dan pesan yang dibawa masuk dimana memastikan kode rahasi dan stampel sesuai dengan ketentuan komplek.
 Penggunaannya pada penanganan HTTP Request itu ketika hit GET /api/product, akan berhenti ke middleware terlebih dahulu untuk diperiksa header data yang dibawa dan stampelnya. Ketika sesuai ada lagi yang diperiksa, rolenya punya permission tersebut tidaknya.
 Ketika sesuai maka data akan lanjut masuk ke Form Validation, jika tidak ada form maka langsung ke Controller URL yang dituju. Sebaliknya jika tidak sesuai maka langsung direject tidak punya authorization.
   
9. Bagaimana Laravel menangani keamanan pada aplikasi web, termasuk perlindungan terhadap serangan CSRF (Cross-Site Request Forgery)? 
*Jawab*    
Pada laravel dapat menanganinya dengan cara menambahkan middleware contohnya bisa menggunakan Sactum yang pakai barrier token pada headernya dan itu sudah sangat aman, kecuali jika menggunakan Sactum yang disimpan pada Cookies. CSRF itu sendiri mencuri token melalui pembacaan Cookies session, sedangkan barrier token itu disimpan pada localStorage atau memory sehingga tidak dicuri.
jika menggunakan Session Cookies maka perlu menggunakan XSRF-TOKEN, dimana pada middleware laravel bawaan terdapat 'VerifyCsrfToken' untuk memverifikasi dan memvalidasi. 

10.  Bagaimana sistem migrasi (migration) bekerja dalam Laravel / Codeigniter 4 dan mengapa mereka penting dalam pengembangan basis data?
*Jawab*
Sistem migrasi pada laravel bekerja dengan cara memberikan tanda waktu pada depan nama file migrasi dimana waktu dibuat dan sebagai acuan urutan eksekusi. File migrasi bisa dibuat dengan cara :
```
php artisan make:migration create_product_table
``` 
pada file code tersebut sudah terdapat template 2 method yaitu up() dan down().
didalam up() bertujuan untuk instruksi apa yang harus dijalankan saat migrasi, sedangkan down() ini untuk kebalikan up, yaitu menghapus semua eksekusi yang dilakukan up().
Migrasi ini penting dikarenakan sebagai pencatatan riwayat eksekusi instruksi terhadap Tabel database, dan juga perubahan pada tabel lebih simple sehingga tidak perlu ke database.


11. Jelaskan penggunaan Laravel Task Scheduling dan manfaatnya dalam pekerjaan yang dijadwalkan.
*Jawab*
Penggunaan laravel task scheduling masih memerlukan aplikasi pada linux yaitu CRON JOB, dimana dapat membuat scheduling code seperti loging, backup data, daily/weekly/monthly eksekusi code ataupun membersihkan data yang sudah melebihi masa retensi.
Manfaatnya menggunakan task scheduling ini:
- Dapat menjaga data dengan schedule daily backup. 
- Memastikan storage terjaga dengan cara menghapus data yang melebihi retensi.
- Dapat mengeksekusi program secara daily/weekly/monthly contohnya pengajian, pembayaran. 
