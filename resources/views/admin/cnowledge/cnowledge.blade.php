@extends('layout.admin')

@section('title', 'Cnowledge')

@section('content')
    {{-- Notif sukses/perubahan/ or smth --}}
    @if(session('success'))
        <div class="position-fixed top-0 end-0 px-5 py-3" style="z-index: 9999">
            <div class="px-4 py-2"
                style="border-radius: 4px;border: 1px solid #658E3D; background: #FFF;box-shadow: 0px 3px 5px 0px rgba(0, 0, 0, 0.20), 0px 1px 18px 0px rgba(0, 0, 0, 0.12), 0px 6px 10px 0px rgba(0, 0, 0, 0.14);">
                <div class="d-flex align-items-center justify-content-start">
                    <i class="bi bi-dot"></i>
                    <span
                        style="color: rgba(0, 0, 0, 0.56);font-size: 10px;font-weight: 600;line-height: 16px;letter-spacing: 0.125px;text-transform: uppercase;">1
                        Min Ago</span>
                </div>
                <div class="">
                    <p style="color: #373737;font-size: 14px;font-weight: 400;line-height: 20px;">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div>
        <img class="w-100 img-fluid" src="{{ asset('assets/img/petani/content.png') }}" alt="">
        <div class="mt-4">
            <span style="color: #26262A;font-size: 18px;font-weight: 700;letter-spacing: 1px;">
                Cnowledge</span>

            <div class="mt-3 d-flex justify-content-between flex-wrap">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <div class="d-flex gap-4 align-items-center px-3 py-2"
                        style="border-radius: 8px;border: 1px solid #D0D5DD;background: #FFF;box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);">
                        <i class="bi bi-filter"></i>
                        <span>Filter</span>
                    </div>
                    <div>
                        <a href="/admin/cnowledge/add">
                            <button type="button" class="btn px-3 py-2"
                                style="color: #FFF;font-size: 14px;font-weight: 500;line-height: 20px;border-radius: 8px;border: 1px solid #7280FF;background: #00452C;box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);">
                                <i class="bi bi-plus-lg"></i> Tambah Artikel
                            </button>
                        </a>
                    </div>
                </div>
                <div id="search-form-container" class="mt-4 mt-md-0" style="position: relative;">
                    <form action="/admin/cnowledge">
                        <input type="text" class="px-5 py-2" placeholder="Search"
                        style="border: 1px solid #26262A;border-radius: 8px"
                        name="search" value="{{ request('search') }}">
                        <i class="px-2 bi bi-search position-absolute"
                        style="left: 5px; top: 50%; transform: translateY(-50%);"></i>
                    </form>
                </div>
            </div>

            <div class="mt-4">
                <div id="petani-table" class="table-responsive" style="margin-bottom: 365px;">
                    <table>
                        <thead class="text-center">
                            <tr>
                                <th style="border-radius: 6px 0 0 0">No</th>
                                <th>Judul</th>
                                <th>Gambar</th>
                                <th>Tanggal</th>
                                <th>Nama Sumber</th>
                                <th>Link Sumber</th>
                                <th>Isi</th>
                                <th style="border-radius: 0 6px 0 0"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($articles->count())
                            @foreach ($articles as $article)
                            <tr style="border: 1px solid #00452C"
                                style="color: #000;font-size: 12px;font-weight: 500;line-height: 150%;">
                                <td>{{ ($articles->currentPage() - 1) * $articles->perPage() + $loop->iteration }}</td>
                                <td>{{ $article->title }}</td>
                                <td>
                                    @if ($article->image !== null)
                                    <img src="{{ asset('storage/'.$article->image) }}" style="max-width: 200px;"
                                    alt="">
                                    @else
                                    <img src="{{ asset('assets/img/artikel-card.png') }}" style="max-width: 200px;"
                                    alt="">
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($article->created_at)->format('d F Y') }}</td>
                                <td>{{ $article->source }}</td>
                                <td>
                                    <a
                                        href="{{ $article->link }}" target="_blank">
                                        Link
                                    </a>
                                </td>
                                <td>
                                    {{ $article->excerpt }} ...
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="/admin/cnowledge/edit/{{ $article->slug }}">
                                            <button class="btn">
                                                <i class="bi bi-pencil fs-5" style="color: #FDB626"></i>
                                            </button>
                                        </a>

                                        <button class="btn" onclick="openFullscreenModal({{ $article->id }})">
                                            <i class="bi bi-trash3 fs-5" style="color: #FF0000"></i>
                                        </button>

                                        <a href="/admin/cnowledge/detail/{{ $article->slug }}">
                                            <button class="btn">
                                                <i class="bi bi-list-nested fs-5" style="color: #658E3D"></i>
                                            </button>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Delete --}}
                            <div id="fullscreenModal{{ $article->id }}" class="fullscreen-modal-overlay d-none">
                                <div class="fullscreen-modal-content" style="width: 80%; max-width: 500px;">
                                    <div class="mt-4 mb-5">
                                        <div>
                                            <p style="color: #000;text-align: center;font-size: 20px;font-weight: 500;line-height: 150%;">
                                                Apakah anda yakin ingin menghapus data ini?</p>
                                        </div>
                                        <div class="d-flex justify-content-center gap-4 mt-5">
                                            <button type="button" onclick="closeFullscreenModal({{ $article->id }})" class="btn px-5"
                                                style="color: #6EBF4B;font-size: 13px;font-weight: 700;line-height: 150%;border-radius: 15px;border: 2px solid #6EBF4B;background: #FFF;">
                                                Batal
                                            </button>

                                            <form action="{{ route('cnowledge.delete', $article->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn px-5" style="color: #FFF; font-size: 13px; font-weight: 700; line-height: 150%; border-radius: 15px; background: #6EBF4B;">
                                                    Ya
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @endforeach
                            @else
                            <tr style="border: 1px solid #00452C"
                            style="color: #000;font-size: 12px;font-weight: 500;line-height: 150%;">
                                <td colspan="8">Tidak ada Article</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="w-100" style="background: #FFF;">
                        @include('admin/custom_pagination', ['paginator' => $articles])
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- modal --}}
    <script>
        function openFullscreenModal(Id) {
            var modal = document.getElementById("fullscreenModal" + Id);
            modal.classList.remove('d-none');
        }

        function closeFullscreenModal(Id) {
            var modal = document.getElementById("fullscreenModal" + Id);
            modal.classList.add('d-none');
        }

        //search
        const searchForm = document.querySelector('#search-form-container form');
        searchForm.querySelector('input[name="search"]').addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                searchForm.submit();
            }
        });
    </script>
@endsection
