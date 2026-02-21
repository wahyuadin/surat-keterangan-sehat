<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Provider;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToCollection, WithHeadingRow
{
  public function collection(Collection $rows)
  {
    foreach ($rows as $row) {
      $provider = Provider::where('clinic_name', $row['nama_klinik'])->first();
      if (!$provider) continue;

      $row = $row->toArray();
      if (
        !isset($row['username']) ||
        !isset($row['alamat_email']) ||
        !isset($row['password'])
      ) continue;

      if (!$row['username'] || !$row['alamat_email'] || !$row['password']) continue;

      if (User::where('username', $row['username'])->exists()) continue;
      if (User::where('email', $row['alamat_email'])->exists()) continue;

      try {
        DB::beginTransaction();
        User::create([
          'clinic_id' => $provider->clinic_id,
          'username' => $row['username'],
          'nama' => $row['nama_lengkap'],
          'email' => $row['alamat_email'],
          'password' => Hash::make($row['password']),
        ]);
        DB::commit();
      } catch (\Throwable $th) {
        DB::rollBack();
        logger()->error('Gagal impor user: ' . $th->getMessage());
      }
    }
  }
}
