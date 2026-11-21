<div class="form-grid">
    <div class="field">
        <label for="code">Kode pemasok</label>
        <input id="code" type="text" name="code" value="{{ old('code', $supplier->code ?? '') }}" required maxlength="30">
        @error('code')<span class="error">{{ $message }}</span>@enderror
    </div>

    <div class="field">
        <label for="name">Nama pemasok</label>
        <input id="name" type="text" name="name" value="{{ old('name', $supplier->name ?? '') }}" required maxlength="150">
        @error('name')<span class="error">{{ $message }}</span>@enderror
    </div>

    <div class="field">
        <label for="phone">Telepon</label>
        <input id="phone" type="text" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}" maxlength="30" inputmode="tel">
        @error('phone')<span class="error">{{ $message }}</span>@enderror
    </div>

    <div class="field">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $supplier->email ?? '') }}" maxlength="150">
        @error('email')<span class="error">{{ $message }}</span>@enderror
    </div>

    <div class="field">
        <label for="payment_term_id">Termin pembayaran</label>
        <select id="payment_term_id" name="payment_term_id" required>
            <option value="">Pilih termin</option>
            @foreach ($terms as $term)
                <option value="{{ $term->id }}" @selected((string) old('payment_term_id', $supplier->payment_term_id ?? '') === (string) $term->id)>{{ $term->name }} ({{ $term->days }} hari)</option>
            @endforeach
        </select>
        @error('payment_term_id')<span class="error">{{ $message }}</span>@enderror
    </div>

    <div class="field">
        <span class="label">Status</span>
        <input type="hidden" name="is_active" value="0">
        <label class="check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $supplier->is_active ?? true))> Aktif dan bisa dipilih di stok masuk</label>
    </div>

    <div class="field full">
        <label for="address">Alamat</label>
        <textarea id="address" name="address" maxlength="500">{{ old('address', $supplier->address ?? '') }}</textarea>
        @error('address')<span class="error">{{ $message }}</span>@enderror
    </div>
</div>
