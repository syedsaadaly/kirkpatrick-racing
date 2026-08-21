@extends('admin.layouts.app')
@section('content')

    @include('admin.layouts.partials.topbar')
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">Pages List</h5>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.cms-builder.pages.create') }}" class="btn btn-primary" role="button"><i
                            class="fas fa-plus me-1"></i> Add New Page</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Page Name</th>
                            <th>Slug</th>
                            <th>Sections</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pages as $page)
                            <tr>
                                <td>{{ $page->id }}</td>
                                <td>{{ $page->name }}</td>
                                <td>{{ $page->slug }}</td>
                                <td>{{ $page->sections_count }}</td>
                                <td>{{ $page->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.cms-builder.pages.edit', $page->id) }}"
                                            class="btn btn-warning btn-sm"> <i class="fas fa-edit me-1"></i>Edit</a>
                                        @if ($page->is_cms == 0)
                                            <a href="{{ route('admin.cms-builder.page.view', $page->id) }}"
                                                class="btn btn-dark btn-sm">
                                                <i class="fas fa-edit me-1"></i> Edit Content
                                            </a>
                                        @endif
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info btn-sm dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-plus me-1"></i> Add Items
                                            </button>

                                            <ul class="dropdown-menu">
                                                @forelse($page->sections->where('is_listable', 1) as $section)
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.cms-builder.pages.sections.items.create', [$page->id, $section->id]) }}">
                                                            {{ $section->section_name ?? 'Section ' . $section->id }}
                                                        </a>
                                                    </li>
                                                @empty
                                                    <li>
                                                        <span class="dropdown-item text-muted">
                                                            No Sections Found
                                                        </span>
                                                    </li>
                                                @endforelse
                                            </ul>
                                        </div>
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#codeModal{{ $page->id }}">
                                            <i class="fas fa-code me-1"></i> View Code
                                        </button>
                                        <form action="{{ route('admin.cms-builder.pages.destroy', $page->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                style="border-top-left-radius: 0; border-bottom-left-radius: 0;"
                                                onclick="return confirm('Are you sure you want to delete this page?')">
                                                <i class="fas fa-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No pages found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if (isset($page->id))
        <div class="modal fade" id="codeModal{{ $page->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">

                    <div class="modal-header d-flex justify-content-between">
                        <h5 class="modal-title">Sample Blade Loop</h5>
                        <button id="copyBtn{{ $page->id }}"
                                class="btn btn-sm btn-outline-primary"
                                onclick="copyCode('codeBlock{{ $page->id }}','copyBtn{{ $page->id }}')">
                            Copy Code
                        </button>
                    </div>

                    <div class="modal-body">

                        @php
                            $snippet = '
                                @if(isset($pageData["Example Section"]["_items"]))
                                    @foreach ($pageData["Example Section"]["_items"] as $example)
                                        <div class="example-wrapper">
                                            {{-- Fetching Image --}}
                                            <figure class="example-image-container">
                                                <img src="{{ isset($example["image"]) ? asset($example["image"]) : asset("front/front/images/example.webp") }}"
                                                class="img-fluid" alt="example image">
                                            </figure>
                                            {{-- Fetching Text --}}
                                            <h1>{{ $example["heading"] ?? "" }}</h1>
                                        </div>
                                    @endforeach
                                @endif';
                        @endphp

                        <pre class="bg-dark text-light p-3 rounded">
                    <code id="codeBlock{{ $page->id }}">{!! e($snippet) !!}</code>
                </pre>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@push('custom-script')
    <script>
        function copyCode(codeId, btnId) {

            const text = document.getElementById(codeId).innerText;

            navigator.clipboard.writeText(text).then(() => {
                const btn = document.getElementById(btnId);
                const original = btn.innerText;

                btn.innerText = "Copied!";
                btn.classList.remove('btn-outline-primary');
                btn.classList.add('btn-success');

                setTimeout(() => {
                    btn.innerText = original;
                    btn.classList.add('btn-outline-primary');
                    btn.classList.remove('btn-success');
                }, 2000);
            });
        }
    </script>
@endpush
