@extends('Dashboard.Layouts.Master')
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('DataTable/dataTables.bootstrap5.min.css') }}">
    <style>
        div[style="z-index:9999;width:100%;position:relative"] {
            display: none !important;
        }


        .switch-lg {
            transform: scale(1.5);
            transform-origin: left center;
        }


        .custom-toast {
            background-color: #38c172;

            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            margin-bottom: 10px;
            font-size: 15px;
            animation: slide-in 0.3s ease, fade-out 0.3s ease 2.7s forwards;
        }

        @keyframes slide-in {
            from {
                opacity: 0;
                transform: translateX(100%);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fade-out {
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
    </style>
@endsection
@section('title_page')
    Articles
@endsection
@section('one')
    Articles
@endsection
@section('two')
    Articles
@endsection

@section('content')
    <section class="section">
        <div class="row">
            <x-alerts></x-alerts>
            <div id="custom-toast-container" style="width:300px; position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>


            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">

                        <div class="data_buttons">
                            <h5 class="card-title">Articles Card </h5>

                            {{-- _______________________________ start Button _______________________________ --}}


                            <a type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#add"
                                data-bs-whatever="@mdo">Add</a>


                            {{-- _______________________________ end Button _______________________________ --}}
                        </div>



                        <table id="example" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>


                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Status</th>

                                    <th>SubCategorie</th>

                                    <th>actions</th>

                                </tr>
                            </thead>
                            <tbody>
                                {{-- _______________________________ start foreach _______________________________ --}}

                                @foreach ($articles as $article)
                                    <tr>

                                        <td>
                                            <img style="width: 60px;height:60px"
                                                src="{{ URL::asset("Backend\Uploades\Articles\\$article->image_file") }}"
                                                alt="">


                                        </td>
                                        <td>{{ $article->title }}</td>
                                        <td>
                                            <div style="cursor: pointer; margin-top: 10px;" class="form-check form-switch">
                                                <input style="cursor: pointer"
                                                    class="form-check-input switch-lg status-toggle" type="checkbox"
                                                    data-id="{{ $article->id }}" {{ $article->status ? 'checked' : '' }}>
                                            </div>
                                        </td>

                                        <td>{{ $article->articlesubcategorie->name }}</td>



                                        <td>



                                            <a href="{{ url('dashboard/article/edit', $article->id) }}"
                                                class="btn btn-outline-warning edit_btn">Edit</a>
                                            <a href="{{ url('dashboard/article/delete', $article->id) }}"
                                                class="btn btn-outline-danger">Delete</a>

                                        </td>

                                    </tr>
                                @endforeach





                                {{-- _______________________________ end foreach _______________________________ --}}

                                </tfoot>
                        </table>


                        <div style="display: flex;justify-content: center;">

                            <div>

                                {!! $articles->links() !!}

                            </div>
                        </div>



                    </div>
                </div>

            </div>


        </div>
    </section>

    @include('Dashboard.pages.Articles.Article.add_model')

    {{-- end model ---- ______________________________________________________________ --}}
@endsection
@section('js')
    @include('Dashboard.pages.Articles.Article.scripts')
    @if ($errors->any())
        <script>
            $(document).ready(function() {
                $('#add').modal('show');
            });
        </script>
    @endif

    <script>
        $(document).ready(function() {
            $('#submit_add').click(function() {

                $(this).hide();

                $('#loading').show();
                setTimeout(function() {
                    $('#loading').hide();
                    $('#submit_add').show();
                }, 6000);
            })
        });
    </script>

    <script>
        $(document).on('change', '.status-toggle', function() {
            let status = $(this).is(':checked') ? 1 : 0;
            let articleId = $(this).data('id');

            $.ajax({
                url: '{{ url('/dashboard/article/update-status') }}',
                type: 'POST',
                data: {
                    status: status,
                    articleId: articleId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    showToast("Status Updated Successfully");

                    setTimeout(() => {
                        $('#alert-box').fadeOut();
                    }, 3000);

                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        });
    </script>

    <script>
        function showToast(message, type = 'success') {
            let bgColor = type === 'error' ? '#e3342f' : '#38c172';
            let toast = $(`
        <div class="custom-toast" style="background-color: ${bgColor};">
            ${message}
        </div>
         `);

            $('#custom-toast-container').append(toast);


            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script>
@endsection
