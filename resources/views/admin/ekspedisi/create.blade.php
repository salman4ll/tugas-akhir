@extends('layouts.blank-admin')

@section('title', 'Tambah Ekspedisi')

@section('content')
<div class="p-4">
    <h2 class="text-xl font-bold mb-4">Tambah Ekspedisi</h2>
    <form action="{{ route('admin.ekspedisi.store') }}" method="POST" class="space-y-4">
        @csrf

        {{-- PILIH KURIR --}}
        <div>
            <label for="courier_code" class="block font-semibold">Pilih Kurir</label>
            <select id="courier_code" name="courier_code" class="w-full border-gray-300 rounded" required>
                <option value="">-- Pilih Kurir --</option>
                @foreach($couriers as $code => $services)
                    <option value="{{ $code }}">{{ strtoupper($code) }} - {{ $services[0]['courier_name'] }}</option>
                @endforeach
            </select>
        </div>

        {{-- PILIH LAYANAN (DINAMIS) --}}
        <div>
            <label for="courier_service_code" class="block font-semibold">Pilih Layanan</label>
            <select id="courier_service_code" name="courier_service_code" class="w-full border-gray-300 rounded" required>
                <option value="">-- Pilih Layanan --</option>
            </select>
        </div>

        {{-- Input Tersembunyi --}}
        <input type="hidden" name="courier_name" id="courier_name">
        <input type="hidden" name="courier_service_name" id="courier_service_name">
        <input type="hidden" name="description" id="description">
        <input type="hidden" name="shipping_type" id="shipping_type">
        <input type="hidden" name="service_type" id="service_type">
        <input type="hidden" name="duration_estimate" id="duration_estimate">
        <input type="hidden" name="is_active" id="is_active" value="1">
        {{-- TOMBOL SIMPAN --}}


        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
    </form>
</div>

<script>
    const data = @json($couriers);

    const courierSelect = document.getElementById('courier_code');
    const serviceSelect = document.getElementById('courier_service_code');

    courierSelect.addEventListener('change', function () {
        const selectedCode = this.value;
        const services = data[selectedCode] || [];

        serviceSelect.innerHTML = '<option value="">-- Pilih Layanan --</option>';

        services.forEach(service => {
            const option = document.createElement('option');
            option.value = service.courier_service_code;
            option.textContent = `${service.courier_service_name} - ${service.description}`;
            option.dataset.service = JSON.stringify(service);
            serviceSelect.appendChild(option);
        });

        // Set courier_name
        if (services.length > 0) {
            document.getElementById('courier_name').value = services[0].courier_name;
        }
    });

    serviceSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const service = JSON.parse(selectedOption.dataset.service || '{}');

        document.getElementById('courier_service_name').value = service.courier_service_name || '';
        document.getElementById('description').value = service.description || '';
        document.getElementById('shipping_type').value = service.shipping_type || '';
        document.getElementById('service_type').value = service.service_type || '';
        document.getElementById('duration_estimate').value = service.shipment_duration_range || '';
    });
</script>
@endsection
