<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

@vite(['resources/js/app.js'])

<style>

#editor-wrapper {
    position: relative;
}

.user-cursor {
    position: absolute;
    width: 2px;
    z-index: 10;
}

.user-label {
    position: absolute;
    top: -11px;
    left: 0;
    color: white;
    padding: 2px 6px;
    border-radius: 5px;
    font-size: 11px;
    white-space: nowrap;
}

</style>

<div class="container mt-5">
    <div class="row">

        <div class="col-md-10">
            <div class="card shadow p-4">

        <h1 class="mb-4">
            Edit Document
        </h1>

        <form>

            <div class="mb-3">

                <label class="form-label">
                    Title
                </label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    class="form-control"
                    value="{{ $dokumen->title }}"
                >

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Content
                </label>

                <div id="online-users" class="mb-3"></div>
                
                <div
                    id="conflict-warning"
                    class="alert alert-warning d-none"
                >
                    Document was updated by another user.
                </div>

            <div id="editor-wrapper" class="position-relative">

                <textarea
                    id="editor"
                    name="content"
                    rows="10"
                    class="form-control"
                >{{ $dokumen->content }}</textarea>
                
                <div id="cursors"></div>
            </div>

            </div>

        </form>

        <div class="d-flex gap-2">

            <a href="/document" class="btn btn-secondary">
                Back to Document
            </a>

        </div>

</div>
</div>

        <div class="col-md-2">

            <div class="card shadow p-3">

                <h5 class="mb-3">
                    Version History
                </h5>

                <div style="
                    max-height: 600px;
                    overflow-y: auto;
                ">

                    @foreach($histories as $history)

                        <div class="border-bottom mb-3 pb-2">

                            <small class="text-muted d-block">
                                {{ $history->user->name }}
                            </small>

                            <small class="text-muted d-block">
                                {{ $history->created_at }}
                            </small>

                            <p class="mb-0 mt-2">
                                {{ $history->content }}
                            </p>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

    </div>

</div>

<script type="module">

    const textarea = document.getElementById('editor');
    const titleInput = document.getElementById('title');

    let timeout;

    async function saveDocument() {

        await fetch('/document/{{ $dokumen->id }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                title: titleInput.value,
                content: textarea.value
            })
        });

    }

   let typingCooldown = false;

    textarea.addEventListener('keyup', async () => {

        if (!typingCooldown) {
            typingCooldown = true;

            await fetch('/document/{{ $dokumen->id }}/typing', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            setTimeout(() => {
                typingCooldown = false;
            }, 1000);

        }

        clearTimeout(timeout);

        timeout = setTimeout(saveDocument, 300);

    });

textarea.addEventListener('keyup', () => {

    channel.whisper('cursor-move', {

        userId: {{ auth()->id() }},

        userName: "{{ auth()->user()->name }}",

        position: textarea.selectionStart

    });

});

    titleInput.addEventListener('input', () => {

        clearTimeout(timeout);

        timeout = setTimeout(saveDocument, 500);

    });

    const channel = window.Echo.join(
        'document.{{ $dokumen->id }}'
    ) 
        .here((users) => {

            const online =
                document.getElementById('online-users');

            online.innerHTML = users.map(user =>

                `<div id="user-${user.id}">
                    <span class="text-success">
                        ${user.name} online
                    </span>
                </div>`

            ).join('');

        })

        .joining((user) => {

            const online =
                document.getElementById('online-users');

            online.innerHTML += `

                <div id="user-${user.id}">
                    <span class="text-success">
                        ${user.name} online
                    </span>
                </div>

            `;

        })

    .leaving((user) => {

        const userElement = document.getElementById(
            `user-${user.id}`
        );

        if (userElement) {
            userElement.remove();
        }

    })

    .listen('.DocumentUpdated', (e) => {

        const warning =
            document.getElementById(
                'conflict-warning'
            );

        if (document.activeElement !== textarea) {

            textarea.value = e.content;

            warning.classList.remove('d-none');

            clearTimeout(window.conflictTimeout);

            window.conflictTimeout = setTimeout(() => {
                warning.classList.add('d-none');
            }, 2000);

        }

        if (document.activeElement !== titleInput) {
            titleInput.value = e.title;
        }

    })

    .listenForWhisper('cursor-move', (e) => {

        if (e.userId == {{ auth()->id() }}) return;

        const oldCursor =
            document.getElementById(
                'cursor-' + e.userId
            );

        if (oldCursor) {
            oldCursor.remove();
        }

        const cursors =
            document.getElementById('cursors');

        const textBeforeCursor =
            textarea.value.substring(
                0,
                e.position
            );

        const lines =
            textBeforeCursor.split('\n');

        const currentLine =
            lines[lines.length - 1];

        const canvas =
            document.createElement('canvas');

        const context =
            canvas.getContext('2d');

        context.font =
            window.getComputedStyle(
                textarea
            ).font;

        const cursorX =
            context.measureText(
                currentLine
            ).width
            - textarea.scrollLeft;

        const lineHeight = 24;

        const cursorY =
            (lines.length - 1)
            * lineHeight;

        const finalY =
            cursorY - textarea.scrollTop;

        const cursor =
            document.createElement('div');

        cursor.id =
            'cursor-' + e.userId;

        cursor.classList.add(
            'user-cursor'
        );

        cursor.style.backgroundColor =
            '#f38fda';

        cursor.style.left =
            (cursorX + 16) + 'px';

        cursor.style.top =
            (finalY + 8) + 'px';

        cursor.style.height =
            lineHeight + 'px';

        const label =
            document.createElement('div');

        label.classList.add(
            'user-label'
        );

        label.innerText =
            e.userName;

        label.style.backgroundColor =
            '#ef4444';

        cursor.appendChild(label);

        cursors.appendChild(cursor);

});


</script>