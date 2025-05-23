<x-layouts.app :title="$title">
    <section class="w-full h-full">
        <x-page-title :title="$title" />
        <div class="flex flex-col w-full md:w-sm mx-auto">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="post" action={{ route('karyawan.store') }} enctype="multipart/form-data"
                        class="space-y-3">
                        @csrf
                        <div>
                            <label class="label-text" for="username">Username</label>
                            <input type="text" id="username" name="username" placeholder="Username" class="input"
                                value="{{ old('username') }}" required />
                        </div>
                        <div>
                            <label class="label-text" for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="Password" class="input"
                                required />
                        </div>
                        <div>
                            <label class="label-text" for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                placeholder="Konfirmasi Password" class="input" required />
                        </div>

                        <hr />

                        <div>
                            <label class="label-text" for="name">Nama Karyawan</label>
                            <input type="text" id="name" name="name" placeholder="Nama Karyawan"
                                class="input" value="{{ old('name') }}" required />
                        </div>

                        <div>
                            <label class="label-text" for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="Email" class="input"
                                value="{{ old('email') }}" required />
                        </div>

                        <div>
                            <label class="label-text" for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone" placeholder="Phone" class="input"
                                value="{{ old('phone') }}" required />
                        </div>

                        <div>
                            <label class="label-text" for="role_karyawan">Role</label>
                            <div class="flex items-center gap-5">
                                <div class="flex items-center gap-1">
                                    <input type="radio" id="role_karyawan" name="role" class="radio"
                                        value="karyawan" @checked(old('role') === 'karyawan') />
                                    <label class="label-text text-base" for="role_karyawan">Karyawan</label>
                                </div>
                                <div class="flex items-center gap-1">
                                    <input type="radio" id="role_admin" name="role" class="radio" value="admin"
                                        @checked(old('role') === 'admin') />
                                    <label class="label-text text-base" for="role_admin">Admin</label>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <button type="submit" class="btn btn-primary w-full md:w-auto">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <a href="{{ route('karyawan') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>

</x-layouts.app>
