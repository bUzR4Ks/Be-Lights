@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4">
    <h1 class="text-3xl font-bold mb-8 text-center text-[#153448]">Bazar SMKN 8 Jakarta</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
        <a href="{{ route('home.section', 'nasi_uduk') }}" class="bg-[#dfd1b7] border border-[#3c5b6f] rounded-xl shadow-sm hover:shadow-lg hover:bg-[#e8dfcd] transition-all duration-200 overflow-hidden">
            <img src="{{ asset('storage/nasiuduk.jpg') }}" alt="Nasi Uduk" class="w-full h-40 object-cover">
            <div class="p-4 text-center">
                <h2 class="text-xl font-semibold text-[#153448]">Nasi Uduk</h2>
            </div>
        </a>
        <a href="{{ route('home.section', 'aneka_semur') }}" class="bg-[#dfd1b7] border border-[#3c5b6f] rounded-xl shadow-sm hover:shadow-lg hover:bg-[#e8dfcd] transition-all duration-200 overflow-hidden">
            <img src="{{ asset('storage/semur.jpg') }}" alt="Aneka Semur" class="w-full h-40 object-cover">
            <div class="p-4 text-center">
                <h2 class="text-xl font-semibold text-[#153448]">Aneka Semur</h2>
            </div>
        </a>
    </div>

    <h2 class="text-2xl font-bold mb-4 text-[#153448] text-center">Menu Bundling</h2>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="{{ route('home.section', 'multiple') }}" class="bg-[#f1e5c6] border border-[#3c5b6f] rounded-xl shadow-sm hover:shadow-lg hover:bg-[#f6ead7] transition-all duration-200 overflow-hidden">
            <div class="p-4 text-center">
                <h3 class="text-lg font-semibold text-[#153448]">Bundling Menu</h3>
                <p class="text-sm text-gray-700">Gabungan Nasi Uduk & Aneka Semur</p>
            </div>
        </a>
    </div>
</div>
@endsection
