### Spesifikasi Development

Project ini dibangun dan dijalankan menggunakan spesifikasi lingkungan berikut:

| Komponen | Versi | Perintah Cek Versi |
| :--- | :--- | :--- |
| **PHP** | `8.5.12` | `php -v` |
| **Composer** | `2.10.3` | `composer -v` |

---

### AI Chat

1. Konsulasi mengenai best practice dan perbandingan arsitektur untuk sistem Role-Based Access Control(RBAC) menggunakan Middleware 'Sactum'
```
    $user = User::with('role.permission')->where('email',$request->email)->first();
    $permissions = $user->role
        ->flatMap(fn($role) => $role->permission)
        ->pluck('code')
        ->map(fn($code) => strtoupper($code))
        ->unique()
        ->values()
        ->toArray();

    $token = $user->createToken(('auth-token'), $permissions)->plainTextToken;

```
*Alasan pakai ai* : 
Mencari cara pengunaan 'Sactum' terhadap kebutuhan User Management yang dimana setiap User memiliki lebih dari satu role atau hanya memiliki satu role
*Logika Penjelasan* : 
Dari potongan kode tersebut dapat diketahui bahwa mengambil data dari tabel User dengan join ke tabel role dan permission dimana dari email request. Kemudian membuat deklarasi $permission kemudian mengambil data Permission dari user role, kemudian deklarasi flat map untuk menampung data dari relasi 'Permission'. kemudian pluck atau mengambil bagian kolom tabel 'code' saja pada flatMap tadi. Lanjut membuat isian permission code tadi menjadi uppercase, yang kemudian unique() ini untuk menghilangkan CODE Permission yang sama. Kemudian mengubah formatnya menjadi Array.

Contoh fungsi flatMap: 
Sebelum: [[SYSTEM:PRODUCT_VIEW, SYSTEM:PRODUCT_EDIT], [SUBSCRIBE:SILVER, SUBSCRIBE:GOLD]]
Setelah: [SYSTEM:PRODUCT_VIEW, SYSTEM:PRODUCT_EDIT, SUBSCRIBE:SILVER, SUBSCRIBE:GOLD]

Kemudian $permission tersebut dimasukkan ke createToken sebagai Sactum Ability yang dimana fungsinya untuk memberikan permission pada fungsi" / endpoint.  

2. Mengganti query pengambilan Permission nomor 1 dari PHP Collection menjadi Query Builder untuk optimasi memory. 

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
*Alasan pakai ai*: 
Belum terbiasa menggunakan laravel, dan belum menemukan sturktur pengunaan database query. Kemudian untuk memastikan pengambilan data ke database yang potensi N+1.
*Logika dan Carakerja*: 
Menggunakan Query Builder pada tabel user dimana : 
- Join tabel role_user berdasarkan data user.id = role_user.user_id
- Join tabel role_permission berdasarkan kesamaan role_user.role_id dengan role_permission.role_id. 
- Join tabel permission berdasarkan kesamaan permission_role.permission_id dengan permission.id
- Mencari data berdasarkan email user request yang sudah tervalidasi auth.
- Select / mengambil kolom 'code' pada tabel permission
- distinct() fungsi ini lebih di database untuk tidak menampilkan data yang sama (unique).
- pluck('code') menghubah hasi query menjadi 1 dimensi
- toArray() => merubah data menjadi array
Menggunakan kode tersebut dikarenakan User::with() PHP collection ini lumayan menguras memori dikarenakan datanya dialokasikan ke model dimana model disimpan di memori. 


