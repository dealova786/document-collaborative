<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">

    <div class="card shadow p-4">

        <h1 class="mb-4">
            Create Document
        </h1>

        <form action="/document" method="POST">

            @csrf

            <div class="mb-3">

                <label class="form-label">
                    Title
                </label>

                <input
                    type="text"
                    name="title"
                    class="form-control"
                    placeholder="Enter title"
                >

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Content
                </label>

                <textarea
                    name="content"
                    rows="8"
                    class="form-control"
                    placeholder="Enter content"
                ></textarea>

            </div>

            <button type="submit" class="btn btn-primary">
                Save
            </button>

            <a href="/document" class="btn btn-secondary">
                Back
            </a>

        </form>

    </div>

</div>