<?php

namespace App\Services;

use App\Exports\UserExport;
use App\Imports\UserImport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;

class DataUserService
{
    public function create($request)
    {
        // dd($request->all());
        $request->validate([
            'username' => [
                'required',
                'string',
                Rule::unique('users', 'username')->whereNull('deleted_at'),
            ],
            'is_active' => 'required|in:0,1',
            'role' => 'required|in:0,1,2',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:6048',
            'nama' => 'required|string',
            'customer_id' => 'nullable|exists:customers,id',
            'clinic_id' => 'nullable|exists:clinics,id',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->whereNull('deleted_at'),
            ],
            'email_verified_at' => 'nullable|date',
            'password' => 'required|string|min:6|same:repassword',
            'repassword' => 'required|string|min:6',
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'is_active.required' => 'Status aktif wajib diisi.',
            'is_active.in' => 'Status aktif tidak valid.',
            'role.required' => 'Role wajib diisi.',
            'role.in' => 'Role tidak valid.',
            'avatar.image' => 'Avatar harus berupa gambar.',
            'avatar.mimes' => 'Format avatar tidak valid. Gunakan jpeg, png, jpg, atau gif.',
            'avatar.max' => 'Ukuran avatar maksimal 6MB.',
            'nama.required' => 'Nama wajib diisi.',
            'customer_id.exists' => 'Customer ID tidak valid.',
            'clinic_id.exists' => 'Clinic ID tidak valid.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'email_verified_at.date' => 'Format tanggal verifikasi email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.same' => 'Password dan konfirmasi password tidak sama.',
            'repassword.required' => 'Konfirmasi password wajib diisi.',
            'repassword.min' => 'Konfirmasi password minimal 6 karakter.',
        ]);


        $data = $request->except('_token', 'repassword');

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->input('password'));
        }

        if ($request->hasFile('avatar')) {
            $hasName            = $request->file('avatar');
            $path               = $hasName->store('avatar', 'public');
            $data['avatar']     = $path;
        }
        try {
            DB::beginTransaction();
            User::create($data);
            toastify()->success('Data berhasil disimpan');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            toastify()->error('Gagal menyimpan data: ' . $th->getMessage());
            throw $th;
        }
    }

    public function update($request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'clinic_id' => 'nullable|string',
            'username' => [
                'nullable',
                'string',
                Rule::unique('users', 'username')->ignore($user->id)->whereNull('deleted_at'),
            ],
            'is_active' => 'nullable|in:0,1',
            'role' => 'nullable|in:0,1,2',
            'customer_id' => 'nullable|exists:customers,id',
            'clinic_id' => 'nullable|exists:clinics,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:6048',
            'nama' => 'nullable|string',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($user->id)->whereNull('deleted_at'),
            ],
            'email_verified_at' => 'nullable|date',
            'password' => 'nullable|string|min:6|same:repassword',
            'repassword' => 'nullable|string|min:6',
        ]);

        $data = $request->except('_token', '_method', 'repassword');

        if ($request->input('role') == '1') {
            $data['customer_id'] = null;
        }

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->input('password'));
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('avatar')) {
            $hasName            = $request->file('avatar');
            $path               = $hasName->store('avatar', 'public');
            $data['avatar']     = $path;
        }

        try {
            DB::beginTransaction();
            $user->update($data);
            DB::commit();
            toastify()->success('Data berhasil diperbarui');
        } catch (\Throwable $th) {
            DB::rollBack();
            toastify()->error('Gagal memperbarui data: ' . $th->getMessage());
            throw $th;
        }
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        try {
            DB::beginTransaction();
            $user->delete();
            toastify()->success('Data berhasil dihapus');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            toastify()->error('Gagal menghapus data: ' . $th->getMessage());
            throw $th;
        }
    }

    public function bulkDelete($request)
    {
        $request->validate([
            'selected_ids' => 'required|array|min:1'
        ]);

        $ids = $request->input('selected_ids');
        if (!$ids || !is_array($ids) || count($ids) === 0) {
            toastify()->error('Tidak ada data yang dipilih untuk dihapus');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();
            User::whereIn('id', $ids)->delete();
            toastify()->success('Data berhasil dihapus');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            toastify()->error('Gagal menghapus data: ' . $th->getMessage());
            return redirect()->back();
        }

        return redirect()->back();
    }

    public function importExcel($request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);
        try {
            Excel::import(new UserImport, $request->file('file'));
            toastify()->success('Success, import Berhasil!');
            return redirect()->back();
        } catch (\Throwable $e) {
            toastify()->error('Gagal menghapus data: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function templateExcel()
    {
        $timestamp = Carbon::now()->format('Y-m-d_H-i');
        $filename = "User_Template_{$timestamp}.xlsx";
        return Excel::download(new UserExport(), $filename);
    }
}
