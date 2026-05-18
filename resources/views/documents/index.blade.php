<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">

@vite(['resources/js/app.js'])

    <div class="d-flex justify-content-end mt-3 me-3">

    <form method="POST" action="{{ route('logout') }}">

        @csrf

        <button type="submit" class="btn btn-danger">
            Logout
        </button>

    </form>

</div>

<div class="container mt-5">

    <h1 class="mb-4 text-center">
        Collaborative Document
    </h1>

    <a href="/document/create" class="btn btn-primary mb-4">
        Create Document
    </a>

    @foreach($dokumen as $doc)

        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <h3 class="title">
                    {{ $doc->title }}
                </h3>

                <p class="content text-muted">
                    {{ $doc->content }}
                </p>

                <small class="text-muted d-block mb-3">

                    Last edited by
                    {{ $doc->user->name }}

                    •

                    {{ $doc->updated_at->format('d M Y H:i') }}

                </small>

                <small class="text-muted d-block mb-3">
                    Created by: {{ $doc->user->name ?? 'Unknown' }}
                </small>

                <div class="d-flex gap-2">

                   <a href="/document/{{ $doc->id }}/edit"
                        class="btn btn-warning d-flex align-items-center justify-content-center"
                        style="height: 38px;">
                        Edit
                    </a>

                    <form action="/document/{{ $doc->id }}" method="POST">

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger">
                            Delete
                        </button>

                    </form>

                </div>

            </div>

        </div>

    @endforeach

</div>

<script type="module">

    window.addEventListener('load', () => {

        @foreach($dokumen as $doc)

            window.Echo.channel('document.{{ $doc->id }}')
                .listen('.DocumentUpdated', (e) => {

                    const titleElement = document.querySelector(
                        '#doc-{{ $doc->id }} .title'
                    );

                    const contentElement = document.querySelector(
                        '#doc-{{ $doc->id }} .content'
                    );

                    if (titleElement) {
                        titleElement.innerText = e.title;
                    }

                    if (contentElement) {
                        contentElement.innerText = e.content;
                    }

                });

        @endforeach

    });

</script>

<script>

    setInterval(() => {

        location.reload();

    }, 10000);

</script>